<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login - Geo Explore</title>
  <script src="https://cdn.tailwindcss.com"></script>
  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body class="bg-white">

  <div class="min-h-screen flex">
    
    {{-- Left Side: Image / Brand --}}
    <div class="hidden lg:flex w-1/2 relative bg-slate-900 overflow-hidden">
      <img src="https://picsum.photos/seed/bromo/1200/1600" 
           class="absolute inset-0 w-full h-full object-cover opacity-60" 
           alt="Background">
      
      <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>

      <div class="relative z-10 p-16 flex flex-col justify-end h-full text-white">
        <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center mb-6 shadow-lg shadow-blue-600/30">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h1 class="text-4xl font-extrabold leading-tight mb-4">
          Kelola Geowisata <br> Kabupaten Probolinggo
        </h1>
        <p class="text-lg text-slate-300 max-w-md">
          Platform administrasi untuk memantau trafik, mengelola geosite, dan memperbarui informasi wisata.
        </p>
      </div>
    </div>

    {{-- Right Side: Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-white">
      <div class="w-full max-w-md space-y-8">
        
        <div class="text-center lg:text-left">
          <h2 class="text-3xl font-extrabold text-slate-900">Selamat Datang 👋</h2>
          <p class="mt-2 text-slate-600">Masuk ke akun admin untuk melanjutkan.</p>
        </div>

        <form id="loginForm" class="space-y-6">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                  </svg>
                </div>
                <input id="email" type="email" required 
                       class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition sm:text-sm" 
                       placeholder="nama@email.com">
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <input id="password" type="password" required 
                       class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition sm:text-sm" 
                       placeholder="••••••••">
              </div>
            </div>
          </div>

          {{-- Error Message --}}
          <div id="err" class="hidden p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
             <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
             <div class="flex-1">
               <h3 class="text-sm font-medium text-red-800">Login Gagal</h3>
               <p id="errMsg" class="text-sm text-red-600 mt-0.5">Kredensial tidak valid.</p>
             </div>
          </div>

          <button type="submit" 
                  class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition mb-4">
            Masuk Dashboard
          </button>
          
          <div class="text-center">
            <a href="/" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition">
              ← Kembali ke Website
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

<script>
document.getElementById('loginForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const err = document.getElementById('err');
  const errMsg = document.getElementById('errMsg');
  const btn = e.target.querySelector('button[type="submit"]');
  
  // reset state
  err.classList.add('hidden');
  btn.disabled = true;
  btn.classList.add('opacity-75', 'cursor-not-allowed');
  const originalBtnText = btn.innerHTML;
  btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;

  try {
    const res = await fetch('/api/admin/login', {
      method: 'POST',
      headers: {'Content-Type':'application/json','Accept':'application/json'},
      body: JSON.stringify({email, password})
    });

    if(res.ok){
      window.location.href = '/admin';
    } else {
      const j = await res.json().catch(()=>({message:'Terjadi kesalahan server'}));
      errMsg.textContent = j.message ?? 'Login gagal, periksa email dan password.';
      err.classList.remove('hidden');
    }
  } catch(error) {
     errMsg.textContent = 'Gagal menghubungi server.';
     err.classList.remove('hidden');
  } finally {
    btn.disabled = false;
    btn.classList.remove('opacity-75', 'cursor-not-allowed');
    btn.innerHTML = originalBtnText;
  }
});
</script>
</body>
</html>
