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
        <h1>Objet : Réservation annulée Mes Vacances En Famille</h1>
        <p>Bonjour, <br />
            La réservation suivante a été annulée :
        </p>
        <h4>Hébergement :</h4>
        <p>{{ $hebergementName }}</p>
        <h4>Date d'arrivée</h4>
        <p>{{ $dayStart }} / {{ $monthStart }} / {{ $yearStart }}</p>
        <h4>Date de départ</h4>
        <p>{{ $dayEnd }} / {{ $monthEnd }} / {{ $yearEnd }}</p>
        <h4>Demandeur</h4>
        <p>Nom : {{ $name }}</p>
        <p>Prénom : {{ $first_name }}</p>
        <h4>Réservation</h4>
        <p>Numéro : {{ $reservationId }}</p>
        <div class="text">
            <p>Le montant du remboursement sera déterminé en fonction de vos conditions générales de vente.<br />
                Notre équipe vous contactera dans les prochaines 24h pour vous communiquer le montant du remboursement
                ainsi que les détails de versement.<br />
                Cordialement, <br />
                L'équipe Mes Vacances En Famille
            </p>
        </div>
    </div>

</body>

</html>