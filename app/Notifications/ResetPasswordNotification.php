<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The password reset email.
     *
     * @var string
     */
    public $email;

    /**
     * Create a new notification instance.
     *
     * @param $token
     * @param $email
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Wachtwoord herstellen')
            ->line('Je ontvangt deze e-mail, omdat we een wachtwoord herstel verzoek ontvangen hebben voor jouw account.')
            ->action('Herstel Wachtwoord', cms_url('password/reset/'.$this->token.'?email='.$this->email))
            ->line('Als je dit verzoek niet hebt ingediend, dan kan je deze e-mail negeren.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
