<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DosenCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dosen;
    public $password;

    public function __construct($dosen, $password)
    {
        $this->dosen = $dosen;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Akun Dosen Baru Anda')
            ->view('emails.dosen_created')
            ->with([
                'nama' => $this->dosen->nama,
                'email' => $this->dosen->email,
                'password' => $this->password,
            ]);
    }
}