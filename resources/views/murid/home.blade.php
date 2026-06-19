@extends('layouts.app')

@section('title', 'Beranda - TPQ Ceria')

@push('styles')
<style>
    @keyframes floatUp { from { opacity:0; transform: translateY(14px);} to {opacity:1; transform:translateY(0);} }
    .anim { opacity:0; animation: floatUp .5s cubic-bezier(.22,1,.36,1) forwards; }
    .d1{animation-delay:.04s}.d2{animation-delay:.12s}.d3{animation-delay:.20s}.d4{animation-delay:.28s}.d5{animation-delay:.36s}
    .press { transition: transform .12s ease; }
    .press:active { transform: scale(.97); }
    @keyframes twinkle { 0%,100%{opacity:.25; transform:scale(1);} 50%{opacity:.6; transform:scale(1.15);} }
    .star { animation: twinkle 3.5s ease-in-out infinite; }
</style>
@endpush

@section('content')
@php
    $warnaMap = [
        'emerald' => ['bg' => 'bg-emerald-500', 'shadow' => 'shadow-emerald-100'],
        'rose'    => ['bg' => 'bg-rose-400',    'shadow' => 'shadow-rose-100'],
        'amber'   => ['bg' => 'bg-amber-500',   'shadow' => 'shadow-amber-100'],
        'sky'     => ['bg' => 'bg-sky-500',     'shadow' => 'shadow-sky-100'],
        'violet'  => ['bg' => 'bg-violet-500',  'shadow' => 'shadow-violet-100'],
        'teal'    => ['bg' => 'bg-teal-500',    'shadow' => 'shadow-teal-100'],
    ];
    $user = auth()->user();
@endphp

