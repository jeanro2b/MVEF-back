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

        .text {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="banner-wrapper">
        <img src="https://mvef.s3.eu-west-3.amazonaws.com/LinkedIn.png" alt="Bannière">
    </div>
    <div class="content-wrapper">
        <h1>Objet : Réservation acceptée Mes Vacances En Famille</h1>
        <p>Bonjour, <br />
            Vous avez accepté une nouvelle demande de réservation !<br />
            Voici les informations relatives à cette réservation :
        </p>
        <h4>Hébergement demandé :</h4>
        <p>{{ $hebergementName }}</p>
        <h4>Date d'arrivée</h4>
        <p>{{ $dayStart }} / {{ $monthStart }} / {{ $yearStart }}</p>
        <h4>Date de départ</h4>
        <p>{{ $dayEnd }} / {{ $monthEnd }} / {{ $yearEnd }}</p>
        <h4>Demandeur</h4>
        <p>Nom : {{ $name }}</p>
        <p>Prénom : {{ $first_name }}</p>
        <p>Téléphone : {{ $phone }}</p>
        <h4>Réservation</h4>
        <p>Numéro : {{ $reservationId }}</p>
        <p>Montant : {{ $amount }}€</p>
        <div class="text">
            <p>Pensez à intégrer cette réservation dans votre système de gestion et votre calendrier.<br/>
            Vous trouverez ci-joint un récapitulatif de la réservation.<br/>
            En cas de problème contactez l’équipe Mes Vacances en Famille : contact@mesvacancesenfamille.com
            </p>
        </div>
    </div>

</body>

</html>