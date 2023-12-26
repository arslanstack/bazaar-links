<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class ResetMail extends Mailable
{
    use SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Please Reset Your Password for Mandi Link Account')
            ->view('emails.reset')
            ->with([
                'otp' => $this->otp,
            ]);
    }
}
