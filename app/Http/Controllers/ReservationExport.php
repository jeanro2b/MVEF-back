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
        ->leftJoin('destinations', 'reservations.destination_id', '=', 'destinations.id')
        ->leftJoin('hebergements', 'reservations.hebergement_id', '=', 'hebergements.id')
        ->select(
            'reservations.id',
            'reservations.created_at',
            'destinations.code as codeDestination',
            'destinations.name as nomDestination',
            'hebergements.code as codeHebergement',
            'reservations.start',
            'reservations.end',
            'reservations.status',
            'reservations.amount',
            'reservations.name',
            'reservations.first_name',
            'reservations.phone',
            'reservations.mail',
            'reservations.voyageurs',
            // Ajoutez d'autres champs ici si nécessaire
            'reservations.amount_nights',
            'destinations.tva',
            'reservations.amount_options',
            'destinations.tva_options',
            'reservations.hebergement_id',
            'reservations.user_id'
        )
        ->whereBetween('reservations.end', [$this->start, $this->end])
        ->get();

        foreach ($reservations as $reservation) {
            $reservation->amount = number_format($reservation->amount / 100, 2, ',', '') . ' €';
            $reservation->amount_options = number_format($reservation->amount_options / 100, 2, ',', '') . ' €';
            $reservation->amount_nights = number_format($reservation->amount_nights / 100, 2, ',', '') . ' €';

            $tvaRate = $reservation->tva / 100;
            $tvaOptionsRate = $reservation->tva_options / 100;
            $reservationAmountHebergementHT = ($reservation->amount_nights / (1 + $tvaRate)) / 100;
            $reservationAmountOptionsHT = ($reservation->amount_options / (1 + $tvaOptionsRate)) / 100;
    
            $reservation->amountHTHebergement = number_format($reservationAmountHebergementHT, 2, ',', '') . ' €';
            $reservation->tvaHebergement = number_format(($reservation->amount_nights / 100) - $reservationAmountHebergementHT, 2, ',', '') . ' €';
            $reservation->amountHTOptions = number_format($reservationAmountOptionsHT, 2, ',', '') . ' €';
            $reservation->amountTVAOptions = number_format(($reservation->amount_options / 100) - $reservationAmountOptionsHT, 2, ',', '') . ' €';
            
        }

        return collect($reservations);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Créé le',
            'Code Destination',
            'Nom Destination',
            'Code Hébergement',
            'Début',
            'Fin',
            'Statut',
            'Montant',
            'Nom',
            'Prénom',
            'Téléphone',
            'Email',
            'Voyageurs',
            'Montant TTC Hébergement',
            'TVA',
            'Montant HT Hébergement',
            'TVA Hébergement',
            'Montant Options',
            'TVA Options',
            'Montant HT Options',
            'Montant TVA options',
            'ID Hébergement',
            'ID Utilisateur',
        ];
    }
}