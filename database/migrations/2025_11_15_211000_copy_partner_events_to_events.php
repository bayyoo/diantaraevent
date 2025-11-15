<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Copy partner_events into events table (idempotent-ish)
        if (!DB::getSchemaBuilder()->hasTable('partner_events')) {
            return; // nothing to copy
        }

        $adminId = DB::table('users')->where('is_admin', 1)->value('id') ?? DB::table('users')->min('id');
        if (!$adminId) {
            // Cannot create events without created_by; abort gracefully
            return;
        }

        $partnerEvents = DB::table('partner_events')->get();

        foreach ($partnerEvents as $pe) {
            // Skip if an event with the same slug already exists
            $slug = $pe->slug ?: Str::slug($pe->title);
            $exists = DB::table('events')->where('slug', $slug)->exists();
            if ($exists) {
                // Try unique slug by appending id
                $slug = $slug.'-pe'.$pe->id;
            }

            // Derive date/time
            $eventDate = $pe->start_date ?? now()->toDateString();
            $eventTime = $pe->end_date ? null : null; // keep null if unknown

            // Compute minimal price from partner_tickets
            $minPrice = null;
            if (DB::getSchemaBuilder()->hasTable('partner_tickets')) {
                $minPrice = DB::table('partner_tickets')
                    ->where('event_id', $pe->id)
                    ->min('price');
            }

            // Insert into events
            DB::table('events')->insert([
                'title' => $pe->title,
                'slug' => $slug,
                'description' => $pe->description,
                'event_date' => $eventDate,
                'event_time' => $eventTime,
                'location' => $pe->location ?? ($pe->location_details ?? ''),
                'capacity' => null,
                'flyer_path' => $pe->poster ?? null,
                'created_by' => $adminId,
                'partner_id' => $pe->partner_id ?? null,
                'organization_id' => $pe->organization_id ?? null,
                'source' => 'partner',
                'poster' => $pe->poster ?? null,
                'banners' => $pe->banners ?? null,
                'price' => $minPrice ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Best-effort rollback: remove rows we created (source='partner')
        if (DB::getSchemaBuilder()->hasTable('events')) {
            DB::table('events')->where('source', 'partner')->delete();
        }
    }
};
