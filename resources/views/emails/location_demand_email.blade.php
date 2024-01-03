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

        .banner-wrapper {
            width: 100%;
            object-fit: fill;
        }

        .content-wrapper {
            padding: 20px;
            color: black;
            line-height: 2.4em;
        }

        .auto {
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="banner-wrapper">
        <img src="https://mvef.s3.eu-west-3.amazonaws.com/LinkedIn.png" alt="Bannière">
    </div>
    <div class="content-wrapper">
        <h1>Objet : Nouvelle réservation depuis Mes Vacances En Famille</h1>
        <p>Bonjour, <br />
            Vous avez une nouvelle demande de réservation !<br />
            Vous trouverez ci-dessous les détails de la demande :
        </p>
        <h4>Hébergement demandé :</h4>
        <p>{{ $hebergementName }}</p>
        @php
            $destinationId = e($destination_id);
            $url = url("https://www.mesvacancesenfamille.com/destinations/{$destinationId}");
        @endphp
        <a href="{{ $url }}">Cliquez ici pour aller sur la page de la destination</a>
        
        <h4>Date d'arrivée</h4>
        <p>{{ $dayStart }} / {{ $monthStart }} / {{ $yearStart }}</p>
        <h4>Date de départ</h4>
        <p>{{ $dayEnd }} / {{ $monthEnd }} / {{ $yearEnd }}</p>
        <h4>Demandeur</h4>
        <p>Nom : {{ $name }}</p>
        <p>Prénom : {{ $first_name }}</p>
        <div class='lien'>
        @php
            $escapedToken = e($token);
            $escapedId = e($reservationId);
            $acceptUrl = url("https://mesvacancesenfamille.com/reservation/accept/?tid={$escapedToken}&id={$escapedId}");
            $refuseUrl = url("https://mesvacancesenfamille.com/reservation/refuse?tid={$escapedToken}&id={$escapedId}");
        @endphp
            <p>Si vous acceptez la réservation, veuillez cliquez ci-joint : 
                <a href="{{ $acceptUrl }}">
                ACCEPTER LA RÉSERVATION</a></p>
        </div>
        <div class='lien'>
            <p>Si vous ne pouvez pas accepter la réservation, veuillez cliquez ci-joint : 
                <a href="{{ $refuseUrl }}">
                REFUSER LA RÉSERVATION</a</p>
        </div>
        <p>Vous disposez de 24h pour répondre à cette demande, en cas d’absence de réponse de votre part, la demande de réservation sera annulée.</p>
        <p>Cordialement,</p>
    </div>

</body>

</html>