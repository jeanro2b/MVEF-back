<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReservationExport implements FromCollection, WithHeadings
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        $reservations = DB::table('reservations')
            ->select(
                'id',
                'created_at',
                'destination_id',
                'start',
                'end',
                'status',
                'amount',
                'intent',
                'name',
                'first_name',
                'phone',
                'mail',
                'voyageurs',
                'hebergement_id',
                'user_id',
                'amount_options',
                'amount_nights',
            )
            ->whereBetween('end', [$this->start, $this->end])
            ->get();

        Log::debug(''. $this->start .''. $this->end);
        Log::debug($reservations);

        foreach ($reservations as $reservation) {
            $destination = DB::table('destinations')
                ->select(
                    'id',
                    'name',
                    'tva',
                    'tva_options'
                )
                ->where('id', $reservation->destination_id)
                ->get();

            foreach ($destination as $dest) {
                $reservation->destination_name = $dest->name;
                $reservation->tva = $dest->tva;
                $reservation->tva_options = $dest->tva_options;
            }

            $hebergement = DB::table('hebergements')
                ->select(
                    'id',
                    'name',
                    'code'
                )
                ->where('id', $reservation->hebergement_id)
                ->get();

            foreach ($hebergement as $heb) {
                $reservation->code = $heb->code;
            }

            $tvaRate = $reservation->tva / 100;
            $reservationAmountHebergement = $reservation->amount_nights;
            $reservation->amountHT = $reservationAmountHebergement / (1 + $tvaRate);
            $reservation->amountTVA = $reservationAmountHebergement - $reservation->amountHT;

            $tvaOptionsRate = $reservation->tva_options / 100; // Ajoutez cette ligne si votre taux est en pourcentage
            $reservation->amountHTOptions = $reservation->amount_options / (1 + $tvaOptionsRate);
            $reservation->amountTVAOptions = $reservation->amount_options - $reservation->amountHTOptions;
        }

        Log::debug($reservations);
        return collect($reservations);
    }

    public function headings(): array
    {
        return [
            'ID', 'Créé le', 'ID Destination', 'Début', 'Fin', 'Statut', 'Montant', 'Intent',
            'Nom', 'Prénom', 'Téléphone', 'Email', 'Voyageurs', 'ID Hébergement', 'ID Utilisateur',
            'Montant Options', 'Nuits', 'Nom Destination', 'TVA', 'TVA Options', 'Code Hébergement',
            'Montant HT Hébergement', 'TVA Hébergement', 'Montant HT Options', 'TVA Options'
        ];
    }
}