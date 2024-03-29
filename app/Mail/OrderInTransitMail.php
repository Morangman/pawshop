<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderInTransitMail extends Mailable
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
        return $this->from('info@rapid-recycle.com', 'rapid-recycle.com')
            ->subject('Your offer in transit')
            ->view('mail.order_in_transit');
    }
}
