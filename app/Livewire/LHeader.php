<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;

class LHeader extends Component
{
    protected $listeners = ['reRenderLayout' => 'render'];

    public $user;

    public function mount(Request $request) {
        $this->user = auth()->user();
    }

    public function render()
    {
        return view('livewire.l-header');
    }
}
