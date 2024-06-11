<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Console\Commands\fillPeriods;
use App\Console\Commands\fillCarbonhydrateTypes;
use App\Console\Commands\fillTimezones;
use App\Console\Commands\fillPhysicalActivityTypes;
use App\Console\Commands\fillSugarLevelTypes;
use App\Console\Commands\fillStressLevelTypes;
use App\Console\Commands\fillRecordTypes;

class fillAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:all';

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
        (new fillPeriods)->handle();
        (new fillCarbonhydrateTypes)->handle();
        (new fillTimezones)->handle();
        (new fillPhysicalActivityTypes)->handle();
        (new fillSugarLevelTypes)->handle();
        (new fillStressLevelTypes)->handle();
        (new fillRecordTypes)->handle();
    }
}
