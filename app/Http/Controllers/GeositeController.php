<?php

namespace App\Http\Controllers;

use App\Models\Geosite;

class GeositeController extends Controller
{
    public function show(string $slug)
    {
        $geosite = Geosite::with(['category','media'])
            ->where('slug', $slug)
            ->where('status','published')
            ->firstOrFail();

        $cover = $geosite->media->firstWhere('is_cover', true)
              ?? $geosite->media->firstWhere('type','photo');

        $photos = $geosite->media->where('type','photo');
        $videos = $geosite->media->where('type','video');

        return view('guest.detail', compact('geosite','cover','photos','videos'));
    }
}
