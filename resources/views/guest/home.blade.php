@php
  use Illuminate\Support\Str;
@endphp

@extends('guest.layout')
@section('title','Home - Geo Explore')

@section('hero')
  <section class="relative overflow-hidden bg-slate-950">
    {{-- background layers --}}
    <div class="absolute inset-0">
      <img
        src="{{ asset('storage/asset/images (1).jpeg') }}"
        class="w-full h-full object-cover opacity-35"
        onerror="this.style.display='none'"
        alt="Hero background"
      >
      <div class="absolute inset-0 bg-gradient-to-b from-slate-950/70 via-slate-950/60 to-slate-950"></div>
      <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full blur-3xl bg-blue-600/20"></div>
      <div class="absolute -bottom-28 -right-28 w-[620px] h-[620px] rounded-full blur-3xl bg-emerald-500/10"></div>
      <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 22px 22px;"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-4 py-20 md:py-24">
      <div class="max-w-3xl mx-auto text-center space-y-6">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-white/90 text-sm backdrop-blur">
          <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
          Geo Explore • Kabupaten Probolinggo
        </div>

        <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight tracking-tight">
          Jelajahi Keindahan
          <span class="bg-gradient-to-r from-blue-400 to-cyan-300 bg-clip-text text-transparent">
            Geopark Probolinggo
          </span>
        </h1>

        <p class="text-slate-200/90 text-lg md:text-xl max-w-2xl mx-auto">
          Temukan destinasi wisata alam, budaya, dan situs geologi yang menakjubkan — lengkap dengan peta, detail, dan navigasi.
        </p>

        {{-- actions --}}
        <div class="mt-8 flex items-center justify-center gap-4">
          <a href="{{ route('explore') }}"
             class="px-8 py-3 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-600/30">
            Mulai Menjelajah
          </a>
          <a href="{{ route('about') }}"
             class="px-8 py-3 rounded-full bg-white/10 backdrop-blur text-white font-semibold hover:bg-white/15 transition border border-white/10">
            Tentang Kami
          </a>
        </div>
      </div>
    </div>

    {{-- bottom fade to content --}}
    <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-b from-transparent to-slate-50"></div>
  </section>
@endsection

