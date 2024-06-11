<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class Profile extends Component
{

    public $user;

    public $current_tab;
    
    public function mount($current_tab='basic')
    {
        $this->user = auth()->user();
        $this->current_tab = $current_tab;
        $this->setUrl();
    }

    public function setUrl() {
        $this->dispatch('setUrl', route('profile', ['current_tab' => $this->current_tab]));
    }

    public function changeTab($tab)
    {
        $this->current_tab = $tab;
        $this->setUrl();
    }
    
    public function render()
    {
        return view('livewire.profile.profile');
    }
}
