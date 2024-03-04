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
                'hebergements.code as codeDestination',
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
                'reservations.user_id',
                'reservations.reduction',
            )
            ->whereBetween('reservations.end', [$this->start, $this->end])
            ->get();

        $formattedReservations = [];

        foreach ($reservations as $reservation) {
            $reservation->codeDestination = substr($reservation->codeHebergement, 0, 4);
            $reservation->amount = number_format($reservation->amount / 100, 2, ',', '') . ' €';
            $reservation->amount_options = number_format($reservation->amount_options / 100, 2, ',', '') . ' €';
            $reservation->amount_nights = number_format($reservation->amount_nights / 100, 2, ',', '') . ' €';

            $tvaRate = $reservation->tva / 100;
            $tvaOptionsRate = $reservation->tva_options / 100;
            $reservationAmountHebergementHT = (floatval($reservation->amount_nights) / (1 + floatval($tvaRate)));
            $reservationAmountOptionsHT = (floatval($reservation->amount_options) / (1 + floatval($tvaOptionsRate)));

            $reservation->amountHTHebergement = number_format($reservationAmountHebergementHT, 2, ',', '') . ' €';
            $reservation->tvaHebergement = number_format((floatval($reservation->amount_nights)) - $reservationAmountHebergementHT, 2, ',', '') . ' €';
            $reservation->amountHTOptions = number_format($reservationAmountOptionsHT, 2, ',', '') . ' €';
            $reservation->amountTVAOptions = number_format((floatval($reservation->amount_options)) - $reservationAmountOptionsHT, 2, ',', '') . ' €';

            $reservation->com = number_format(floatval($reservation->amount_nights) * (floatval($reservation->reduction) /
                100), 2, ',', '') . ' €';
            $reservation->montantVerse = number_format((floatval($reservation->amount_nights)) -
                (floatval($reservation->amount_nights)
                    * (floatval($reservation->reduction) /
                        100)), 2, ',', '') . ' €';

            $formattedReservation = (object) [
                'id' => $reservation->id,
                'created_at' => $reservation->created_at,
                'codeDestination' => substr($reservation->codeHebergement, 0, 4),
                'nomDestination' => $reservation->nomDestination,
                'codeHebergement' => $reservation->codeHebergement,
                'start' => $reservation->start,
                'end' => $reservation->end,
                'status' => $reservation->status,
                'amount' => $reservation->amount,
                'name' => $reservation->name,
                'first_name' => $reservation->first_name,
                'phone' => $reservation->phone,
                'mail' => $reservation->mail,
                'voyageurs' => $reservation->voyageurs,
                'amount_nights' => $reservation->amount_nights,
                'tva' => $reservation->tva . ' %',
                'amountHTHebergement' => $reservation->amountHTHebergement,
                'tvaHebergement' => $reservation->tvaHebergement,
                'amount_options' => $reservation->amount_options,
                'tva_options' => $reservation->tva_options . ' %',
                'amountHTOptions' => $reservation->amountHTOptions,
                'amountTVAOptions' => $reservation->amountTVAOptions,
                'hebergement_id' => $reservation->hebergement_id,
                'user_id' => $reservation->user_id,
                'commission' => $reservation->com,
                'montantVerse' => $reservation->montantVerse,
            ];

            array_push($formattedReservations, $formattedReservation);
        }

        return collect($formattedReservations);
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
            'Montant TVA Hébergement',
            'Montant Options',
            'TVA Options',
            'Montant HT Options',
            'Montant TVA Options',
            'ID Hébergement',
            'ID Utilisateur',
            'Commission MVEF',
            "Montant versé à l'hébergeur",
        ];
    }
}