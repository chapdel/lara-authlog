<?php

use Illuminate\Notifications\Messages\MailMessage;

class Welcome extends WelcomeNotification
{
    public function buildWelcomeNotificationMessage(): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name'));
    }
}
