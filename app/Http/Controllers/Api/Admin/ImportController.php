<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Geosite;
use App\Services\OverpassService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function tourismProbolinggo(Request $request, OverpassService $overpass)
    {
        try {
            $data = $request->validate([
                'category_id' => ['required','exists:categories,id'],
                'status' => ['nullable','in:draft,published'],
                'limit' => ['nullable','integer','min:1','max:500'],
            ]);

            $categoryId = (int)$data['category_id'];
            $status = $data['status'] ?? 'draft';
            $limit = $data['limit'] ?? 200;

            // 1. Fetch from Overpass
            $elements = $overpass->tourismProbolinggo(9674162);

            $created = 0;
            $updated = 0;

            foreach (array_slice($elements, 0, $limit) as $el) {
                $osmType = $el['type'] ?? null;
                $osmId = $el['id'] ?? null;
                $tags = $el['tags'] ?? [];

                $name = $tags['name'] ?? null;
                if (!$osmType || !$osmId || !$name) { continue; }

                $lat = $el['lat'] ?? ($el['center']['lat'] ?? null);
                $lng = $el['lon'] ?? ($el['center']['lon'] ?? null);
                if (!$lat || !$lng) { continue; }

                // 2. Enhanced Description
                $descRaw = $tags['description:id'] ?? ($tags['description'] ?? null);
                $tourism = $tags['tourism'] ?? 'tourism';
                
                $desc = $descRaw 
                    ? "{$descRaw}\n\n(Sumber: OpenStreetMap)" 
                    : "Destinasi wisata kategori {$tourism} di Probolinggo.\n\n(Sumber: OpenStreetMap)";

                // 3. Enhanced Address construction
                $address = $tags['addr:full'] ?? null;
                if (!$address) {
                    $addrParts = [];
                    if (!empty($tags['addr:street'])) $addrParts[] = $tags['addr:street'];
                    if (!empty($tags['addr:housenumber'])) $addrParts[] = $tags['addr:housenumber'];
                    if (!empty($tags['addr:village'])) $addrParts[] = $tags['addr:village'];
                    if (!empty($tags['addr:city'])) $addrParts[] = $tags['addr:city'];
                    
                    if (count($addrParts) > 0) {
                        $address = implode(', ', $addrParts);
                    }
                }

                // Mapping Harga
                $fee = $tags['fee'] ?? ($tags['charge'] ?? ($tags['entrance'] ?? null));

                // Find or Create
                $geosite = Geosite::where('osm_type', $osmType)->where('osm_id', $osmId)->first();

                if ($geosite) {
                    // Update existing data with enhanced info if available
                    $geosite->update([
                        'latitude' => (float)$lat,
                        'longitude' => (float)$lng,
                        'address' => $address ?? $geosite->address, // prefer new address if found
                        'description' => $desc, // force update description format
                        'open_hours' => $tags['opening_hours'] ?? $geosite->open_hours,
                        'ticket_price' => $fee ?? $geosite->ticket_price,
                        // leave name/slug/category/status as is to preserve admin edits
                    ]);
                    $updated++;
                } else {
                    $slugBase = Str::slug($name);
                    $slug = $this->uniqueSlug($slugBase);

                    $geosite = Geosite::create([
                        'category_id' => $categoryId,
                        'name' => $name,
                        'slug' => $slug,
                        'description' => $desc,
                        'latitude' => (float)$lat,
                        'longitude' => (float)$lng,
                        'address' => $address,
                        'region' => 'Kabupaten Probolinggo',
                        'open_hours' => $tags['opening_hours'] ?? null,
                        'ticket_price' => $fee,
                        'status' => $status,
                        'osm_type' => $osmType,
                        'osm_id' => (int)$osmId,
                        'osm_source' => 'overpass',
                    ]);
                    $created++;
                }

                // 4. Enhanced Image Fetching
                // Only fetch if geosite has no photos yet
                if ($geosite->media()->where('type', 'photo')->doesntExist()) {
                    $imageUrl = $this->resolveImageUrl($tags);

                    if ($imageUrl) {
                        \App\Models\Media::create([
                            'geosite_id' => $geosite->id,
                            'type' => 'photo',
                            'path' => $imageUrl,
                            'caption' => 'OSM/Wikidata Image',
                            'is_cover' => true,
                        ]);
                    }
                }
            }

            return response()->json([
                'message' => 'Import selesai',
                'created' => $created,
                'updated' => $updated,
                'total_fetched' => count($elements),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Import gagal',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base ?: 'geosite';
        $i = 1;

        while (Geosite::where('slug', $slug)->exists()) {
            $slug = ($base ?: 'geosite') . '-' . $i++;
        }

        return $slug;
    }

    /**
     * Resolves an image URL from OSM tags or Wikidata.
     */
    private function resolveImageUrl(array $tags): ?string
    {
        // 1. Direct Image Tag (high priority)
        $direct = $tags['image'] ?? ($tags['image:url'] ?? null);
        if ($direct && $this->isValidImageUrl($direct)) {
            return $direct;
        }

        // 2. Wikidata Image (via API)
        if (!empty($tags['wikidata'])) {
            $wikiImage = $this->fetchWikidataImage($tags['wikidata']);
            if ($wikiImage) return $wikiImage;
        }

        // 3. Fallback: Website (only if it looks like an image, unlikely but possible)
        // We generally avoid saving 'website' as 'image' because it's usually an HTML page.
        // So we skip it unless explicitly requested.
        
        return null;
    }

    private function isValidImageUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) && 
               preg_match('/\.(jpg|jpeg|png|webp|gif|svg)$/i', $url);
    }

    private function fetchWikidataImage(string $qid): ?string
    {
        try {
            // Wikidata JSON API
            $url = "https://www.wikidata.org/wiki/Special:EntityData/{$qid}.json";
            
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get($url);
            
            if ($response->ok()) {
                $data = $response->json();
                $claims = $data['entities'][$qid]['claims'] ?? [];
                
                // P18 is the property for "image"
                if (!empty($claims['P18'][0]['mainsnak']['datavalue']['value'])) {
                    $filename = $claims['P18'][0]['mainsnak']['datavalue']['value'];
                    // Convert filename to Wikimedia Commons URL
                    // Strategy: Use the special Main_Page redirector or hash calculation
                    // Easiest: Use commons.wikimedia.org/wiki/Special:FilePath/Filename
                    
                    return "https://commons.wikimedia.org/wiki/Special:FilePath/" . rawurlencode($filename);
                }
            }
        } catch (\Throwable $e) {
            // ignore wikidata errors, just return null
        }
        return null;
    }
}
