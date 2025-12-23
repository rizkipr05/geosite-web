@extends('admin.layout')

@section('title','Geosite')
@section('breadcrumb','Admin / Geosite')
@section('pageTitle','Geosite')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
  <div class="flex gap-2">
    <input id="q" placeholder="Cari nama/alamat/region..."
           class="w-full md:w-80 rounded-lg border border-slate-200 px-3 py-2 text-sm bg-white">
    <button onclick="load(1)" class="px-3 py-2 rounded-lg border border-slate-200 text-sm hover:bg-slate-50 bg-white">
      Cari
    </button>
  </div>
  <button onclick="openModal()"
          class="px-3 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800">
    + Tambah Geosite
  </button>
</div>

<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-slate-600">
        <tr>
          <th class="text-left p-3">Nama</th>
          <th class="text-left p-3">Kategori</th>
          <th class="text-left p-3">Region</th>
          <th class="text-left p-3">Status</th>
          <th class="text-right p-3">Aksi</th>
        </tr>
      </thead>
      <tbody id="rows"></tbody>
    </table>
  </div>

  <div class="p-3 border-t border-slate-200 flex items-center justify-between">
    <div class="text-sm text-slate-600" id="pageInfo">-</div>
    <div class="flex gap-2">
      <button id="prevBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">Prev</button>
      <button id="nextBtn" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">Next</button>
    </div>
  </div>
</div>

{{-- Modal Geosite --}}
<div id="modal" class="fixed inset-0 bg-black/30 hidden items-center justify-center p-4">
  <div class="bg-white w-full max-w-2xl rounded-2xl border border-slate-200 shadow-sm">
    <div class="p-4 border-b border-slate-200 flex items-center justify-between">
      <div class="font-semibold" id="modalTitle">Tambah Geosite</div>
      <button class="p-2 rounded hover:bg-slate-100" onclick="closeModal()">✕</button>
    </div>

    <form id="form" class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
      <input type="hidden" id="id">

      <div class="md:col-span-2">
        <label class="text-sm text-slate-600">Nama</label>
        <input id="name" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
      </div>

      <div>
        <label class="text-sm text-slate-600">Kategori</label>
        <select id="category_id" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 bg-white"></select>
      </div>

      <div>
        <label class="text-sm text-slate-600">Region</label>
        <input id="region" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" placeholder="mis. Paiton">
      </div>

      <div class="md:col-span-2">
        <label class="text-sm text-slate-600">Alamat</label>
        <input id="address" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
      </div>

      <div>
        <label class="text-sm text-slate-600">Latitude</label>
        <input id="latitude" type="number" step="0.0000001" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
      </div>

      <div>
        <label class="text-sm text-slate-600">Longitude</label>
        <input id="longitude" type="number" step="0.0000001" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
      </div>

      <div class="md:col-span-2">
        <label class="text-sm text-slate-600">Deskripsi</label>
        <textarea id="description" required rows="4" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2"></textarea>
      </div>

      <div>
        <label class="text-sm text-slate-600">Jam Buka</label>
        <input id="open_hours" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" placeholder="08.00-17.00">
      </div>

      <div>
        <label class="text-sm text-slate-600">Harga Tiket</label>
        <input id="ticket_price" type="number" min="0" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" placeholder="0">
      </div>

      <div class="md:col-span-2 flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeModal()" class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">
          Batal
        </button>
        <button class="px-3 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-800">
          Simpan
        </button>
      </div>

      <p id="err" class="md:col-span-2 text-sm text-red-600 hidden"></p>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
let page = 1, lastPage = 1;
let categories = [];

function badge(status){
  return status === 'published'
    ? `<span class="px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">Published</span>`
    : `<span class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700">Draft</span>`;
}

async function loadCategories(){
  categories = await api('/api/admin/categories') || [];
  const sel = document.getElementById('category_id');
  sel.innerHTML = categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
}

