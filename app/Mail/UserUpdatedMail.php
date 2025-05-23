<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $newPassword;

    public function __construct($user, $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    public function build()
    {
        return $this->subject('Password Anda Telah Diubah')
            ->view('emails.user_updated')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => $this->newPassword,
            ]);
    }
}
