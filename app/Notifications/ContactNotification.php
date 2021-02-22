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
     * @var \Illuminate\Http\Request
     */
    protected $contactData;

    /**
     * ContactNotification constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->contactData = $request;
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
            'name' => $this->contactData->get('name'),
            'phone' => $this->contactData->get('phone'),
            'email' => $this->contactData->get('email'),
            'text' => $this->contactData->get('text'),
            'date' => (new Carbon())->toDateTimeString(),
        ];
    }
}
