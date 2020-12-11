<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPassword extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public $password;

    /**
     * Create a new notification instance.
     *
     * @param $password
     */
    public function __construct($password)
    {
        $this->password = $password;
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
        $password = $this->password;

        return (new MailMessage)
            ->view(
                'emails.reset_password_success',
                [
                    'password' => $password,
                    'offerLink' => config('app.url') . 'offer',
                    'confidentialityLink' => config('app.url') . 'confidentiality',
                ]
            )->subject('PLIZI: Ваш новый пароль');
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
