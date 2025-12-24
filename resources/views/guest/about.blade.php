@extends('guest.layout')
@section('title','About - Geo Explore')

@section('content')
<div class="space-y-10">

  {{-- HERO --}}
  <section class="relative overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white">
    <div class="absolute inset-0">
      <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full blur-3xl bg-blue-600/10"></div>
      <div class="absolute -bottom-28 -right-28 w-[620px] h-[620px] rounded-full blur-3xl bg-emerald-500/10"></div>
      <div class="absolute inset-0 opacity-[0.06]"
           style="background-image: radial-gradient(circle at 1px 1px, #0f172a 1px, transparent 0); background-size: 22px 22px;">
      </div>
    </div>

    <div class="relative p-8 md:p-12">
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-sm font-semibold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Tentang Geo Explore
      </div>

      <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mt-4 leading-tight">
        Mengenal lebih dekat <span class="text-blue-700">Geo Explore Probolinggo</span>
      </h1>

      <p class="text-slate-600 mt-3 text-lg max-w-3xl leading-relaxed">
        Geo Explore adalah platform informasi geowisata berbasis lokasi untuk membantu wisatawan menemukan geosite,
        memahami keunikan geologi, dan mengenal kearifan lokal Kabupaten Probolinggo.
      </p>

      <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <a href="{{ route('explore') }}"
           class="px-6 py-3 rounded-2xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 transition shadow-sm">
          Buka Explore
        </a>
        <a href="mailto:info@geoexplore.id"
           class="px-6 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-bold text-sm hover:bg-slate-100 transition">
          Hubungi Kami
        </a>
      </div>
    </div>
  </section>

  {{-- ABOUT TEXT + HIGHLIGHTS --}}
  <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-7">
      <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 md:p-10 space-y-5 leading-relaxed">
        <h2 class="text-2xl font-extrabold text-slate-900">Apa itu Geo Explore?</h2>

        <p class="text-slate-700">
          <strong>Geo Explore</strong> didedikasikan untuk memperkenalkan keindahan alam, keragaman geologi, serta
          situs budaya di wilayah Kabupaten Probolinggo dan sekitarnya.
        </p>

        <p class="text-slate-700">
          Kami mengintegrasikan data dari <strong>OpenStreetMap</strong> dan kontributor lokal untuk menyajikan informasi
          yang akurat, mudah diakses, serta membantu perencanaan perjalanan wisata secara lebih efektif.
        </p>

        <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
            <div class="text-blue-600 mb-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div class="font-extrabold text-slate-900">Berbasis Lokasi</div>
            <div class="text-sm text-slate-600 mt-1">Peta interaktif + navigasi langsung ke lokasi.</div>
          </div>

          <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
            <div class="text-emerald-600 mb-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div class="font-extrabold text-slate-900">Edukasi Geologi</div>
            <div class="text-sm text-slate-600 mt-1">Info geosite disusun agar mudah dipahami.</div>
          </div>
        </div>
      </div>
    </div>

    {{-- SIDE CARDS --}}
    <div class="lg:col-span-5 space-y-6">
      <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8">
        <h3 class="text-xl font-extrabold text-slate-900">Tujuan Utama</h3>
        <div class="mt-4 space-y-3">
          <div class="flex gap-3">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
              <div class="font-bold text-slate-900">Informasi Geowisata</div>
              <div class="text-sm text-slate-600">Menampilkan destinasi berbasis lokasi yang mudah dijelajahi.</div>
            </div>
          </div>

          <div class="flex gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <div>
              <div class="font-bold text-slate-900">Wisata Berkelanjutan</div>
              <div class="text-sm text-slate-600">Mendorong kunjungan yang ramah lingkungan & edukatif.</div>
            </div>
          </div>

          <div class="flex gap-3">
            <div class="w-10 h-10 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
              <div class="font-bold text-slate-900">Kearifan Lokal</div>
              <div class="text-sm text-slate-600">Mengangkat budaya lokal sebagai nilai tambah wisata.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-slate-950 rounded-[2.5rem] border border-white/10 p-8 text-white">
        <h3 class="text-xl font-extrabold">Visi Kami</h3>
        <ul class="mt-4 space-y-3 text-white/80 text-sm leading-relaxed">
          <li class="flex gap-2">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Menjadi pusat informasi geowisata terpercaya di Probolinggo.
          </li>
          <li class="flex gap-2">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Mendukung pengembangan pariwisata berkelanjutan.
          </li>
          <li class="flex gap-2">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Mengedukasi masyarakat tentang konservasi geologi.
          </li>
        </ul>

        <div class="mt-6 rounded-3xl bg-white/5 border border-white/10 p-5">
          <div class="text-sm font-semibold text-white/90">Ingin berkontribusi data?</div>
          <div class="text-sm text-white/70 mt-1">
            Kamu bisa bantu menambah foto, deskripsi, atau lokasi geosite.
          </div>
          <a href="mailto:info@geoexplore.id"
             class="mt-4 inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
            Email Kami
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section class="rounded-[2.5rem] border border-slate-200 bg-white p-8 md:p-10 text-center">
    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900 text-white text-sm font-bold">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
      Mulai Jelajah
    </div>
    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mt-4">
      Temukan destinasi terbaik di Probolinggo sekarang
    </h2>
    <p class="text-slate-600 mt-2 max-w-2xl mx-auto">
      Gunakan Explore untuk melihat peta, filter kategori/wilayah, dan dapatkan navigasi ke lokasi tujuan.
    </p>
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
      <a href="{{ route('explore') }}"
         class="px-7 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition">
        Buka Explore
      </a>
      <a href="{{ route('home') }}"
         class="px-7 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 font-bold hover:bg-slate-100 transition">
        Kembali ke Home
      </a>
    </div>
  </section>

</div>
@endsection
