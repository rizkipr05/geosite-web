<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Geosite;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Geosite $geosite)
    {
        return $geosite->media()->latest()->get();
    }

    public function store(Request $request, Geosite $geosite)
    {
        $data = $request->validate([
            'type' => ['required','in:photo,video'],
            'photo' => ['required_if:type,photo','file','mimes:jpg,jpeg,png,webp','max:5120'],
            'video_url' => ['required_if:type,video','url','max:255'],
            'caption' => ['nullable','string','max:150'],
            'is_cover' => ['nullable','boolean'],
        ]);

        $path = null;

        if ($data['type'] === 'photo') {
            $path = $request->file('photo')->store("geosites/{$geosite->id}", 'public');
        } else {
            $path = $data['video_url'];
        }

        // kalau is_cover true, nonaktifkan cover lain
        if (!empty($data['is_cover'])) {
            $geosite->media()->where('is_cover', true)->update(['is_cover' => false]);
        }

        $media = Media::create([
            'geosite_id' => $geosite->id,
            'type' => $data['type'],
            'path' => $path,
            'caption' => $data['caption'] ?? null,
            'is_cover' => !empty($data['is_cover']),
        ]);

        return response()->json($media, 201);
    }

    public function destroy(Media $media)
    {
        if ($media->type === 'photo' && str_starts_with($media->path, 'geosites/')) {
            Storage::disk('public')->delete($media->path);
        }

        $media->delete();
        return response()->json(['message' => 'deleted']);
    }
}
