<?php

namespace Chapdel\AuthLog\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Chapdel\AuthLog\AuthLog;

class NewDevice extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The authentication log.
     *
     * @var \Chapdel\AuthLog\AuthLog
     */
    public $authenticationLog;

    /**
     * Create a new notification instance.
     *
     * @param  \Chapdel\AuthLog\AuthLog  $authenticationLog
     * @return void
     */
    public function __construct(AuthLog $authenticationLog)
    {
        $this->authenticationLog = $authenticationLog;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable->notifyAuthLogVia();
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
            ->subject(trans('authlog::messages.subject'))
            ->markdown('authlog::emails.new', [
                'account' => $notifiable,
                'time' => $this->authenticationLog->login_at,
                'ipAddress' => $this->authenticationLog->ip_address,
                'browser' => $this->authenticationLog->user_agent,
            ]);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->from(config('app.name'))
            ->warning()
            ->content(trans('authlog::messages.content', ['app' => config('app.name')]))
            ->attachment(function ($attachment) use ($notifiable) {
                $attachment->fields([
                    'Account' => $notifiable->email,
                    'Time' => $this->authenticationLog->login_at->toCookieString(),
                    'IP Address' => $this->authenticationLog->ip_address,
                    'Browser' => $this->authenticationLog->user_agent,
                ]);
            });
    }

    /**
     * Get the Nexmo / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
            ->content(trans('authlog::messages.content', ['app' => config('app.name')]));
    }
}
