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
    public $authLog;

    /**
     * Create a new notification instance.
     *
     * @param  \Chapdel\AuthLog\AuthLog  $authLog
     * @return void
     */
    public function __construct(AuthLog $authLog)
    {
        $this->authLog = $authLog;
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
                'time' => $this->authLog->login_at,
                'ipAddress' => $this->authLog->ip_address,
                'browser' => $this->authLog->user_agent,
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
                    'Time' => $this->authLog->login_at->toCookieString(),
                    'IP Address' => $this->authLog->ip_address,
                    'Browser' => $this->authLog->user_agent,
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

