<?php

namespace App\Notifications;

use App\Models\Apply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationNotification extends Notification
{
    use Queueable;

    public $apply;

    /**
     * Create a new notification instance.
     */
    public function __construct(Apply $apply)
    {
        $this->apply = $apply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Yeni müraciət')
            ->line("{$this->apply->user->name} ({$this->apply->user->username}) yeni müraciət göndərib.")
            ->action('Müraciətə bax', url('/applies/'.$this->apply->id))
            ->line('Təşəkkürlər!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}