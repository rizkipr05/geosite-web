@extends('admin.layout')

@section('title','Dashboard')
@section('breadcrumb','Admin / Dashboard')
@section('pageTitle','Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  <div class="bg-white border border-slate-200 rounded-2xl p-4">
    <div class="text-sm text-slate-500">Total Kategori</div>
    <div id="statCategories" class="text-2xl font-semibold mt-1">-</div>
  </div>
  <div class="bg-white border border-slate-200 rounded-2xl p-4">
    <div class="text-sm text-slate-500">Total Geosite</div>
    <div id="statGeosites" class="text-2xl font-semibold mt-1">-</div>
  </div>
  <div class="bg-white border border-slate-200 rounded-2xl p-4">
    <div class="text-sm text-slate-500">Total Media</div>
    <div id="statMedia" class="text-2xl font-semibold mt-1">-</div>
  </div>
</div>

<div class="mt-6 bg-white border border-slate-200 rounded-2xl p-4">
  <div class="font-semibold mb-2">Catatan</div>
  <ul class="text-sm text-slate-600 list-disc pl-5 space-y-1">
    <li>Kelola kategori untuk filter destinasi wisata.</li>
    <li>Kelola geosite (lokasi wisata) + status publish/unpublish.</li>
    <li>Kelola media foto/video untuk galeri detail destinasi.</li>
  </ul>
</div>
@endsection

@push('scripts')
<script>
(async ()=>{
  const cats = await api('/api/admin/categories');
  document.getElementById('statCategories').textContent = cats?.length ?? 0;

  const geoPage = await api('/api/admin/geosites?page=1');
  document.getElementById('statGeosites').textContent = geoPage?.total ?? 0;

  // ambil media count sederhana: (opsi cepat) iterasi 1 halaman geosite lalu hit media per geosite, atau bikin endpoint stat sendiri.
  // sementara: tampilkan "-"
  document.getElementById('statMedia').textContent = '-';
})();
</script>
@endpush
