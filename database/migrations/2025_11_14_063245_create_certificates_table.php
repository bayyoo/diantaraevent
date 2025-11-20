<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel `certificates` sudah dibuat oleh migration
        // 2025_08_26_020700_create_certificates_table.
        // Migration ini dibuat belakangan dan hanya berisi
        // schema minimal, jadi kita kosongkan supaya
        // tidak terjadi error "Base table or view already exists".

        // Tidak ada aksi yang perlu dilakukan di sini.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
