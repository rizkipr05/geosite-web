@extends('guest.layout')
@section('title','Home - Geo Explore')

@section('hero')
  <div class="relative bg-slate-900 overflow-hidden min-h-[500px] flex items-center justify-center text-center px-4">
    <div class="absolute inset-0">
      {{-- Background Image --}}
      <img src="https://picsum.photos/seed/geo/1920/1080"
           class="w-full h-full object-cover opacity-50"
           onerror="this.style.display='none'">
      <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-slate-900/10"></div>
    </div>
    <div class="relative z-10 max-w-3xl mx-auto space-y-6">
      <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight drop-shadow-lg">
        Jelajahi Keindahan <span class="text-blue-400">Geopark Probolinggo</span>
      </h1>
      <p class="text-slate-200 text-lg md:text-xl max-w-2xl mx-auto drop-shadow-md">
        Temukan destinasi wisata alam, budaya, dan situs geologi menakjubkan di sekitar Anda.
      </p>
      <div class="pt-6 flex justify-center gap-4">
        <a href="{{ route('explore') }}" class="px-8 py-3 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow-lg hover:shadow-blue-500/30">
          Mulai Menjelajah
        </a>
        <a href="{{ route('about') }}" class="px-8 py-3 rounded-full bg-white/10 backdrop-blur text-white font-semibold hover:bg-white/20 transition border border-white/20">
          Tentang Kami
        </a>
      </div>
    </div>
  </div>
@endsection

@section('content')
<div class="space-y-12">

  {{-- FEATURED SECTION --}}
  <div>
    <h2 class="text-2xl font-bold mb-6 text-slate-800 border-b pb-4 border-slate-200">
      Destinasi Unggulan
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse($featured as $g)
        @php
          $cover = $g->media->firstWhere('is_cover', true) ?? $g->media->firstWhere('type','photo');
        @endphp
        <a href="{{ route('geosites.show', $g->slug) }}" class="group block bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition">
          <div class="aspect-video bg-slate-100 relative overflow-hidden">
            @if($cover && $cover->type === 'photo')
              <img src="{{ asset('storage/'.$cover->path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            @else
              <div class="w-full h-full flex items-center justify-center text-slate-400">No Image</div>
            @endif
            <div class="absolute top-2 right-2 px-2 py-1 bg-white/90 backdrop-blur rounded text-xs font-semibold shadow-sm">
              {{ $g->category?->name ?? 'Wisata' }}
            </div>
          </div>
          <div class="p-4">
            <h3 class="font-bold text-lg mb-1 group-hover:text-blue-600 transition">{{ $g->name }}</h3>
            <p class="text-sm text-slate-500 mb-3">{{ $g->region ?? 'Probolinggo' }}</p>
            <p class="text-sm text-slate-600 line-clamp-2">
              {{ Str::limit($g->description, 100) }}
            </p>
          </div>
        </a>
      @empty
        <div class="col-span-full py-12 text-center text-slate-500">
          Belum ada data destinasi.
        </div>
      @endforelse
    </div>
  </div>

</div>
@endsection
