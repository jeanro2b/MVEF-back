<!DOCTYPE html>
<html>

<head>
    <style>
        /* Ajoutez ici le style CSS de votre e-mail */
        body {
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        .banner {
            width: 100%;
        }

        .content-wrapper {
            padding: 20px;
            color: black;
            line-height: 2.4em;
        }

        .highlight {
            background-color: yellow;
        }

        .auto {
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="banner">
        <img src="https://mvef.s3.eu-west-3.amazonaws.com/LinkedIn.png" alt="Bannière">
    </div>

    <div class="content-wrapper">
        <p>Bonjour,<br />
            Un planning a été modifié depuis l’intranet client !<br />
            Client : {{ $clientName }}<br />
            Libellé planning : {{ $libellePlanning}}<br />
            Destination : {{ $destinationName }}
            <br />Veuillez trouver ci-dessous les modifications apportées :
        </p>

        <table>
            <thead>
                <tr>
                    @foreach ($baseArray[0] as $property => $value)
                    <th>{{ $property }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($modifyArray as $index => $modifyObject)
                <tr>
                    @foreach ($modifyObject as $property => $value)
                    <td class="{{ $baseArray[$index][$property] !== $value ? 'highlight' : '' }}">{{ $value }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="auto">cet email est envoyé automatiquement, veuilliez ne pas y répondre s'il vous plaît</p>
    </div>

</body>

</html>

<!-- a modifier ! -->