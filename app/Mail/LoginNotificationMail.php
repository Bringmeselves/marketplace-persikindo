<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $time;

    public function __construct($user)
    {
        $this->user = $user;
        $this->time = now()->format('d-m-Y H:i');
    }

    public function build()
    {
        return $this->subject('Notifikasi Login Google - Marketplace PERSIKINDO')
            ->view('emails.login_notification');
    }
}
