<?php

namespace App\Console\Commands;

use App\Models\BonBarang;
use Illuminate\Console\Command;

class FixRejectedBonDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-rejected-bon-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all detail statuses to ditolak for rejected bon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating rejected bon details...');

        $rejectedBons = BonBarang::where('status', 'ditolak')->get();
        $count = 0;

        foreach ($rejectedBons as $bon) {
            $updated = $bon->details()->update(['status_detail' => 'ditolak']);
            $count += $updated;
            $this->info("Updated bon ID {$bon->id}: {$updated} details");
        }

        $this->info("Done! Updated {$count} detail records for {$rejectedBons->count()} rejected bon.");
        return 0;
    }
}
