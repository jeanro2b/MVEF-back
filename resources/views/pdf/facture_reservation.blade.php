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
            <p>Facture # {{ $reservationId }}</p>
            <p>Date de génération: {{ $date }}</p>
            <p>Montant: {{ number_format($reservationAmount, 2, ',', '') }}€</p>
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
            <table>
                <thead>
                    <tr>
                        <th>DESCRIPTION</th>
                        <th>QUANTITÉ</th>
                        <th>PRIX UNITAIRE</th>
                        <th>TVA %</th>
                        <th>MONTANT (EUR)</th>
                        <th>MONTANT TTC</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $reservationNumberOfNights }} Nuit(s) {{ $reservationHebergementTitle }}</td>
                        <td>1</td>
                        <td>{{ number_format($reservationAmountExclOptions, 2, ',', '') }}€</td>
                        <td>{{ $reservationTVA }}</td>
                        <td>{{ number_format($reservationAmountExclOptions, 2, ',', '') }}€</td>
                        <td>{{ number_format($reservationAmountExclOptions + ($reservationAmountExclOptions *
                            $reservationTVA /
                            100), 2, ',', '')}}€
                        </td>
                    </tr>
                    @foreach ($reservationOptionsData as $option)
                    <tr>
                        <td>{{ $option['label'] }}</td>
                        <td>{{ $option['count'] }}</td>
                        <td>{{ number_format($option['amount'], 2, ',', '') }}€</td>
                        <td>{{ $reservationTVAOptions }}</td>
                        <td>{{ number_format($option['amount'] * $option['count'], 2, ',', '') }}€</td>
                        <td>{{ number_format($option['amount'] * $option['count'] + ($option['amount'] *
                            $option['count'] * $reservationTVAOptions / 100), 2, ',', '') }}€</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total">
                @php
                $totalExclVAT = $reservationAmountExclOptions; // Montant initial pour l'hébergement hors options
                $totalVAT = $totalExclVAT * $reservationTVA / 100; // TVA pour l'hébergement

                // Ajoutez les montants des options HT et calculez la TVA pour chaque option
                foreach ($reservationOptionsData as $option) {
                $totalExclVAT += $option['amount'] * $option['count']; // Ajouter le montant HT de l'option au total HT
                $totalVAT += $option['amount'] * $option['count'] * $reservationTVAOptions / 100;
                }

                $totalInclVAT = $totalExclVAT + $totalVAT; // Total TTC
                @endphp

                <p>Total excl. TVA: {{ number_format($totalExclVAT, 2, ',', '') }}€</p>
                <p>TVA @ {{ $reservationTVA }}%: {{ number_format($totalVAT, 2, ',', '') }}€</p>
                <p>Total incl. TVA: {{ number_format($totalInclVAT, 2, ',', '') }}€</p>
            </div>
        </div>
        @if (!empty($reservationIntent))
        <div class="footer">
            <p>Paiement effectué par stripe</p>
        </div>
        @endif
    </div>

</body>

</html>