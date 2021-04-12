<?php

declare(strict_types = 1);

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lang;

/**
 * Class ContactNotification
 *
 * @package App\Notifications
 */
class ContactNotification extends Notification
{
    use Queueable;

    /**
     * @var int
     */
    protected $callbackId;

    /**
     * ContactNotification constructor.
     *
     * @param int $callbackId
     */
    public function __construct(int $callbackId)
    {
        $this->callbackId = $callbackId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['database'];
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function toArray(): array
    {
        return [
            'title' => 'Callback notification',
            'callback_id' => $this->callbackId,
        ];
    }
}
