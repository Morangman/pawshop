<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Class ReminderNotification
 *
 * @package App\Notifications
 */
class ReminderNotification extends Notification
{
    use Queueable;

    /**
     * @var array
     */
    protected $notificationData;

    /**
     * @param array $notificationData
     */
    public function __construct(array $notificationData)
    {
        $this->notificationData = $notificationData;
    }

    /**
     * @return array
     */
    public function via()
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
        return $this->notificationData;
    }
}
