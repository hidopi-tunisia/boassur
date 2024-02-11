@extends('front.layouts.app')

@section('content')
<div class="w-full bg-presence bg-local bg-clip-content bg-bottom bg-no-repeat bg-cover"
     style="background-image: url('/images/background.jpg')">
    <div class="flex justify-end max-w-screen-xl space-x-12 mx-auto py-16 lg:py-36 xl:py-32">
        <div class="w-full bg-white rounded opacity-90 px-10 p-6 lg:w-1/4">
            <p class="mb-8 text-md text-center font-bold">
                <span class="text-presence text-2xl">Assurance voyage</span><br>
                C'est avec l'esprit libre que l'on voyage.<br />
                <span class="text-sm">Souscrivez en ligne pour vos prochaines vacances</span>
            </p>
            <a href="/acheter-une-assurance"
               class="block py-2.5 w-full my-2 text-white uppercase font-bold text-lg text-center rounded-md bg-presence focus:outline-none">
                ACHETEZ EN LIGNE
            </a>
        </div>
    </div>
</div>
<section class="bg-presence">
    <div class="container flex flex-col items-center px-5 pt-16 pb-40 flex-wrap mx-auto text-white md:flex-row">
        <div class="w-1/2 p-6 text-3xl font-bold">
            Seul, en couple ou en famille&nbsp;:<br />
            choisissez votre assurance en toute liberté
        </div>
        <div class="w-1/2 p-6 text-lg">
            Bénéficiez des garanties les plus complètes et les plus compétitives du marché. Conscient aujourd'hui
            qu'être bien assuré est un gage de voyage réussi, nous avons tous types d'assurances avec toutes les
            garanties adaptées à chaque voyage...
        </div>
    </div>
</section>
<section class="-mt-28">
    <div
         class="container flex flex-col justify-between items-start flex-wrap mx-auto space-x-0 md:space-x-8 md:flex-row md:items-stretch">
        <div class="card-home">
            <img src="/images/paiement-securise.jpg"
                 class="w-full rounded-md"
                 alt="Payez en toute sécurité" />
            <div class="card-home-text">
                <div class="h-16 flex justify-start items-center px-6 space-x-4 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span class="ml-3">Payez en Toute Sécurité</span>
                </div>
                <div class="py-3 px-6">Notre système de paiement est assuré par Ingenico / leader mondial des solutions
                    de paiement intégrées.</div>
            </div>
        </div>
        <div class="card-home">
            <img src="/images/nos-garanties.jpg"
                 class="w-full rounded-md"
                 alt="Nos garanties" />
            <div class="card-home-text">
                <div class="h-16 flex justify-start items-center px-6 space-x-4 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="ml-3">Nos Garanties</span>
                </div>
                <div class="py-3 px-6">Annulation, Départ et Retour Manqué, Bagages, Retard de transport, Interruption
                    de séjour, Voyage de remplacement, etc...<br /> Téléchargez notre contrat Multirisques</div>
            </div>
        </div>
        <div class="card-home">
            <img src="/images/contactez-nous.jpg"
                 class="w-full rounded-md"
                 alt="Contactez-nous" />
            <div class="card-home-text">
                <div class="h-16 flex justify-start items-center px-6 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span class="ml-3">Contactez Nous</span>
                </div>
                <div class="py-3 px-6">Du lundi au vendredi de <strong>9h30 à 18h</strong> au
                    <strong>01&nbsp;55&nbsp;90&nbsp;47&nbsp;25</strong> Une équipe de professionnels se tient à votre
                    disposition pour tous renseignements complémentaires.
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bg-white">
    <div class="container flex flex-col items-start px-5 py-16 flex-wrap mx-auto text-gray-600 md:flex-row">
        <div class="w-1/4 text-center">
            <h3 class="lg:text-3xl font-black"><span class="timer"
                      data-to="2.5"
                      data-speed="3000">3</span> Millions</h3>
            <p class="font-medium text-sm text-presence">De personnes assurées par an</p>
        </div>
        <div class="w-1/4 text-center">
            <h3 class="lg:text-3xl font-black"><span class="timer"
                      data-to="29000"
                      data-speed="3000">29000</span></h3>
            <p class="font-medium text-sm text-presence">Dossiers gérés par an</p>
        </div>
        <div class="w-1/4 text-center">
            <h3 class="lg:text-3xl font-black"><span class="timer"
                      data-to="92.44"
                      data-speed="3000">92</span>%</h3>
            <p class="font-medium text-sm text-presence">Des dossiers ouverts sont indemnisés</p>
        </div>
        <div class="w-1/4 text-center">
            <h3 class="lg:text-3xl font-black"><span class="timer"
                      data-to="1680"
                      data-speed="3000">1680</span></h3>
            <p class="font-medium text-sm text-presence">Personnes assistées à l’étranger en 2019</p>
        </div>
    </div>
</section>
@endsection