<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - TPQ Ceria')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; }
        .font-fredoka { font-family: 'Fredoka', sans-serif; }
        .font-arabic { font-family: 'Amiri', serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-60 bg-white border-r border-stone-200 flex flex-col py-6 px-4 shrink-0">
        <div class="flex items-center gap-2.5 px-2 mb-8">
            <div class="w-9 h-9 rounded-xl bg-emerald-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 0 1 2-2h6v17H5a2 2 0 0 1-2-2V5zm10-2h6a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2h-6V3z"/></svg>
            </div>
            <span class="font-fredoka font-bold text-lg leading-none">TPQ Ceria</span>
        </div>

        @php
            $aktif = 'flex items-center gap-3 px-3 py-2.5 rounded-xl bg-emerald-50 text-emerald-600 font-bold';
            $biasa = 'flex items-center gap-3 px-3 py-2.5 rounded-xl text-stone-500 font-semibold hover:bg-stone-50';
        @endphp

        <nav class="space-y-1 flex-1">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? $aktif : $biasa }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h8v8H3zM13 3h8v5h-8zM13 10h8v11h-8zM3 13h8v8H3z"/></svg> Dashboard
            </a>
            <a href="{{ route('admin.pelajaran.index') }}" class="{{ request()->routeIs('admin.pelajaran.*') ? $aktif : $biasa }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 0 1 2-2h6v17H5a2 2 0 0 1-2-2V5zm10-2h6a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2h-6V3z"/></svg> Pelajaran
            </a>
            <a href="{{ route('admin.modul.index') }}" class="{{ request()->routeIs('admin.modul.*') ? $aktif : $biasa }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linejoin="round" d="M12 2 2 7l10 5 10-5zM2 12l10 5 10-5M2 17l10 5 10-5"/></svg> Modul
            </a>
            <a href="{{ route('admin.materi.index') }}" class="{{ request()->routeIs('admin.materi.*') ? $aktif : $biasa }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h16v4H4zM4 10h16v4H4zM4 16h16v4H4z"/></svg> Materi
            </a>
            <a href="{{ route('admin.murid.index') }}" class="{{ request()->routeIs('admin.murid.*') ? $aktif : $biasa }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm-8 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0 2c-3.3 0-6 1.8-6 4v2h9v-2c0-1 .4-1.9 1-2.7C9.9 14.4 9 14 8 14zm8 0c-.4 0-.8 0-1.1.1 1.3 1 2.1 2.3 2.1 3.9v2h7v-2c0-2.2-2.7-4-6-4z"/></svg> Akun Murid
            </a>
        </nav>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-stone-500 font-semibold hover:bg-rose-50 hover:text-rose-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 17l5-5-5-5M21 12H9M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg> Keluar
            </button>
        </form>
    </aside>

    {{-- Konten --}}
    <main class="flex-1 p-8">
        @if ($errors->any())
            <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3">
                <p class="text-sm font-bold text-rose-600 mb-1">Ada yang perlu diperbaiki:</p>
                <ul class="text-sm text-rose-600 list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('konten')
    </main>
</div>
<script>
    // Terjemahkan tooltip "wajib diisi" bawaan browser ke Indonesia
    document.addEventListener('invalid', function (e) {
        if (e.target.validity.valueMissing) {
            e.target.setCustomValidity('Kolom ini wajib diisi.');
        }
    }, true);
    // reset pesannya begitu user mulai isi
    document.addEventListener('input',  function (e) { e.target.setCustomValidity(''); }, true);
    document.addEventListener('change', function (e) { e.target.setCustomValidity(''); }, true);
</script>
@stack('scripts')
</body>
</html>