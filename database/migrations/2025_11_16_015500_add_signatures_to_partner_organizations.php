<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('partner_organizations', function (Blueprint $table) {
            $table->string('signature1_name')->nullable()->after('logo');
            $table->string('signature1_title')->nullable()->after('signature1_name');
            $table->string('signature1_image')->nullable()->after('signature1_title');
            $table->enum('signature1_type', ['upload', 'draw'])->nullable()->after('signature1_image');

            $table->string('signature2_name')->nullable()->after('signature1_type');
            $table->string('signature2_title')->nullable()->after('signature2_name');
            $table->string('signature2_image')->nullable()->after('signature2_title');
            $table->enum('signature2_type', ['upload', 'draw'])->nullable()->after('signature2_image');

            $table->string('stamp_image')->nullable()->after('signature2_type');
        });
    }

    public function down(): void
    {
        Schema::table('partner_organizations', function (Blueprint $table) {
            $table->dropColumn([
                'signature1_name', 'signature1_title', 'signature1_image', 'signature1_type',
                'signature2_name', 'signature2_title', 'signature2_image', 'signature2_type',
                'stamp_image'
            ]);
        });
    }
};
