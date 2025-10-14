<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplyStatusNotification extends Notification
{
    use Queueable;

    protected $apply;

    public function __construct($apply)
    {
        $this->apply = $apply;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $statusText = match ($this->apply->status) {
            'approved' => 'Müraciətiniz təsdiq olunub! 🎉',
            'rejected' => 'Təəsüfki, müraciətiniz imtina olunub ❌',
            default => 'Sizin statusunuz yenilənib',
        };

        return (new MailMessage)
            ->subject('Sizin statusunuz yenilənib')
            ->greeting('Salam, ' . $notifiable->name . '!')
            ->line($statusText)
            ->line('Təlim: ' . $this->apply->course->title)
            ->line('Mesaj: ' . ($this->apply->message ?? '—'))
            ->line('Xidmətimizdən istifadə etdiyiniz üçün təşəkkür edirik!');
    }
}
