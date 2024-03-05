<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LocationAcceptedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservationId;
    public $hebergementName;
    public $yearStart;
    public $monthStart;
    public $dayStart;
    public $yearEnd;
    public $monthEnd;
    public $dayEnd;
    public $name;
    public $first_name;
    public $phone;
    public $amount;

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
     * @param  string  $name
     * @param  string  $first_name
     * @param  string  $phone
     * @param  string  $amount
     * @return void
     */
    public function __construct($hebergementName, $reservationId, $amount, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd, $name, $first_name, $phone)
    {
        $this->reservationId = $reservationId;
        $this->hebergementName = $hebergementName;
        $this->yearStart = $yearStart;
        $this->monthStart = $monthStart;
        $this->dayStart = $dayStart;
        $this->yearEnd = $yearEnd;
        $this->monthEnd = $monthEnd;
        $this->dayEnd = $dayEnd;
        $this->name = $name;
        $this->first_name = $first_name;
        $this->phone = $phone;
        $this->amount = $amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.location_accepted_email')
            ->subject('Réservation acceptée Mes Vacances En Famille')
            ->with([
                'reservationId' => $this->reservationId,
                'hebergementName' => $this->hebergementName,
                'yearStart' => $this->yearStart,
                'monthStart' => $this->monthStart,
                'dayStart' => $this->dayStart,
                'yearEnd' => $this->yearEnd,
                'monthEnd' => $this->monthEnd,
                'dayEnd' => $this->dayEnd,
                'name' => $this->name,
                'first_name' => $this->first_name,
                'phone' => $this->phone,
                'amount' => $this->amount,
            ]);
    }
}
