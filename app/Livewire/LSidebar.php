<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Session;
use App\Models\Team;

class LSidebar extends Component
{
    protected $listeners = ['reRenderLayout' => 'render', 'setTab' => 'setTab'];

    public $user;
    public $current_tab;

    public function reRenderParent()
    {
        $this->render();
    }
    public function mount()
    {
        $route_name = request()->route()->getName();
        $this->current_tab = $tab = explode('.', $route_name)[0];

        $this->user = auth()->user();
    }
    public function render()
    {
        return view('livewire.l-sidebar');
    }
}
