<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bon de séjour</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <link href="{{ asset('font/Nunito-Regular.ttf') }}" rel="stylesheet">
    <style>
        /* Ajoutez ici le style CSS de votre e-mail */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Nunito', 'Nunito-Regular', sans-serif; 
        }

        .header {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
            height: 120px;
            text-align: center;
            transform: translateX(32%);
        }

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
            margin: 22px 0 5px 0;
            width: 100%;
        }

        .sejour {
            font-weight: bold;
            text-align: center;
            margin: 5px 0;
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

        .dest-name {
            margin: 10px;
            display: flex;
            flex-direction: row;
            flex: wrap;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .dest-name img {
            margin-left: 100px;
            margin-right: 50px;
            font-size: 2em;
            height: 80px;
            width: 80px;
        }

        .dest-name p {
            margin: 0 10px;
            position: absolute;
        }

        .dest-date {
            margin: 10px;
            display: flex;
            flex-direction: row;
            flex: wrap;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .dest-date img {
            margin-left: 100px;
            margin-right: 100px;
            font-size: 2em;
            height: 80px;
            width: 80px;
        }

        .dest-date p {
            margin: 0 10px;
            position: absolute;
            width: 300px;
            left: 350px;
        }

        .arrive {
            margin: 10px;
        }

        .arrive p {
            margin: 0 10px;
        }

        .depart {
            margin: 10px;
        }

        .depart p {
            margin: 0 10px;
        }

        .hebergement {
            display: flex;
            flex-direction: column;
            width: 45%;
            position: absolute;
            right: 10px;
            top: 430px;
        }

        .logo {
            position: absolute;
            left: 50px;
            top: 0;
        }

        .logo-2 {
            position: absolute;
            right: 50px;
            top: 0;
            transform: translateY(10px);
        }

        .img {
            margin: 0px 0px 5px 10px;
        }

        .two-columns-list {
        display: flex;
        flex-wrap: wrap;
        padding: 0;
        }

        .two-columns-list li {
        width: 40%; /* Pour afficher deux li sur chaque ligne */
        box-sizing: border-box; /* Inclure le padding et la bordure dans la largeur */
        /* padding: 5px; */
        }

        .services {
        position: relative; /* Définir la classe "services" en position relative */
        margin: 10px 0 30px 15px;
        }

        .services h2 {
        margin: 30px 0 4px 0px;
        }

        h2 {
            margin: 20px 0 4px 0px;
        }

        .services h3 {
        margin: 10px 0 4px 0px;
        }

        .columns-container {
        position: relative; /* Définir le conteneur en position relative */
        margin: 0px 0px 15px 0px;
        }

        .first-column {
        /* Ajoutez ici les styles de mise en page pour la première colonne */
        }

        .second-column {
        /* Styles pour la deuxième colonne en position absolue */
        position: absolute;
        top: -18px;
        left: 350px;
        width: 800px;
        }

        .mar {
            margin-top: 23px
        }

    </style>
</head>

<body>
    @if($nomClient === 'Vacances Authentiques')
        <div class="header-2">
            <img class="logo" src="data:image/png;base64,{{ $logoData }}" alt="img" width="215px">
            <img class="logo-2" src="data:image/png;base64,{{ $logoVacancesAuthData }}" alt="img" width="250px">
        </div>
    @else
        <!-- <div class="header">
            <img class="logo" src="data:image/png;base64,{{ $logoData }}" alt="img" width="250px">
        </div> -->
        <div class="header-2">
            <img class="logo" src="data:image/png;base64,{{ $bslogoData }}" alt="img" width="215px">
            <img class="logo-2" src="data:image/png;base64,{{ $bslogotxtData }}" alt="img" width="250px">
        </div>
    @endif
    <!-- <div class="header">
        <img class="logo" src="data:image/png;base64,{{ $logoData }}" alt="img" width="250px">
        <div class="infos">
            <p><strong>Mes Vacances en Famille</strong></p>
            <p>Hameau de Lutina, 20237,</p>
            <p>Poggio-Marinaccio</p>
            <p>Service commercial</p>
            <p>145 route Millery - 69700 MONTAGNY</p>
            <a href="www.mesvacancesenfamille.com">www.mesvacancesenfamille.com</a>
        </div>
    </div> -->
    <div class="first-section">
        <h2>Carnet de voyage</h2>
        <h2>{{ $nomVoyageur }}</h2>
        <h2>Client : {{ $nomClient }}</h2>
        <h2>{{ $libellePlanning }}</h2>
    </div>
    <!-- <div class="sejour">
        Votre séjour
    </div> -->
    <div class="services">
        <h2>Destination</h2>
        <p><strong>Nom : </strong>{{ $nomDestination }} à {{ $villeDestination }}</p>
        <p><strong>Adresse : </strong>{{ $addressBetter }}</p>

        @if($mail !== null && $mail !== '')
            <p><strong>Mail : </strong>{{ $mail }}</p>
        @endif

        @if($phone !== null && $phone !== '')
            <p><strong>Téléphone : </strong>{{ $phone }}</p>
        @endif
        
        @if($latitude !== null && $latitude !== '')
            <p><strong>Latitude : </strong>{{ $latitude }}</p>
        @endif

        @if($longitude !== null && $longitude !== '')
            <p><strong>Longitude : </strong>{{ $longitude }}</p>
        @endif
        
        @if($caution !== null && $caution !== '')
            <p><strong>Caution : </strong>{{ $caution }}</p>
        @endif
        @if($taxe !== null && $taxe !== '')
            <p><strong>Taxe de séjour : </strong>{{ $taxe }}</p>
        @endif
    </div>
    <div class="services">
        <h2 class="mar">Dates et Horaires</h2>
        <p><strong>Arrivée : </strong>le {{ $dateArrive }} à partir de {{ $heureArrive }}</p>
        <p><strong>Départ : </strong>le {{ $dateDepart }} avant {{ $heureDepart }}</p>
    </div>
    <div class="services">
        <h2 class="mar">Hébergement</h2>
        <p>{{ $nomHebergement }}</p>
        <p>{!! $descriptionHebergement !!}</p>
    </div>
    @php
        $servicesArray = $services->toArray();

        $halfCount = ceil(count($servicesArray) / 2);
        $firstHalf = array_slice($servicesArray, 0, $halfCount);
        $secondHalf = array_slice($servicesArray, $halfCount);
    @endphp
    <div class="services">
        <h2>Services inclus</h2>
        <div class="columns-container">
            <!-- Première colonne -->
            <ul class="two-columns-list first-column">
            @foreach ($firstHalf as $service)
                <li>{{ $service['text'] }}</li>
            @endforeach
            </ul>
            <!-- Deuxième colonne (en position relative) -->
            <ul class="two-columns-list second-column">
            @foreach ($secondHalf as $service)
                <li>{{ $service['text'] }}</li>
            @endforeach
            </ul>
        </div>
        <h2>Informations pratiques et autres services</h2>
        <p>{!! $renseignement !!}</p>
    </div> 
</body>

</html>
