<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Geosite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GeositeController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $data = Geosite::with('category')
            ->when($q, function($query) use ($q) {
                $query->where('name','like',"%$q%")
                      ->orWhere('address','like',"%$q%")
                      ->orWhere('region','like',"%$q%");
            })
            ->latest()
            ->paginate(10);

        return $data;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required','exists:categories,id'],
            'name' => ['required','string','max:150'],
            'description' => ['required','string'],
            'latitude' => ['required','numeric','between:-90,90'],
            'longitude' => ['required','numeric','between:-180,180'],
            'address' => ['nullable','string','max:255'],
            'region' => ['nullable','string','max:100'],
            'open_hours' => ['nullable','string','max:100'],
            'ticket_price' => ['nullable','integer','min:0'],
            'status' => ['nullable','in:draft,published'],
        ]);

        $slug = Str::slug($data['name']);
        $slug = $this->uniqueSlug($slug);

        $geo = Geosite::create([
            ...$data,
            'slug' => $slug,
            'status' => $data['status'] ?? 'published',
        ]);

        return response()->json($geo->load('category'), 201);
    }

    public function show(Geosite $geosite)
    {
        return $geosite->load(['category','media']);
    }

    public function update(Request $request, Geosite $geosite)
    {
        $data = $request->validate([
            'category_id' => ['required','exists:categories,id'],
            'name' => ['required','string','max:150'],
            'description' => ['required','string'],
            'latitude' => ['required','numeric','between:-90,90'],
            'longitude' => ['required','numeric','between:-180,180'],
            'address' => ['nullable','string','max:255'],
            'region' => ['nullable','string','max:100'],
            'open_hours' => ['nullable','string','max:100'],
            'ticket_price' => ['nullable','integer','min:0'],
            'status' => ['nullable','in:draft,published'],
        ]);

        $slug = Str::slug($data['name']);
        if ($slug !== $geosite->slug) {
            $slug = $this->uniqueSlug($slug, $geosite->id);
        }

        $geosite->update([
            ...$data,
            'slug' => $slug,
        ]);

        return $geosite->load('category');
    }

    public function destroy(Geosite $geosite)
    {
        $geosite->delete();
        return response()->json(['message' => 'deleted']);
    }

    public function toggleStatus(Geosite $geosite)
    {
        $geosite->status = $geosite->status === 'published' ? 'draft' : 'published';
        $geosite->save();

        return response()->json([
            'id' => $geosite->id,
            'status' => $geosite->status
        ]);
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base;
        $i = 1;

        while (
            Geosite::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id','!=',$ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
