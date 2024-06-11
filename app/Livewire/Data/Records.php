<?php

namespace App\Livewire\Data;

use Livewire\Component;

class Records extends Component
{
    protected $listeners = ['reRenderRecordsList' => 'render'];

    public $user, $records_dates, $current_tab, $records;

    public function mount($current_tab=null) {
        $this->user = auth()->user();
        $this->records_dates = $this->user->getRecordsDates();
        $this->records = [];

        if ($current_tab) {
            $this->changeTab($current_tab);
        } else {
            if (count($this->records_dates) > 0) {
                $this->changeTab($this->records_dates[0]);
            }
        }
    }
    
    public function setUrl() {
        $this->dispatch('setUrl', route('records', ['current_tab' => $this->current_tab]));
    }

    public function changeTab($tab)
    {
        $this->current_tab = $tab;
        $this->records = $this->user->getRecordsByDate($tab);
        $this->setUrl();
    }

    public function render()
    {
        return view('livewire.data.records');
    }
}
