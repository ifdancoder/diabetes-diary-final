<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RecordType;

class fillRecordTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:recordtypes';

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
        if (RecordType::count() > 0) {
            RecordType::truncate();
        }

        RecordType::create([
            'id' => 1,
            'name' => 'Обычная запись',
        ]);
        RecordType::create([
            'id' => 2,
            'name' => 'Запись в ходе эксперимента',
        ]);
        RecordType::create([
            'id' => 3,
            'name' => 'Спрогнозированная запись',
        ]);

        return Command::SUCCESS;
    }
}
