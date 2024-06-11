<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class RealTimeNotifications extends Component
{
    public $user;

    public function mount($id)
    {
        $this->user = User::find($id);
        $this->isShow = false;
    }
    public function markAsRead() {
        $this->user->unreadNotifications->markAsRead();
    }
    public function render()
    {
        return view('livewire.real-time-notifications');
    }
}
