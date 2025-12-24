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

<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen">

  {{-- NAVBAR --}}
  <header class="sticky top-0 z-50">
    <div class="bg-slate-950/80 backdrop-blur-xl border-b border-white/10">
      <div class="max-w-7xl mx-auto px-4">
        <div class="h-16 flex items-center justify-between gap-3">

          {{-- Brand --}}
          <a href="{{ route('home') }}" class="flex items-center gap-2 group">
            <div class="text-white font-extrabold text-xl tracking-tight group-hover:text-cyan-200 transition">
              Geo Explore
            </div>
          </a>

          {{-- Desktop Nav --}}
          <nav class="hidden md:flex items-center gap-2">
            @php
              $navClass = "px-4 py-2 rounded-2xl text-sm font-semibold transition";
              $active   = "bg-white/10 text-white border border-white/10";
              $idle     = "text-white/70 hover:text-white hover:bg-white/5";
            @endphp

            <a href="{{ route('home') }}"
               class="{{ $navClass }} {{ request()->routeIs('home') ? $active : $idle }}">
              Home
            </a>

            <a href="{{ route('explore') }}"
               class="{{ $navClass }} {{ request()->routeIs('explore*') ? $active : $idle }}">
              Explore
            </a>

            <a href="{{ route('about') }}"
               class="{{ $navClass }} {{ request()->routeIs('about') ? $active : $idle }}">
              About
            </a>
          </nav>

          {{-- Search (desktop) --}}
          <div class="hidden md:flex items-center gap-2">
            <form action="{{ route('explore') }}" method="GET" class="relative">
              <input name="q" value="{{ request('q') }}"
                     placeholder="Cari: Bromo, pantai, air terjun..."
                     class="w-[300px] rounded-2xl bg-white/10 border border-white/10 text-white placeholder:text-white/50
                            px-4 py-2 pr-12 text-sm outline-none focus:bg-white/15 transition">
              <button type="submit"
                      class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl
                             bg-white text-slate-900 text-sm font-semibold hover:bg-slate-100 transition">
                Cari
              </button>
            </form>

            <a href="{{ route('explore') }}"
               class="px-4 py-2 rounded-2xl bg-blue-600 text-white text-sm font-bold
                      hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
              Buka Peta
            </a>
          </div>

          {{-- Mobile menu button --}}
          <button id="menuBtn"
                  class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-2xl
                         bg-white/10 border border-white/10 text-white hover:bg-white/15 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
          </button>
        </div>

        {{-- Mobile panel --}}
        <div id="mobilePanel" class="md:hidden hidden pb-4">
          <div class="pt-2 space-y-3">

            <form action="{{ route('explore') }}" method="GET" class="relative">
              <input name="q" value="{{ request('q') }}"
                     placeholder="Cari wisata..."
                     class="w-full rounded-2xl bg-white/10 border border-white/10 text-white placeholder:text-white/50
                            px-4 py-2 pr-12 text-sm outline-none focus:bg-white/15 transition">
              <button type="submit"
                      class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl
                             bg-white text-slate-900 text-sm font-semibold hover:bg-slate-100 transition">
                Cari
              </button>
            </form>

            <div class="grid grid-cols-3 gap-2">
              <a href="{{ route('home') }}" class="text-center px-3 py-2 rounded-2xl text-sm font-semibold bg-white/10 text-white">Home</a>
              <a href="{{ route('explore') }}" class="text-center px-3 py-2 rounded-2xl text-sm font-semibold bg-white/5 text-white/70">Explore</a>
              <a href="{{ route('about') }}" class="text-center px-3 py-2 rounded-2xl text-sm font-semibold bg-white/5 text-white/70">About</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </header>

  {{-- HERO --}}
  @yield('hero')

  {{-- CONTENT --}}
  <main class="max-w-6xl mx-auto px-4 py-8 flex-grow w-full">
    @yield('content')
  </main>

  {{-- FOOTER (FIXED & CLEAN) --}}
  <footer class="bg-slate-950 text-white/70 border-t border-white/10">
    <div class="relative overflow-hidden">

      {{-- glow halus --}}
      <div class="pointer-events-none absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[700px] rounded-full blur-3xl bg-blue-600/10"></div>

      <div class="relative max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10">

          {{-- Brand --}}
          <div class="md:col-span-5 space-y-4">
            <div class="text-white font-extrabold text-xl">Geo Explore</div>
            <p class="text-sm leading-relaxed text-white/60 max-w-md">
              Platform informasi geowisata berbasis lokasi untuk membantu wisatawan
              menemukan destinasi, belajar geologi, dan mengenal kearifan lokal Probolinggo.
            </p>
          </div>

          {{-- Navigasi --}}
          <div class="md:col-span-3">
            <h3 class="text-white font-bold mb-4">Navigasi</h3>
            <ul class="space-y-3 text-sm">
              <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
              <li><a href="{{ route('explore') }}" class="hover:text-white transition">Explore</a></li>
              <li><a href="{{ route('about') }}" class="hover:text-white transition">Tentang</a></li>
            </ul>
          </div>

          {{-- Kontak --}}
          <div class="md:col-span-4">
            <h3 class="text-white font-bold mb-4">Kontak</h3>
            <div class="space-y-3 text-sm text-white/70">
              <div>Kabupaten Probolinggo, Jawa Timur</div>
              <a href="mailto:info@geoexplore.id" class="hover:text-white hover:underline transition">
                info@geoexplore.id
              </a>
            </div>
          </div>

        </div>
      </div>

      {{-- Bottom --}}
      <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-xs text-white/50">
          &copy; {{ date('Y') }} Geo Explore Probolinggo. All rights reserved.
        </div>
      </div>

    </div>
  </footer>

  {{-- Mobile Menu Script --}}
  <script>
    const btn = document.getElementById('menuBtn');
    const panel = document.getElementById('mobilePanel');
    if (btn && panel) {
      btn.addEventListener('click', () => {
        panel.classList.toggle('hidden');
      });
    }
  </script>

</body>
</html>
