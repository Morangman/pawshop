<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $data;

    /**
     * @param array $data
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@rapid-recycle.com', 'rapid-recycle.com')
            ->subject('New message')
            ->view('mail.message');
    }
}
