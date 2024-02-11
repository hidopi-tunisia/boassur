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
    <p>Une nouvelle demande de rappel a été enregistrée sur le site CSE</p>
    <u>Nom&nbsp;:</u> {{$rappel->nom}}<br />
    <u>Téléphone&nbsp;:</u> {{$rappel->telephone}}<br />
    <u>Jour&nbsp;:</u> {{\Carbon\Carbon::parse($rappel->date_rappel)->format('d/m/y')}}<br />
    <u>Heure&nbsp;:</u> {{$rappel->heure_rappel}}<br /><br />
    <p class="font-size: 12px;">
        Date de la demande&nbsp;: {{\Carbon\Carbon::parse($rappel->created_at)->format('d/m/y H:i')}}
    </p>
</body>

</html>