@extends('layouts.admin')

@section('title', 'Ubah Password - Admin')

@section('konten')
<div class="max-w-md">
    <h1 class="font-fredoka text-2xl font-bold text-stone-800 mb-1">Ubah Password</h1>
    <p class="text-stone-500 mb-6">Ganti password akun admin kamu.</p>

    @if (session('sukses'))
        <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">{{ session('sukses') }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-stone-200 p-6">
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            @php
                $fields = [
                    ['current_password', 'Password Saat Ini'],
                    ['password', 'Password Baru'],
                    ['password_confirmation', 'Konfirmasi Password Baru'],
                ];
            @endphp

            @foreach ($fields as [$nama, $label])
                <div>
                    <label class="block text-sm font-semibold text-stone-600 mb-1">{{ $label }}</label>
                    <div class="relative">
                        <input type="password" name="{{ $nama }}" required
                            class="w-full rounded-xl border border-stone-200 px-4 py-2.5 pr-12 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none">
                        <button type="button" onclick="toggleLihat(this)" aria-label="Lihat/sembunyikan password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600">
                            <svg class="eye w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            <svg class="eye-off w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>
            @endforeach

            <button type="submit"
                class="rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-fredoka font-semibold px-5 py-2.5 transition">
                Simpan Password
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleLihat(btn) {
        const input  = btn.parentElement.querySelector('input');
        const eye    = btn.querySelector('.eye');
        const eyeOff = btn.querySelector('.eye-off');
        const show   = input.type === 'password';
        input.type = show ? 'text' : 'password';
        eye.classList.toggle('hidden', show);
        eyeOff.classList.toggle('hidden', !show);
    }
</script>
@endpush