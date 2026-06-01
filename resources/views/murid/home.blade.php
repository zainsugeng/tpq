@extends('layouts.app')

@section('title', 'Beranda - TPQ Ceria')

@push('styles')
<style>
    @keyframes floatUp { from { opacity:0; transform: translateY(14px);} to {opacity:1; transform:translateY(0);} }
    .anim { opacity:0; animation: floatUp .5s cubic-bezier(.22,1,.36,1) forwards; }
    .d1{animation-delay:.04s}.d2{animation-delay:.14s}.d3{animation-delay:.24s}
    .press { transition: transform .12s ease; }
    .press:active { transform: scale(.97); }
</style>
@endpush

@section('content')
@php
    $tema = [
        'Iqro'        => ['bg' => 'bg-emerald-500', 'shadow' => 'shadow-emerald-100', 'glyph' => 'ابت', 'sub' => 'Belajar huruf hijaiyah'],
        'Bahasa Arab' => ['bg' => 'bg-rose-400',    'shadow' => 'shadow-rose-100',    'glyph' => 'كلمة', 'sub' => 'Kosakata dasar'],
    ];
    $default = ['bg' => 'bg-teal-500', 'shadow' => 'shadow-teal-100', 'glyph' => 'أ', 'sub' => 'Mulai belajar'];
    $user = auth()->user();
@endphp

<div class="min-h-screen flex flex-col md:justify-center">
    <div class="w-full max-w-md md:max-w-3xl mx-auto px-6 py-10">

        {{-- Top bar: sapaan + streak --}}
        <header class="anim d1 flex items-center justify-between mb-9">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center shadow-md shadow-emerald-200/60">
                    <span class="font-fredoka text-white text-xl font-semibold">{{ strtoupper(substr($user->nama, 0, 1)) }}</span>
                </div>
                <div>
                    <p class="text-xs text-amber-700/60 font-semibold">Assalamu'alaikum,</p>
                    <p class="font-fredoka text-lg font-semibold leading-tight">{{ $user->nama }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1.5 bg-white rounded-full pl-2 pr-3.5 py-1.5 shadow-sm border border-amber-100">
                    <svg viewBox="0 0 24 24" class="w-5 h-5" fill="#FB923C">
                        <path d="M13.5 .67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5 .67z"/>
                    </svg>
                    <span class="font-fredoka font-bold text-orange-500 leading-none">{{ $user->jumlah_streak }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="w-9 h-9 rounded-full bg-white shadow-sm border border-amber-100 flex items-center justify-center text-stone-400 hover:text-rose-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 17l5-5-5-5M21 12H9M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                    </button>
                </form>
            </div>
        </header>

        {{-- Heading --}}
        <h1 class="anim d2 font-fredoka text-2xl font-semibold mb-6">Mau belajar apa hari ini?</h1>

        {{-- Kartu pelajaran (looping dari database) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($pelajaran as $p)
                @php $t = $tema[$p->nama] ?? $default; @endphp
                <a href="{{ route('modul.index', $p->id) }}"
                   class="anim d3 press rounded-3xl p-6 {{ $t['bg'] }} shadow-lg {{ $t['shadow'] }} flex items-center gap-4 text-left">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center shrink-0">
                        <span class="font-arabic text-white text-2xl leading-none">{{ $t['glyph'] }}</span>
                    </div>
                    <div>
                        <p class="font-fredoka text-xl font-bold text-white leading-tight">{{ $p->nama }}</p>
                        <p class="text-white/85 text-sm font-semibold mt-0.5">{{ $t['sub'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</div>
@endsection