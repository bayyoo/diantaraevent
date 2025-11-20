<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'has_certificate')) {
                $table->boolean('has_certificate')->default(false)->after('approved_at');
            }

            if (!Schema::hasColumn('events', 'certificate_template')) {
                $table->enum('certificate_template', ['template_a','template_b','custom'])
                    ->default('template_a')
                    ->after('has_certificate');
            }

            if (!Schema::hasColumn('events', 'custom_certificate_path')) {
                $table->string('custom_certificate_path')->nullable()->after('certificate_template');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['has_certificate','certificate_template','custom_certificate_path']);
        });
    }
};
