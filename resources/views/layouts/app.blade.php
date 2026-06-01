<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TPQ Ceria')</title>

    {{-- Tailwind via CDN (praktis buat prototipe) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Nunito', sans-serif; }
        .font-fredoka { font-family: 'Fredoka', sans-serif; }
        .font-arabic  { font-family: 'Amiri', serif; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-gradient-to-br from-amber-50 via-emerald-50 to-teal-50 text-slate-700">
    @yield('content')
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