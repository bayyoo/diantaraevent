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
            $table->boolean('has_certificate')->default(false)->after('status');
            $table->enum('certificate_type', ['template', 'custom'])->default('template')->after('has_certificate');
            $table->string('certificate_title')->nullable()->after('certificate_type');
            $table->string('certificate_file_path')->nullable()->after('certificate_title');
            $table->text('certificate_signature')->nullable()->after('certificate_file_path');
            $table->string('certificate_signature_name')->nullable()->after('certificate_signature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'has_certificate',
                'certificate_type', 
                'certificate_title',
                'certificate_file_path',
                'certificate_signature',
                'certificate_signature_name'
            ]);
        });
    }
};
