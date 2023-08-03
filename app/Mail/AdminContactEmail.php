<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientName;
    public $clientEmail;
    public $clientMessage;

    /**
     * Create a new message instance.
     *
     * @param  string  $clientName
     * @param  string  $clientEmail
     * @param  string  $clientMessage
     * @return void
     */
    public function __construct($clientName, $clientEmail, $clientMessage)
    {
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
        $this->clientMessage = $clientMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin_contact_email')
            ->subject('Nouvelle demande de contact')
            ->with([
                'clientName' => $this->clientName,
                'clientEmail' => $this->clientEmail,
                'clientMessage' => $this->clientMessage,
            ]);
    }
}
