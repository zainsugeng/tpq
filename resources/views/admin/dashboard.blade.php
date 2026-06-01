@extends('layouts.admin')

@section('title', 'Dashboard - Admin')

@section('konten')
@php $admin = auth()->user(); @endphp

<div class="flex items-center justify-between mb-7">
    <div>
        <h1 class="font-fredoka text-2xl font-bold">Dashboard</h1>
        <p class="text-sm text-stone-500 font-semibold">Ringkasan pembelajaran TPQ</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="text-right leading-tight">
            <p class="font-bold text-sm">{{ $admin->nama }}</p>
            <p class="text-xs text-stone-400">Admin</p>
        </div>
        <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold font-fredoka">{{ strtoupper(substr($admin->nama, 0, 1)) }}</div>
    </div>
</div>

{{-- stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
    <div class="bg-white rounded-2xl border border-stone-200 p-5">
        <div class="w-11 h-11 rounded-xl bg-sky-50 flex items-center justify-center mb-3"><svg class="w-6 h-6 text-sky-500" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm-8 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0 2c-3.3 0-6 1.8-6 4v2h9v-2c0-1 .4-1.9 1-2.7C9.9 14.4 9 14 8 14zm8 0c-.4 0-.8 0-1.1.1 1.3 1 2.1 2.3 2.1 3.9v2h7v-2c0-2.2-2.7-4-6-4z"/></svg></div>
        <p class="text-sm text-stone-500 font-semibold">Murid</p>
        <p class="font-fredoka text-3xl font-bold">{{ $statistik['murid'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-stone-200 p-5">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center mb-3"><svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 0 1 2-2h6v17H5a2 2 0 0 1-2-2V5zm10-2h6a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2h-6V3z"/></svg></div>
        <p class="text-sm text-stone-500 font-semibold">Pelajaran</p>
        <p class="font-fredoka text-3xl font-bold">{{ $statistik['pelajaran'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-stone-200 p-5">
        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center mb-3"><svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linejoin="round" d="M12 2 2 7l10 5 10-5zM2 12l10 5 10-5M2 17l10 5 10-5"/></svg></div>
        <p class="text-sm text-stone-500 font-semibold">Modul</p>
        <p class="font-fredoka text-3xl font-bold">{{ $statistik['modul'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-stone-200 p-5">
        <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center mb-3"><svg class="w-6 h-6 text-rose-400" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h16v4H4zM4 10h16v4H4zM4 16h16v4H4z"/></svg></div>
        <p class="text-sm text-stone-500 font-semibold">Materi</p>
        <p class="font-fredoka text-3xl font-bold">{{ $statistik['materi'] }}</p>
    </div>
</div>

{{-- murid terbaru --}}
<div class="bg-white rounded-2xl border border-stone-200 p-6">
    <h2 class="font-fredoka font-bold text-lg mb-2">Murid Terbaru</h2>
    @forelse ($muridTerbaru as $m)
        <div class="flex items-center gap-3 py-3 border-b border-stone-100 last:border-0">
            <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold font-fredoka">{{ strtoupper(substr($m->nama, 0, 1)) }}</div>
            <div class="flex-1"><p class="font-bold text-sm">{{ $m->nama }}</p><p class="text-xs text-stone-400">{{ '@'.$m->username }}</p></div>
            <span class="text-xs font-bold {{ $m->jumlah_streak > 0 ? 'text-orange-500 bg-orange-50' : 'text-stone-400 bg-stone-100' }} px-2.5 py-1 rounded-full">{{ $m->jumlah_streak }} hari</span>
        </div>
    @empty
        <p class="text-sm text-stone-400 py-3">Belum ada murid.</p>
    @endforelse
</div>
@endsection