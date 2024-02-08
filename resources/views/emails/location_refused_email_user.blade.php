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

        .content-text {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="banner-wrapper">
        <img src="https://mvef.s3.eu-west-3.amazonaws.com/LinkedIn.png" alt="Bannière">
    </div>
    <div class="content-wrapper">
        <h1>Objet : Refus de réservation Mes Vacances En Famille</h1>
        <p>Bonjour, <br />
            <!-- Vous venez d'effectuer une demande de réservation depuis Mes Vacances En Famille.<br />
            Celle-ci à été transmise au propriétaire concerné et est en attente d'acceptation par celui-ci.<br />
            Vous serez débité et recevrez un mail de confirmation si l'hébergeur accepte votre demande. -->
            Malheureusement votre réservation n’a pas été confirmée par notre partenaire.<br/>
            Rappel de votre demande :
        </p>
        @php
            $destinationId = e($destination_id);
            $url = url("https://www.mesvacancesenfamille.com/destinations/{$destinationId}");
        @endphp
        <a href="{{ $url }}">Cliquez ici pour aller sur la page de la destination</a>
        
        <h4>Date d'arrivée</h4>
        <p>{{ $dayStart }} / {{ $monthStart }} / {{ $yearStart }}</p>
        <h4>Date de départ</h4>
        <p>{{ $dayEnd }} / {{ $monthEnd }} / {{ $yearEnd }}</p>
        <h4>Réservation</h4>
        <p>Numéro : {{ $reservationId }}</p>
        <p>Montant : {{ $amount }}€</p>
        <div class="content-text">
            <p>L’empreinte bancaire a été levée. Nous vous confirmons que vous ne serez pas débité du montant de la réservation.<br/>
            Si vous avez utilisé un code de réduction, celui-ci est encore valable.<br/>
            Nous vous invitons à renouveler votre demande de réservation à d’autres dates ou pour une autre destination.<br/>
            Voici le lien vers nos destinations : https://www.mesvacancesenfamille.com/destinations <br/>
            L’équipe Mes Vacances en Famille vous présente ses excuses pour cette situation indépendante de notre volonté.
            </p>
        </div>
    </div>

</body>

</html>