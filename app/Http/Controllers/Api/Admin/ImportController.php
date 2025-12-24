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
            $status = $data['status'] ?? 'draft'; // biar aman: import jadi draft dulu
            $limit = $data['limit'] ?? 200;

            // ambil elemen OSM tourism di area Probolinggo
            $elements = $overpass->tourismProbolinggo(9674162);

            $created = 0;
            $skipped = 0;

            foreach (array_slice($elements, 0, $limit) as $el) {
                $osmType = $el['type'] ?? null;
                $osmId = $el['id'] ?? null;
                $tags = $el['tags'] ?? [];

                $name = $tags['name'] ?? null;
                if (!$osmType || !$osmId || !$name) { $skipped++; continue; }

                // cek duplikat import
                $exists = Geosite::where('osm_type', $osmType)->where('osm_id', $osmId)->exists();
                if ($exists) { $skipped++; continue; }

                $lat = $el['lat'] ?? ($el['center']['lat'] ?? null);
                $lng = $el['lon'] ?? ($el['center']['lon'] ?? null);
                if (!$lat || !$lng) { $skipped++; continue; }

                $slugBase = Str::slug($name);
                $slug = $this->uniqueSlug($slugBase);

                // isi deskripsi default dari tags biar tidak kosong
                $tourism = $tags['tourism'] ?? 'tourism';
                $desc = "Destinasi wisata (OSM: {$tourism}).\n\nSumber: OpenStreetMap.";

                // Mapping Harga
                $fee = $tags['fee'] ?? ($tags['charge'] ?? ($tags['entrance'] ?? null));

                $geo = Geosite::create([
                    'category_id' => $categoryId,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $desc,
                    'latitude' => (float)$lat,
                    'longitude' => (float)$lng,
                    'address' => $tags['addr:full'] ?? $tags['addr:street'] ?? null,
                    'region' => 'Kabupaten Probolinggo',
                    'open_hours' => $tags['opening_hours'] ?? null,
                    'ticket_price' => $fee, // Sekarang string, jadi aman
                    'status' => $status,
                    'osm_type' => $osmType,
                    'osm_id' => (int)$osmId,
                    'osm_source' => 'overpass',
                ]);

                // Simpan Gambar jika ada
                $imageUrl = $tags['image'] ?? ($tags['image:url'] ?? ($tags['url'] ?? ($tags['website'] ?? null)));
                if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    // Cek ekstensi gambar valid atau website (kadang website bukan gambar, tapi kita coba saja sebagai 'link' atau placeholder)
                    // Jika wikimedia commons, url-nya mungkin bukan direct image.
                    // Untuk simplisitas, kita simpan sebagai Media type 'photo' dengan path URL.
                    // Frontend harus menghandle jika path dimulai dengan http.

                    \App\Models\Media::create([
                        'geosite_id' => $geo->id,
                        'type' => 'photo',
                        'path' => $imageUrl,
                        'caption' => 'OSM Image',
                        'is_cover' => true,
                    ]);
                }

                $created++;
            }

            return response()->json([
                'message' => 'Import selesai',
                'created' => $created,
                'skipped' => $skipped,
                'total_fetched' => count($elements),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Import gagal',
                'error' => $e->getMessage(),
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
}
