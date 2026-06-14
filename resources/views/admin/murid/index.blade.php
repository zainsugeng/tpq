@extends('layouts.admin')

@section('title', 'Kelola Akun Murid')

@section('konten')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-fredoka text-2xl font-bold">Akun Murid</h1>
        <p class="text-sm text-stone-500 font-semibold">Kelola akun & data murid</p>
    </div>
    <button onclick="bukaModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white font-fredoka font-semibold rounded-xl px-5 py-2.5 shadow flex items-center gap-2 shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        Tambah Murid
    </button>
</div>

@if (session('sukses'))
    <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">{{ session('sukses') }}</div>
@endif

{{-- Cari --}}
<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-4">
    <div class="relative flex-1">
        <svg class="w-5 h-5 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3m1.3-5.4a6.7 6.7 0 11-13.4 0 6.7 6.7 0 0113.4 0z"/></svg>
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama, username, atau nama ortu..."
            class="w-full rounded-xl border border-stone-200 pl-11 pr-4 py-2.5 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
    </div>
    <button type="submit" class="rounded-xl bg-stone-800 hover:bg-stone-900 text-white font-semibold px-5 py-2.5">Cari</button>
    @if (request('cari'))
        <a href="{{ route('admin.murid.index') }}" class="rounded-xl border border-stone-200 text-stone-600 font-semibold px-5 py-2.5 hover:bg-stone-50 text-center">Reset</a>
    @endif
</form>

