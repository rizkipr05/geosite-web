<?php

namespace App\Http\Controllers;

use App\Models\Geosite;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil beberapa geosite acak/populer untuk showcase di home
        $featured = Geosite::with(['category','media'])
            ->where('status','published')
            ->latest()
            ->take(6)
            ->get();

        return view('guest.home', compact('featured'));
    }

    public function about()
    {
        return view('guest.about');
    }
}
