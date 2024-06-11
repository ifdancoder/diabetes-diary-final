<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class Notifications extends Component
{
    public $settings;
    public $log_in_out_notifications;
    public $reminder_notifications;
    public $notifications_from_social;

    public function mount()
    {
        $this->settings = auth()->user()->personalSettings;
        $this->log_in_out_notifications = $this->settings->log_in_out_notifications;
        $this->reminder_notifications = $this->settings->reminder_notifications;
        $this->notifications_from_social = $this->settings->notifications_from_social;
    }

    public function updateNotifications() {
        $this->settings->update([
            'log_in_out_notifications' => $this->log_in_out_notifications,
            'reminder_notifications' => $this->reminder_notifications,
            'notifications_from_social' => $this->notifications_from_social
        ]);
    }
    
    public function render()
    {
        return view('livewire.profile.notifications');
    }
}
