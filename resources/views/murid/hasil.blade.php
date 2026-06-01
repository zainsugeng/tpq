@extends('layouts.app')

@section('title', 'Selesai!')

@push('styles')
<style>
    .press { transition: transform .12s ease; }
    .press:active { transform: scale(.97); }
    @keyframes pop2 { 0%{transform:scale(0);opacity:0} 60%{transform:scale(1.12)} 100%{transform:scale(1);opacity:1} }
    .badge { animation: pop2 .6s cubic-bezier(.34,1.56,.64,1) both; }
    @keyframes floatUp { from {opacity:0; transform:translateY(14px)} to {opacity:1; transform:translateY(0)} }
    .anim  { opacity:0; animation: floatUp .5s ease .35s forwards; }
    .anim2 { opacity:0; animation: floatUp .5s ease .5s forwards; }
    .confetti { position: fixed; top:-24px; width:10px; height:14px; border-radius:3px; animation: fall ease-in forwards; }
    @keyframes fall { 0%{transform:translateY(-24px) rotate(0);opacity:0} 12%{opacity:1} 100%{transform:translateY(108vh) rotate(560deg);opacity:1} }
</style>
@endpush

@section('content')
@php
    $streak = session('streak', auth()->user()->jumlah_streak);
    $streakNaik = session('streakNaik', false);
    $modulBerikut = session('modulBerikut');
@endphp

<span class="confetti" style="left:8%;  background:#10B981; animation-duration:3.2s; animation-delay:.0s"></span>
<span class="confetti" style="left:20%; background:#FB923C; animation-duration:3.8s; animation-delay:.3s"></span>
<span class="confetti" style="left:33%; background:#FB7185; animation-duration:3.0s; animation-delay:.1s"></span>
<span class="confetti" style="left:47%; background:#FBBF24; animation-duration:3.6s; animation-delay:.5s"></span>
<span class="confetti" style="left:60%; background:#34D399; animation-duration:3.1s; animation-delay:.2s"></span>
<span class="confetti" style="left:73%; background:#F472B6; animation-duration:3.9s; animation-delay:.45s"></span>
<span class="confetti" style="left:86%; background:#FB923C; animation-duration:3.3s; animation-delay:.05s"></span>
<span class="confetti" style="left:92%; background:#10B981; animation-duration:3.7s; animation-delay:.6s"></span>

<div class="min-h-screen flex flex-col items-center justify-center w-full max-w-md mx-auto px-6 py-10 text-center">

    <div class="badge w-28 h-28 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 shadow-xl shadow-amber-200 flex items-center justify-center mb-6">
        <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
    </div>

    <h1 class="anim font-fredoka text-4xl font-bold">Masya Allah!</h1>
    <p class="anim text-stone-500 font-semibold mt-1.5 mb-7">Kamu menyelesaikan {{ $modul->nama }}</p>

    <div class="anim2 bg-white rounded-3xl shadow-lg shadow-emerald-100/40 border border-amber-100 p-5 w-full space-y-4">
        {{-- streak --}}
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-full bg-orange-50 flex items-center justify-center shrink-0">
                <svg viewBox="0 0 24 24" class="w-6 h-6" fill="#FB923C"><path d="M13.5 .67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5 .67z"/></svg>
            </div>
            <div class="flex-1 text-left">
                <p class="text-xs text-stone-400 font-semibold">Streak harian</p>
                <p class="font-fredoka font-bold">{{ $streak }} hari berturut-turut</p>
            </div>
            @if ($streakNaik)
                <span class="text-xs font-bold text-orange-500 bg-orange-50 px-2.5 py-1 rounded-full shrink-0">+1</span>
            @endif
        </div>

        {{-- modul baru (cuma kalau ada yang baru kebuka) --}}
        @if ($modulBerikut)
            <div class="h-px bg-amber-100"></div>
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-emerald-50 flex items-center justify-center shrink-0">
                    <svg viewBox="0 0 24 24" class="w-6 h-6" fill="#10B981"><path d="M12 2l1.6 6.4L20 10l-6.4 1.6L12 18l-1.6-6.4L4 10l6.4-1.6z"/></svg>
                </div>
                <div class="flex-1 text-left">
                    <p class="text-xs text-stone-400 font-semibold">Modul baru</p>
                    <p class="font-fredoka font-bold">{{ $modulBerikut }} terbuka!</p>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full shrink-0">Baru</span>
            </div>
        @endif
    </div>

    <a href="{{ route('modul.index', $modul->pelajaran_id) }}" class="anim2 press w-full rounded-2xl py-5 mt-7 bg-emerald-500 shadow-lg shadow-emerald-200 font-fredoka text-white font-bold text-lg text-center">Lanjut</a>
    <a href="{{ route('kuis.index', $modul->id) }}" class="anim2 press mt-3 font-fredoka text-stone-400 font-bold py-2">Ulangi modul ini</a>

</div>
@endsection