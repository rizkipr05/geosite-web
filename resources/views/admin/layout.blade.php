<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin Geo Explore')</title>

  {{-- Tailwind CDN (cepat, cocok untuk admin panel) --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="min-h-screen flex">
  {{-- Sidebar --}}
  {{-- Sidebar (Desktop) --}}
  <aside class="w-64 bg-slate-900 border-r border-slate-800 hidden md:flex flex-col">
    <div class="px-6 py-5 border-b border-slate-800">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-blue-600/20">
          <span class="text-white font-bold text-sm">A</span>
        </div>
        <div>
          <div class="text-white font-bold text-lg leading-none">Geo Admin</div>
          <div class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider">Control Panel</div>
        </div>
      </div>
    </div>

    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
      <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 px-2 mt-2">Main Menu</div>
      
      @php
        $linkClass = "flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition duration-200 group";
        $activeClass = "bg-blue-600 text-white shadow-lg shadow-blue-900/20";
        $inactiveClass = "text-slate-400 hover:bg-slate-800 hover:text-white";
      @endphp

      <a href="{{ route('admin.dashboard') }}"
         class="{{ $linkClass }} {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        Dashboard
      </a>
      <a href="{{ route('admin.geosites') }}"
         class="{{ $linkClass }} {{ request()->routeIs('admin.geosites') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
        Geosite
      </a>
      <a href="{{ route('admin.media') }}"
         class="{{ $linkClass }} {{ request()->routeIs('admin.media') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        Media Gallery
      </a>
    </nav>
    
    <div class="p-4 border-t border-slate-800">
      <div class="bg-slate-800/50 rounded-xl p-3 flex items-center gap-3">
        <div class="min-w-[32px] w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs text-white">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <div class="overflow-hidden">
          <div class="text-xs text-white font-semibold truncate" id="adminUserSidebar">Admin</div>
          <div class="text-[10px] text-slate-400">Online</div>
        </div>
      </div>
    </div>
  </aside>

  {{-- Main --}}
  <main class="flex-1 flex flex-col min-w-0 bg-slate-50">
    {{-- Topbar --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
      <div class="px-4 md:px-8 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <button class="md:hidden p-2 -ml-2 rounded-lg hover:bg-slate-100 text-slate-600" onclick="toggleMobileSidebar()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
          </button>
          <div class="hidden md:block">
            <div class="text-xs text-slate-500 font-medium mb-0.5">@yield('breadcrumb', 'Admin Area')</div>
            <h1 class="text-xl font-bold text-slate-800 leading-none">@yield('pageTitle', 'Dashboard')</h1>
          </div>
          <div class="md:hidden font-bold text-slate-800">
             @yield('pageTitle', 'Dashboard')
          </div>
        </div>

        <div class="flex items-center gap-3">
          <!-- User Dropdown -->
          <div class="relative">
            <button onclick="toggleUserDropdown()" class="flex items-center gap-3 hover:bg-slate-50 rounded-xl p-2 transition outline-none">
              <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
              </div>
              <div class="hidden md:block text-left">
                <div class="text-xs font-bold text-slate-700" id="headerUser">Admin</div>
                <div class="text-[10px] text-slate-500">Administrator</div>
              </div>
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-1 z-50 transform origin-top-right transition-all">
              <div class="px-4 py-3 border-b border-slate-50 md:hidden">
                 <div class="text-xs font-bold text-slate-700" id="headerUserMobile">Admin</div>
                 <div class="text-[10px] text-slate-500">Administrator</div>
              </div>
              <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Lihat Website
              </a>
              <button onclick="adminLogout()" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
              </button>
            </div>
          </div>
        </div>
      </div>
    </header>

    {{-- Content --}}
    <section class="flex-1 px-4 md:px-8 py-6 overflow-y-auto">
      @yield('content')
    </section>
  </main>
</div>

{{-- Mobile Sidebar --}}
<div id="mobileSidebar" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden md:hidden transition-opacity" onclick="toggleMobileSidebar()">
  <div class="w-72 bg-slate-900 h-full shadow-2xl flex flex-col" onclick="event.stopPropagation()">
    <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-blue-600/20">
          <span class="text-white font-bold text-sm">A</span>
        </div>
        <div class="text-white font-bold text-lg">Geo Admin</div>
      </div>
      <button class="p-2 -mr-2 text-slate-400 hover:text-white" onclick="toggleMobileSidebar()">✕</button>
    </div>
    
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
      @php
        // reuse classes
      @endphp
      <a href="{{ route('admin.dashboard') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        Dashboard
      </a>
      <a href="{{ route('admin.geosites') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.geosites') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
        Geosite
      </a>
      <a href="{{ route('admin.media') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.media') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        Media Gallery
      </a>
    </nav>

    <div class="p-4 border-t border-slate-800">
        <button onclick="adminLogout()" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-red-900/20 hover:text-red-400 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Keluar Sesi
        </button>
    </div>
  </div>
</div>

<script>
  function toggleMobileSidebar(){
    document.getElementById('mobileSidebar').classList.toggle('hidden');
  }

  function toggleUserDropdown(){
    document.getElementById('userDropdown').classList.toggle('hidden');
  }

  // Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdown');
    const button = event.target.closest('button[onclick="toggleUserDropdown()"]');
    if (!button && !dropdown.contains(event.target) && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('hidden');
    }
  });

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
      if(me) {
        if(document.getElementById('adminUserSidebar')) document.getElementById('adminUserSidebar').textContent = me.email;
        if(document.getElementById('headerUser')) document.getElementById('headerUser').textContent = me.name || me.email;
        if(document.getElementById('headerUserMobile')) document.getElementById('headerUserMobile').textContent = me.name || me.email;
      }
    }catch(e){ /* ignore */ }
  }

  async function adminLogout(){
    try{
      await api('/api/admin/logout', { method: 'POST' });
    }catch(e){}
    window.location.href = "{{ route('admin.login') }}";
  }

  loadAdminMe();
  
  // Custom SweetAlert Mixin
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
  });

  const Confirm = Swal.mixin({
    customClass: {
      confirmButton: 'px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 font-medium ml-2',
      cancelButton: 'px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium',
      popup: 'rounded-2xl border border-slate-100 shadow-xl'
    },
    buttonsStyling: false
  });
</script>

@stack('scripts')
</body>
</html>
