<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\SugarLevelType;

class fillSugarLevelTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:sugarleveltypes';

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
        if (SugarLevelType::count() > 0) {
            SugarLevelType::truncate();
        }

        SugarLevelType::create([
            'id' => 1,
            'name' => 'Показания c CGM',
        ]);
        SugarLevelType::create([
            'id' => 2,
            'name' => 'Показания с глюкометра',
        ]);
        SugarLevelType::create([
            'id' => 3,
            'name' => 'Спрогнозированное значение',
        ]);

        return Command::SUCCESS;
    }
}
