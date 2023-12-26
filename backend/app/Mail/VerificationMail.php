<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class VerificationMail extends Mailable
{
    use SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Please Verify Your Email Address')
            ->view('emails.verify')
            ->with([
                'otp' => $this->otp,
            ]);
    }
}
