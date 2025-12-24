<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Geosite;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $categoryId = $request->query('category_id');
        $region = $request->query('region');

        $categories = Category::orderBy('name')->get();

        $geosites = Geosite::with(['category', 'media'])
            ->where('status', 'published')
            ->when($q, function($query) use ($q) {
                $query->where('name','like',"%$q%")
                      ->orWhere('address','like',"%$q%")
                      ->orWhere('region','like',"%$q%");
            })
            ->when($categoryId, fn($query) => $query->where('category_id', $categoryId))
            ->when($region, fn($query) => $query->where('region', $region))
            ->latest()
            ->get();

        // daftar region unik dari data published (buat dropdown)
        $regions = Geosite::where('status','published')
            ->whereNotNull('region')
            ->select('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        return view('guest.explore', compact('geosites','categories','regions','q','categoryId','region'));
    }
}
