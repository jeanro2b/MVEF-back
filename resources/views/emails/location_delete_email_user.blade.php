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
        <h1>Objet : Annulation de réservation Mes Vacances En Famille</h1>
        <p>Bonjour, <br />
            Suite à votre échange avec notre service client ou avec notre partenaire, votre réservation N°{{
            $reservationId }} a été
            annulée. <br />
            Vous serez remboursé conformément aux conditions mises en place par notre partenaire. <br />
            Notre équipe vous contactera dans les prochaines 24h pour vous communiquer le montant de votre remboursement
            ainsi que les détails de versement. <br />
            Cordialement, <br />
            L'équipe Mes Vacances En Famille
        </p>
    </div>

</body>

</html>