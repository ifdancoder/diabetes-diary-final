<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;

class Basic extends Component
{
    protected $listeners = ['reRenderBasic' => 'render'];

    public $user;
    public $first_name;
    public $last_name;

    public function mount()
    {
        $this->user = auth()->user();
        $this->first_name = $this->user->first_name;
        $this->last_name = $this->user->last_name;
    }
    
    public function updateNames() {
        $this->user->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name
        ]);

        $this->dispatch('reRenderLayout');
    }

    public function render()
    {
        return view('livewire.profile.basic');
    }
}
