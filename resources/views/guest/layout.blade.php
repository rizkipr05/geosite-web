<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Geo Explore')</title>
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Leaflet --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">
  {{-- NAVBAR --}}
  <header class="sticky top-0 z-50 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 shadow-lg transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8 w-full md:w-auto">
        <a href="{{ route('home') }}" class="font-bold text-xl flex items-center gap-2 text-white">
          Geo Explore
        </a>

        <nav class="flex gap-4 md:gap-6 text-sm font-medium text-slate-300">
          <a href="{{ route('home') }}" class="hover:text-white transition {{ request()->routeIs('home') ? 'text-white font-bold' : '' }}">Home</a>
          <a href="{{ route('explore') }}" class="hover:text-white transition {{ request()->routeIs('explore*') ? 'text-white font-bold' : '' }}">Explore</a>
          <a href="{{ route('about') }}" class="hover:text-white transition {{ request()->routeIs('about') ? 'text-white font-bold' : '' }}">About</a>
        </nav>
      </div>

      <div class="flex items-center gap-3 w-full md:w-auto">
        <form action="{{ route('explore') }}" method="GET" class="flex gap-2 w-full">
          <input name="q" value="{{ request('q') }}"
                 placeholder="Cari wisata..."
                 class="w-full md:w-48 rounded-full border border-slate-700 px-4 py-2 text-sm bg-slate-800 text-white placeholder-slate-400 focus:bg-slate-700 outline-none transition">
        </form>
      </div>
    </div>
  </header>

  {{-- FULL WIDTH HERO --}}
  @yield('hero')

  <main class="max-w-6xl mx-auto px-4 py-8">
    @yield('content')
  </main>

  <footer class="bg-slate-900 text-slate-400 mt-12 border-t border-slate-800">
    <div class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
      {{-- Brand --}}
      <div class="md:col-span-2 space-y-4">
        <div class="text-white font-bold text-xl flex items-center gap-2">
          Geo Explore
        </div>
        <p class="text-sm leading-relaxed max-w-sm">
          Platform informasi geowisata Kabupaten Probolinggo. Temukan keindahan alam dan warisan geologi yang menakjubkan.
        </p>
      </div>

      {{-- Links --}}
      <div>
        <h3 class="text-white font-semibold mb-4">Navigasi</h3>
        <ul class="space-y-2 text-sm">
          <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
          <li><a href="{{ route('explore') }}" class="hover:text-white transition">Jelajah Peta</a></li>
          <li><a href="{{ route('about') }}" class="hover:text-white transition">Tentang Kami</a></li>
        </ul>
      </div>

      {{-- Contact --}}
      <div>
        <h3 class="text-white font-semibold mb-4">Kontak</h3>
        <ul class="space-y-2 text-sm">
          <li>Kabupaten Probolinggo, Jawa Timur</li>
          <li><a href="mailto:info@geoexplore.id" class="hover:text-white transition">info@geoexplore.id</a></li>
        </ul>
      </div>
    </div>

    {{-- Bottom --}}
    <div class="border-t border-slate-800 py-6 text-center text-xs text-slate-500">
      &copy; {{ date('Y') }} Geo Explore Probolinggo. All rights reserved.
    </div>
  </footer>
</body>
</html>
