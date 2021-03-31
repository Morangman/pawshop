<?php

declare(strict_types = 1);

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

/**
 * Class OrderConfirmNotification
 *
 * @package App\Notifications
 */
class OrderConfirmNotification extends Notification
{
    use Queueable;

    /**
     * @var array
     */
    protected $order;

    /**
     * OrderConfirmNotification constructor.
     *
     * @param array $order
     */
    public function __construct(array $order)
    {
        $this->order = $order;
    }

    /**
     * @return array
     */
    public function via()
    {
        return ['database'];
    }

//    /**
//     * Get the mail representation of the notification.
//     *
//     * @param  mixed  $notifiable
//     *
//     * @return \Illuminate\Notifications\Messages\MailMessage
//     */
//    public function toMail($notifiable)
//    {
//        $date = (new Carbon())->toDateTimeString();
//
//        return (new MailMessage)
//            ->greeting('Order was be confirmed!')
//            ->line('Order was be confirmed by user')
//            ->action('Show order', URL::route('admin.order.edit', ['order' => $this->order['id']]))
//            ->salutation($date);
//    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function toArray(): array
    {
        return [
            'title' => 'Order was be confirmed',
            'order_id' => $this->order['id'],
        ];
    }
}