@section('content')
<div class="space-y-14">

  {{-- FEATURED SECTION --}}
  <section>
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3 mb-6">
      <div>
        <div class="inline-flex items-center gap-2 text-sm text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-full">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
          Pilihan terbaik
        </div>
        <h2 class="text-2xl md:text-3xl font-extrabold mt-3 text-slate-900">
          Destinasi Unggulan
        </h2>
        <p class="text-slate-600 mt-2">
          Beberapa tempat yang paling sering direkomendasikan untuk kamu kunjungi.
        </p>
      </div>

      <a href="{{ route('explore') }}"
         class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
        Lihat Semua <span>→</span>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse($featured as $g)
        @php
          $cover = $g->media->firstWhere('is_cover', true) ?? $g->media->firstWhere('type','photo');
          $coverUrl = null;
          if($cover && $cover->type === 'photo'){
            $coverUrl = Str::startsWith($cover->path, ['http','https']) ? $cover->path : asset('storage/'.$cover->path);
          }
        @endphp

        <a href="{{ route('geosites.show', $g->slug) }}"
           class="group relative block overflow-hidden rounded-3xl border border-slate-200 bg-white hover:shadow-xl hover:shadow-slate-200/60 transition">
          <div class="aspect-[16/10] bg-slate-100 relative">
            @if($coverUrl)
              <img src="{{ $coverUrl }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-500" alt="{{ $g->name }}">
            @else
              <div class="w-full h-full flex items-center justify-center text-slate-400">No Image</div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/15 to-transparent"></div>

            <div class="absolute top-3 left-3 flex items-center gap-2">
              <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-white/90 backdrop-blur border border-white/40">
                {{ $g->category?->name ?? 'Wisata' }}
              </span>
              <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-white/15 text-white backdrop-blur border border-white/10">
                {{ $g->region ?? 'Probolinggo' }}
              </span>
            </div>
          </div>

          <div class="p-5">
            <h3 class="font-extrabold text-lg md:text-xl text-slate-900 group-hover:text-blue-700 transition">
              {{ $g->name }}
            </h3>
            <p class="text-sm text-slate-600 mt-2 line-clamp-2">
              {{ Str::limit(strip_tags($g->description ?? ''), 120) }}
            </p>

            <div class="mt-4 flex items-center justify-between">
              <span class="text-sm text-slate-500 inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="truncate max-w-[220px]">{{ $g->address ?? ($g->region ?? 'Kabupaten Probolinggo') }}</span>
              </span>
              <span class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700">
                Detail <span class="group-hover:translate-x-0.5 transition">→</span>
              </span>
            </div>
          </div>
        </a>
      @empty
        <div class="col-span-full py-12 text-center">
          <div class="mx-auto max-w-md rounded-3xl border border-slate-200 bg-white p-8">
            <div class="text-3xl text-slate-400 mb-4 inline-flex justify-center">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            </div>
            <div class="mt-3 font-bold text-slate-900">Belum ada destinasi unggulan</div>
            <div class="mt-1 text-sm text-slate-600">
              Tambahkan beberapa geosite melalui admin lalu publish.
            </div>
            <a href="{{ route('explore') }}"
               class="mt-5 inline-flex items-center justify-center px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
              Buka Explore
            </a>
          </div>
        </div>
      @endforelse
    </div>
  </section>

  {{-- INTRODUCTION SECTION --}}
  <section class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
    <div class="space-y-5">
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-100 text-sm font-semibold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Mengenal Geopark
      </div>

      <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">
        Warisan Bumi yang Memukau di Probolinggo
      </h2>

      <p class="text-slate-600 leading-relaxed text-lg">
        Kabupaten Probolinggo bukan hanya tentang Bromo. Di sini ada banyak geosite bernilai ilmiah, estetika, dan budaya yang siap kamu jelajahi.
      </p>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-4">
          <div class="font-bold text-slate-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            Edukasi Geologi
          </div>
          <div class="text-sm text-slate-600 mt-1">Pahami proses pembentukan bumi lewat situs yang nyata.</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4">
          <div class="font-bold text-slate-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Kearifan Lokal
          </div>
          <div class="text-sm text-slate-600 mt-1">Kenali budaya masyarakat setempat yang unik dan hangat.</div>
        </div>
      </div>

      <div class="pt-2">
        <a href="{{ route('about') }}"
           class="inline-flex items-center gap-2 text-blue-700 font-semibold hover:text-blue-800 transition">
          Pelajari Selengkapnya <span>→</span>
        </a>
      </div>
    </div>

    <div class="relative">
      <div class="absolute -inset-2 bg-gradient-to-tr from-blue-600/20 to-emerald-500/10 blur-2xl rounded-[2.5rem]"></div>
      <div class="relative rounded-[2.5rem] overflow-hidden border border-slate-200 bg-white shadow-xl">
        <img src="https://picsum.photos/seed/bromo/900/700" class="w-full h-[380px] md:h-[460px] object-cover" alt="Bromo">
        <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent"></div>
        <div class="absolute bottom-4 left-4 right-4">
          <div class="rounded-2xl bg-white/90 backdrop-blur border border-white/30 p-4">
            <div class="font-bold text-slate-900">Rekomendasi perjalanan</div>
            <div class="text-sm text-slate-600 mt-1">
              Coba mulai dari Explore → pilih kategori → cek peta → klik navigasi.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FEATURES / WHY VISIT --}}
  <section class="relative overflow-hidden rounded-[2.5rem] bg-slate-950 text-white">
    <div class="absolute inset-0">
      <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full blur-3xl bg-blue-600/15"></div>
      <div class="absolute -bottom-28 -right-28 w-[620px] h-[620px] rounded-full blur-3xl bg-emerald-500/10"></div>
      <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 24px 24px;"></div>
    </div>

    <div class="relative p-8 md:p-12 text-center">
      <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Mengapa Mengunjungi Geo Explore?</h2>
      <p class="text-slate-300 max-w-2xl mx-auto mb-10 text-lg">
        Pengalaman wisata yang memanjakan mata sekaligus memperkaya wawasan.
      </p>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
        <div class="p-6 bg-white/5 rounded-3xl border border-white/10 backdrop-blur space-y-3 hover:bg-white/10 transition">
          <div class="w-12 h-12 rounded-2xl bg-blue-600/20 border border-blue-600/25 flex items-center justify-center text-blue-400">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
          </div>
          <h3 class="text-xl font-bold">Fenomena Geologi Unik</h3>
          <p class="text-slate-300 text-sm leading-relaxed">
            Jejak aktivitas vulkanik purba, formasi batuan langka, dan lanskap yang dramatis.
          </p>
        </div>

        <div class="p-6 bg-white/5 rounded-3xl border border-white/10 backdrop-blur space-y-3 hover:bg-white/10 transition">
          <div class="w-12 h-12 rounded-2xl bg-emerald-600/20 border border-emerald-600/25 flex items-center justify-center text-emerald-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
          </div>
          <h3 class="text-xl font-bold">Biodiversitas Kaya</h3>
          <p class="text-slate-300 text-sm leading-relaxed">
            Jelajahi ekosistem beragam — dari hutan, sungai, hingga pesisir.
          </p>
        </div>

        <div class="p-6 bg-white/5 rounded-3xl border border-white/10 backdrop-blur space-y-3 hover:bg-white/10 transition">
          <div class="w-12 h-12 rounded-2xl bg-orange-600/20 border border-orange-600/25 flex items-center justify-center text-orange-400">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
          </div>
          <h3 class="text-xl font-bold">Budaya & Kearifan Lokal</h3>
          <p class="text-slate-300 text-sm leading-relaxed">
            Tradisi lokal yang hidup berdampingan dengan alam dan membentuk identitas daerah.
          </p>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA SECTION --}}
  <section class="text-center py-10">
    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-10 md:p-14 shadow-sm">
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900 text-white text-sm font-semibold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
        Siap Menjelajah?
      </div>
      <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mt-5 mb-4">
        Buka Peta Digital & Mulai Petualanganmu
      </h2>
      <p class="text-slate-600 max-w-2xl mx-auto mb-8 text-lg">
        Rencanakan perjalananmu sekarang — klik peta, pilih destinasi, dan langsung navigasi ke lokasi.
      </p>
      <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('explore') }}"
           class="px-8 py-4 rounded-2xl bg-slate-900 text-white font-bold text-lg hover:bg-slate-800 transition shadow-xl">
          Buka Explore
        </a>
        <a href="{{ route('about') }}"
           class="px-8 py-4 rounded-2xl bg-slate-50 text-slate-900 font-bold text-lg hover:bg-slate-100 transition border border-slate-200">
          Tentang Geo Explore
        </a>
      </div>
    </div>
  </section>

</div>
@endsection
