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
            width: 40%;
            margin-top: 15px;
        }

        .dest-name {
            margin: 10px;
        }

        .dest-name p {
            margin: 0 10px;
        }

        .dest-date {
            margin: 10px;
        }

        .dest-date p {
            margin: 0 10px;
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
            left: 0;
            top: 0;
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
        margin: 15px 0 0 15px;
        }

        .columns-container {
        position: relative; /* Définir le conteneur en position relative */
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
        <h2>Carnet de voyage - {{ $libellePlanning }}</h2>
        <h2>Client - {{ $nomClient }}</h2>
        <h2>Voyageur - {{ $nomVoyageur }}</h2>
    </div>
    <div class="sejour">
        Votre séjour
    </div>
    <div class="second-section">
        <div class="destination">
            <div class="dest-name">
                <img class="img" src="data:image/png;base64,{{ $destData }}" alt="dest" width="30px">
                <p>{{ $nomDestination }}</p>
            </div>
            <div class="dest-date">
                <img class="img" src="data:image/png;base64,{{ $calData }}" alt="cal" width="30px">
                <p>Du {{ $dateArrive }} au {{ $dateDepart }}</p>
            </div>
            <div class="arrive">
                <p>Heure d'arrivée : {{ $heureArrive }}</p>
            </div>
            <div class="depart">
                <p>Heure de départ : {{ $heureDepart }}</p>
            </div>
        </div>
        <div class="hebergement">
            <h3>Votre hébergement</h3>
            <p>{!! $descriptionHebergement !!}</p>
        </div>
    </div>
    @php
        $servicesArray = $services->toArray();

        $halfCount = ceil(count($servicesArray) / 2);
        $firstHalf = array_slice($servicesArray, 0, $halfCount);
        $secondHalf = array_slice($servicesArray, $halfCount);
    @endphp
    <div class="services">
        <h3>Services</h3>
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
        <p>{{ $addressBetter }}</p>
        <p>{{ $mail }}</p>
        <p>{{ $phone }}</p>
        <p>{{ $latitude }}</p>
        <p>{{ $longitude }}</p>
        <p>{!! nl2br(e($renseignement)) !!}</p>
    </div>  
</body>

</html>

<!-- <ul class="two-columns-list">
            @foreach ($services as $service)
            <li>{{ $service['text'] }}</li>
            @endforeach
        </ul> -->
