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
            // Status: draft, pending, approved, rejected, published
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'published'])
                  ->default('published')
                  ->after('location');
            
            // Rejection reason (if rejected)
            $table->text('rejection_reason')->nullable()->after('status');
            
            // Approved by admin
            $table->foreignId('approved_by')
                  ->nullable()
                  ->after('rejection_reason')
                  ->constrained('users')
                  ->nullOnDelete();
            
            // Approval timestamp
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'status',
                'rejection_reason',
                'approved_by',
                'approved_at'
            ]);
        });
    }
};
