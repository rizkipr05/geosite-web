@php
  use Illuminate\Support\Str;
@endphp

@extends('guest.layout')
@section('title','Explore - Geo Explore')

@section('content')
<div class="space-y-6">

  {{-- HEADER --}}
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-sm font-semibold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Explore Geosite
      </div>
      <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mt-3">Jelajah Peta Geowisata</h1>
      <p class="text-slate-600 mt-1">
        Temukan geosite di Kabupaten Probolinggo — klik marker atau pilih dari daftar.
      </p>
    </div>

    <div class="flex items-center gap-2">
      <div class="px-4 py-2 rounded-2xl bg-white border border-slate-200 text-sm">
        Total: <span class="font-bold text-slate-900">{{ $geosites->count() }}</span>
      </div>

      <a href="{{ route('explore.index') }}"
         class="px-4 py-2 rounded-2xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
        Reset
      </a>
    </div>
  </div>

  {{-- ACTIVE FILTER CHIPS --}}
  <div class="flex flex-wrap items-center gap-2">
    @if(!empty($q))
      <span class="px-3 py-1.5 rounded-full bg-slate-900 text-white text-xs font-semibold inline-flex items-center gap-1">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        {{ $q }}
      </span>
    @endif
    @if(!empty($region))
      <span class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-semibold inline-flex items-center gap-1">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        {{ $region }}
      </span>
    @endif

    @if(empty($q) && empty($region))
      <span class="text-sm text-slate-500">Menampilkan semua geosite.</span>
    @endif
  </div>

  {{-- MAIN GRID --}}
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    {{-- MAP --}}
    <div class="lg:col-span-7">
      <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm lg:sticky lg:top-20">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
          <div>
            <div class="font-extrabold text-slate-900">Peta Interaktif</div>
            <div class="text-sm text-slate-500">Klik marker untuk detail cepat</div>
          </div>
          <button type="button" id="fitBtn"
                  class="px-3 py-2 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
            Fit Semua
          </button>
        </div>

        <div id="map" class="h-[520px]"></div>

        <div class="px-5 py-4 border-t border-slate-200 text-sm text-slate-600 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
          <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
            </span>
            <span>Gunakan filter untuk mempersempit hasil.</span>
          </div>
          <a href="{{ route('explore.index') }}" class="text-blue-700 font-semibold hover:text-blue-800 transition">
            Lihat semua tanpa filter →
          </a>
        </div>
      </div>
    </div>

    {{-- SIDE: FILTER + LIST --}}
    <div class="lg:col-span-5 space-y-6">

      {{-- FILTER --}}
      <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div>
            <div class="font-extrabold text-slate-900">Filter</div>
            <div class="text-sm text-slate-500">Wilayah</div>
          </div>

          <a href="{{ route('explore.index') }}"
             class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
            Reset
          </a>
        </div>

        <form method="GET" action="{{ route('explore.index') }}" class="space-y-4">
          {{-- kalau kamu punya search di navbar yang kirim q, ini menjaga nilai --}}
          <input type="hidden" name="q" value="{{ $q }}">

          <div class="hidden">
             
          </div>

          <div>
            <label class="text-sm font-semibold text-slate-700">Wilayah (Region)</label>
            <select name="region"
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
              <option value="">Semua</option>
              @foreach($regions as $r)
                <option value="{{ $r }}" @selected($region === $r)>{{ $r }}</option>
              @endforeach
            </select>
          </div>

          <button class="w-full px-4 py-3 rounded-2xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
            Terapkan Filter
          </button>
        </form>
      </div>

      {{-- LIST --}}
      <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-200">
          <div class="flex items-center justify-between">
            <div>
              <div class="font-extrabold text-slate-900">Daftar Geosite</div>
              <div class="text-sm text-slate-500">Klik item untuk fokus ke marker</div>
            </div>
            <div class="text-xs px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 font-semibold">
              {{ $geosites->count() }} hasil
            </div>
          </div>
        </div>

        <div class="divide-y divide-slate-100 max-h-[520px] overflow-auto" id="list">
          @forelse($geosites as $g)
            @php
              $cover = $g->media->firstWhere('is_cover', true) ?? $g->media->firstWhere('type','photo');
              $coverUrl = null;
              if($cover && $cover->type === 'photo'){
                $coverUrl = Str::startsWith($cover->path, ['http','https'])
                  ? $cover->path
                  : asset('storage/'.$cover->path);
              }
            @endphp

            <button type="button"
                    class="w-full text-left p-4 hover:bg-slate-50 transition"
                    onclick="focusMarker('{{ $g->slug }}')">

              <div class="flex gap-3">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 overflow-hidden border border-slate-200 flex-shrink-0">
                  @if($coverUrl)
                    <img src="{{ $coverUrl }}" class="w-full h-full object-cover" alt="{{ $g->name }}">
                  @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                  @endif
                </div>

                <div class="min-w-0 flex-1">
                  <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                      <div class="font-extrabold text-slate-900 truncate">{{ $g->name }}</div>

                      <div class="mt-1 flex flex-wrap gap-1">
                        <span class="text-[11px] px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 font-semibold">
                          {{ $g->region ?? 'Probolinggo' }}
                        </span>
                      </div>

                      <div class="text-xs text-slate-600 mt-2 truncate">
                        {{ $g->address ?? 'Alamat belum tersedia' }}
                      </div>
                    </div>

                    <a href="{{ route('geosites.show', $g->slug) }}"
                       class="flex-shrink-0 px-3 py-2 rounded-2xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                      Detail
                    </a>
                  </div>

                  <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ number_format((float)$g->latitude, 4) }}, {{ number_format((float)$g->longitude, 4) }}
                    </span>
                    <a target="_blank"
                       href="https://www.google.com/maps/dir/?api=1&destination={{ $g->latitude }},{{ $g->longitude }}"
                       class="text-blue-700 font-semibold hover:text-blue-800 transition">
                      Arah →
                    </a>
                  </div>
                </div>
              </div>
            </button>
          @empty
            <div class="p-6 text-sm text-slate-500">
              Tidak ada data geosite sesuai filter.
            </div>
          @endforelse
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  // center Probolinggo (perkiraan)
  const map = L.map('map', { scrollWheelZoom: true }).setView([-7.80, 113.25], 10);

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
      'address'=>$g->address,
    ]);
  @endphp

  const data = @json($mapData);

  const markersBySlug = {};
  const markers = [];

  function popupHtml(p){
    const toDetail = `/geosites/${p.slug}`;
    const navUrl = `https://www.google.com/maps/dir/?api=1&destination=${p.lat},${p.lng}`;
    return `
      <div style="min-width:220px">
        <div style="font-weight:800; margin-bottom:2px">${escapeHtml(p.name)}</div>
        <div style="font-size:12px; color:#64748b; margin-bottom:8px">
          ${p.region ? escapeHtml(p.region) : ''}
        </div>
        ${p.address ? `<div style="font-size:12px; color:#334155; margin-bottom:10px">${escapeHtml(p.address)}</div>` : ''}
        <div style="display:flex; gap:8px">
          <a href="${toDetail}" style="display:inline-block; padding:8px 10px; border-radius:12px; background:#0f172a; color:#fff; text-decoration:none; font-size:12px; font-weight:700">Detail</a>
          <a href="${navUrl}" target="_blank" style="display:inline-block; padding:8px 10px; border-radius:12px; background:#2563eb; color:#fff; text-decoration:none; font-size:12px; font-weight:700">Navigasi</a>
        </div>
      </div>
    `;
  }

  // helper prevent XSS in popup
  function escapeHtml(s){
    return String(s ?? '')
      .replaceAll('&','&amp;')
      .replaceAll('<','&lt;')
      .replaceAll('>','&gt;')
      .replaceAll('"','&quot;')
      .replaceAll("'","&#039;");
  }

  data.forEach(p => {
    if(!p.lat || !p.lng) return;

    const m = L.marker([p.lat, p.lng]).addTo(map);
    m.bindPopup(popupHtml(p));
    markers.push(m);
    markersBySlug[p.slug] = m;
  });

  function fitAll(){
    if(markers.length){
      const group = L.featureGroup(markers);
      map.fitBounds(group.getBounds().pad(0.2));
    }
  }

  // fit bounds awal
  fitAll();

  // button fit
  document.getElementById('fitBtn')?.addEventListener('click', fitAll);

  // fokus marker dari list
  window.focusMarker = function(slug){
    const m = markersBySlug[slug];
    if(!m) return;
    const latlng = m.getLatLng();
    map.setView(latlng, Math.max(map.getZoom(), 13), { animate: true });
    m.openPopup();

    // auto scroll ke map di mobile
    if(window.innerWidth < 1024){
      document.getElementById('map')?.scrollIntoView({ behavior:'smooth', block:'start' });
    }
  }
</script>
@endsection
