<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountVerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $validationAccountData;

    public function __construct($validationAccountData)
    {
        
        $this->validationAccountData = $validationAccountData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->markdown('emails.auth.AccountVerificationMail')
            ->with([
                'OtpToken'   => $this->validationAccountData['OtpToken'],
                'email'      => $this->validationAccountData['email'],
                'name'       => $this->validationAccountData['name'],
                'expiredAt'  => $this->validationAccountData['expiredAt']
            ]);
    }
}
