@extends('admin.layout')

@section('title','Kategori')
@section('breadcrumb','Admin / Kategori')
@section('pageTitle','Kategori')

@section('content')
<div class="flex items-center justify-between mb-4">
  <div>
    <div class="text-sm text-slate-500">Kelola kategori untuk filter destinasi</div>
  </div>
  <button onclick="openCategoryModal()"
          class="px-3 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800">
    + Tambah
  </button>
</div>

<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
  <div class="p-3 border-b border-slate-200">
    <input id="q" placeholder="Cari kategori..."
           class="w-full md:w-72 rounded-lg border border-slate-200 px-3 py-2 text-sm">
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-slate-600">
        <tr>
          <th class="text-left p-3">Nama</th>
          <th class="text-left p-3">Slug</th>
          <th class="text-right p-3">Aksi</th>
        </tr>
      </thead>
      <tbody id="rows"></tbody>
    </table>
  </div>
</div>

{{-- Modal --}}
<div id="modal" class="fixed inset-0 bg-black/30 hidden items-center justify-center p-4">
  <div class="bg-white w-full max-w-lg rounded-2xl border border-slate-200 shadow-sm">
    <div class="p-4 border-b border-slate-200 flex items-center justify-between">
      <div class="font-semibold" id="modalTitle">Tambah Kategori</div>
      <button class="p-2 rounded hover:bg-slate-100" onclick="closeModal()">✕</button>
    </div>

    <form id="form" class="p-4 space-y-3">
      <input type="hidden" id="id">
      <div>
        <label class="text-sm text-slate-600">Nama</label>
        <input id="name" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
      </div>

      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeModal()"
                class="px-3 py-2 rounded-lg border border-slate-200 text-sm hover:bg-slate-50">
          Batal
        </button>
        <button class="px-3 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800">
          Simpan
        </button>
      </div>

      <p id="err" class="text-sm text-red-600 hidden"></p>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
let categories = [];

function openCategoryModal(cat=null){
  document.getElementById('modal').classList.remove('hidden');
  document.getElementById('modal').classList.add('flex');
  document.getElementById('err').classList.add('hidden');

  if(cat){
    document.getElementById('modalTitle').textContent = 'Edit Kategori';
    document.getElementById('id').value = cat.id;
    document.getElementById('name').value = cat.name;
  }else{
    document.getElementById('modalTitle').textContent = 'Tambah Kategori';
    document.getElementById('id').value = '';
    document.getElementById('name').value = '';
  }
}

function closeModal(){
  document.getElementById('modal').classList.add('hidden');
  document.getElementById('modal').classList.remove('flex');
}

function render(){
  const q = (document.getElementById('q').value || '').toLowerCase();
  const filtered = categories.filter(c => c.name.toLowerCase().includes(q) || c.slug.toLowerCase().includes(q));

  document.getElementById('rows').innerHTML = filtered.map(c => `
    <tr class="border-t border-slate-100">
      <td class="p-3 font-medium">${c.name}</td>
      <td class="p-3 text-slate-600">${c.slug}</td>
      <td class="p-3">
        <div class="flex justify-end gap-2">
          <button class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50"
                  onclick='openCategoryModal(${JSON.stringify(c)})'>Edit</button>
          <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-500"
                  onclick="del(${c.id})">Hapus</button>
        </div>
      </td>
    </tr>
  `).join('');
}

async function load(){
  categories = await api('/api/admin/categories') || [];
  render();
}

async function del(id){
  if(!confirm('Hapus kategori ini?')) return;
  await api('/api/admin/categories/'+id, {method:'DELETE'});
  load();
}

document.getElementById('q').addEventListener('input', render);

document.getElementById('form').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const err = document.getElementById('err');
  err.classList.add('hidden');

  const id = document.getElementById('id').value;
  const name = document.getElementById('name').value;

  try{
    if(id){
      await api('/api/admin/categories/'+id, {
        method:'PUT',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({name})
      });
    }else{
      await api('/api/admin/categories', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({name})
      });
    }
    closeModal();
    load();
  }catch(e){
    err.textContent = 'Gagal menyimpan. Pastikan nama valid.';
    err.classList.remove('hidden');
  }
});

load();
</script>
@endpush
