<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProdukController extends Controller
{
    /** Halaman publik daftar harga: pencarian, filter kategori, urutan. */
    public function index(Request $request)
    {
        $cari = trim((string) $request->query('cari', ''));
        $kategori = (string) $request->query('kategori', 'Semua');
        $urut = (string) $request->query('urut', 'nama');

        $daftar = Produk::query()
            ->when($cari !== '', fn ($q) => $q->where('nama', 'like', "%{$cari}%"))
            ->when(
                in_array($kategori, Produk::KATEGORI, true),
                fn ($q) => $q->where('kategori', $kategori)
            )
            ->when($urut === 'murah', fn ($q) => $q->orderBy('harga'))
            ->when($urut === 'mahal', fn ($q) => $q->orderByDesc('harga'))
            ->when(! in_array($urut, ['murah', 'mahal'], true), fn ($q) => $q->orderBy('nama'))
            ->get();

        return view('harga.index', [
            'daftar' => $daftar,
            'cari' => $cari,
            'kategoriAktif' => $kategori,
            'urut' => $urut,
            'jumlahBarang' => Produk::count(),
            'jumlahPromo' => Produk::where('promo', true)->count(),
            'jumlahStokTipis' => Produk::where('stok', '<=', Produk::AMBANG_STOK_TIPIS)->count(),
            'keranjang' => $this->barisKeranjang($request),
        ]);
    }

    public function kelola()
    {
        return view('kelola.index', [
            'produk' => Produk::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:120'],
            'kategori' => ['required', 'in:'.implode(',', Produk::KATEGORI)],
            'satuan' => ['required', 'string', 'max:20'],
            'harga' => ['required', 'integer', 'min:0'],
            'harga_grosir' => ['nullable', 'integer', 'min:0', 'required_with:min_grosir'],
            'min_grosir' => ['nullable', 'integer', 'min:1', 'required_with:harga_grosir'],
            'stok' => ['required', 'integer', 'min:0'],
            'promo' => ['nullable', 'boolean'],
        ]);

        $data['promo'] = $request->boolean('promo');
        Produk::create($data);

        return back()->with('sukses', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'harga' => ['required', 'integer', 'min:0'],
            'harga_grosir' => ['nullable', 'integer', 'min:0', 'required_with:min_grosir'],
            'min_grosir' => ['nullable', 'integer', 'min:1', 'required_with:harga_grosir'],
            'stok' => ['required', 'integer', 'min:0'],
            'promo' => ['nullable', 'boolean'],
        ]);

        $data['promo'] = $request->boolean('promo');
        $produk->update($data);

        return back()->with('sukses', "Harga {$produk->nama} diperbarui.");
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();

        return back()->with('sukses', 'Barang dihapus.');
    }

    /** Unduh daftar harga sebagai CSV untuk dicetak/dibagikan. */
    public function ekspor(): StreamedResponse
    {
        $nama = 'daftar-harga-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Nama', 'Kategori', 'Satuan', 'Harga', 'Harga Grosir', 'Min Grosir', 'Stok', 'Promo']);

            Produk::orderBy('kategori')->orderBy('nama')->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $p) {
                    fputcsv($out, [
                        $p->nama, $p->kategori, $p->satuan, $p->harga,
                        $p->harga_grosir, $p->min_grosir, $p->stok, $p->promo ? 'ya' : 'tidak',
                    ]);
                }
            });

            fclose($out);
        }, $nama, ['Content-Type' => 'text/csv']);
    }

    /** Menyusun isi keranjang dari session menjadi baris siap tampil. */
    protected function barisKeranjang(Request $request): array
    {
        $isi = $request->session()->get('keranjang', []);
        if (empty($isi)) {
            return ['baris' => [], 'total' => 0];
        }

        $produk = Produk::whereIn('id', array_keys($isi))->get()->keyBy('id');
        $baris = [];
        $total = 0;

        foreach ($isi as $id => $qty) {
            $p = $produk->get($id);
            if (! $p) {
                continue;
            }
            $harga = $p->hargaEfektif((int) $qty);
            $subtotal = $harga * (int) $qty;
            $total += $subtotal;
            $baris[] = [
                'produk' => $p,
                'qty' => (int) $qty,
                'harga' => $harga,
                'grosir' => $harga < $p->harga,
                'subtotal' => $subtotal,
            ];
        }

        return ['baris' => $baris, 'total' => $total];
    }
}