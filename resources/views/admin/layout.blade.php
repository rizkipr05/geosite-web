<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin Geo Explore')</title>

  {{-- Tailwind CDN (cepat, cocok untuk admin panel) --}}
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="min-h-screen flex">
  {{-- Sidebar --}}
  <aside class="w-64 bg-white border-r border-slate-200 hidden md:block">
    <div class="px-5 py-4 border-b border-slate-200">
      <div class="text-lg font-semibold">Geo Explore</div>
      <div class="text-xs text-slate-500">Admin Panel</div>
    </div>

    <nav class="p-3 space-y-1">
      <a href="{{ route('admin.dashboard') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-100 font-medium' : '' }}">
        Dashboard
      </a>
      <a href="{{ route('admin.categories') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->routeIs('admin.categories') ? 'bg-slate-100 font-medium' : '' }}">
        Kategori
      </a>
      <a href="{{ route('admin.geosites') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->routeIs('admin.geosites') ? 'bg-slate-100 font-medium' : '' }}">
        Geosite
      </a>
      <a href="{{ route('admin.media') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->routeIs('admin.media') ? 'bg-slate-100 font-medium' : '' }}">
        Media
      </a>
    </nav>
  </aside>

  {{-- Main --}}
  <main class="flex-1">
    {{-- Topbar --}}
    <header class="bg-white border-b border-slate-200">
      <div class="px-4 md:px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <button class="md:hidden p-2 rounded hover:bg-slate-100" onclick="toggleMobileSidebar()">☰</button>
          <div>
            <div class="text-sm text-slate-500">@yield('breadcrumb', 'Admin')</div>
            <div class="font-semibold">@yield('pageTitle', 'Dashboard')</div>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <span id="adminUser" class="text-sm text-slate-600 hidden sm:inline"></span>
          <button onclick="adminLogout()"
                  class="px-3 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800">
            Logout
          </button>
        </div>
      </div>
    </header>

    {{-- Content --}}
    <section class="px-4 md:px-6 py-6">
      @yield('content')
    </section>
  </main>
</div>

{{-- Mobile Sidebar --}}
<div id="mobileSidebar" class="fixed inset-0 bg-black/30 hidden md:hidden" onclick="toggleMobileSidebar()">
  <div class="w-72 bg-white h-full" onclick="event.stopPropagation()">
    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
      <div>
        <div class="text-lg font-semibold">Geo Explore</div>
        <div class="text-xs text-slate-500">Admin Panel</div>
      </div>
      <button class="p-2 rounded hover:bg-slate-100" onclick="toggleMobileSidebar()">✕</button>
    </div>
    <nav class="p-3 space-y-1">
      <a class="block px-3 py-2 rounded-lg hover:bg-slate-100" href="{{ route('admin.dashboard') }}">Dashboard</a>
      <a class="block px-3 py-2 rounded-lg hover:bg-slate-100" href="{{ route('admin.categories') }}">Kategori</a>
      <a class="block px-3 py-2 rounded-lg hover:bg-slate-100" href="{{ route('admin.geosites') }}">Geosite</a>
      <a class="block px-3 py-2 rounded-lg hover:bg-slate-100" href="{{ route('admin.media') }}">Media</a>
    </nav>
  </div>
</div>

<script>
  function toggleMobileSidebar(){
    document.getElementById('mobileSidebar').classList.toggle('hidden');
  }

  // helper fetch: cookie jwt otomatis ikut
  async function api(url, options = {}) {
    const res = await fetch(url, {
      headers: { 'Accept': 'application/json', ...(options.headers || {}) },
      ...options
    });

    if (res.status === 401) {
      // token invalid/expired
      window.location.href = "{{ route('admin.login') }}";
      return null;
    }
    if (!res.ok) {
      const msg = await res.text().catch(()=> 'Request error');
      throw new Error(msg);
    }
    const ct = res.headers.get('content-type') || '';
    return ct.includes('application/json') ? res.json() : res.text();
  }

  async function loadAdminMe(){
    try{
      const me = await api('/api/admin/me');
      if(me) document.getElementById('adminUser').textContent = me.email;
    }catch(e){ /* ignore */ }
  }

  async function adminLogout(){
    try{
      await api('/api/admin/logout', { method: 'POST' });
    }catch(e){}
    window.location.href = "{{ route('admin.login') }}";
  }

  loadAdminMe();
</script>

@stack('scripts')
</body>
</html>