<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[880px]">
            <thead class="bg-stone-50 text-stone-500 text-left">
                <tr>
                    <th class="px-5 py-3 font-semibold">Nama</th>
                    <th class="px-5 py-3 font-semibold">Nama Ortu/Wali</th>
                    <th class="px-5 py-3 font-semibold w-28">Jenis Kelamin</th>
                    <th class="px-5 py-3 font-semibold w-40">Tanggal Lahir</th>
                    <th class="px-5 py-3 font-semibold">Username</th>
                    <th class="px-5 py-3 font-semibold w-24">Streak</th>
                    <th class="px-5 py-3 font-semibold w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($murid as $m)
                    <tr>
                        <td class="px-5 py-3 font-bold text-stone-700">{{ $m->nama }}</td>
                        <td class="px-5 py-3 text-stone-500">{{ $m->nama_ortu ?: '—' }}</td>
                        <td class="px-5 py-3 text-stone-500">{{ $m->jenis_kelamin === 'L' ? 'Laki-laki' : ($m->jenis_kelamin === 'P' ? 'Perempuan' : '—') }}</td>
                        <td class="px-5 py-3 text-stone-500">{{ $m->tanggal_lahir ? \Carbon\Carbon::parse($m->tanggal_lahir)->format('d-m-Y') : '—' }}</td>
                        <td class="px-5 py-3 text-stone-500">{{ $m->username }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-bold {{ $m->jumlah_streak > 0 ? 'text-orange-500 bg-orange-50' : 'text-stone-400 bg-stone-100' }} px-2.5 py-1 rounded-full">{{ $m->jumlah_streak }} hari</span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="editMurid(this)"
                                    data-id="{{ $m->id }}"
                                    data-nama="{{ $m->nama }}"
                                    data-nama_ortu="{{ $m->nama_ortu }}"
                                    data-jenis_kelamin="{{ $m->jenis_kelamin }}"
                                    data-tanggal_lahir="{{ $m->tanggal_lahir ? \Carbon\Carbon::parse($m->tanggal_lahir)->format('Y-m-d') : '' }}"
                                    data-username="{{ $m->username }}"
                                    class="text-xs font-bold text-sky-600 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-lg">Edit</button>
                                <form method="POST" action="{{ route('admin.murid.destroy', $m->id) }}" onsubmit="return confirm('Hapus akun murid ini? Progres belajarnya ikut terhapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-8 text-center text-stone-400">
                        {{ request('cari') ? 'Tidak ada murid yang cocok.' : 'Belum ada murid.' }}
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if ($murid->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4">
        <p class="text-sm text-stone-500 font-semibold">
            Menampilkan {{ $murid->firstItem() }}–{{ $murid->lastItem() }} dari {{ $murid->total() }}
        </p>
        <div class="flex items-center gap-2">
            @if ($murid->onFirstPage())
                <span class="px-4 py-2 rounded-xl text-stone-300 font-semibold bg-stone-50">Sebelumnya</span>
            @else
                <a href="{{ $murid->previousPageUrl() }}" class="px-4 py-2 rounded-xl text-stone-600 font-semibold bg-white border border-stone-200 hover:bg-stone-50">Sebelumnya</a>
            @endif

            <span class="text-sm text-stone-500 font-semibold px-1">Hal {{ $murid->currentPage() }}/{{ $murid->lastPage() }}</span>

            @if ($murid->hasMorePages())
                <a href="{{ $murid->nextPageUrl() }}" class="px-4 py-2 rounded-xl text-white font-semibold bg-emerald-500 hover:bg-emerald-600">Berikutnya</a>
            @else
                <span class="px-4 py-2 rounded-xl text-stone-300 font-semibold bg-stone-50">Berikutnya</span>
            @endif
        </div>
    </div>
@endif

{{-- Modal --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center px-4 z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
        <h3 id="modalJudul" class="font-fredoka text-lg font-bold mb-4">Tambah Murid</h3>

        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-rose-50 text-rose-600 px-4 py-3 text-sm font-semibold">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="modalForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="modalMethod" value="POST">
            <input type="hidden" name="edit_id" id="fEditId">

            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Nama Murid</label>
                <input type="text" name="nama" id="fNama" required
                    class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Nama Orang Tua / Wali</label>
                <input type="text" name="nama_ortu" id="fNamaOrtu" required
                    class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Jenis Kelamin</label>
                <div class="flex gap-3">
                    <label class="flex-1 flex items-center gap-2 rounded-xl border border-stone-200 px-4 py-2.5 cursor-pointer hover:bg-stone-50 has-[:checked]:border-emerald-400 has-[:checked]:bg-emerald-50">
                        <input type="radio" name="jenis_kelamin" id="fJK_L" value="L" required
                            class="w-4 h-4 text-emerald-500 focus:ring-emerald-400">
                        <span class="text-sm font-semibold text-stone-600">Laki-laki</span>
                    </label>
                    <label class="flex-1 flex items-center gap-2 rounded-xl border border-stone-200 px-4 py-2.5 cursor-pointer hover:bg-stone-50 has-[:checked]:border-emerald-400 has-[:checked]:bg-emerald-50">
                        <input type="radio" name="jenis_kelamin" id="fJK_P" value="P"
                            class="w-4 h-4 text-emerald-500 focus:ring-emerald-400">
                        <span class="text-sm font-semibold text-stone-600">Perempuan</span>
                    </label>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="fTanggalLahir" required
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

    function bukaModal(d = {}) {
        if (d.id) {
            document.getElementById('modalJudul').textContent = 'Edit Murid';
            document.getElementById('modalMethod').value = 'PUT';
            form.action = baseUrl + '/' + d.id;
            document.getElementById('fEditId').value = d.id;
            document.getElementById('fPassword').required = false;
            document.getElementById('passInfo').textContent = 'Kosongkan kalau tidak ingin ganti password.';
        } else {
            document.getElementById('modalJudul').textContent = 'Tambah Murid';
            document.getElementById('modalMethod').value = 'POST';
            form.action = storeUrl;
            document.getElementById('fEditId').value = '';
            document.getElementById('fPassword').required = true;
            document.getElementById('passInfo').textContent = 'Saran: pakai tanggal lahir murid (mis. 20180115).';
        }
        document.getElementById('fNama').value = d.nama ?? '';
        document.getElementById('fNamaOrtu').value = d.nama_ortu ?? '';
        const jk = d.jenis_kelamin ?? '';
        document.getElementById('fJK_L').checked = (jk === 'L');
        document.getElementById('fJK_P').checked = (jk === 'P');
        document.getElementById('fTanggalLahir').value = d.tanggal_lahir ?? '';
        document.getElementById('fUsername').value = d.username ?? '';
        document.getElementById('fPassword').value = '';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function editMurid(btn) { bukaModal(btn.dataset); }
    function tutupModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    modal.addEventListener('click', (e) => { if (e.target === modal) tutupModal(); });

    // Kalau validasi gagal: buka lagi modalnya dengan isian yang tadi
    @if ($errors->any())
        bukaModal({
            id:            @json(old('edit_id')),
            nama:          @json(old('nama', '')),
            nama_ortu:     @json(old('nama_ortu', '')),
            jenis_kelamin: @json(old('jenis_kelamin', '')),
            tanggal_lahir: @json(old('tanggal_lahir', '')),
            username:      @json(old('username', ''))
        });
    @endif
</script>
@endpush