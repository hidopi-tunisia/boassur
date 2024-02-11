<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>Presence Assistance Tourisme</title>

    <link rel="preconnect"
          href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;400;600&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="{{ mix('css/app.css') }}">

    <script src="{{ mix('js/app.js') }}"
            defer></script>
</head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        {{ $slot }}
    </div>
</body>

</html>