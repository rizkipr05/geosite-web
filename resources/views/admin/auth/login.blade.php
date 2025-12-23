<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login - Geo Explore</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
  <div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
      <div class="mb-5">
        <div class="text-xl font-semibold">Admin Login</div>
        <div class="text-sm text-slate-500">Masuk untuk mengelola data Geo Explore</div>
      </div>

      <form id="loginForm" class="space-y-3">
        <div>
          <label class="text-sm text-slate-600">Email</label>
          <input id="email" type="email" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-900/20">
        </div>
        <div>
          <label class="text-sm text-slate-600">Password</label>
          <input id="password" type="password" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-900/20">
        </div>

        <button class="w-full rounded-lg bg-slate-900 text-white py-2 hover:bg-slate-800">
          Login
        </button>

        <p id="err" class="text-sm text-red-600 hidden"></p>
      </form>
    </div>
  </div>

<script>
document.getElementById('loginForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const err = document.getElementById('err');
  err.classList.add('hidden');

  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;

  const res = await fetch('/api/admin/login', {
    method: 'POST',
    headers: {'Content-Type':'application/json','Accept':'application/json'},
    body: JSON.stringify({email, password})
  });

  if(res.ok){
    window.location.href = '/admin';
  } else {
    const j = await res.json().catch(()=>({message:'Login gagal'}));
    err.textContent = j.message ?? 'Login gagal';
    err.classList.remove('hidden');
  }
});
</script>
</body>
</html>
