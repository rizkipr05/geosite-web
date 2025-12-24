@extends('guest.layout')
@section('title','About - Geo Explore')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
  <div class="text-center space-y-4">
    <h1 class="text-3xl font-bold text-slate-900">Tentang Kami</h1>
    <p class="text-slate-600">Mengenal lebih dekat Geo Explore Probolinggo.</p>
  </div>

  <div class="bg-white rounded-3xl border border-slate-200 p-8 space-y-6 text-slate-700 leading-relaxed">
    <p>
      <strong>Geo Explore</strong> adalah platform informasi geowisata yang didedikasikan untuk memperkenalkan
      keindahan alam, keragaman geologi, dan situs warisan budaya yang ada di wilayah Kabupaten Probolinggo
      dan sekitarnya.
    </p>
    <p>
      Kami mengintegrasikan data dari OpenStreetMap dan kontributor lokal untuk menyajikan informasi
      yang akurat dan mudah diakses bagi para wisatawan, peneliti, dan masyarakat umum.
    </p>

    <h3 class="text-xl font-bold text-slate-900 pt-4">Visi Kami</h3>
    <ul class="list-disc pl-5 space-y-2">
      <li>Menjadi pusat informasi geowisata terpercaya.</li>
      <li>Mendukung pengembangan pariwisata berkelanjutan.</li>
      <li>Mengedukasi masyarakat tentang pentingnya konservasi geologi.</li>
    </ul>

    <h3 class="text-xl font-bold text-slate-900 pt-4">Hubungi Kami</h3>
    <p>
      Jika Anda memiliki pertanyaan, saran, atau ingin berkontribusi data, silakan hubungi kami melalui:
    </p>
    <div>
      <a href="mailto:info@geoexplore.id" class="text-blue-600 hover:underline">info@geoexplore.id</a>
    </div>
  </div>
</div>
@endsection
