@extends('layouts.admin')

@section('title', 'Kelola Pelajaran')

@section('konten')
@php
    $warnaOptions = [
        'emerald' => ['label' => 'Hijau',      'dot' => 'bg-emerald-500'],
        'rose'    => ['label' => 'Merah Muda', 'dot' => 'bg-rose-400'],
        'amber'   => ['label' => 'Kuning',     'dot' => 'bg-amber-500'],
        'sky'     => ['label' => 'Biru',       'dot' => 'bg-sky-500'],
        'violet'  => ['label' => 'Ungu',       'dot' => 'bg-violet-500'],
        'teal'    => ['label' => 'Teal',       'dot' => 'bg-teal-500'],
    ];
@endphp

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-fredoka text-2xl font-bold">Pelajaran</h1>
        <p class="text-sm text-stone-500 font-semibold">Kelola daftar pelajaran</p>
    </div>
    <button onclick="bukaModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white font-fredoka font-semibold rounded-xl px-5 py-2.5 shadow flex items-center gap-2 shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        Tambah Pelajaran
    </button>
</div>

@if (session('sukses'))
    <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">{{ session('sukses') }}</div>
@endif

{{-- Cari & filter --}}
<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-4">
    <div class="relative flex-1">
        <svg class="w-5 h-5 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3m1.3-5.4a6.7 6.7 0 11-13.4 0 6.7 6.7 0 0113.4 0z"/></svg>
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau sub judul..."
            class="w-full rounded-xl border border-stone-200 pl-11 pr-4 py-2.5 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
    </div>
    <select name="warna" class="rounded-xl border border-stone-200 px-4 py-2.5 bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
        <option value="" @selected(!request('warna'))>Semua warna</option>
        @foreach ($warnaOptions as $key => $opt)
            <option value="{{ $key }}" @selected(request('warna') === $key)>{{ $opt['label'] }}</option>
        @endforeach
    </select>
    <button type="submit" class="rounded-xl bg-stone-800 hover:bg-stone-900 text-white font-semibold px-5 py-2.5">Cari</button>
    @if (request('cari') || request('warna'))
        <a href="{{ route('admin.pelajaran.index') }}" class="rounded-xl border border-stone-200 text-stone-600 font-semibold px-5 py-2.5 hover:bg-stone-50 text-center">Reset</a>
    @endif
</form>

