<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori')->index();
            $table->string('satuan', 20)->default('pcs');
            $table->unsignedInteger('harga');
            $table->unsignedInteger('harga_grosir')->nullable();
            $table->unsignedInteger('min_grosir')->nullable();
            $table->unsignedInteger('stok')->default(0);
            $table->boolean('promo')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};