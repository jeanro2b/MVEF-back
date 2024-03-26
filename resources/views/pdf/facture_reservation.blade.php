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
            font-size: 13px;
            text-align: right;
        }

        .total p {
            font-size: 13px;
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
            <p>Facture # {{ $reservationId }}</p>
            <p>Date d'émission : {{ $date }}</p>
            <p>Montant: {{ number_format($reservationAmount / 100, 2, ',', '') }}€</p>
            <p>Client ID: {{ $userId }}</p>
        </div>
        <div class="invoice-body">
            <section>
                <div class="bold">CLIENT</div>
                <p>{{ $reservationClientName}}</p>
                <p>{{ $reservationClientFirstName }}</p>
                <p>{{ $reservationClientMail }}</p>
                <p>{{ $reservationClientPhone }}</p>
            </section>
            <section>
                <div class="bold">Mes Vacances En Famille</div>
                <p>SAS 8000€ RCS BASTIA - APE 5520Z - Siret 50513668900047</p>
                <p>Numéro individuel d'identification à la TVA : F R 3 1 5 0 5 1 3 6 6 8 9 0 0 0 4 7</p>
                <p>Siège social : Hameau de Lutina, 20237, Poggio-Marinaccio</p>
                <p>Secrétariat : 145 route de Millery - 69700 MONTAGNY</p>
                <p> <a href="www.mesvacancesenfamille.com">www.mesvacancesenfamille.com</a> / <a
                        href="info@mesvacancesenfamille.com">info@mesvacancesenfamille.com</a></p>
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
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $reservationNumberOfNights }} Nuit(s) {{ $reservationHebergementTitle }}</td>
                        <td>1</td>
                        <td>{{$dayStart}}/{{$monthStart}}/{{$yearStart}}</td>
                        <td>{{$dayEnd}}/{{$monthEnd}}/{{$yearEnd}}</td>
                        <td>{{ number_format($reservationAmountExclOptionsHT / 100, 2, ',', '') }} €</td>
                        <td>{{ $reservationTVA }}</td>
                        <td>{{ number_format($reservationAmountExclOptionsHT / 100, 2, ',', '') }} €</td>
                        <td>{{ number_format(($reservationAmountExclOptions / 100), 2, ',', '')}} €
                        </td>
                    </tr>
                    @foreach ($reservationOptionsData as $option)
                    <tr>
                        <td>{{ $option['label'] }}</td>
                        <td>{{ $option['count'] }}</td>
                        <td>{{$dayStart}}/{{$monthStart}}/{{$yearStart}}</td>
                        <td>{{$dayEnd}}/{{$monthEnd}}/{{$yearEnd}}</td>
                        <td>{{ number_format($option['amount'] * (1 - ($reservationTVAOptions / 100)), 2, ',', '') }} €
                        </td>
                        <td>{{ $reservationTVAOptions }}</td>
                        <td>{{ number_format($option['amount'] * (1 - ($reservationTVAOptions / 100)) *
        $option['count'], 2, ',', '') }} €</td>
                        <td>{{ number_format($option['amount'] * $option['count'], 2, ',', '') }} €</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total">
                @php
$totalExclVAT = $reservationAmountExclOptionsHT / 100; // Montant initial pour l'hébergement hors
$totalInclVAT = $reservationAmountExclOptions / 100;

// Ajoutez les montants des options HT et calculez la TVA pour chaque option
foreach ($reservationOptionsData as $option) {
    $totalInclVAT += $option['amount'] * $option['count']; // Ajouter le montant HT de l'option au total HT
    $totalExclVAT += $option['amount'] * (1 - ($reservationTVAOptions / 100)) *
        $option['count']; // Ajouter le montant HT de l'option au total HT
}

$totalInclVAT = number_format($totalInclVAT, 2, ',', ''); // Total TTC
$totalExclVAT = number_format($totalExclVAT, 2, ',', ''); // Total TTC

$comission = $reservationAmountExclOptions * ($reduction / 100);
$montantVerse = $reservationAmountExclOptions - $comission;
                @endphp

                <!-- <p>Comission MVEF: {{ number_format($comission / 100, 2, ',', '') }} €</p>
                <p>Montant versé à l'hébergeur: {{ number_format($montantVerse / 100, 2, ',', '') }} €</p> -->
                <p>Total excl. TVA: {{ $totalExclVAT }} €</p>
                <p>Total incl. TVA: {{ $totalInclVAT }} €</p>
            </div>
        </div>
        @if (!empty($reservationIntent))
        <div class="footer">
            <p>Paiement effectué par stripe</p>
            <p>En matière de TVA l 'agence Mes Vacances en Famille bénéfice du régime spécifique des agences de voyage : article 266 du CGI et BOI-TVA-DECLA-30-20-20-10- TVA sur Marge sur la partie transport et hébergement.</p>
        </div>
        @else
        <div class="footer">
            <p>En matière de TVA l 'agence Mes Vacances en Famille bénéfice du régime spécifique des agences de voyage : article 266 du CGI et BOI-TVA-DECLA-30-20-20-10- TVA sur Marge sur la partie transport et hébergement.</p>
        </div>
        @endif
    </div>

</body>

</html>