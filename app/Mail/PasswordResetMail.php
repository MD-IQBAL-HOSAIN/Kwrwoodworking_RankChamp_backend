<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    // Constructor to pass the token
    public function __construct($token)
    {
        $this->token = $token;
    }

    // Build the email
    public function build()
    {
        return $this->view('backend.layout.emails.password_reset')
            ->with(['token' => $this->token])
            ->subject('Password Reset Request OTP');
    }
}
