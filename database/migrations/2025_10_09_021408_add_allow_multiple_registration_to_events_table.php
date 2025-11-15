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
            // Add price column if it doesn't exist
            if (!Schema::hasColumn('events', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('capacity');
            }
            $table->boolean('allow_multiple_registration')->default(false)->after('price');
            $table->integer('max_registrations_per_user')->nullable()->after('allow_multiple_registration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['allow_multiple_registration', 'max_registrations_per_user']);
        });
    }
};
