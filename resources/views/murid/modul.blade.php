@extends('layouts.app')

@section('title', $pelajaran->nama . ' - Modul')

@push('styles')
<style>
    @keyframes floatUp { from { opacity:0; transform: translateY(12px);} to {opacity:1; transform:translateY(0);} }
    .anim { opacity:0; animation: floatUp .45s cubic-bezier(.22,1,.36,1) forwards; }
    .press { transition: transform .12s ease; }
    .press:active { transform: scale(.98); }
</style>
@endpush

@section('content')
@php
    $total  = $moduls->count();
    $persen = $total ? round($jumlahSelesai / $total * 100) : 0;
    $user   = auth()->user();
@endphp

<div class="w-full max-w-md md:max-w-2xl mx-auto px-6 py-8">

    {{-- Header --}}
    <header class="anim flex items-center gap-3 mb-6" style="animation-delay:.04s">
        <a href="{{ route('home') }}" class="w-10 h-10 rounded-full bg-white shadow-sm border border-amber-100 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-stone-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="font-fredoka text-2xl font-bold flex-1 leading-tight">{{ $pelajaran->nama }}</h1>
        <div class="flex items-center gap-1.5 bg-white rounded-full pl-2 pr-3.5 py-1.5 shadow-sm border border-amber-100">
            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="#FB923C"><path d="M13.5 .67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5 .67z"/></svg>
            <span class="font-fredoka font-bold text-orange-500 leading-none">{{ $user->jumlah_streak }}</span>
        </div>
    </header>

    {{-- Progress --}}
    <div class="anim mb-7" style="animation-delay:.12s">
        <div class="flex justify-between text-sm font-semibold text-amber-700/60 mb-1.5">
            <span>Progres belajar</span><span>{{ $jumlahSelesai }} / {{ $total }} modul</span>
        </div>
        <div class="h-2.5 rounded-full bg-stone-200 overflow-hidden">
            <div class="h-full rounded-full bg-emerald-500" style="width:{{ $persen }}%"></div>
        </div>
    </div>

    {{-- Daftar modul --}}
    <div class="space-y-3">
        @forelse ($moduls as $m)
            @php $sub = $m->materi->pluck('label')->take(4)->implode(' · '); @endphp

            @if ($m->status === 'selesai')
                <a href="{{ route('belajar.index', $m->id) }}"
                   class="anim press rounded-2xl p-4 bg-white shadow-sm border border-stone-100 flex items-center gap-4"
                   style="animation-delay:{{ 0.2 + $loop->index * 0.08 }}s">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-fredoka font-bold text-stone-700">{{ $m->nama }}</p>
                        <p class="text-sm text-amber-700/60 font-semibold">{{ $sub }}</p>
                    </div>
                </a>

            @elseif ($m->status === 'terbuka')
                <a href="{{ route('belajar.index', $m->id) }}"
                   class="anim press rounded-2xl p-4 bg-emerald-500 shadow-lg shadow-emerald-200/60 flex items-center gap-4"
                   style="animation-delay:{{ 0.2 + $loop->index * 0.08 }}s">
                    <div class="w-12 h-12 rounded-full bg-white/25 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-fredoka font-bold text-white">{{ $m->nama }}</p>
                        <p class="text-sm text-white/85 font-semibold">{{ $sub }}</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-white px-3.5 py-2 rounded-full shrink-0">Mulai</span>
                </a>

            @else
                <div class="anim rounded-2xl p-4 bg-stone-100 flex items-center gap-4 opacity-80"
                     style="animation-delay:{{ 0.2 + $loop->index * 0.08 }}s">
                    <div class="w-12 h-12 rounded-full bg-stone-200 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-stone-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5zm3 8H9V6a3 3 0 0 1 6 0v3z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-fredoka font-bold text-stone-400">{{ $m->nama }}</p>
                        <p class="text-sm text-stone-400 font-semibold">{{ $sub }}</p>
                    </div>
                </div>
            @endif

        @empty
            <div class="text-center text-stone-400 py-10">Belum ada modul di pelajaran ini.</div>
        @endforelse
    </div>

</div>
@endsection