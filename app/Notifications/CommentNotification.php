<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class CommentNotification
 *
 * @package App\Notifications
 */
class CommentNotification extends Notification
{
    use Queueable;

    /**
     * @var array
     */
    protected $commentId;

    /**
     * OrderNotification constructor.
     *
     * @param int $commentId
     */
    public function __construct(int $commentId)
    {
        $this->commentId = $commentId;
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
        return [
            'title' => 'Новий відгук',
            'comment_id' => $this->commentId,
        ];
    }
}
