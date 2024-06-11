<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use NotificationChannels\Telegram\TelegramMessage;

class LoggedOut extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($notifiable->personalSettings->log_in_out_notifications) {
            if ($notifiable->tg_id && $notifiable->personalSettings->notifications_from_social) {
                return ['database', 'telegram'];
            } else {
                if ($notifiable->personalSettings->log_in_out_notifications) {
                    return ['database'];
                } else {
                    return [];
                }
            }
        } else {
            return [];
        }
    }

    public function toTelegram(object $notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->tg_id)
            ->content("Вы вышли из системы <b>Дневник диабетика</b>")
            ->parseMode('HTML');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'header' => '<b>' . ($notifiable->first_name ?? $notifiable->email) . '</b>, вы вышли из системы <b>Дневник диабетика</b>',
            'color' => 'status-dot-animated bg-red',
            'link' => route('home'),
            'description' => 'Вы выполнили выход из системы в '. now()->format('d.m.Y H:i:s'),
        ];
    }
}
