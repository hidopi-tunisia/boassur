<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>Presence Assistance Tourisme</title>
    <link rel="preconnect"
          href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet"
          href="{{ mix('css/style.css') }}">
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js"
            defer></script>

    <script src="{{ mix('js/main.js') }}"
            defer></script>
</head>

<body class="flex flex-col antialiased bg-presence">
    <div class="sticky z-50 w-full text-gray-700 body-font top-0">
        <div class="container flex flex-col flex-wrap mx-auto h-24 lg:items-center lg:flex-row">
            <div class="flex items-center flex-none w-[200px]">
                <a href="/">
                    <img src="/images/logo-presence2.svg"
                         alt="Presence Assistance Tourisme"
                         width="200" />
                </a>
            </div>
            <div class="block lg:hidden">
                <button
                        class="navbar-burger flex items-center px-3 py-2 border rounded text-white border-white hover:text-white hover:border-white">
                    <svg class="fill-current h-6 w-6 text-presence"
                         viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </button>
            </div>
            <div class="flex flex-1 flex-col w-auto items-stretch justify-center lg:flex-row">
                <a href="#"
                   class="menu-link">A Propos</a>
                <a href="#"
                   class="menu-link">Nos Assurances </a>
                <a href="#"
                   class="menu-link">FAQ</a>
                <a href="#"
                   class="menu-link">Contactez-nous</a>
                <a href="#"
                   class="menu-link">Mentions légales</a>
            </div>
            <div class="flex items-center px-8 py-2 ml-auto font-semibold">
                <div class="pr-2 text-presence">
                    <svg width="30"
                         height="30"
                         viewBox="0 0 20 20"
                         fill="currentColor">
                        <path
                              d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                        </path>
                    </svg>
                </div>
                <div class="text-xs font-extralight leading-3">
                    Besoin d'aide&nbsp;?<br />
                    <span class="text-lg font-extrabold">01&nbsp;55&nbsp;90&nbsp;47&nbsp;25</span>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-100 min-h-screen -mt-28">
        @yield('content')
    </div>
    <footer class="bg-gray-200 border-t border-gray-300">
        <div class="container items-center px-5 py-10 mx-auto md:flex-row">
            <p class="text-center text-gray-600 pb-4"><a href="#"
                   class="text-center text-gray-600">Mentions légales</a> | <a href="#"
                   class="text-center text-gray-600">Politique de confidentialité</a></p>
            <p class="text-center text-gray-600"><strong>SAS de Courtage d’Assurances au capital de
                    1.118.880&nbsp;€</strong> - Siren 622&nbsp;035&nbsp;947</p>
            <p class="text-center text-gray-600 leading-6">Agrément ORIAS n° 07001824 - www.orias.fr - sous le contrôle
                de l’ACPR 4 place de Budapest 75436 PARIS -<br />
                Garanties Financières et Assurance Responsabilité Civile Professionnelle conformes à la Législation</p>
        </div>
    </footer>
    <div class="container p-5 mx-auto text-right">
        <p class="text-white">© 2021 - Presence Assistance Tourisme</p>
    </div>

    @stack('scripts')
</body>

</html>