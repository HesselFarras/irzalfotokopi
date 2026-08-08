@extends('layouts.app')

@section('title', 'Daftar Harga Toko Kelontong Berkah Jaya')
@section('description', 'Cek daftar harga sembako, minuman, bumbu dapur, dan kebutuhan harian di Toko Berkah Jaya. Lengkap dengan harga grosir dan kalkulator belanja.')

@section('konten')
    <section class="overflow-hidden rounded-2xl border border-line bg-brand/5 p-6 sm:p-10">
        <span class="inline-block rounded-full bg-[#efeade] px-3 py-1 text-xs font-medium">
            Diperbarui {{ now()->translatedFormat('d F Y') }}
        </span>
        <h1 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">
            Daftar Harga Fotokopi Irzal Mantap
        </h1>
        <p class="mt-3 max-w-2xl text-sm text-[#63705f] sm:text-base">
            Cek harga barang barang. Ada info stok (kalo niat nyetok), dan kalkulator belanja biar kaga salah hitung.
        </p>
        <div class="mt-6 flex flex-wrap gap-6 text-sm">
            <div>
                <p class="font-display text-2xl font-semibold">{{ $jumlahBarang }}</p>
                <p class="text-[#63705f]">barang terdaftar</p>
            </div>
            <div>
                <p class="font-display text-2xl font-semibold text-price">{{ $jumlahPromo }}</p>
                <p class="text-[#63705f]">sedang promo</p>
            </div>
            <div>
                <p class="font-display text-2xl font-semibold">{{ $jumlahStokTipis }}</p>
                <p class="text-[#63705f]">stok menipis</p>
            </div>
        </div>
    </section>

    <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_22rem]">
        <div>
            <!-- Form ditambahkan id="form-cari" -->
            <form id="form-cari" method="GET" action="{{ route('harga.index') }}" class="flex flex-col gap-3 sm:flex-row">
                <input type="hidden" name="kategori" value="{{ $kategoriAktif }}">
                
                <!-- Input ditambahkan id="input-cari" & autocomplete="off" -->
                <input type="search" name="cari" id="input-cari" value="{{ $cari }}" aria-label="Cari barang"
                       placeholder="Cari barang, cth. kertas, pulpen, cetak…" autocomplete="off"
                       class="flex-1 rounded-lg border border-line bg-white px-3 py-2 text-sm outline-none focus:border-brand">
                
                <select name="urut" onchange="this.form.submit()"
                        class="rounded-lg border border-line bg-white px-3 py-2 text-sm">
                    <option value="nama" @selected($urut === 'nama')>Urut A–Z</option>
                    <option value="murah" @selected($urut === 'murah')>Termurah</option>
                    <option value="mahal" @selected($urut === 'mahal')>Termahal</option>
                </select>
                <button type="submit" class="rounded-lg bg-brand px-4 py-2 text-sm font-medium text-white">Cari</button>
            </form>

            <div class="mt-4 flex flex-wrap gap-2">
                @foreach (array_merge(['Semua'], App\Models\Produk::KATEGORI) as $k)
                    <a href="{{ route('harga.index', ['kategori' => $k, 'cari' => $cari, 'urut' => $urut]) }}"
                       class="rounded-lg border px-3 py-1.5 text-sm {{ $kategoriAktif === $k ? 'border-brand bg-brand text-white' : 'border-line bg-white' }}">
                        {{ $k }}
                    </a>
                @endforeach
            </div>

            <p class="mt-4 text-xs text-[#63705f]">{{ $daftar->count() }} barang ditampilkan</p>

            <ul class="mt-3 space-y-3">
                @forelse ($daftar as $p)
                    <li class="flex flex-wrap items-center gap-3 rounded-2xl border border-line bg-white p-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-display text-base font-semibold">{{ $p->nama }}</h3>
                                @if ($p->promo)
                                    <span class="rounded-full bg-brand px-2 py-0.5 text-xs text-white">Promo</span>
                                @endif
                                @if ($p->stok === 0)
                                    <span class="rounded-full bg-red-600 px-2 py-0.5 text-xs text-white">Habis</span>
                                @elseif ($p->stokTipis())
                                    <span class="rounded-full bg-[#efeade] px-2 py-0.5 text-xs">Sisa {{ $p->stok }}</span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-[#63705f]">
                                {{ $p->kategori }} · per {{ $p->satuan }}
                                @if ($p->harga_grosir && $p->min_grosir)
                                    · grosir Rp {{ number_format($p->harga_grosir, 0, ',', '.') }} mulai {{ $p->min_grosir }} {{ $p->satuan }}
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-display text-xl font-semibold text-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-[#63705f]">/ {{ $p->satuan }}</p>
                        </div>
                        <form method="POST" action="{{ route('keranjang.tambah', $p) }}">
                            @csrf
                            <button class="rounded-lg bg-brand px-3 py-2 text-sm font-medium text-white disabled:opacity-40"
                                    @disabled($p->stok === 0) aria-label="Tambah {{ $p->nama }} ke keranjang">
                                + Tambah
                            </button>
                        </form>
                    </li>
                @empty
                    <li class="rounded-2xl border border-line bg-white p-8 text-center text-sm text-[#63705f]">
                        Barang tidak ditemukan. Coba kata kunci lain atau
                        <a href="{{ route('kelola.index') }}" class="font-medium text-brand underline">tambahkan barang baru</a>.
                    </li>
                @endforelse
            </ul>
        </div>

        <aside>
            @include('partials.keranjang', ['keranjang' => $keranjang])
        </aside>
    </div>

    <!-- Script Live Search / Auto Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputCari = document.getElementById('input-cari');
            const formCari = document.getElementById('form-cari');
            let timeout = null;

            if (inputCari && formCari) {
                // Otomatis fokus & pindahkan kursor ke akhir teks setelah submit/reload
                if (inputCari.value.length > 0) {
                    inputCari.focus();
                    inputCari.setSelectionRange(inputCari.value.length, inputCari.value.length);
                }

                // Event listener saat pengguna mengetik
                inputCari.addEventListener('input', () => {
                    clearTimeout(timeout);

                    // Tunggu 350 milidetik setelah karakter terakhir diketik sebelum submit form
                    timeout = setTimeout(() => {
                        formCari.submit();
                    }, 350);
                });
            }
        });
    </script>
@endsection