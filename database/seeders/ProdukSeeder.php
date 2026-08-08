<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Jasa Fotokopi & Cetak
            [
                'nama' => 'Fotokopi A4 / F4 (70gr)',
                'kategori' => 'Fotokopi & Print',
                'satuan' => 'lembar',
                'harga' => 300,
                'harga_grosir' => 200,
                'min_grosir' => 100, // Minimal 100 lembar dapat 200/lembar
                'stok' => 9999,
                'promo' => false,
            ],
            [
                'nama' => 'Print Hitam Putih A4',
                'kategori' => 'Fotokopi & Print',
                'satuan' => 'lembar',
                'harga' => 500,
                'harga_grosir' => 350,
                'min_grosir' => 50,
                'stok' => 9999,
                'promo' => false,
            ],
            [
                'nama' => 'Print Full Color A4 (Foto/Gambar)',
                'kategori' => 'Fotokopi & Print',
                'satuan' => 'lembar',
                'harga' => 2000,
                'harga_grosir' => 1500,
                'min_grosir' => 20,
                'stok' => 9999,
                'promo' => true, // Lagi promo
            ],

            // Jasa Jilid & Finishing
            [
                'nama' => 'Jilid Lakban + Cover Mika',
                'kategori' => 'Jilid & Finishing',
                'satuan' => 'buku',
                'harga' => 4000,
                'harga_grosir' => 3000,
                'min_grosir' => 10,
                'stok' => 999,
                'promo' => false,
            ],
            [
                'nama' => 'Laminating Pres A4 / F4',
                'kategori' => 'Jilid & Finishing',
                'satuan' => 'lembar',
                'harga' => 5000,
                'harga_grosir' => null,
                'min_grosir' => null,
                'stok' => 500,
                'promo' => false,
            ],

            // ATK & Retail
            [
                'nama' => 'Pulpen Joyko Gel 0.5mm Hitam',
                'kategori' => 'ATK',
                'satuan' => 'pcs',
                'harga' => 3500,
                'harga_grosir' => 3000,
                'min_grosir' => 12, // Grosir per lusin
                'stok' => 144,
                'promo' => false,
            ],
            [
                'nama' => 'Buku Tulis Sidu 38 Lembar',
                'kategori' => 'ATK',
                'satuan' => 'pcs',
                'harga' => 4000,
                'harga_grosir' => 3500,
                'min_grosir' => 10, // Grosir per pack
                'stok' => 50,
                'promo' => true,
            ],
            [
                'nama' => 'Stopmap Kertas F4 (Warna)',
                'kategori' => 'ATK',
                'satuan' => 'pcs',
                'harga' => 1500,
                'harga_grosir' => 1000,
                'min_grosir' => 20,
                'stok' => 200,
                'promo' => false,
            ],
        ];

        foreach ($data as $item) {
            Produk::create($item);
        }
    }
}