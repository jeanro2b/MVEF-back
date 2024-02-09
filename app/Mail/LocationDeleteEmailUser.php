<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LocationDeleteEmailUser extends Mailable
{
    use Queueable, SerializesModels;

    public $reservationId;


    public function __construct($reservationId)
    {
        $this->reservationId = $reservationId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.location_delete_email_user')
            ->subject('Annulation de réservation Mes Vacances En Famille')
            ->with([
                'reservationId' => $this->reservationId,
            ]);
    }
}
