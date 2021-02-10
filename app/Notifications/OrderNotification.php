<?php

declare(strict_types = 1);

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

/**
 * Class OrderNotification
 *
 * @package App\Notifications
 */
class OrderNotification extends Notification
{
    use Queueable;

    /**
     * @var array
     */
    protected $orderId;

    /**
     * OrderNotification constructor.
     *
     * @param int $orderId
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return array
     */
    public function via()
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $date = (new Carbon())->toDateTimeString();

        return (new MailMessage)
            ->greeting('Вітаю!')
            ->line('Нове замовлення')
            ->action('Замовлення', URL::route('admin.order.edit', ['order' => $this->orderId]))
            ->salutation($date);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function toArray(): array
    {
        return [
            'title' => 'Нове замовлення',
            'order_id' => $this->orderId,
        ];
    }
}
