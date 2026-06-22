@extends('layouts.app')

@section('title', 'Lupa Password Admin')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm text-center">

        <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-amber-100 mb-5">
            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008M21.75 12a9.75 9.75 0 1 1-19.5 0 9.75 9.75 0 0 1 19.5 0Z"/></svg>
        </div>

        <h1 class="font-fredoka text-2xl font-bold text-slate-800 mb-3">Lupa Password?</h1>

        <div class="bg-white rounded-3xl shadow-xl p-7">
            <p class="text-slate-600 leading-relaxed">
                Password admin bisa diatur ulang oleh admin lain. Kalau kamu lupa, silakan
                <span class="font-bold text-slate-800">hubungi admin satunya</span>
                untuk mereset lewat menu <span class="font-bold text-slate-800">Akun Admin</span>.
            </p>
        </div>

        <a href="{{ route('admin.login') }}" class="inline-block mt-6 text-sm font-semibold text-slate-500 hover:underline">← Kembali ke halaman masuk</a>

    </div>
</div>
@endsection