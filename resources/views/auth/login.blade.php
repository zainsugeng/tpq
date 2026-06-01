@extends('layouts.app')

@section('title', 'Masuk - TPQ Ceria')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">

        {{-- Logo & judul --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-emerald-500 shadow-lg mb-4">
                <span class="font-arabic text-4xl text-white">إقرأ</span>
            </div>
            <h1 class="font-fredoka text-3xl font-bold text-emerald-700">TPQ Ceria</h1>
            <p class="text-slate-500 mt-1">Yuk belajar mengaji!</p>
        </div>

        {{-- Kartu form --}}
        <div class="bg-white rounded-3xl shadow-xl p-8">

            {{-- Pesan error (muncul kalau username/password salah) --}}
            @if ($errors->any())
                <div class="mb-4 rounded-2xl bg-rose-50 text-rose-600 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.proses') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required autofocus
                        placeholder="Masukkan username"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Password</label>
                    <input type="password" name="password" required
                        placeholder="Masukkan password"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
                </div>

                <button type="submit"
                    class="w-full rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-fredoka font-semibold text-lg py-3 shadow-lg transition">
                    Masuk
                </button>
            </form>
        </div>

    </div>
</div>
@endsection