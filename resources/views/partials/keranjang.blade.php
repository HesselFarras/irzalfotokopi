{{-- Kalkulator belanja: total, harga grosir otomatis, hitung kembalian, kirim struk WA --}}
@php
    $baris = $keranjang['baris'] ?? [];
    $total = $keranjang['total'] ?? 0;
    $teksStruk = "*Struk Belanja*\n";
    foreach ($baris as $b) {
        $teksStruk .= $b['produk']->nama.' x'.$b['qty'].' '.$b['produk']->satuan.' = Rp '.number_format($b['subtotal'], 0, ',', '.')."\n";
    }
    $teksStruk .= 'Total: Rp '.number_format($total, 0, ',', '.');
@endphp

<div class="sticky top-24 rounded-2xl border border-line bg-white p-5 shadow-sm">
    <div class="flex items-baseline justify-between">
        <h2 class="font-display text-lg font-semibold">Kalkulator Belanja</h2>
        <span class="text-xs text-[#63705f]">{{ count($baris) }} item</span>
    </div>

    @if (empty($baris))
        <p class="mt-4 text-sm text-[#63705f]">
            Belum ada barang. Klik <span class="font-medium text-[#22372c]">Tambah</span> pada daftar
            harga untuk menghitung total belanja.
        </p>
    @else
        <ul class="mt-4 space-y-3">
            @foreach ($baris as $b)
                <li class="flex items-start gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium">{{ $b['produk']->nama }}</p>
                        <p class="text-xs text-[#63705f]">
                            Rp {{ number_format($b['harga'], 0, ',', '.') }} / {{ $b['produk']->satuan }}
                            @if ($b['grosir']) · harga grosir @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-1">
                        <form method="POST" action="{{ route('keranjang.ubah', $b['produk']) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="qty" value="{{ $b['qty'] - 1 }}">
                            <button class="h-7 w-7 rounded-md border border-line" aria-label="Kurangi {{ $b['produk']->nama }}">−</button>
                        </form>
                        <span class="w-8 text-center text-sm font-semibold">{{ $b['qty'] }}</span>
                        <form method="POST" action="{{ route('keranjang.ubah', $b['produk']) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="qty" value="{{ $b['qty'] + 1 }}">
                            <button class="h-7 w-7 rounded-md border border-line" aria-label="Tambah {{ $b['produk']->nama }}">+</button>
                        </form>
                    </div>
                    <span class="w-24 text-right text-sm font-semibold text-price">
                        Rp {{ number_format($b['subtotal'], 0, ',', '.') }}
                    </span>
                </li>
            @endforeach
        </ul>
    @endif

    <hr class="my-4 border-line">

    <div class="flex items-center justify-between">
        <span class="text-sm text-[#63705f]">Total</span>
        <span class="font-display text-2xl font-semibold text-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
    </div>

    <div class="mt-4">
        <label for="bayar" class="text-xs font-medium text-[#63705f]">Uang pembeli</label>
        <input id="bayar" type="number" inputmode="numeric" placeholder="cth. 50000"
               data-total="{{ $total }}"
               class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm outline-none focus:border-brand">
        <p id="kembalian" class="mt-2 text-sm"></p>
    </div>

    <div class="mt-4 flex gap-2">
        <a href="https://wa.me/081213581336?text={{ urlencode($teksStruk) }}" target="_blank" rel="noopener"
           class="flex-1 rounded-lg bg-brand px-3 py-2 text-center text-sm font-medium text-white {{ empty($baris) ? 'pointer-events-none opacity-50' : '' }}">
            Kirim struk
        </a>
        <form method="POST" action="{{ route('keranjang.kosongkan') }}">
            @csrf @method('DELETE')
            <button class="rounded-lg border border-line px-3 py-2 text-sm" @disabled(empty($baris))>Kosongkan</button>
        </form>
    </div>
</div>

<script>
    // Hitung kembalian di sisi klien (tanpa reload)
    document.getElementById('bayar')?.addEventListener('input', (e) => {
        const total = Number(e.target.dataset.total || 0);
        const bayar = Number(e.target.value || 0);
        const out = document.getElementById('kembalian');
        if (!e.target.value) { out.textContent = ''; return; }
        const selisih = bayar - total;
        const fmt = (n) => 'Rp ' + n.toLocaleString('id-ID');
        out.textContent = selisih >= 0 ? 'Kembalian: ' + fmt(selisih) : 'Kurang ' + fmt(Math.abs(selisih));
        out.className = 'mt-2 text-sm ' + (selisih >= 0 ? 'font-semibold' : 'text-red-600');
    });
</script>