<div class="relative min-h-screen flex flex-col">

    {{-- Latar dekoratif (mengisi ruang kosong) --}}
    <div class="absolute inset-0 -z-10 pointer-events-none overflow-hidden">
        <div class="absolute -top-24 -left-28 w-96 h-96 rounded-full bg-emerald-300/30 blur-3xl"></div>
        <div class="absolute top-1/3 -right-28 w-[28rem] h-[28rem] rounded-full bg-rose-300/25 blur-3xl"></div>
        <div class="absolute -bottom-24 left-1/4 w-96 h-96 rounded-full bg-amber-200/35 blur-3xl"></div>
        <svg viewBox="0 0 24 24" fill="currentColor" class="absolute top-16 right-[12%] w-12 h-12 text-amber-300/40"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg viewBox="0 0 24 24" fill="currentColor" class="star absolute top-28 left-[14%] w-6 h-6 text-amber-400/50"><path d="M12 2c.4 3.6 1.8 5 5.4 5.4-3.6.4-5 1.8-5.4 5.4-.4-3.6-1.8-5-5.4-5.4 3.6-.4 5-1.8 5.4-5.4z"/></svg>
        <svg viewBox="0 0 24 24" fill="currentColor" class="star absolute top-[55%] left-[8%] w-5 h-5 text-emerald-400/40" style="animation-delay:1s"><path d="M12 2c.4 3.6 1.8 5 5.4 5.4-3.6.4-5 1.8-5.4 5.4-.4-3.6-1.8-5-5.4-5.4 3.6-.4 5-1.8 5.4-5.4z"/></svg>
        <svg viewBox="0 0 24 24" fill="currentColor" class="star absolute top-[24%] right-[20%] w-4 h-4 text-rose-400/40" style="animation-delay:.6s"><path d="M12 2c.4 3.6 1.8 5 5.4 5.4-3.6.4-5 1.8-5.4 5.4-.4-3.6-1.8-5-5.4-5.4 3.6-.4 5-1.8 5.4-5.4z"/></svg>
        <svg viewBox="0 0 24 24" fill="currentColor" class="star absolute bottom-[22%] right-[14%] w-6 h-6 text-amber-400/45" style="animation-delay:1.8s"><path d="M12 2c.4 3.6 1.8 5 5.4 5.4-3.6.4-5 1.8-5.4 5.4-.4-3.6-1.8-5-5.4-5.4 3.6-.4 5-1.8 5.4-5.4z"/></svg>
        <svg viewBox="0 0 24 24" fill="currentColor" class="star absolute bottom-[30%] left-[18%] w-4 h-4 text-emerald-400/40" style="animation-delay:2.4s"><path d="M12 2c.4 3.6 1.8 5 5.4 5.4-3.6.4-5 1.8-5.4 5.4-.4-3.6-1.8-5-5.4-5.4 3.6-.4 5-1.8 5.4-5.4z"/></svg>
    </div>

    {{-- Top bar: profil + streak + keluar --}}
    <header class="anim d1 w-full max-w-md md:max-w-3xl mx-auto px-6 pt-8 flex flex-wrap items-center justify-between gap-x-3 gap-y-3">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center shadow-md shadow-emerald-200/60 shrink-0">
                <svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-amber-700/60 font-semibold">Assalamu'alaikum,</p>
                <p class="font-fredoka text-lg font-semibold leading-tight truncate">{{ $user->nama }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center justify-end gap-2">
            {{-- streak --}}
            <div class="flex items-center gap-1.5 bg-white rounded-full pl-2.5 pr-4 py-2 shadow-sm border border-amber-100">
                <svg viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="#FB923C"><path d="M13.5 .67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5 .67z"/></svg>
                <span class="font-fredoka font-bold text-orange-500 leading-none">{{ $user->jumlah_streak }}</span>
                <span class="text-orange-500 text-sm font-semibold leading-none whitespace-nowrap">Hari Beruntun</span>
            </div>
            {{-- ubah password --}}
            <a href="{{ route('password.edit') }}" title="Ubah Password"
                class="press flex items-center gap-2 bg-white rounded-full pl-3.5 pr-4 py-2 shadow-sm border border-amber-100 text-stone-500 hover:text-emerald-600">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.03 5.91c-.56-.1-1.16.03-1.56.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.82c0-.6.24-1.17.66-1.59l6.5-6.5c.4-.4.53-1 .43-1.56A6 6 0 1 1 21.75 8.25Z"/></svg>
                <span class="text-sm font-bold">Password</span>
            </a>
            {{-- keluar --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar" class="press flex items-center gap-2 bg-white rounded-full pl-3.5 pr-4 py-2 shadow-sm border border-amber-100 text-stone-500 hover:text-rose-500">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 17l5-5-5-5M21 12H9M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                    <span class="text-sm font-bold">Keluar</span>
                </button>
            </form>
        </div>
    </header>
    {{-- Konten utama (di tengah secara vertikal) --}}
    <main class="flex-1 flex flex-col justify-center w-full max-w-md md:max-w-3xl mx-auto px-6 py-10">
        <h1 class="anim d2 font-fredoka text-3xl font-semibold mb-1">Mau belajar apa hari ini?</h1>
        <p class="anim d3 text-amber-700/60 font-semibold mb-7">Pilih salah satu pelajaran untuk mulai.</p>

        {{-- Kartu pelajaran (looping dari database) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($pelajaran as $p)
                @php $c = $warnaMap[$p->warna] ?? $warnaMap['emerald']; @endphp
                <a href="{{ route('modul.index', $p->id) }}"
                class="anim d4 press rounded-3xl p-7 {{ $c['bg'] }} shadow-lg {{ $c['shadow'] }} flex items-center gap-4 text-left">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center shrink-0">
                        {{-- ikon belajar (buku) untuk semua pelajaran --}}
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-fredoka text-xl font-bold text-white leading-tight">{{ $p->nama }}</p>
                        <p class="text-white/85 text-sm font-semibold mt-0.5">{{ $p->subjudul ?: 'Mulai belajar' }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-5 h-5 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    </main>

    {{-- Footer --}}
    <footer class="anim d5 w-full max-w-md md:max-w-3xl mx-auto px-6 pb-8 flex items-center justify-center gap-2 text-amber-700/50">
        <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-amber-400/70"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <span class="font-fredoka text-sm font-semibold">TPQ Nurul Iman</span>
    </footer>

</div>
@endsection