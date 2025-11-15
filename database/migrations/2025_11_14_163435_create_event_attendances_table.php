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
        Schema::create('event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('attendance_token', 10)->unique();
            $table->boolean('is_attended')->default(false);
            $table->timestamp('attended_at')->nullable();
            $table->string('verified_by')->nullable(); // organizer who verified attendance
            $table->timestamps();
            
            $table->unique(['event_id', 'user_id']); // One attendance record per user per event
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendances');
    }
};
