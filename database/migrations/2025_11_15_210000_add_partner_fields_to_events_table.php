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
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'partner_id')) {
                $table->unsignedBigInteger('partner_id')->nullable();
            }
            if (!Schema::hasColumn('events', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable();
            }
            if (!Schema::hasColumn('events', 'source')) {
                $table->string('source', 20)->default('admin');
            }
            if (!Schema::hasColumn('events', 'poster')) {
                $table->string('poster')->nullable();
            }
            if (!Schema::hasColumn('events', 'banners')) {
                $table->json('banners')->nullable();
            }
            // Note: we skip inspecting/adding unique index for slug here to avoid Doctrine dependency
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'banners')) {
                $table->dropColumn('banners');
            }
            if (Schema::hasColumn('events', 'poster')) {
                $table->dropColumn('poster');
            }
            if (Schema::hasColumn('events', 'source')) {
                $table->dropColumn('source');
            }
            if (Schema::hasColumn('events', 'organization_id')) {
                $table->dropColumn('organization_id');
            }
            if (Schema::hasColumn('events', 'partner_id')) {
                $table->dropColumn('partner_id');
            }
            // keep slug unique if was added; dropping may break existing data
        });
    }
};
