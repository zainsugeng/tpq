@extends('layouts.app')

@section('title', 'Kuis - ' . $modul->nama)

@push('styles')
<style>
    .arab { font-family: 'Amiri', serif; }
    .press { transition: transform .12s ease; }
    .press:active { transform: scale(.96); }
    .opt { transition: transform .12s ease, background .15s, border-color .15s, color .15s; }
    .opt.salah { animation: shake .4s; border-color:#F87171 !important; background:#FEF2F2 !important; }
    .opt.benar { background:#10B981 !important; border-color:#10B981 !important; color:#fff !important; }
    @keyframes shake { 0%,100%{transform:translateX(0)} 20%,60%{transform:translateX(-7px)} 40%,80%{transform:translateX(7px)} }
    #sukses { transform: translateY(120%); transition: transform .35s cubic-bezier(.22,1,.36,1); }
    #sukses.show { transform: translateY(0); }
    #toast { opacity:0; transition: opacity .25s; }
    #toast.show { opacity:1; }
    .ring { animation: ring 1.9s ease-out infinite; }
    @keyframes ring { 0%{transform:scale(1);opacity:.45} 100%{transform:scale(1.9);opacity:0} }
</style>
@endpush

@section('content')
<div class="min-h-screen flex flex-col w-full max-w-md md:max-w-lg mx-auto px-6 py-6">

    {{-- Header: tutup + progress (tanpa nyawa = no-fail) --}}
    <header class="flex items-center gap-3 mb-1">
        <a href="{{ route('modul.index', $modul->pelajaran_id) }}" class="w-10 h-10 rounded-full bg-white shadow-sm border border-amber-100 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-stone-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </a>
        <div class="flex-1 h-3 rounded-full bg-stone-200 overflow-hidden">
            <div id="bar" class="h-full rounded-full bg-emerald-500 transition-all" style="width:0%"></div>
        </div>
    </header>

    <main class="flex-1 flex flex-col justify-center">
        <p class="font-fredoka text-center text-xl font-semibold mb-2">Dengarkan, lalu pilih jawabannya</p>
        <p id="hint" class="text-center text-sm text-stone-400 mb-8"></p>

        {{-- tombol audio --}}
        <div class="relative flex justify-center mb-12">
            <span class="ring absolute w-24 h-24 rounded-full bg-emerald-500"></span>
            <button id="btnDengar" class="press relative w-24 h-24 rounded-full bg-emerald-500 shadow-xl shadow-emerald-200 flex items-center justify-center">
                <svg viewBox="0 0 24 24" class="w-11 h-11 text-white" fill="white"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3a4.5 4.5 0 0 0-2.5-4v8a4.5 4.5 0 0 0 2.5-4zM14 3.2v2.1c2.9.9 5 3.5 5 6.7s-2.1 5.8-5 6.7v2.1c4-1 7-4.5 7-8.8s-3-7.8-7-8.8z"/></svg>
            </button>
        </div>

        {{-- pilihan (diisi JS) --}}
        <div id="opsi" class="grid grid-cols-3 gap-3.5"></div>
    </main>
</div>

{{-- toast salah --}}
<div id="toast" class="fixed bottom-7 left-0 right-0 text-center pointer-events-none">
    <span class="font-fredoka bg-red-400 text-white font-bold px-5 py-2.5 rounded-full shadow-lg">Coba lagi ya!</span>
</div>

{{-- banner sukses --}}
<div id="sukses" class="fixed bottom-0 left-0 right-0 bg-emerald-500 px-6 py-5 shadow-2xl">
    <div class="max-w-lg mx-auto flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-full bg-white/25 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="font-fredoka text-white font-bold text-xl">Hebat!</span>
        </div>
        <button id="btnLanjut" class="font-fredoka bg-white text-emerald-600 font-bold rounded-xl px-7 py-2.5 shadow">Lanjut</button>
    </div>
</div>

{{-- form tersembunyi: dikirim saat kuis selesai --}}
<form id="formSelesai" method="POST" action="{{ route('kuis.selesai', $modul->id) }}" class="hidden">@csrf</form>
@endsection

@push('scripts')
<script>
    const soal = @json($soal);
    const total = soal.length;
    let q = 0;
    let terkunci = false;   // sudah jawab benar di soal ini?

    const elOpsi = document.getElementById('opsi');
    const elHint = document.getElementById('hint');
    const elBar  = document.getElementById('bar');
    const toast  = document.getElementById('toast');
    const sukses = document.getElementById('sukses');

    function render() {
        const s = soal[q];
        terkunci = false;
        sukses.classList.remove('show');

        elHint.textContent = 'petunjuk: ' + s.label;   // sementara (boleh dihapus saat audio ada)
        elBar.style.width = (q / total * 100) + '%';

        elOpsi.innerHTML = '';
        s.opsi.forEach(o => {
            const btn = document.createElement('button');
            btn.className = 'opt press bg-white rounded-2xl border-2 border-stone-200 shadow-sm py-6 flex items-center justify-center min-h-[92px]';
            if (o.tipe === 'gambar' && o.gambar) {
                btn.innerHTML = '<img src="' + o.gambar + '" class="max-h-16 object-contain">';
            } else {
                btn.innerHTML = '<span class="arab text-5xl text-emerald-900">' + o.teks + '</span>';
            }
            btn.addEventListener('click', () => pilih(btn, o.id));
            elOpsi.appendChild(btn);
        });

        play();   // auto-play audio tiap soal baru
    }

    function pilih(btn, id) {
        if (terkunci) return;
        if (id === soal[q].benarId) {
            terkunci = true;
            btn.classList.add('benar');
            elBar.style.width = ((q + 1) / total * 100) + '%';
            sukses.classList.add('show');
        } else {
            btn.classList.add('salah');
            toast.classList.add('show');
            setTimeout(() => { btn.classList.remove('salah'); toast.classList.remove('show'); }, 850);
        }
    }

    function play() {
        const s = soal[q];
        if (s.audio) new Audio(s.audio).play().catch(() => {});
    }

    document.getElementById('btnDengar').addEventListener('click', play);
    document.getElementById('btnLanjut').addEventListener('click', () => {
        q++;
        if (q < total) render();
        else document.getElementById('formSelesai').submit();   // selesai → kirim ke server
    });

    render();
</script>
@endpush