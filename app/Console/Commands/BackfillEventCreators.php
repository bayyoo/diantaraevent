<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillEventCreators extends Command
{
    protected $signature = 'diantara:backfill-event-creators {--dry-run : Show changes without writing}';

    protected $description = 'Backfill events.creator metadata: source, partner_id, organization_id based on partner_events mapping';

    public function handle(): int
    {
        $dry = $this->option('dry-run');

        if (!DB::getSchemaBuilder()->hasTable('events')) {
            $this->error('events table not found');
            return self::FAILURE;
        }

        if (!DB::getSchemaBuilder()->hasTable('partner_events')) {
            $this->warn('partner_events table not found. Nothing to backfill.');
            return self::SUCCESS;
        }

        $count = 0;
        $events = DB::table('events')->select('id','slug','source','partner_id','organization_id')->get();
        foreach ($events as $e) {
            // Find partner event by slug match first, else by poster path match
            $pe = DB::table('partner_events')->where('slug', $e->slug)->first();
            if (!$pe) {
                // Try match by title as fallback
                $pe = DB::table('partner_events')->where('title', function($q) use ($e){ $q->select('title')->from('events')->where('id',$e->id); })->first();
            }
            if ($pe) {
                $update = [
                    'source' => 'partner',
                    'partner_id' => $pe->partner_id,
                    'organization_id' => $pe->organization_id,
                ];
                if ($dry) {
                    $this->line("Would update event #{$e->id} slug={$e->slug} -> ".json_encode($update));
                } else {
                    DB::table('events')->where('id', $e->id)->update($update);
                    $count++;
                }
            } else {
                // If not partner match, ensure source=admin for cleanliness
                if ($e->source !== 'partner') {
                    if ($dry) {
                        $this->line("Would set source=admin for event #{$e->id} slug={$e->slug}");
                    } else {
                        DB::table('events')->where('id', $e->id)->update(['source' => 'admin']);
                    }
                }
            }
        }

        if ($dry) {
            $this->info('Dry run completed.');
        } else {
            $this->info("Backfilled $count event(s) with partner creator info.");
        }
        return self::SUCCESS;
    }
}
