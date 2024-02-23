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

            $reservation->amount = number_format($reservation->amount / 100, 2, ',', '') . ' €';
            $reservationAmountOptions = $reservation->amount_options;
            $reservation->amount_options = number_format($reservation->amount_options, 2, ',', '') . ' €';
            $tvaRate = $reservation->tva / 100;
            $reservationAmountHebergement = $reservation->amount_nights;
            $reservationAmountHebergementHT = $reservationAmountHebergement / (1 + $tvaRate);
            $reservation->amount_nights = number_format($reservation->amount_nights / 100, 2, ',', '') . ' €';
            $reservation->amountHT = number_format($reservationAmountHebergement / (1 + $tvaRate), 2, ',', '') . ' €';
            $reservation->amountTVA = number_format($reservationAmountHebergement - $reservationAmountHebergementHT, 2, ',', '') . ' €';

            $tvaOptionsRate = $reservation->tva_options / 100; // Ajoutez cette ligne si votre taux est en pourcentage
            $reservationAmountHTOptions = $reservationAmountOptions / (1 + $tvaOptionsRate);
            $reservation->amountHTOptions = number_format($reservationAmountOptions / (1 + $tvaOptionsRate), 2, ',', '') . ' €';
            $reservation->amountTVAOptions = number_format($reservationAmountOptions - $reservationAmountHTOptions, 2, ',', '') . ' €';
        }

        return collect($reservations);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Créé le',
            'ID Destination',
            'Début',
            'Fin',
            'Statut',
            'Montant',
            'Intent',
            'Nom',
            'Prénom',
            'Téléphone',
            'Email',
            'Voyageurs',
            'ID Hébergement',
            'ID Utilisateur',
            'Montant Options',
            'Montant TTC Hébergement',
            'Nom Destination',
            'TVA',
            'TVA Options',
            'Code Hébergement',
            'Montant HT Hébergement',
            'TVA Hébergement',
            'Montant HT Options',
            'TVA Options'
        ];
    }
}