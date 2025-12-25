@extends('guest.layout')
@section('title', $geosite->name.' - Geo Explore')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="lg:col-span-2 space-y-4">
    {{-- Cover --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
      <div class="h-64 bg-slate-100">
        @if($cover && $cover->type === 'photo')
          @if(Str::startsWith($cover->path, ['http','https']))
            <img src="{{ $cover->path }}" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='https://picsum.photos/seed/{{ $geosite->id }}/800/400?blur=2';this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-slate-400\'>Image not available</div>'">
          @else
            <img src="{{ asset('storage/'.$cover->path) }}" class="w-full h-full object-cover">
          @endif
        @else
          <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">No Cover</div>
        @endif
      </div>
      <div class="p-4">
        <div class="text-xs text-slate-500">{{ $geosite->region ?? 'Probolinggo' }}</div>
        <h1 class="text-2xl font-semibold mt-1">{{ $geosite->name }}</h1>
        <p class="text-sm text-slate-600 mt-2 whitespace-pre-line">{{ $geosite->description }}</p>
      </div>
    </div>

    {{-- Gallery --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4">
      <div class="font-semibold mb-3">Galeri</div>

      @if($photos->count())
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
          @foreach($photos as $p)
            <a href="{{ asset('storage/'.$p->path) }}" target="_blank">
              <img src="{{ asset('storage/'.$p->path) }}" class="w-full h-32 object-cover rounded-xl border border-slate-200">
            </a>
          @endforeach
        </div>
      @else
        <div class="text-sm text-slate-500">Belum ada foto.</div>
      @endif

      @if($videos->count())
        <div class="mt-4 space-y-2">
          @foreach($videos as $v)
            <div class="text-sm text-slate-700 break-all">
              Video: <a class="text-blue-600 hover:underline" href="{{ $v->path }}" target="_blank">{{ $v->path }}</a>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  {{-- Sidebar --}}
  <div class="space-y-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-4">
      <div class="font-semibold">Info Lokasi</div>
      <div class="text-sm text-slate-600 mt-2">
        <div><span class="text-slate-500">Alamat:</span> {{ $geosite->address ?? '-' }}</div>
        <div class="mt-1"><span class="text-slate-500">Koordinat:</span> {{ $geosite->latitude }}, {{ $geosite->longitude }}</div>
        @if($geosite->open_hours)
          <div class="mt-1"><span class="text-slate-500">Jam:</span> {{ $geosite->open_hours }}</div>
        @endif
        @if($geosite->ticket_price)
          <div class="mt-1"><span class="text-slate-500">Tiket:</span> {{ $geosite->ticket_price }}</div>
        @endif
      </div>

      <a target="_blank"
         href="https://www.google.com/maps/dir/?api=1&destination={{ $geosite->latitude }},{{ $geosite->longitude }}"
         class="mt-4 inline-block w-full text-center px-3 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800">
        Navigasi (Google Maps)
      </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
      <div id="miniMap" style="height: 260px;"></div>
    </div>
  </div>
</div>

<script>
  const mini = L.map('miniMap').setView([{{ (float)$geosite->latitude }}, {{ (float)$geosite->longitude }}], 14);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; OpenStreetMap'
  }).addTo(mini);

  L.marker([{{ (float)$geosite->latitude }}, {{ (float)$geosite->longitude }}]).addTo(mini)
    .bindPopup(`<b>{{ $geosite->name }}</b>`).openPopup();
</script>
@endsection
