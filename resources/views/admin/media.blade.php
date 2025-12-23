@extends('admin.layout')

@section('title','Media')
@section('breadcrumb','Admin / Media')
@section('pageTitle','Media')

@section('content')
<div class="bg-white border border-slate-200 rounded-2xl p-4">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <div class="md:col-span-2">
      <label class="text-sm text-slate-600">Pilih Geosite</label>
      <select id="geositeSelect" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 bg-white"></select>
    </div>
    <div class="flex items-end">
      <button onclick="loadMedia()"
              class="w-full px-3 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800">
        Load Media
      </button>
    </div>
  </div>

  <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
    <div class="border border-slate-200 rounded-2xl p-4">
      <div class="font-semibold mb-3">Upload Foto</div>
      <form id="photoForm" class="space-y-3">
        <input type="file" id="photo" accept="image/*" required class="block w-full text-sm">
        <input type="text" id="photoCaption" placeholder="Caption (opsional)"
               class="w-full rounded-lg border border-slate-200 px-3 py-2">
        <label class="flex items-center gap-2 text-sm">
          <input type="checkbox" id="photoCover"> Jadikan cover
        </label>
        <button class="px-3 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800">Upload</button>
      </form>
    </div>

    <div class="border border-slate-200 rounded-2xl p-4">
      <div class="font-semibold mb-3">Tambah Video (URL)</div>
      <form id="videoForm" class="space-y-3">
        <input type="url" id="videoUrl" placeholder="https://www.youtube.com/watch?v=..."
               class="w-full rounded-lg border border-slate-200 px-3 py-2" required>
        <input type="text" id="videoCaption" placeholder="Caption (opsional)"
               class="w-full rounded-lg border border-slate-200 px-3 py-2">
        <label class="flex items-center gap-2 text-sm">
          <input type="checkbox" id="videoCover"> Jadikan cover
        </label>
        <button class="px-3 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800">Simpan</button>
      </form>
    </div>
  </div>
</div>

<div class="mt-4 bg-white border border-slate-200 rounded-2xl overflow-hidden">
  <div class="p-3 border-b border-slate-200 font-semibold">Daftar Media</div>
  <div id="mediaGrid" class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"></div>
</div>
@endsection

@push('scripts')
<script>
let geosites = [];

async function loadGeosites(){
  // ambil banyak sekaligus: gunakan paginate besar (sementara)
  const data = await api('/api/admin/geosites?page=1');
  geosites = data?.data ?? [];
  const sel = document.getElementById('geositeSelect');
  sel.innerHTML = geosites.map(g => `<option value="${g.id}">${g.name}</option>`).join('');
}

function mediaCard(m){
  const isPhoto = m.type === 'photo';
  const cover = m.is_cover ? `<span class="text-xs px-2 py-1 rounded-full bg-slate-900 text-white">Cover</span>` : '';
  const title = `<div class="flex items-center justify-between">
    <div class="text-sm font-semibold">${m.type.toUpperCase()}</div>${cover}
  </div>`;

  let body = '';
  if(isPhoto){
    body = `<img class="w-full h-40 object-cover rounded-xl border border-slate-200" src="/storage/${m.path}" alt="">`;
  } else {
    body = `<div class="w-full h-40 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-500 text-sm">Video URL</div>
            <div class="text-xs text-slate-600 break-all mt-2">${m.path}</div>`;
  }

  return `
  <div class="border border-slate-200 rounded-2xl p-3 bg-white">
    ${title}
    <div class="mt-2">${body}</div>
    ${m.caption ? `<div class="text-sm text-slate-600 mt-2">${m.caption}</div>` : ''}
    <div class="mt-3 flex justify-end">
      <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-500"
              onclick="delMedia(${m.id})">Hapus</button>
    </div>
  </div>`;
}

async function loadMedia(){
  const geositeId = document.getElementById('geositeSelect').value;
  const list = await api(`/api/admin/geosites/${geositeId}/media`);
  document.getElementById('mediaGrid').innerHTML = (list || []).map(mediaCard).join('') || '<div class="text-sm text-slate-500">Belum ada media.</div>';
}

async function delMedia(id){
  if(!confirm('Hapus media ini?')) return;
  await api('/api/admin/media/'+id, {method:'DELETE'});
  loadMedia();
}

document.getElementById('photoForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const geositeId = document.getElementById('geositeSelect').value;

  const fd = new FormData();
  fd.append('type','photo');
  fd.append('photo', document.getElementById('photo').files[0]);
  fd.append('caption', document.getElementById('photoCaption').value || '');
  fd.append('is_cover', document.getElementById('photoCover').checked ? '1' : '0');

  await api(`/api/admin/geosites/${geositeId}/media`, {method:'POST', body: fd});
  e.target.reset();
  loadMedia();
});

document.getElementById('videoForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const geositeId = document.getElementById('geositeSelect').value;

  await api(`/api/admin/geosites/${geositeId}/media`, {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify({
      type:'video',
      video_url: document.getElementById('videoUrl').value,
      caption: document.getElementById('videoCaption').value || null,
      is_cover: document.getElementById('videoCover').checked ? 1 : 0,
    })
  });

  e.target.reset();
  loadMedia();
});

(async ()=>{
  await loadGeosites();
  await loadMedia();
})();
</script>
@endpush
