<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bon de séjour</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rammetto+One&display=swap" rel="stylesheet">
    <style>
        /* Ajoutez ici le style CSS de votre e-mail */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Rammetto One', sans-serif;
            transform: rotate(90deg);
            transform-origin: left top;
            overflow-x: hidden;
            position: absolute;
            width: 100vh;
            height: 100vw;
            top: 0;
            left: 0;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: cover;
        }

        .header,
        .header-2 {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
            height: 150px;
            text-align: center;
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

        .second-section {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            height: 180px;
        }

        .destination {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-top: 15px;
        }

        .dest-name,
        .dest-date,
        .arrive,
        .depart {
            margin: 10px;
            display: flex;
            flex-direction: row;
            flex: wrap;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .dest-name img,
        .dest-date img {
            margin-left: 100px;
            margin-right: 50px;
            font-size: 2em;
            height: 80px;
            width: 80px;
        }

        .dest-name p,
        .dest-date p,
        .arrive p,
        .depart p {
            margin: 0 10px;
            position: absolute;
        }

        .hebergement {
            display: flex;
            flex-direction: column;
            width: 45%;
            position: absolute;
            right: 10px;
            top: 430px;
        }

        .logo,
        .logo-2 {
            position: absolute;
            top: 0;
        }

        .logo {
            left: 0;
        }

        .logo-2 {
            right: 0;
            transform: translateY(10px);
        }

        .two-columns-list {
            display: flex;
            flex-wrap: wrap;
            padding: 0;
        }

        .two-columns-list li {
            width: 50%; /* Une colonne pour les services */
            box-sizing: border-box; /* Inclure le padding et la bordure dans la largeur */
            margin-bottom: 10px;
        }

        .services {
            position: relative;
            margin: 185px 0 30px 15px;
        }

        .services h2 {
            margin: 10px 0 4px 0px;
        }

        .columns-container {
            display: flex;
        }
    </style>
</head>

<body>
    <img class="background" src="https://mvef.s3.eu-west-3.amazonaws.com/fond.jpg" alt="background">

    @if($nomClient === 'Vacances Authentiques')
        <div class="header-2">
            <img class="logo" src="data:image/png;base64,{{ $logoData }}" alt="img" width="250px">
            <img class="logo-2" src="data:image/png;base64,{{ $logoVacancesAuthData }}" alt="img" width="300px">
        </div>
    @else
        <div class="header">
            <img class="logo" src="data:image/png;base64,{{ $logoData }}" alt="img" width="250px">
        </div>
    @endif

    <div class="first-section">
        <h2>Carnet de voyage</h2>
        <h2>Voyageur : {{ $nomVoyageur }}</h2>
        <h2>Client : {{ $nomClient }}</h2>
        <h2>{{ $libellePlanning }}</h2>
    </div>

    <div class="second-section">
        <div class="destination">
            <div class="dest-name">
                <img class="img" src="data:image/png;base64,{{ $destData }}" alt="dest" width="30px">
                <p>{{ $nomDestination }} à {{ $villeDestination }}</p>
            </div>
            <div class="dest-date">
                <img class="img" src="data:image/png;base64,{{ $calData }}" alt="cal" width="30px">
                <p>Du {{ $dateArrive }} au {{ $dateDepart }}<br>
                    Heure d'arrivée : {{ $heureArrive }}<br>
                    Heure de départ : {{ $heureDepart }}
                </p>
            </div>
        </div>

        <div class="hebergement">
            <h3>Votre hébergement</h3>
            <p>{!! $descriptionHebergement !!}</p>
        </div>
    </div>
    <div class="services">
        <h2>Services</h2>
        <div class="columns-container">
            <!-- Première colonne -->
            <ul class="two-columns-list first-column">
                <li>test</li>
            </ul>
            <!-- Deuxième colonne (en position relative) -->
            <ul class="two-columns-list second-column">
                <li>test</li>
            </ul>
        </div>
        <p>{{ $addressBetter }}</p>
        <p>{{ $mail }}</p>
        <p>{{ $phone }}</p>
        <p>{{ $latitude }}</p>
        <p>{{ $longitude }}</p>
        <h2>Informations pratiques et autres services</h2>
        <p>{!! $renseignement !!}</p>
    </div>  
</body>

</html>
