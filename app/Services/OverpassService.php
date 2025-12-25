<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OverpassService
{
    private array $endpoints = [
        'https://overpass-api.de/api/interpreter',
        'https://overpass.kumi.systems/api/interpreter',
    ];

    public function tourismProbolinggo(int $relationId = 9674162): array
    {
        // ID Area = 3600000000 + Relation ID
        $areaId = 3600000000 + $relationId;

        $query = <<<QL
[out:json][timeout:60];
area($areaId)->.searchArea;
(
  // Prioritas Wisata Alam & Atraksi
  nwr["tourism"="attraction"](area.searchArea);
  nwr["tourism"="viewpoint"](area.searchArea);
  nwr["natural"~"waterfall|beach|peak|volcano|cave_entrance|spring|water"](area.searchArea);
  nwr["waterway"="waterfall"](area.searchArea);
  nwr["leisure"="nature_reserve"](area.searchArea);
  nwr["leisure"="park"](area.searchArea);
);
// Filter Out: Akomodasi & Tempat Makan
(._; - nwr["tourism"~"hotel|guest_house|hostel|motel|camp_site|apartment|resort|chalet"](area.searchArea););
(._; - nwr["amenity"~"cafe|restaurant|fast_food|bar|pub|food_court"](area.searchArea););
(._; - nwr["shop"](area.searchArea););
out center tags;
QL;

        $errors = [];

        foreach ($this->endpoints as $url) {
            try {
                $res = Http::withHeaders([
                        'User-Agent' => 'GeoExplore/1.0 (Laravel)',
                        'Accept' => 'application/json',
                    ])
                    ->retry(3, 800)       // 3x retry
                    ->timeout(70)
                    ->asForm()
                    ->post($url, ['data' => $query]);

                // kalau Overpass balas 429/503, coba endpoint berikutnya
                if (in_array($res->status(), [429, 502, 503, 504], true)) {
                    $errors[] = "{$url}: HTTP {$res->status()}";
                    continue;
                }

                // Jika error lain (misal 400 Bad Request), throw biar ditangkap catch
                $res->throw();

                return $res->json('elements') ?? [];
            } catch (\Throwable $e) {
                $errors[] = "{$url}: " . $e->getMessage();
                continue;
            }
        }

        $msg = implode('; ', $errors);
        throw new \RuntimeException("Overpass failed. Details: {$msg}");
    }
}
