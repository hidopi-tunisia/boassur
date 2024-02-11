<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Presence Assistance Tourisme</title>
    <style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: sans-serif;
        }

        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="background-color: #ffffff; font-size: 16px">
    <p>
        Bonjour<br /><br />
        Nous n'avons pas pu délivrer le mail de confirmation de souscription à votre client.<br /><br />
        Concerne souscription : <strong>{{ $souscription }}</strong><br />
        Nom du client : {{ $nom }}<br />
        Adresse mail du client : {{ $email }}<br />
        Numéro de téléphone : {{ $telephone }}<br />
        <br />
        Merci de faire le nécessaire.
        <br /><br />
        Bonne journée.<br />
        Backoffice site {{ $site }}
    <p>
</body>

</html>