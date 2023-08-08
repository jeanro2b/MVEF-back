<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminContactCEEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientName;
    public $clientEmail;
    public $clientMessage;
    public $clientPhone;
    public $clientSociety;
    public $clientFirstName;

    /**
     * Create a new message instance.
     *
     * @param  string  $clientName
     * @param  string  $clientEmail
     * @param  string  $clientMessage
     * @return void
     */
    public function __construct($clientName, $clientEmail, $clientMessage, $clientPhone, $clientSociety, $clientFirstName)
    {
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
        $this->clientMessage = $clientMessage;
        $this->clientPhone = $clientPhone;
        $this->clientSociety = $clientSociety;
        $this->clientFirstName = $clientFirstName;


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin_contact_ce_email')
            ->subject('Nouvelle demande de contact CE')
            ->with([
                'clientName' => $this->clientName,
                'clientEmail' => $this->clientEmail,
                'clientMessage' => $this->clientMessage,
                'clientPhone' => $this->clientPhone,
                'clientSociety' => $this->clientSociety,
                'clientFirstName' => $this->clientFirstName,
            ]);
    }
}
