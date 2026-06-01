@extends('layouts.admin')

@section('title', 'Kelola Pelajaran')

@section('konten')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-fredoka text-2xl font-bold">Pelajaran</h1>
        <p class="text-sm text-stone-500 font-semibold">Kelola daftar pelajaran</p>
    </div>
    <button onclick="bukaModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white font-fredoka font-semibold rounded-xl px-5 py-2.5 shadow flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        Tambah Pelajaran
    </button>
</div>

@if (session('sukses'))
    <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">{{ session('sukses') }}</div>
@endif

<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-stone-500 text-left">
            <tr>
                <th class="px-5 py-3 font-semibold w-20">Urutan</th>
                <th class="px-5 py-3 font-semibold">Nama Pelajaran</th>
                <th class="px-5 py-3 font-semibold w-32 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse ($pelajaran as $p)
                <tr>
                    <td class="px-5 py-3 text-stone-400 font-bold">{{ $p->urutan }}</td>
                    <td class="px-5 py-3 font-bold text-stone-700">{{ $p->nama }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="editPelajaran(this)" data-id="{{ $p->id }}" data-nama="{{ $p->nama }}" data-urutan="{{ $p->urutan }}"
                                class="text-xs font-bold text-sky-600 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-lg">Edit</button>
                            <form method="POST" action="{{ route('admin.pelajaran.destroy', $p->id) }}" onsubmit="return confirm('Hapus pelajaran ini? Semua modul & materi di dalamnya ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-5 py-8 text-center text-stone-400">Belum ada pelajaran.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal tambah/edit --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center px-4 z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 id="modalJudul" class="font-fredoka text-lg font-bold mb-4">Tambah Pelajaran</h3>
        <form id="modalForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="modalMethod" value="POST">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Nama Pelajaran</label>
                <input type="text" name="nama" id="fNama" required
                    class="w-full rounded-xl border border-stone-200 px-4 py-2.5 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
            </div>
            <div class="mb-5">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Urutan</label>
                <input type="number" name="urutan" id="fUrutan" value="0"
                    class="w-full rounded-xl border border-stone-200 px-4 py-2.5 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="tutupModal()" class="px-4 py-2.5 rounded-xl text-stone-500 font-semibold hover:bg-stone-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('modal');
    const form = document.getElementById('modalForm');
    const storeUrl = "{{ route('admin.pelajaran.store') }}";
    const baseUrl = "{{ url('admin/pelajaran') }}";

    function bukaModal(id = null, nama = '', urutan = 0) {
        if (id) {
            document.getElementById('modalJudul').textContent = 'Edit Pelajaran';
            document.getElementById('modalMethod').value = 'PUT';
            form.action = baseUrl + '/' + id;
            document.getElementById('fNama').value = nama;
            document.getElementById('fUrutan').value = urutan;
        } else {
            document.getElementById('modalJudul').textContent = 'Tambah Pelajaran';
            document.getElementById('modalMethod').value = 'POST';
            form.action = storeUrl;
            document.getElementById('fNama').value = '';
            document.getElementById('fUrutan').value = 0;
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function editPelajaran(btn) {
        bukaModal(btn.dataset.id, btn.dataset.nama, btn.dataset.urutan);
    }
    function tutupModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    modal.addEventListener('click', (e) => { if (e.target === modal) tutupModal(); });
</script>
@endpush