<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
            <thead class="bg-stone-50 text-stone-500 text-left">
                <tr>
                    <th class="px-5 py-3 font-semibold w-20">Urutan</th>
                    <th class="px-5 py-3 font-semibold">Nama Pelajaran</th>
                    <th class="px-5 py-3 font-semibold">Sub Judul</th>
                    <th class="px-5 py-3 font-semibold w-36">Warna</th>
                    <th class="px-5 py-3 font-semibold w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($pelajaran as $p)
                    <tr>
                        <td class="px-5 py-3 text-stone-400 font-bold">{{ $p->urutan }}</td>
                        <td class="px-5 py-3 font-bold text-stone-700">{{ $p->nama }}</td>
                        <td class="px-5 py-3 text-stone-500">{{ $p->subjudul ?: '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center gap-2 text-xs font-bold text-stone-600 bg-stone-50 border border-stone-200 px-2.5 py-1 rounded-full">
                                <span class="w-3 h-3 rounded-full {{ $warnaOptions[$p->warna]['dot'] ?? 'bg-stone-300' }}"></span>
                                {{ $warnaOptions[$p->warna]['label'] ?? $p->warna }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="editPelajaran(this)"
                                    data-id="{{ $p->id }}" data-nama="{{ $p->nama }}" data-urutan="{{ $p->urutan }}"
                                    data-subjudul="{{ $p->subjudul }}" data-warna="{{ $p->warna }}"
                                    class="text-xs font-bold text-sky-600 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-lg">Edit</button>
                                <form method="POST" action="{{ route('admin.pelajaran.destroy', $p->id) }}" onsubmit="return confirm('Hapus pelajaran ini? Semua modul & materi di dalamnya ikut terhapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-stone-400">
                        {{ (request('cari') || request('warna')) ? 'Tidak ada pelajaran yang cocok.' : 'Belum ada pelajaran.' }}
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if ($pelajaran->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4">
        <p class="text-sm text-stone-500 font-semibold">
            Menampilkan {{ $pelajaran->firstItem() }}–{{ $pelajaran->lastItem() }} dari {{ $pelajaran->total() }}
        </p>
        <div class="flex items-center gap-2">
            @if ($pelajaran->onFirstPage())
                <span class="px-4 py-2 rounded-xl text-stone-300 font-semibold bg-stone-50">Sebelumnya</span>
            @else
                <a href="{{ $pelajaran->previousPageUrl() }}" class="px-4 py-2 rounded-xl text-stone-600 font-semibold bg-white border border-stone-200 hover:bg-stone-50">Sebelumnya</a>
            @endif

            <span class="text-sm text-stone-500 font-semibold px-1">Hal {{ $pelajaran->currentPage() }}/{{ $pelajaran->lastPage() }}</span>

            @if ($pelajaran->hasMorePages())
                <a href="{{ $pelajaran->nextPageUrl() }}" class="px-4 py-2 rounded-xl text-white font-semibold bg-emerald-500 hover:bg-emerald-600">Berikutnya</a>
            @else
                <span class="px-4 py-2 rounded-xl text-stone-300 font-semibold bg-stone-50">Berikutnya</span>
            @endif
        </div>
    </div>
@endif

{{-- Modal tambah/edit --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center px-4 z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 id="modalJudul" class="font-fredoka text-lg font-bold mb-4">Tambah Pelajaran</h3>

        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-rose-50 text-rose-600 px-4 py-3 text-sm font-semibold">{{ $errors->first() }}</div>
        @endif

        <form id="modalForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="modalMethod" value="POST">
            <input type="hidden" name="edit_id" id="fEditId">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Nama Pelajaran</label>
                <input type="text" name="nama" id="fNama" required
                    class="w-full rounded-xl border border-stone-200 px-4 py-2.5 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Sub Judul <span class="text-stone-400 font-normal">(opsional)</span></label>
                <input type="text" name="subjudul" id="fSubjudul" placeholder="mis. Belajar huruf hijaiyah"
                    class="w-full rounded-xl border border-stone-200 px-4 py-2.5 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-stone-600 mb-1">Warna Kartu</label>
                <select name="warna" id="fWarna"
                    class="w-full rounded-xl border border-stone-200 px-4 py-2.5 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none bg-white">
                    @foreach ($warnaOptions as $key => $opt)
                        <option value="{{ $key }}">{{ $opt['label'] }}</option>
                    @endforeach
                </select>
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

    function bukaModal(id = null, nama = '', urutan = 0, subjudul = '', warna = 'emerald') {
        if (id) {
            document.getElementById('modalJudul').textContent = 'Edit Pelajaran';
            document.getElementById('modalMethod').value = 'PUT';
            form.action = baseUrl + '/' + id;
            document.getElementById('fEditId').value = id;
        } else {
            document.getElementById('modalJudul').textContent = 'Tambah Pelajaran';
            document.getElementById('modalMethod').value = 'POST';
            form.action = storeUrl;
            document.getElementById('fEditId').value = '';
        }
        document.getElementById('fNama').value = nama;
        document.getElementById('fUrutan').value = urutan;
        document.getElementById('fSubjudul').value = subjudul;
        document.getElementById('fWarna').value = warna || 'emerald';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function editPelajaran(btn) {
        bukaModal(btn.dataset.id, btn.dataset.nama, btn.dataset.urutan, btn.dataset.subjudul, btn.dataset.warna);
    }
    function tutupModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    modal.addEventListener('click', (e) => { if (e.target === modal) tutupModal(); });

    // Kalau validasi gagal: buka lagi modalnya dengan isian yang tadi
    @if ($errors->any())
        bukaModal(
            @json(old('edit_id')) || null,
            @json(old('nama', '')),
            @json(old('urutan', 0)),
            @json(old('subjudul', '')),
            @json(old('warna', 'emerald'))
        );
    @endif
</script>
@endpush