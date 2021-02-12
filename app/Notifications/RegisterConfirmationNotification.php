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
 * Class RegisterConfirmationNotification
 *
 * @package App\Notifications
 */
class RegisterConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var int
     */
    protected $registerCode;

    /**
     * RegisterConfirmationNotification constructor.
     *
     * @param int $registerCode
     */
    public function __construct(int $registerCode)
    {
        $this->registerCode = $registerCode;
    }

    /**
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(Lang::get('auth.register.verify.email.subject'))
            ->line(Lang::get('auth.register.verify.email.line.description'))
            ->action(
                Lang::get('auth.register.verify.email.buttons.submit.text'),
                URL::route('web.email.verify', [
                    'code' => $this->registerCode,
                    'email' => $notifiable->getAttribute('email'),
                ])
            );
    }
}
