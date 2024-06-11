<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use NotificationChannels\Telegram\TelegramMessage;

class RecordReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($notifiable->personalSettings->reminder_notifications) {
            if ($notifiable->tg_id && $notifiable->personalSettings->notifications_from_social) {
                return ['database', 'telegram'];
            }
            return ['database'];
        }
        else {
            return [];
        }
    }

    public function toTelegram(object $notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->tg_id)
            ->content("Вы давно не делали записей в системе <b>Дневник диабетика</b>.\nНе забывайте проводить самоконтроль.")
            ->parseMode('HTML')
            ->button('🔗 Открыть сайт', route('home'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'header' => '<b>' . ($notifiable->first_name ?? $notifiable->email) . '</b>, вы давно не делали записей в системе <b>Дневник диабетика</b>',
            'color' => 'status-dot-animated bg-red',
            'link' => route('home'),
            'description' => 'Не забывайте проводить самоконтроль',
        ];
    }
}
