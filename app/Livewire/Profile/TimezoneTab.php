<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Timezone;

class TimezoneTab extends Component
{
    public $settings;
    public $timezones;
    public $timezone_id;
    public $show_datetime_type;
    public function mount() {
        $this->settings = auth()->user()->personalSettings;
        
        $this->timezone_id = $this->settings->timezone_id ?? 1;

        $this->timezones = Timezone::all();

        $this->show_datetime_type = $this->settings->show_datetime_type;
    }
    public function updateTimezone() {
        $this->settings->update([
            'timezone_id' => $this->timezone_id
        ]);
    }
    public function updateShowDatetimeType() {
        $this->settings->update([
            'show_datetime_type' => $this->show_datetime_type
        ]);
    }
    public function render()
    {
        return view('livewire.profile.timezone-tab');
    }
}
