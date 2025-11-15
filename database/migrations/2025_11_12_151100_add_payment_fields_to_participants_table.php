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
        Schema::table('participants', function (Blueprint $table) {
            if (!Schema::hasColumn('participants', 'order_id')) {
                $table->string('order_id')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('participants', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'challenge'])->default('pending')->after('order_id');
            }
            if (!Schema::hasColumn('participants', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('participants', 'snap_token')) {
                $table->text('snap_token')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('participants', 'attendance_token')) {
                $table->string('attendance_token', 10)->nullable()->after('snap_token');
            }
            if (!Schema::hasColumn('participants', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('attendance_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn([
                'order_id',
                'payment_status', 
                'amount',
                'snap_token',
                'attendance_token',
                'paid_at'
            ]);
        });
    }
};
