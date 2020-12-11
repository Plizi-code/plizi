<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $token = $this->token;
        $email = $notifiable->getEmailForPasswordReset();

        return (new MailMessage)
            ->view(
                'emails.reset_password_link',
                [
                    'url' => config('app.url') . 'password/update?token=' . $token . '&email=' . $email,
                    'minutes' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
                    'offerLink' => config('app.url') . 'offer',
                    'confidentialityLink' => config('app.url') . 'confidentiality',
                ]
            )->subject('PLIZI: Восстановление пароля');
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
