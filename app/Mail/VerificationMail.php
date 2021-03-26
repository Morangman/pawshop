<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $email;

    /**
     * Create a new message instance.
     * 
     * @param string $code
     * @param string $email
     *
     * @return void
     */
    public function __construct(string $code, string $email)
    {
        $this->code = $code;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@rapid-recycle.com', 'rapid-recycle.com')
            ->subject('Verify E-mail Address')
            ->view('mail.account_verification');
    }
}
