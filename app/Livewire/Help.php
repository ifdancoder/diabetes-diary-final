<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CarbonhydrateType;
use App\Models\PhysicalActivityType;
use App\Models\StressLevelType;

class Help extends Component
{

    public $current_tab;

    public $descriptions;
    
    public function mount($current_tab='insulin-injection-types')
    {
        $this->current_tab = $current_tab;
        $this->descriptions = [
            'carbonhydrate_types' => CarbonhydrateType::select('name', 'description')->get()->toArray(),
            'physical_activity_types' => PhysicalActivityType::select('name', 'description')->get()->toArray(),
            'stress_level_types' => StressLevelType::select('name', 'description')->get()->toArray(),
        ];
        $this->setUrl();
    }

    public function setUrl() {
        $this->dispatch('setUrl', route('help', ['current_tab' => $this->current_tab]));
    }

    public function changeTab($tab)
    {
        $this->current_tab = $tab;
        $this->setUrl();
    }
    
    public function render()
    {
        
        return view('livewire.help');
    }
}
