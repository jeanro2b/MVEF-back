<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FileEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientName;

    /**
     * Create a new message instance.
     *
     * @param  string  $clientName
     * @return void
     */
    public function __construct($clientName)
    {
        $this->clientName = $clientName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.file_email')
                    ->subject('Nouveau document disponible dans votre espace client')
                    ->with([
                        'clientName' => $this->clientName,
                    ]);
    }
}
