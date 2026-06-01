@extends('layouts.admin')

@section('title', 'Kelola Akun Murid')

@section('konten')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-fredoka text-2xl font-bold">Akun Murid</h1>
        <p class="text-sm text-stone-500 font-semibold">Kelola akun login murid</p>
    </div>
    <button onclick="bukaModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white font-fredoka font-semibold rounded-xl px-5 py-2.5 shadow flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        Tambah Murid
    </button>
</div>

@if (session('sukses'))
    <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">{{ session('sukses') }}</div>
@endif

<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-stone-500 text-left">
            <tr>
                <th class="px-5 py-3 font-semibold">Nama</th>
                <th class="px-5 py-3 font-semibold">Username</th>
                <th class="px-5 py-3 font-semibold w-24">Streak</th>
                <th class="px-5 py-3 font-semibold w-32 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse ($murid as $m)
                <tr>
                    <td class="px-5 py-3 font-bold text-stone-700">{{ $m->nama }}</td>
                    <td class="px-5 py-3 text-stone-500">{{ $m->username }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-bold {{ $m->jumlah_streak > 0 ? 'text-orange-500 bg-orange-50' : 'text-stone-400 bg-stone-100' }} px-2.5 py-1 rounded-full">{{ $m->jumlah_streak }} hari</span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="editMurid(this)" data-id="{{ $m->id }}" data-nama="{{ $m->nama }}" data-username="{{ $m->username }}"
                                class="text-xs font-bold text-sky-600 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-lg">Edit</button>
                            <form method="POST" action="{{ route('admin.murid.destroy', $m->id) }}" onsubmit="return confirm('Hapus akun murid ini? Progres belajarnya ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-8 text-center text-stone-400">Belum ada murid.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center px-4 z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 id="modalJudul" class="font-fredoka text-lg font-bold mb-4">Tambah Murid</h3>
        <form id="modalForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="modalMethod" value="POST">

            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Nama</label>
                <input type="text" name="nama" id="fNama" required
                    class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Username</label>
                <input type="text" name="username" id="fUsername" required
                    class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200">
            </div>
            <div class="mb-5">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Password</label>
                <input type="text" name="password" id="fPassword"
                    class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200">
                <p id="passInfo" class="text-xs text-stone-400 mt-1"></p>
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
    const storeUrl = "{{ route('admin.murid.store') }}";
    const baseUrl = "{{ url('admin/murid') }}";

    function bukaModal(d = null) {
        form.reset();
        if (d) {
            document.getElementById('modalJudul').textContent = 'Edit Murid';
            document.getElementById('modalMethod').value = 'PUT';
            form.action = baseUrl + '/' + d.id;
            document.getElementById('fNama').value = d.nama;
            document.getElementById('fUsername').value = d.username;
            document.getElementById('fPassword').required = false;
            document.getElementById('passInfo').textContent = 'Kosongkan kalau tidak ingin ganti password.';
        } else {
            document.getElementById('modalJudul').textContent = 'Tambah Murid';
            document.getElementById('modalMethod').value = 'POST';
            form.action = storeUrl;
            document.getElementById('fPassword').required = true;
            document.getElementById('passInfo').textContent = 'Saran: pakai tanggal lahir murid (mis. 20180115).';
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function editMurid(btn) { bukaModal(btn.dataset); }
    function tutupModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    modal.addEventListener('click', (e) => { if (e.target === modal) tutupModal(); });
</script>
@endpush