<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class Connection extends Component
{
    public $user;

    public function mount()
    {
        $this->user = auth()->user();
    }
    
    public function render()
    {
        return view('livewire.profile.connection');
    }
}
