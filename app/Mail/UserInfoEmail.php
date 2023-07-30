<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInfoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientName;
    public $clientEmail;
    public $clientPassword;

    /**
     * Create a new message instance.
     *
     * @param  string  $clientName
     * @param  string  $clientEmail
     * @param  string  $clientPassword
     * @return void
     */
    public function __construct($clientName, $clientEmail, $clientPassword)
    {
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
        $this->clientPassword = $clientPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user_info_email')
            ->subject('Vos identifiants de connexion')
            ->with([
                'clientName' => $this->clientName,
                'clientEmail' => $this->clientEmail,
                'clientPassword' => $this->clientPassword,
            ]);
    }
}
