<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Period;

class fillPeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:periods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (Period::count() > 0) {
            Period::truncate();
        }
        
        for ($i = 0; $i < 24; $i++) {

            $first_hour = $i;
            $last_hour = $i + 1 == 24 ? 0 : $i + 1;

            $padded_first_hour = str_pad($first_hour, 2, '0', STR_PAD_LEFT);
            $padded_last_hour = str_pad($last_hour, 2, '0', STR_PAD_LEFT);

            Period::create([
                'id' => $i + 1,
                'period' => $i + 1,
                'name' => $padded_first_hour . ':00-' . $padded_last_hour . ':00',
            ]);
        }
    }
}
