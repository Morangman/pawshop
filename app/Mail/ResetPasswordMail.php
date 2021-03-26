<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $token;

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
    public function __construct(string $token, string $email)
    {
        $this->token = $token;
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
        ->subject('Reset My Password')
        ->view('mail.reset_password');
    }
}
