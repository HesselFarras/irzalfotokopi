<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function tambah(Request $request, Produk $produk)
    {
        $isi = $request->session()->get('keranjang', []);
        $isi[$produk->id] = ($isi[$produk->id] ?? 0) + 1;
        $request->session()->put('keranjang', $isi);

        return back()->with('sukses', "{$produk->nama} masuk keranjang.");
    }

    public function ubah(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $isi = $request->session()->get('keranjang', []);

        if ((int) $data['qty'] === 0) {
            unset($isi[$produk->id]);
        } else {
            $isi[$produk->id] = (int) $data['qty'];
        }

        $request->session()->put('keranjang', $isi);

        return back();
    }

    public function kosongkan(Request $request)
    {
        $request->session()->forget('keranjang');

        return back()->with('sukses', 'Keranjang dikosongkan.');
    }
}