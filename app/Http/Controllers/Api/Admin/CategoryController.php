<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
        ]);

        $slug = Str::slug($data['name']);
        $slug = $this->uniqueSlug($slug);

        $cat = Category::create([
            'name' => $data['name'],
            'slug' => $slug,
        ]);

        return response()->json($cat, 201);
    }

    public function show(Category $category)
    {
        return $category;
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
        ]);

        $slug = Str::slug($data['name']);
        if ($slug !== $category->slug) {
            $slug = $this->uniqueSlug($slug, $category->id);
        }

        $category->update([
            'name' => $data['name'],
            'slug' => $slug,
        ]);

        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'deleted']);
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base;
        $i = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id','!=',$ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
