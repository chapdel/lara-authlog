<?php

use Illuminate\Notifications\Messages\MailMessage;

class Welcome extends WelcomeNotification
{

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }


    public function buildWelcomeNotificationMessage(): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name'));
    }
}
