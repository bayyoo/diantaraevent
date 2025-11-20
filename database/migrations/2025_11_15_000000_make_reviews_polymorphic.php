<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Tambah kolom polymorphic kalau belum ada
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'reviewable_id')) {
                $table->unsignedBigInteger('reviewable_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('reviews', 'reviewable_type')) {
                $table->string('reviewable_type')->nullable()->after('reviewable_id');
            }
        });

        // 2. Migrasikan event_id ke polymorphic
        if (Schema::hasColumn('reviews', 'event_id')) {
            DB::table('reviews')
                ->whereNotNull('event_id')
                ->update([
                    'reviewable_id' => DB::raw('event_id'),
                    'reviewable_type' => 'App\\Models\\Event',
                ]);
        }

        // 3. Drop FK & drop event_id kalau masih ada
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'event_id')) {

                // Drop FK jika ada
                try { 
                    $table->dropForeign(['event_id']); 
                } catch (\Throwable $e) {}

                try { 
                    $table->dropForeign('reviews_event_id_foreign'); 
                } catch (\Throwable $e) {}

                // Drop kolom event_id
                try {
                    $table->dropColumn('event_id');
                } catch (\Throwable $e) {}
            }

            // Tambah index polymorphic (aman walau sudah ada)
            try { 
                $table->index(['reviewable_type', 'reviewable_id'], 'reviews_reviewable_index');
            } catch (\Throwable $e) {}

            // Unique untuk mencegah review duplikat
            try { 
                $table->unique(['user_id', 'reviewable_type', 'reviewable_id'], 'reviews_user_reviewable_unique'); 
            } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        // 1. Kembalikan event_id
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'event_id')) {
                $table->unsignedBigInteger('event_id')->nullable()->after('user_id');
            }
        });

        // 2. Migrasi balik hanya untuk Event
        DB::table('reviews')
            ->where('reviewable_type', 'App\\Models\\Event')
            ->update(['event_id' => DB::raw('reviewable_id')]);

        // 3. Drop index & kolom polymorphic
        Schema::table('reviews', function (Blueprint $table) {
            try { $table->dropUnique('reviews_user_reviewable_unique'); } catch (\Throwable $e) {}

            try { $table->dropIndex('reviews_reviewable_index'); } catch (\Throwable $e) {}

            if (Schema::hasColumn('reviews', 'reviewable_id')) {
                try { $table->dropColumn('reviewable_id'); } catch (\Throwable $e) {}
            }

            if (Schema::hasColumn('reviews', 'reviewable_type')) {
                try { $table->dropColumn('reviewable_type'); } catch (\Throwable $e) {}
            }

            // Restore unique lama
            try { $table->unique(['user_id', 'event_id']); } catch (\Throwable $e) {}
        });
    }
};
