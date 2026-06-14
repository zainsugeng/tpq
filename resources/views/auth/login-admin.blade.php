@extends('layouts.app')

@section('title', 'Login Admin - TPQ Nurul Iman')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-800 shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
            </div>
            <h1 class="font-fredoka text-2xl font-bold text-slate-800">Panel Admin</h1>
            <p class="text-slate-500 mt-1 text-sm">TPQ Nurul Iman · Pengelola</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl p-8 border border-slate-100">

            @if ($errors->any())
                <div class="mb-4 rounded-2xl bg-rose-50 text-rose-600 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.proses') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required autofocus
                        placeholder="Username admin"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            placeholder="Password"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 pr-12 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
                        <button type="button" onclick="togglePassword()" aria-label="Lihat/sembunyikan password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg id="iconEye" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            <svg id="iconEyeOff" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full rounded-2xl bg-slate-800 hover:bg-slate-900 text-white font-fredoka font-semibold text-lg py-3 shadow-lg transition">
                    Masuk
                </button>
            </form>
        </div>

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