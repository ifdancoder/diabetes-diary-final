<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\InsulinInjectionType;

class fillInsulinInjectionTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:insulininjectiontypes';

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
        if (InsulinInjectionType::count() > 0) {
            InsulinInjectionType::truncate();
        }

        InsulinInjectionType::create([
            'id' => 1,
            'name' => 'Обычный болюс',
            'description' => 'Обычный болюс представляет собой стандартную дозу инсулина, вводимую непосредственно перед приёмом пищи для быстрого покрытия углеводов в пище и корректировки высокого уровня сахара в крови. Этот тип болюса рассчитывается на основе текущего уровня сахара в крови и количества углеводов, которые будут потреблены. Он быстро всасывается и действует в течение нескольких часов.',
        ]);
        InsulinInjectionType::create([
            'id' => 2,
            'name' => 'Растянутый болюс',
            'description' => 'Растянутый болюс позволяет вводить инсулин постепенно в течение длительного времени. Этот режим подходит для приёмов пищи с высоким содержанием жиров или белков, которые медленно усваиваются и вызывают постепенное повышение уровня сахара в крови. Таким образом, растянутый болюс помогает предотвратить резкие скачки сахара после еды и поддерживать стабильный уровень глюкозы в течение более длительного времени.',
        ]);

        return Command::SUCCESS;
    }
}
