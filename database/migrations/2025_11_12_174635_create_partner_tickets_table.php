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
        Schema::create('partner_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('partner_events')->onDelete('cascade');
            $table->string('name'); // Regular, VIP, Early Bird, etc
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->integer('sold')->default(0);
            $table->datetime('sale_start');
            $table->datetime('sale_end');
            $table->json('benefits')->nullable(); // list of ticket benefits
            $table->boolean('is_active')->default(true);
            $table->integer('min_purchase')->default(1);
            $table->integer('max_purchase')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_tickets');
    }
};
