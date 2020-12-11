<?php

namespace App\Listeners;

use App\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;

        $data = [
            'confirmLink' => route('auth.confirm', $user->token),
            'password' => $event->rawPassword,
            'restoreLink' => route('password.reset.token', $user->token),
            'confirmEmailLink' => config('app.url') . "email-confirm/{$user->token}",
            'loginLink' => config('app.url') . 'login',
            'offerLink' => config('app.url') . 'offer',
            'confidentialityLink' => config('app.url') . 'confidentiality',
        ];

        Mail::send('emails.register', $data, static function ($message) use ($user) {
            $message->from(config('mail.from.address', 'info@example.com'), config('mail.from.name', 'PLIZI'));
            $message->subject('PLIZI: Регистрация');
            $message->to($user->email, $user->profile->first_name . ' ' . $user->profile->last_name);
        });

    }
}
