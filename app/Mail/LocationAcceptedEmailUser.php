<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LocationAcceptedEmailUser extends Mailable
{
    use Queueable, SerializesModels;

    public $reservationId;
    public $destinationName;
    public $yearStart;
    public $monthStart;
    public $dayStart;
    public $yearEnd;
    public $monthEnd;
    public $dayEnd;
    public $destination_id;
    public $amount;
    public $output_facture;
    public $filename_facture;

    /**
     * Create a new message instance.
     *
     * @param  string  $reservationId
     * @param  string  $hebergementName
     * @param  string  $yearStart
     * @param  string  $monthStart
     * @param  string  $hebergementdayStartName
     * @param  string  $yearEnd
     * @param  string  $monthEnd
     * @param  string  $dayEnd
     * @param  string  $destination_id
     * @param  string  $amount
     * @param  string  $output_facture
     * @param  string  $filename_facture
     * @return void
     */
    public function __construct($destination_id, $destinationName, $reservationId, $amount, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd, $output_facture, $filename_facture)
    {
        $this->reservationId = $reservationId;
        $this->destinationName = $destinationName;
        $this->yearStart = $yearStart;
        $this->monthStart = $monthStart;
        $this->dayStart = $dayStart;
        $this->yearEnd = $yearEnd;
        $this->monthEnd = $monthEnd;
        $this->dayEnd = $dayEnd;
        $this->destination_id = $destination_id;
        $this->amount = $amount;
        $this->output_facture = $output_facture;
        $this->filename_facture = $filename_facture;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.location_accepted_email_user')
            ->subject('Réservation acceptée Mes Vacances En Famille')
            ->with([
                'reservationId' => $this->reservationId,
                'destinationName' => $this->destinationName,
                'yearStart' => $this->yearStart,
                'monthStart' => $this->monthStart,
                'dayStart' => $this->dayStart,
                'yearEnd' => $this->yearEnd,
                'monthEnd' => $this->monthEnd,
                'dayEnd' => $this->dayEnd,
                'destination_id' => $this->destination_id,
                '$amount' => $this->amount,
            ])
            ->attachData($this->output_facture, $this->filename_facture);
    }
}
