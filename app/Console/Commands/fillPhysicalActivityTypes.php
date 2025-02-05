<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\PhysicalActivityType;

class fillPhysicalActivityTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:physicalactivitytypes';

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
        if (PhysicalActivityType::count() > 0) {
            PhysicalActivityType::truncate();
        }

        PhysicalActivityType::create([
            'id' => 1,
            'name' => 'Нулевая физическая активность',
            'description' => 'Нулевая физическая активность характеризуется отсутствием двигательной активности или минимальной активностью, связанной с ежедневными задачами, такими как сидение, лежание, небольшие перемещения по дому или офису. Этот уровень подразумевает мало или совсем не требует энергетических затрат сверх базовых функций организма.',
            'intensity' => 0
        ]);
        PhysicalActivityType::create([
            'id' => 2,
            'name' => 'Легкая физическая активность',
            'description' => 'Легкая физическая активность включает в себя действия, которые слегка повышают частоту сердечных сокращений и потребление кислорода. Примеры такой активности могут включать ходьбу на малые расстояния, легкую уборку, садоводство или медленный танец. Эти виды активности требуют небольшого усилия и не вызывают заметного увеличения дыхания.',
            'intensity' => 1
        ]);
        PhysicalActivityType::create([
            'id' => 3,
            'name' => 'Умеренная физическая активность',
            'description' => 'Умеренная физическая активность повышает частоту сердечных сокращений и дыхания до умеренной степени. К такой активности относятся быстрая ходьба, езда на велосипеде на равнинной местности, легкий бег, плавание или двойной теннис. Этот уровень активности подходит для большинства людей и способствует улучшению физической формы.',
            'intensity' => 2
        ]);
        PhysicalActivityType::create([
            'id' => 4,
            'name' => 'Интенсивная физическая активность',
            'description' => 'Интенсивная физическая активность значительно увеличивает частоту сердечных сокращений и потребление кислорода. Примеры включают бег, активную игру в баскетбол, футбол, быстрое плавание или тяжелую атлетику. Эти виды деятельности подходят для улучшения выносливости и физической формы и требуют значительного физического усилия.',
            'intensity' => 3
        ]);
        PhysicalActivityType::create([
            'id' => 5,
            'name' => 'Экстремальная физическая активность',
            'description' => 'Экстремальная физическая активность представляет собой деятельность, которая требует исключительных усилий и высокого уровня энергозатрат. Это может включать марафоны, ультрамарафоны, профессиональный спорт, продолжительные горные восхождения или триатлон. Этот уровень активности предназначен для высокоподготовленных спортсменов и может включать высокий риск травм.',
            'intensity' => 4
        ]);

        return Command::SUCCESS;
    }
}
