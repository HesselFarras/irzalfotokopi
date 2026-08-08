@extends('layouts.app')

@section('title', 'Kelola Barang & Harga — Toko Berkah Jaya')
@section('description', 'Tambah barang baru, ubah harga jual dan harga grosir, atur stok, lalu ekspor daftar harga toko kelontong ke CSV.')

@section('konten')
    <h1 class="font-display text-3xl font-semibold">Kelola Barang & Harga</h1>
    <p class="mt-2 max-w-2xl text-sm text-[#63705f]">
        Ubah harga langsung di tabel, tambah barang baru, atau unduh daftar harga untuk dicetak.
    </p>

    <section class="mt-6 rounded-2xl border border-line bg-white p-5">
        <h2 class="font-display text-lg font-semibold">Tambah barang</h2>
        <form method="POST" action="{{ route('kelola.store') }}" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @csrf
            <label class="text-sm">Nama barang
                <input name="nama" value="{{ old('nama') }}" required placeholder="cth. Fotokopi Warna A4 1 Lembar"
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2">
            </label>
            <label class="text-sm">Kategori
                <select name="kategori" class="mt-1 w-full rounded-lg border border-line px-3 py-2">
                    @foreach (App\Models\Produk::KATEGORI as $k)
                        <option value="{{ $k }}" @selected(old('kategori') === $k)>{{ $k }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm">Satuan
                <input name="satuan" value="{{ old('satuan', 'pcs') }}" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2">
            </label>
            <label class="text-sm">Harga jual
                <input name="harga" type="number" min="0" value="{{ old('harga') }}" required placeholder="12000"
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2">
            </label>
            <label class="text-sm">Harga grosir (opsional)
                <input name="harga_grosir" type="number" min="0" value="{{ old('harga_grosir') }}"
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2">
            </label>
            <label class="text-sm">Min. jumlah grosir
                <input name="min_grosir" type="number" min="1" value="{{ old('min_grosir') }}"
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2">
            </label>
            <label class="text-sm">Stok
                <input name="stok" type="number" min="0" value="{{ old('stok', 0) }}" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2">
            </label>
            <label class="flex items-end gap-2 text-sm">
                <input type="checkbox" name="promo" value="1" class="h-4 w-4"> Tandai promo
            </label>
            <div class="flex flex-wrap gap-2 lg:col-span-4">
                <button class="rounded-lg bg-brand px-4 py-2 text-sm font-medium text-white">Simpan barang</button>
                <a href="{{ route('kelola.ekspor') }}" class="rounded-lg border border-line px-4 py-2 text-sm">Unduh CSV</a>
            </div>
        </form>
    </section>

    <section class="mt-8 overflow-x-auto rounded-2xl border border-line bg-white p-2">
        <table class="w-full text-sm">
            <thead class="text-left text-xs uppercase text-[#63705f]">
                <tr>
                    <th class="p-3">Barang</th>
                    <th class="p-3">Harga</th>
                    <th class="p-3">Grosir</th>
                    <th class="p-3">Min</th>
                    <th class="p-3">Stok</th>
                    <th class="p-3">Promo</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produk as $p)
                    <tr class="border-t border-line align-middle">
                        <td class="p-3">
                            <p class="font-medium">{{ $p->nama }}</p>
                            <p class="text-xs text-[#63705f]">{{ $p->kategori }} · per {{ $p->satuan }}</p>
                        </td>
                        <td class="p-3">
                            <input form="ubah-{{ $p->id }}" name="harga" type="number" min="0" value="{{ $p->harga }}"
                                   aria-label="Harga {{ $p->nama }}" class="w-28 rounded-lg border border-line px-2 py-1.5">
                        </td>
                        <td class="p-3">
                            <input form="ubah-{{ $p->id }}" name="harga_grosir" type="number" min="0" value="{{ $p->harga_grosir }}"
                                   aria-label="Harga grosir {{ $p->nama }}" class="w-28 rounded-lg border border-line px-2 py-1.5">
                        </td>
                        <td class="p-3">
                            <input form="ubah-{{ $p->id }}" name="min_grosir" type="number" min="1" value="{{ $p->min_grosir }}"
                                   aria-label="Minimal grosir {{ $p->nama }}" class="w-20 rounded-lg border border-line px-2 py-1.5">
                        </td>
                        <td class="p-3">
                            <input form="ubah-{{ $p->id }}" name="stok" type="number" min="0" value="{{ $p->stok }}"
                                   aria-label="Stok {{ $p->nama }}" class="w-20 rounded-lg border border-line px-2 py-1.5">
                        </td>
                        <td class="p-3">
                            <input form="ubah-{{ $p->id }}" type="checkbox" name="promo" value="1" @checked($p->promo)
                                   class="h-4 w-4" aria-label="Promo {{ $p->nama }}">
                        </td>
                        <td class="space-x-2 p-3 whitespace-nowrap">
                            <button form="ubah-{{ $p->id }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-medium text-white">Simpan</button>
                            <button type="button" 
                                    onclick="bukaModalHapus('{{ route('kelola.destroy', $p) }}', '{{ addslashes($p->nama) }}')" 
                                    class="text-xs font-medium text-red-600">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    {{-- Form ubah disimpan di luar tabel --}}
    @foreach ($produk as $p)
        <form method="POST" action="{{ route('kelola.update', $p) }}" id="ubah-{{ $p->id }}" class="hidden">
            @csrf @method('PATCH')
        </form>
    @endforeach

    <!-- Modal Konfirmasi Hapus Custom -->
    <div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm transition-opacity duration-200 opacity-0">
        <div class="w-full max-w-sm scale-95 transform rounded-2xl border border-line bg-white p-6 shadow-xl transition-all duration-200">
            <div class="flex items-center gap-3">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-display text-base font-semibold text-gray-900">Hapus Barang?</h3>
                    <p class="text-xs text-[#63705f]">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <p id="nama-barang-hapus" class="mt-4 rounded-xl border border-line bg-gray-50 p-3 text-center text-sm font-semibold text-gray-800">
                <!-- Nama barang dimuat secara dinamis -->
            </p>

            <div class="mt-6 flex gap-2">
                <button type="button" id="btn-batal-hapus" class="flex-1 rounded-lg border border-line bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form id="form-hapus-modal" method="POST" action="" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Modal Hapus -->
    <script>
        const modalHapus = document.getElementById('modal-hapus');
        const modalContent = modalHapus.querySelector('div');
        const formHapus = document.getElementById('form-hapus-modal');
        const namaBarangText = document.getElementById('nama-barang-hapus');
        const btnBatal = document.getElementById('btn-batal-hapus');

        function bukaModalHapus(actionUrl, namaBarang) {
            formHapus.action = actionUrl;
            namaBarangText.textContent = namaBarang;

            modalHapus.classList.remove('hidden');
            modalHapus.classList.add('flex');
            setTimeout(() => {
                modalHapus.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function tutupModalHapus() {
            modalHapus.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                modalHapus.classList.remove('flex');
                modalHapus.classList.add('hidden');
            }, 200);
        }

        btnBatal.addEventListener('click', tutupModalHapus);

        modalHapus.addEventListener('click', (e) => {
            if (e.target === modalHapus) {
                tutupModalHapus();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modalHapus.classList.contains('hidden')) {
                tutupModalHapus();
            }
        });
    </script>
@endsection