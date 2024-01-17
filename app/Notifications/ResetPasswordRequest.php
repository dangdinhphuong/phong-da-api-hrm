<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordRequest extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = config('app.web_url') . 'https://da-hrm.netlify.app/reset-password?token=' . $this->token;

        return (new MailMessage)
            ->subject(__('message.email_reset_pass.subject'))
            ->greeting(__('message.email_reset_pass.greeting'))
            ->line(__('message.email_reset_pass.content'))
            ->action(__('message.email_reset_pass.reset_password'), $url)
            ->line(__('message.email_reset_pass.content_if'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
