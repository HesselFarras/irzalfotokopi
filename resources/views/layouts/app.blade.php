<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Toko Kelontong Berkah Jaya — Daftar Harga')</title>
    <meta name="description" content="@yield('description', 'Daftar harga sembako dan kebutuhan harian Toko Kelontong Berkah Jaya.')">
    <meta property="og:title" content="@yield('title', 'Toko Kelontong Berkah Jaya — Daftar Harga')">
    <meta property="og:description" content="@yield('description', 'Daftar harga sembako dan kebutuhan harian Toko Kelontong Berkah Jaya.')">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&display=swap" rel="stylesheet">
    {{-- Kalau sudah pakai Vite + Tailwind lokal, ganti baris di bawah dengan @vite('resources/css/app.css') --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#1f6b45',
                        price: '#b4531b',
                        cream: '#fbfaf3',
                        line: '#e6e3d8',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bricolage Grotesque"', 'sans-serif'],
                    },
                },
            },
        }
    </script>
</head>
<body class="min-h-screen bg-cream font-sans text-[#22372c]">
    <header class="sticky top-0 z-40 border-b border-line bg-cream/90 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
            <a href="{{ route('harga.index') }}" class="flex items-center gap-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand text-lg text-white">🧺</span>
                <span class="font-display text-base font-semibold leading-tight">
                    Irzal Fotokopi
                    <span class="block text-xs font-normal text-[#63705f]">Fotokopi semua ada</span>
                </span>
            </a>
            <nav class="flex items-center gap-1 text-sm">
                <a href="{{ route('harga.index') }}"
                   class="rounded-md px-3 py-2 font-medium {{ request()->routeIs('harga.index') ? 'bg-[#efeade] font-semibold' : 'text-[#63705f] hover:bg-[#efeade]' }}">
                    Daftar Harga
                </a>
                <a href="{{ route('kelola.index') }}"
                   class="rounded-md px-3 py-2 font-medium {{ request()->routeIs('kelola.index') ? 'bg-[#efeade] font-semibold' : 'text-[#63705f] hover:bg-[#efeade]' }}">
                    Kelola
                </a>
            </nav>
        </div>
    </header>

    @if (session('sukses'))
        <div class="mx-auto mt-4 max-w-6xl px-4 sm:px-6">
            <div class="rounded-lg border border-brand/30 bg-brand/10 px-4 py-3 text-sm text-brand">
                {{ session('sukses') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mx-auto mt-4 max-w-6xl px-4 sm:px-6">
            <ul class="list-inside list-disc rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main class="mx-auto max-w-6xl px-4 pb-20 pt-8 sm:px-6">
        @yield('konten')
    </main>

    <footer class="border-t border-line py-8 text-center text-xs text-[#63705f]">
        Dibuat sambil ngopi ama <a href="https://instagram.com/binatangsudahjinak" target="_blank" class="font-medium text-brand underline">@binatangsudahjinak</a> &mdash; <a href="
    </footer>
</body>
</html>