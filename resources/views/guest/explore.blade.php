@extends('guest.layout')
@section('title','Explore - Geo Explore')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  {{-- MAP --}}
  <div class="lg:col-span-2">
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
      <div id="map" style="height: 520px;"></div>
    </div>
  </div>

  {{-- FILTER + LIST --}}
  <div class="space-y-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-4">
      <div class="font-semibold mb-3">Filter</div>

      <form method="GET" action="{{ route('explore.index') }}" class="space-y-3">
        <input type="hidden" name="q" value="{{ $q }}">

        <div>
          <label class="text-sm text-slate-600">Kategori</label>
          <select name="category_id" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 bg-white">
            <option value="">Semua</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" @selected((string)$categoryId === (string)$c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="text-sm text-slate-600">Wilayah (Region)</label>
          <select name="region" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 bg-white">
            <option value="">Semua</option>
            @foreach($regions as $r)
              <option value="{{ $r }}" @selected($region === $r)>{{ $r }}</option>
            @endforeach
          </select>
        </div>

        <button class="w-full px-3 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800">
          Terapkan
        </button>
      </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
      <div class="p-4 border-b border-slate-200">
        <div class="font-semibold">Daftar Geosite</div>
        <div class="text-sm text-slate-500">Total: {{ $geosites->count() }}</div>
      </div>

      <div class="divide-y divide-slate-100 max-h-[420px] overflow-auto">
        @forelse($geosites as $g)
          @php
            $cover = $g->media->firstWhere('is_cover', true) ?? $g->media->firstWhere('type','photo');
          @endphp
          <a href="{{ route('geosites.show', $g->slug) }}" class="block p-4 hover:bg-slate-50">
            <div class="flex gap-3">
              <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 flex-shrink-0">
                @if($cover && $cover->type === 'photo')
                  <img src="{{ asset('storage/'.$cover->path) }}" class="w-full h-full object-cover">
                @endif
              </div>
              <div class="min-w-0">
                <div class="font-semibold truncate">{{ $g->name }}</div>
                <div class="text-xs text-slate-500">
                  {{ $g->category?->name ?? '-' }} • {{ $g->region ?? 'Probolinggo' }}
                </div>
                <div class="text-xs text-slate-600 truncate">{{ $g->address }}</div>
              </div>
            </div>
          </a>
        @empty
          <div class="p-4 text-sm text-slate-500">Tidak ada data.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>

<script>
  // center Probolinggo (perkiraan)
  const map = L.map('map').setView([-7.80, 113.25], 10);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  @php
  $mapData = $geosites->map(fn($g)=>[
    'name'=>$g->name,
    'slug'=>$g->slug,
    'lat'=>(float)$g->latitude,
    'lng'=>(float)$g->longitude,
    'category'=>$g->category?->name,
    'region'=>$g->region,
  ]);
  @endphp

  const data = @json($mapData);

  const markers = [];
  data.forEach(p => {
    const m = L.marker([p.lat, p.lng]).addTo(map);
    m.bindPopup(`
      <b>${p.name}</b><br>
      ${p.category ?? ''} ${p.region ? '• '+p.region : ''}<br>
      <a href="/geosites/${p.slug}">Lihat detail</a>
    `);
    markers.push(m);
  });

  if(markers.length){
    const group = L.featureGroup(markers);
    map.fitBounds(group.getBounds().pad(0.2));
  }
</script>
@endsection
