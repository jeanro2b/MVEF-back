<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ModifyPeriodEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientName;
    public $libellePlanning;
    public $destinationName;
    public $baseArray;
    public $modifyArray;

    /**
     * Create a new message instance.
     *
     * @param  string  $clientName
     * @param  string  $libellePlanning
     * @param  string  $destinationName
     *  @param  array  $baseArray
     * @param  array  $modifyArray
     * @return void
     */
    public function __construct($clientName, $libellePlanning, $destinationName, $baseArray, $modifyArray)
    {
        $this->clientName = $clientName;
        $this->libellePlanning = $libellePlanning;
        $this->destinationName = $destinationName;
        $this->baseArray = $baseArray;
        $this->modifyArray = $modifyArray;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.modify_period_email')
            ->subject("Planning modifié depuis l'intranet client")
            ->with([
                'clientName' => $this->clientName,
                'libellePlanning' => $this->libellePlanning,
                'destinationName' => $this->destinationName,
                'baseArray' => $this->baseArray,
                'modifyArray' => $this->modifyArray,
            ]);
    }
}
