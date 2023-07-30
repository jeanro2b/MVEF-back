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
        <h1>Objet : Vos identifiants de connexion !</h1>
        <p>Madame, Monsieur <br />
            Nous sommes ravi de vous compter parmi nos clients et de vous accompagner vers
            nos plus belles destinations !<br />Vous trouverez ci-dessous vos identifiants de connexion à notre extranet client :<br />
            Identifiant : {{ $clientEmail }}<br />
            Mot de passe : {{ $clientPassword }}
            <br />Accéder à l’espace client : https://mesvacancesenfamille.com , cliquez sur connexion en haut à droite et rentrez vos informations de connexion.<br />
            Pour rappel, cet extranet vous permet :
        <ul>
            <li>De gérer les plannings relatifs à vos différentes destination.</li>
            <li>Consulter les informations de vos destination.</li>
            <li>Transmettre les bons de s/jour à vos ayants droit.</li>
            <li>Consulter vos documents 2 contrats, factures...</li>
        </ul>
        <br />Cordialement,<br />L'équipe de « MES VACANCES EN FAMILLE »
        </p>
        <p class="auto">cet email est envoyé automatiquement, veuilliez ne pas y répondre s'il vous plaît</p>
    </div>

</body>

</html>