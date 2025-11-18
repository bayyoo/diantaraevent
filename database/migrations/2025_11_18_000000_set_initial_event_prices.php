<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hanya set harga untuk event yang saat ini harganya 0
        // Aturan sederhana berbasis judul event supaya beragam

        // Workshop / Bootcamp
        DB::table('events')
            ->where('price', 0)
            ->where(function ($q) {
                $q->where('title', 'like', '%Workshop%')
                  ->orWhere('title', 'like', '%Bootcamp%');
            })
            ->update(['price' => 75000]);

        // Seminar / Tech Talk / Digital Marketing
        DB::table('events')
            ->where('price', 0)
            ->where(function ($q) {
                $q->where('title', 'like', '%Seminar%')
                  ->orWhere('title', 'like', '%Tech Talk%');
            })
            ->update(['price' => 100000]);

        // Festival / Music / FEST
        DB::table('events')
            ->where('price', 0)
            ->where(function ($q) {
                $q->where('title', 'like', '%Festival%')
                  ->orWhere('title', 'like', '%Music%')
                  ->orWhere('title', 'like', '%FEST%');
            })
            ->update(['price' => 300000]);

        // Competition / Championship / Contest
        DB::table('events')
            ->where('price', 0)
            ->where(function ($q) {
                $q->where('title', 'like', '%Competition%')
                  ->orWhere('title', 'like', '%Kompetisi%')
                  ->orWhere('title', 'like', '%CHAMPIONSHIP%')
                  ->orWhere('title', 'like', '%Contest%');
            })
            ->update(['price' => 150000]);

        // Exhibition / Fair
        DB::table('events')
            ->where('price', 0)
            ->where(function ($q) {
                $q->where('title', 'like', '%Exhibition%')
                  ->orWhere('title', 'like', '%BOOK FAIR%')
                  ->orWhere('title', 'like', '%Fair%');
            })
            ->update(['price' => 50000]);

        // Sisa event yang masih 0 bisa tetap gratis (price = 0)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset hanya event yang kita set di atas (berdasarkan kombinasi judul + harga)

        DB::table('events')
            ->whereIn('price', [75000, 100000, 150000, 300000, 50000])
            ->where(function ($q) {
                $q->where('title', 'like', '%Workshop%')
                  ->orWhere('title', 'like', '%Bootcamp%')
                  ->orWhere('title', 'like', '%Seminar%')
                  ->orWhere('title', 'like', '%Tech Talk%')
                  ->orWhere('title', 'like', '%Festival%')
                  ->orWhere('title', 'like', '%Music%')
                  ->orWhere('title', 'like', '%FEST%')
                  ->orWhere('title', 'like', '%Competition%')
                  ->orWhere('title', 'like', '%Kompetisi%')
                  ->orWhere('title', 'like', '%CHAMPIONSHIP%')
                  ->orWhere('title', 'like', '%Contest%')
                  ->orWhere('title', 'like', '%Exhibition%')
                  ->orWhere('title', 'like', '%BOOK FAIR%')
                  ->orWhere('title', 'like', '%Fair%');
            })
            ->update(['price' => 0]);
    }
};
