@extends('layouts.app')

@section('title', $modul->nama . ' - Belajar')

@push('styles')
<style>
    .arab { font-family: 'Amiri', serif; }
    @keyframes pop { from { opacity:0; transform: scale(.94);} to {opacity:1; transform:scale(1);} }
    .pop { animation: pop .4s cubic-bezier(.22,1,.36,1) both; }
    .press { transition: transform .12s ease; }
    .press:active { transform: scale(.96); }
</style>
@endpush

@section('content')
@php
    $kartu = $materi->map(fn ($m) => [
        'tipe'   => $m->tipe_konten,
        'teks'   => $m->teks_arab,
        'gambar' => $m->gambar ? asset('storage/'.$m->gambar) : null,
        'label'  => $m->label,
        'audio'  => $m->audio ? asset('storage/'.$m->audio) : null,
    ])->values();
@endphp

<div class="min-h-screen flex flex-col w-full max-w-md md:max-w-lg mx-auto px-6 py-7">

    {{-- Header --}}
    <header class="flex items-center gap-3 mb-2">
        <a href="{{ route('modul.index', $modul->pelajaran_id) }}" class="w-10 h-10 rounded-full bg-white shadow-sm border border-amber-100 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-stone-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="font-fredoka text-lg font-bold flex-1 leading-tight">{{ $modul->nama }} <span class="text-stone-400 font-semibold">· {{ $modul->pelajaran->nama }}</span></h1>
        <span id="counter" class="text-sm font-bold text-stone-400">1 / {{ $kartu->count() }}</span>
    </header>

    {{-- Flashcard --}}
    <main class="flex-1 flex flex-col justify-center">
        <div id="kartu" class="pop bg-white rounded-[34px] shadow-xl shadow-emerald-100/50 border border-amber-100 px-8 py-10 text-center">
            <div id="dots" class="flex justify-center gap-2 mb-7"></div>
            <div id="isi" class="mb-2"></div>
            <p id="label" class="font-fredoka text-xl font-semibold text-stone-400 mb-9"></p>
            <button id="btnDengar" class="press inline-flex items-center gap-3 bg-emerald-500 text-white rounded-full pl-6 pr-8 py-4 shadow-lg shadow-emerald-200">
                <svg viewBox="0 0 24 24" class="w-6 h-6" fill="white"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3a4.5 4.5 0 0 0-2.5-4v8a4.5 4.5 0 0 0 2.5-4zM14 3.2v2.1c2.9.9 5 3.5 5 6.7s-2.1 5.8-5 6.7v2.1c4-1 7-4.5 7-8.8s-3-7.8-7-8.8z"/></svg>
                <span class="font-fredoka font-bold text-lg">Dengar</span>
            </button>
        </div>

        <div class="flex items-center justify-between mt-7 px-1">
            <button id="btnPrev" class="press w-14 h-14 rounded-full bg-white shadow-sm border border-amber-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-stone-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <span class="text-sm font-semibold text-stone-400">Geser kartu</span>
            <button id="btnNext" class="press w-14 h-14 rounded-full bg-emerald-500 shadow-lg shadow-emerald-200 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </main>

    {{-- CTA mulai kuis --}}
    <a href="{{ route('kuis.index', $modul->id) }}" class="press mt-7 w-full rounded-2xl py-5 bg-gradient-to-r from-orange-400 to-orange-500 shadow-lg shadow-orange-200 flex items-center justify-center gap-2.5">
        <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="white"><path d="M8 5v14l11-7z"/></svg>
        <span class="font-fredoka text-white font-bold text-lg">Mulai Kuis</span>
    </a>
</div>
@endsection

@push('scripts')
<script>
    const kartu = @json($kartu);
    let i = 0;

    const elIsi = document.getElementById('isi');
    const elLabel = document.getElementById('label');
    const elDots = document.getElementById('dots');
    const elCounter = document.getElementById('counter');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    const elKartu = document.getElementById('kartu');

    function render() {
        const k = kartu[i];

        if (k.tipe === 'gambar' && k.gambar) {
            elIsi.innerHTML = '<img src="' + k.gambar + '" class="mx-auto max-h-44 object-contain">';
        } else {
            elIsi.innerHTML = '<p class="arab text-emerald-900 leading-none" style="font-size:130px">' + (k.teks ?? '') + '</p>';
        }

        elLabel.textContent = k.label ?? '';
        elCounter.textContent = (i + 1) + ' / ' + kartu.length;

        elDots.innerHTML = '';
        kartu.forEach((_, idx) => {
            const dot = document.createElement('span');
            dot.className = 'w-2.5 h-2.5 rounded-full ' + (idx === i ? 'bg-emerald-500' : 'bg-stone-200');
            elDots.appendChild(dot);
        });

        btnPrev.style.opacity = (i === 0) ? '0.5' : '1';
        btnNext.style.opacity = (i === kartu.length - 1) ? '0.5' : '1';

        elKartu.classList.remove('pop');
        void elKartu.offsetWidth;   // trik reflow biar animasi ngulang
        elKartu.classList.add('pop');
    }

    function play() {
        const k = kartu[i];
        if (k.audio) new Audio(k.audio).play().catch(() => {});
    }

    btnPrev.addEventListener('click', () => { if (i > 0) { i--; render(); } });
    btnNext.addEventListener('click', () => { if (i < kartu.length - 1) { i++; render(); } });
    document.getElementById('btnDengar').addEventListener('click', play);

    render();
</script>
@endpush