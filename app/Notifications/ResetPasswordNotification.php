<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lang;
use URL;

/**
 * Class ResetPasswordNotification
 *
 * @package App\Notifications
 */
class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(Lang::get('auth.password_request.email.subject'))
            ->line(Lang::get('auth.password_request.email.line.description'))
            ->action(
                Lang::get('auth.password_request.email.button.text'),
                URL::route('web.password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->getAttribute('email'),
                ])
            )
            ->line(Lang::get('auth.password_request.email.line.unknown_request'));
    }
}
