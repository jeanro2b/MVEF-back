<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bon de séjour</title>
    <style>
        /* Ajoutez ici le style CSS de votre e-mail */
        body {
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            height: 150px;
        }

        .infos {
            position: absolute;
            text-align: right;
            top: 0;
            right: 0;
        }

        .first-section {
            font-weight: bold;
            text-align: center;
            margin: 90px 0 10px 0;
            width: 100%;
        }

        .sejour {
            font-weight: bold;
            text-align: center;
            margin: 5px 0;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
        }

        .highlight {
            background-color: yellow;
        }

    </style>
</head>

<body>
    <div class="header">
        <img class="logo" src="data:image/png;base64,{{ $logoData }}" alt="img" width="250px">
        <div class="infos">
            <p><strong>Mes Vacances en Famille</strong></p>
            <p>Hameau de Lutina, 20237,</p>
            <p>Poggio-Marinaccio</p>
            <p>Service commercial</p>
            <p>145 route Millery - 69700 MONTAGNY</p>
            <a href="www.mesvacancesenfamille.com">www.mesvacancesenfamille.com</a>
        </div>
    </div>
    <div class="first-section">
        <h2>Nom de la destination - {{ $destinationName }}</h2>
        <h2>Client - {{ $clientName }}</h2>
        <h2>Libellé de l'hébergement - {{ $hebergementTitle }}</h2>
    </div>
    <div class="sejour">
        <table> 
            <thead>
                <tr>
                    @foreach ($modifiedBaseArray as $property => $value)
                    <th>{{ $property }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($modifyArray as $index => $modifyObject)
                <tr>
                    @foreach ($modifyObject as $property => $value)
                    <td class="{{ $modifiedBaseArray[$loop->index][$property] !== $value ? 'highlight' : '' }}">{{ $value }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div> 
</body>

</html>

