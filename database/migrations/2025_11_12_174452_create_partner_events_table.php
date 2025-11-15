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
        Schema::create('partner_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained('partner_organizations')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('category');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('location');
            $table->json('location_details'); // address, coordinates, etc
            $table->string('poster')->nullable();
            $table->json('banners')->nullable(); // multiple banner images
            $table->text('terms_conditions')->nullable();
            $table->enum('status', ['draft', 'pending_review', 'approved', 'published', 'cancelled'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->json('metadata')->nullable(); // additional event data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_events');
    }
};
