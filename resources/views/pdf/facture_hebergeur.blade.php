<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Facture</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            background: #f8f8f8;
            padding: 20px;
        }

        .header-2 {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
            height: 150px;
            text-align: center;
        }

        .invoice-header {
            text-align: right;
            margin-top: 20px;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .invoice-header p {
            margin: 0;
            color: #666;
        }

        .invoice-body {
            padding: 20px;
        }

        .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .total {
            margin-top: 20px;
            font-size: 18px;
            text-align: right;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background: #f8f8f8;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-2">
            <img class="logo" src="data:image/png;base64,{{ $bslogoData }}" alt="img" width="215px">
            <img class="logo-2" src="data:image/png;base64,{{ $bslogotxtData }}" alt="img" width="250px">
        </div>
        <div class="invoice-header">
            <h1>FACTURE</h1>
            <p>Facture # {{ $destinationId }}</p>
            <p>Date de génération: {{ $date }}</p>
            <p>Client ID: {{ $destinationId }}</p>
        </div>
        <div class="invoice-body">
            <section>
                <div class="bold">HEBERGEUR</div>
                <p>{{ $destinationName}}</p>
                <p>{{ $destinationPhone }}</p>
                <p>{!! $destinationAddress !!}</p>
                <p>{{ $destinationCity }}</p>
                <p>{{ $destinationMail }}</p>
            </section>
            <section>
                <div class="bold">MVEF</div>
                <p>Mes Vacances en Famille</p>
                <p>SAS 8000€ RCS BASTIA - APE 5520Z - Siret 50513668900047</p>
                <p>Siège social : Hameau de Lutina, 20237, Poggio-Marinaccio</p>
                <p>Secrétariat : 145 route de Millery - 69700 MONTAGNY</p>
                <p> <a href="www.mesvacancesenfamille.com">www.mesvacancesenfamille.com</a> / <a
                        href="mailto:info@mesvacancesenfamille.com">info@mesvacancesenfamille.com</a></p>
            </section>
            <table>
                <thead>
                    <tr>
                        <th>DESCRIPTION</th>
                        <th>QTTÉ</th>
                        <th>DÉBUT</th>
                        <th>FIN</th>
                        <th>PRIX UNITAIRE</th>
                        <th>TVA %</th>
                        <th>MONTANT</th>
                        <th>MONTANT TTC</th>
                        <th>COMISSION</th>
                        <th>MONTANT REVERSÉ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->reservationNumberOfNights }} Nuit(s) {{
                            $reservation->reservationHebergementTitle }}</td>
                        <td>1</td>
                        <td>{{$reservation->dayStart}}/{{$reservation->monthStart}}/{{$reservation->yearStart}}</td>
                        <td>{{$reservation->dayEnd}}/{{$reservation->monthEnd}}/{{$reservation->yearEnd}}</td>
                        <td>{{ number_format($reservation->reservationAmountExclOptionsHT / 100, 2, ',', '') }} €</td>
                        <td>{{ $destinationTVA }}</td>
                        <td>{{ number_format($reservation->reservationAmountExclOptionsHT / 100, 2, ',', '') }} €</td>
                        <td>{{ number_format(($reservation->reservationAmountExclOptions / 100 ), 2, ',', '')}} €
                        </td>
                        <td>{{ number_format($reservation->reservationAmountExclOptions * ($reservation->reduction /
                            100) / 100, 2, ',', '') }} €</td>
                        <td>{{ number_format($reservation->reservationAmountExclOptions -
                            ($reservation->reservationAmountExclOptions
                            * ($reservation->reduction /
                            100)) / 100, 2, ',', '') }} €</td>
                    </tr>
                    @foreach ($reservation->reservationOptionsData as $option)
                    <tr>
                        <td>{{ $option['label'] }}</td>
                        <td>{{ $option['count'] }}</td>
                        <td>{{ number_format($option['amount'] * (1 - ($destinationTVAOptions / 100)), 2, ',', '') }} €
                        </td>
                        <td>{{ $destinationTVAOptions }}</td>
                        <td>{{ number_format($option['amount'] * (1 - ($destinationTVAOptions / 100)) *
                            $option['count'], 2, ',', '') }} €</td>
                        <td>{{ number_format($option['amount'] * $option['count'], 2, ',', '') }} €</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                    @endforeach


                </tbody>
            </table>
            <div class="total">
                @php
                $totalExclVAT = 0;
                $totalInclVAT = 0;
                $totalComission = 0;
                $totalMontantVerse = 0;

                foreach($reservations as $reservation) {
                $totalExclVAT += $reservation->reservationAmountExclOptionsHT / 100;
                $totalInclVAT += $reservation->reservationAmountExclOptions / 100;

                $totalComission += $reservation->reservationAmountExclOptions * ($reservation->reduction / 100);
                $totalMontantVerse += $reservation->reservationAmountExclOptions -
                ($reservation->reservationAmountExclOptions * ($reservation->reduction / 100));

                foreach ($reservation->reservationOptionsData as $option) {
                $totalInclVAT += $option['amount'] * $option['count']; // Ajouter le montant HT de l'option au total HT
                $totalExclVAT += $option['amount'] * (1 - ($destinationTVAOptions / 100)) *
                $option['count']; // Ajouter le montant HT de l'option au total HT
                }
                }

                // Ajoutez les montants des options HT et calculez la TVA pour chaque option


                $totalInclVAT = number_format($totalInclVAT, 2, ',', ''); // Total TTC
                $totalExclVAT = number_format($totalExclVAT, 2, ',', ''); // Total TTC
                $totalComission = number_format($totalComission / 100, 2, ',', '');
                $totalMontantVerse = number_format($totalMontantVerse / 100, 2, ',', '');
                @endphp

                <p>Total Comission MVEF: {{ $totalComission }} €</p>
                <p>Montant Total versé à l'hébergeur: {{ $totalMontantVerse }} €</p>
                <p>Total excl. TVA: {{ $totalExclVAT }} €</p>
                <p>Total incl. TVA: {{ $totalInclVAT }} €</p>
            </div>
        </div>
        @if (!empty($reservationIntent))
        <div class="footer">
            <p>Paiement : le paiement sera effectué par Mes Vacances en Famille par virement avant le 30/31 du mois</p>
        </div>
        @endif
    </div>

</body>

</html>