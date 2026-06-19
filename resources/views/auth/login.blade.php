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
            <h1 class="font-fredoka text-3xl font-bold text-emerald-700">TPQ Nurul Iman</h1>
            <p class="text-slate-500 mt-1">Yuk belajar!</p>
        </div>

        {{-- Kartu form --}}
        <div class="bg-white rounded-3xl shadow-xl p-8">

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
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            placeholder="Masukkan password"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 pr-12 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
                        <button type="button" onclick="togglePassword()" aria-label="Lihat/sembunyikan password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg id="iconEye" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            <svg id="iconEyeOff" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>
                {{-- Lupa password --}}
                <div class="text-right -mt-3 mb-5">
                    <a href="{{ route('lupa-password') }}" class="text-sm font-semibold text-emerald-600 hover:underline">Lupa password?</a>
                </div>
                <button type="submit"
                    class="w-full rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-fredoka font-semibold text-lg py-3 shadow-lg transition">
                    Masuk
                </button>
            </form>
        </div>

        {{-- Link ke halaman login admin --}}
        <p class="text-center text-sm text-slate-400 mt-6">
            Pengelola TPQ?
            <a href="{{ route('admin.login') }}"
                class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Masuk sebagai Admin</a>
        </p>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eye = document.getElementById('iconEye');
        const eyeOff = document.getElementById('iconEyeOff');
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.add('hidden');
            eyeOff.classList.remove('hidden');
        } else {
            input.type = 'password';
            eye.classList.remove('hidden');
            eyeOff.classList.add('hidden');
        }
    }
</script>
@endpush