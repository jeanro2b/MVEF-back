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
        <h1>Objet : Nouveau document disponible dans votre espace client</h1>
        <p>Madame, Monsieur <br />
            Nous vous informons qu’un nouveau document à été déposé dans votre espace
            client {{ $clientName }}. <br />Nous vous invitons à en prendre connaissance dans les plus brefs délais.<br />Accéder à l’espace client : https://mesvacancesenfamille.com , cliquez sur connexion en haut à droite et rentrez vos informations de connexion.
            <br />Cordialement,<br />L'équipe de « MES VACANCES EN FAMILLE »
        </p>
        <p class="auto">Cet email est envoyé automatiquement, veuillez ne pas y répondre s'il vous plaît</p>
    </div>


</body>

</html>