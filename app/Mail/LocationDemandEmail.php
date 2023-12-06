<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LocationDemandEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $reservationId;
    public $hebergementName;
    public $yearStart;
    public $monthStart;
    public $dayStart;
    public $yearEnd;
    public $monthEnd;
    public $dayEnd;
    public $destination_id;

    /**
     * Create a new message instance.
     *
     * @param  string  $token
     * @param  string  $reservationId
     * @param  string  $hebergementName
     * @param  string  $yearStart
     * @param  string  $monthStart
     * @param  string  $hebergementdayStartName
     * @param  string  $yearEnd
     * @param  string  $monthEnd
     * @param  string  $dayEnd
     * @param  string  $destination_id
     * @return void
     */
    public function __construct($token, $reservationId, $hebergementName, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd, $destination_id)
    {
        $this->token = $token;
        $this->reservationId = $reservationId;
        $this->hebergementName = $hebergementName;
        $this->yearStart = $yearStart;
        $this->monthStart = $monthStart;
        $this->dayStart = $dayStart;
        $this->yearEnd = $yearEnd;
        $this->monthEnd = $monthEnd;
        $this->dayEnd = $dayEnd;
        $this->destination_id = $destination_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.location_demand_email')
            ->subject('Demande de réservation Mes Vacances En Famille')
            ->with([
                'token' => $this->token,
                'reservationId' => $this->reservationId,
                'hebergementName' => $this->hebergementName,
                'yearStart' => $this->yearStart,
                'monthStart' => $this->monthStart,
                'dayStart' => $this->dayStart,
                'yearEnd' => $this->yearEnd,
                'monthEnd' => $this->monthEnd,
                'dayEnd' => $this->dayEnd,
                'destination_id' => $this->destination_id,
            ]);
    }
}