function openModal(item=null){
  document.getElementById('modal').classList.remove('hidden');
  document.getElementById('modal').classList.add('flex');
  document.getElementById('err').classList.add('hidden');

  if(item){
    document.getElementById('modalTitle').textContent = 'Edit Geosite';
    document.getElementById('id').value = item.id;
    document.getElementById('name').value = item.name;
    document.getElementById('category_id').value = item.category_id;
    document.getElementById('region').value = item.region ?? '';
    document.getElementById('address').value = item.address ?? '';
    document.getElementById('latitude').value = item.latitude;
    document.getElementById('longitude').value = item.longitude;
    document.getElementById('description').value = item.description;
    document.getElementById('open_hours').value = item.open_hours ?? '';
    document.getElementById('ticket_price').value = item.ticket_price ?? '';
  }else{
    document.getElementById('modalTitle').textContent = 'Tambah Geosite';
    ['id','name','region','address','latitude','longitude','description','open_hours','ticket_price'].forEach(id=>{
      document.getElementById(id).value = '';
    });
  }
}

function closeModal(){
  document.getElementById('modal').classList.add('hidden');
  document.getElementById('modal').classList.remove('flex');
}

async function load(p=1){
  page = p;
  const q = document.getElementById('q').value || '';
  const data = await api(`/api/admin/geosites?page=${page}&q=${encodeURIComponent(q)}`);
  if(!data) return;

  lastPage = data.last_page;
  document.getElementById('pageInfo').textContent = `Hal ${data.current_page} / ${data.last_page} • Total ${data.total}`;

  document.getElementById('prevBtn').disabled = data.current_page <= 1;
  document.getElementById('nextBtn').disabled = data.current_page >= data.last_page;

  document.getElementById('rows').innerHTML = data.data.map(g => `
    <tr class="border-t border-slate-100">
      <td class="p-3 font-medium">${g.name}</td>
      <td class="p-3 text-slate-600">${g.category?.name ?? '-'}</td>
      <td class="p-3 text-slate-600">${g.region ?? '-'}</td>
      <td class="p-3">${badge(g.status)}</td>
      <td class="p-3">
        <div class="flex justify-end gap-2">
          <button class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50"
                  onclick='edit(${g.id})'>Edit</button>
          <button class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50"
                  onclick='toggleStatus(${g.id})'>Toggle</button>
          <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-500"
                  onclick='del(${g.id})'>Hapus</button>
        </div>
      </td>
    </tr>
  `).join('');
}

document.getElementById('prevBtn').addEventListener('click', ()=> load(Math.max(1, page-1)));
document.getElementById('nextBtn').addEventListener('click', ()=> load(Math.min(lastPage, page+1)));

async function edit(id){
  const g = await api('/api/admin/geosites/'+id);
  openModal(g);
}

async function toggleStatus(id){
  await api(`/api/admin/geosites/${id}/status`, {method:'PATCH'});
  load(page);
}

async function del(id){
  if(!confirm('Hapus geosite ini?')) return;
  await api('/api/admin/geosites/'+id, {method:'DELETE'});
  load(page);
}

document.getElementById('form').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const err = document.getElementById('err');
  err.classList.add('hidden');

  const id = document.getElementById('id').value;
  const payload = {
    category_id: Number(document.getElementById('category_id').value),
    name: document.getElementById('name').value,
    region: document.getElementById('region').value || null,
    address: document.getElementById('address').value || null,
    latitude: Number(document.getElementById('latitude').value),
    longitude: Number(document.getElementById('longitude').value),
    description: document.getElementById('description').value,
    open_hours: document.getElementById('open_hours').value || null,
    ticket_price: document.getElementById('ticket_price').value ? Number(document.getElementById('ticket_price').value) : null,
  };

  try{
    if(id){
      await api('/api/admin/geosites/'+id, {
        method:'PUT',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify(payload)
      });
    }else{
      await api('/api/admin/geosites', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify(payload)
      });
    }
    closeModal();
    load(page);
  }catch(e){
    err.textContent = 'Gagal menyimpan. Cek input (kategori, koordinat, deskripsi).';
    err.classList.remove('hidden');
  }
});

(async ()=>{
  await loadCategories();
  await load(1);
})();
</script>
@endpush
