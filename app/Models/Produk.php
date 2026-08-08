<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    public const KATEGORI = [
        'Fotokopi & Print',
        'Jilid & Finishing',
        'ATK',
        'Aksesoris',
        'Lainnya',
    ];

    public const AMBANG_STOK_TIPIS = 5;

    protected $fillable = [
        'nama',
        'kategori',
        'satuan',
        'harga',
        'harga_grosir',
        'min_grosir',
        'stok',
        'promo',
    ];

    protected $casts = [
        'harga' => 'integer',
        'harga_grosir' => 'integer',
        'min_grosir' => 'integer',
        'stok' => 'integer',
        'promo' => 'boolean',
    ];

    /** Cek apakah stok barang dalam kondisi tipis (stok > 0 dan <= AMBANG_STOK_TIPIS) */
    public function stokTipis(): bool
    {
        return $this->stok > 0 && $this->stok <= self::AMBANG_STOK_TIPIS;
    }

    /** Helper kalkulasi harga grosir vs harga normal berdasarkan jumlah beli */
    public function hargaEfektif(int $qty): int
    {
        if ($this->harga_grosir && $this->min_grosir && $qty >= $this->min_grosir) {
            return $this->harga_grosir;
        }

        return $this->harga;
    }
}