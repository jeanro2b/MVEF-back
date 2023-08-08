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
        <h1>Objet : Nouvelle demande de contact d'un CE depuis le site.</h1>
        <h4>Mail expéditeur :</h4>
        <p>{{ $clientEmail }}</p>
        <h4>Société expéditeur :</h4>
        <p>{{ $clientSociety }}</p>
        <h4>Nom expéditeur :</h4>
        <p>{{ $clientName }}</p>
        <h4>Prénom expéditeur :</h4>
        <p>{{ $clientFirstName }}</p>
        <h4>Téléphone expéditeur :</h4>
        <p>{{ $clientPhone }}</p>
        <h4>Message :</h4>
        <p>{{ $clientMessage }}</p>
        <p class="auto">Cet email est envoyé automatiquement, veuillez ne pas y répondre s'il vous plaît</p>
    </div>

</body>

</html>