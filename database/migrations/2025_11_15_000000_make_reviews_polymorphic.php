<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'reviewable_id')) {
                $table->unsignedBigInteger('reviewable_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('reviews', 'reviewable_type')) {
                $table->string('reviewable_type')->nullable()->after('reviewable_id');
            }
        });

        // Migrate existing data from event_id -> reviewable
        if (Schema::hasColumn('reviews', 'event_id')) {
            DB::table('reviews')->whereNotNull('event_id')->update([
                'reviewable_id' => DB::raw('event_id'),
                'reviewable_type' => 'App\\Models\\Event',
            ]);
        }

        Schema::table('reviews', function (Blueprint $table) {
            // Drop old constraints and column if exists
            if (Schema::hasColumn('reviews', 'event_id')) {
                // Drop unique index on (user_id, event_id) if present
                try { $table->dropUnique(['user_id', 'event_id']); } catch (\Throwable $e) {}
                // Attempt to drop named unique if Laravel named it
                try { $table->dropUnique('reviews_user_id_event_id_unique'); } catch (\Throwable $e) {}
                // Attempt to drop FK if present (name may vary)
                try { $table->dropForeign(['event_id']); } catch (\Throwable $e) {}
                try { $table->dropForeign('reviews_event_id_foreign'); } catch (\Throwable $e) {}
                // Finally drop the column
                $table->dropColumn('event_id');
            }
            // Indexes for polymorphic
            $table->index(['reviewable_type', 'reviewable_id'], 'reviews_reviewable_index');
            // Prevent duplicate reviews by same user on same reviewable
            try { $table->unique(['user_id', 'reviewable_type', 'reviewable_id'], 'reviews_user_reviewable_unique'); } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'event_id')) {
                $table->unsignedBigInteger('event_id')->nullable()->after('user_id');
            }
        });

        // Attempt best-effort rollback for Event only
        DB::table('reviews')
            ->where('reviewable_type', 'App\\Models\\Event')
            ->update(['event_id' => DB::raw('reviewable_id')]);

        Schema::table('reviews', function (Blueprint $table) {
            // Drop new unique and index
            try { $table->dropUnique('reviews_user_reviewable_unique'); } catch (\Throwable $e) {}
            if (Schema::hasColumn('reviews', 'reviewable_id')) {
                $table->dropIndex('reviews_reviewable_index');
                $table->dropColumn('reviewable_id');
            }
            if (Schema::hasColumn('reviews', 'reviewable_type')) {
                $table->dropColumn('reviewable_type');
            }
            // Restore unique on (user_id, event_id)
            try { $table->unique(['user_id', 'event_id']); } catch (\Throwable $e) {}
            // Re-add FK if needed will be handled by separate migration
        });
    }
};
