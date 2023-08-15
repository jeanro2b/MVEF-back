<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ModifyPeriodEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     */
    public function __construct(array $mailData, $clientName, $libellePlanning, $destinationName, $hebergementCode, $hebergementName, $hebergementTitle, $formatDate)
    {
        $this->mailData = $mailData;
        $this->clientName = $clientName;
        $this->libellePlanning = $libellePlanning;
        $this->destinationName = $destinationName;
        $this->hebergementCode = $hebergementCode;
        $this->hebergementName = $hebergementName;
        $this->hebergementTitle = $hebergementTitle;
        $this->formatDate = $formatDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Modification d'un planning")
        ->view('emails.modify_period_email')
        ->with([
            'clientName' => $this->clientName,
            'libellePlanning' => $this->libellePlanning,
            'destinationName' => $this->destinationName,
            'hebergementCode' => $this->hebergementCode,
            'hebergementName' => $this->hebergementName,
            'hebergementTitle' => $this->hebergementTitle,
            'formatDate' => $this->formatDate,
        ])
        ->attachData($this->mailData['attachmentData'], $this->mailData['attachmentName']);
    }
}
