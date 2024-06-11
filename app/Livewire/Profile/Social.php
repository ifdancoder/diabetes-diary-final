<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class Social extends Component
{
    public $user;

    public function mount()
    {
        $this->user = auth()->user();
    }
    
    public function disableTelegram() {
        $this->user->update([
            'tg_id' => null
        ]);
    }

    public function render()
    {
        return view('livewire.profile.social');
    }
}
