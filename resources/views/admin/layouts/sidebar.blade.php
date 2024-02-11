<nav x-data="{ openmobile: false }" class="relative shadow-xl bg-white md:w-64 md:h-full md:fixed md:overflow-y-auto md:flex-row">
    <div class="relative h-16 z-50 flex flex-wrap px-4 items-center shadow-md md:h-auto md:shadow-none">
        <div class="flex-1 py-4 md:w-full">
            <a href="../../index.html">
                <x-application-logo class="block h-8 w-auto" />
            </a>
        </div>
        <div class="w-8 flex-0 flex justify-end md:hidden">
            <button @click="openmobile = !openmobile" x-bind:aria-expanded="openmobile.toString()" type="button" class="cursor-pointer bg-transparent border border-solid border-transparent" aria-controls="mobile-menu" aria-expanded="false">
                <svg x-state:on="Menu ouvert" x-state:off="Menu fermé" class="text-gray-600 w-7" :class="{ 'hidden': openmobile, 'block': !(openmobile) }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-state:on="Menu ouvert" x-state:off="Menu fermé" class="text-gray-600 w-7" :class="{ 'block': openmobile, 'hidden': !(openmobile) }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    <div class="hidden p-4 bg-white w-full z-40 md:block">
        <ul class="flex flex-col list-none md:min-w-full md:mb-4">
            <x-admin.dropdown-link href="{{route('admin.dashboard')}}">
                Dashboard
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="{{route('admin.sites.liste')}}">
                Sites
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="{{route('admin.destinations.liste')}}">
                Destinations
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="{{route('admin.commandes.liste')}}">
                Réservations
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="{{route('admin.contrats.liste')}}">
                Contrats
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="{{route('admin.rappels.liste')}}">
                Rappels
            </x-admin.dropdown-link>
        </ul>
    </div>

    <div x-show="openmobile" x-description="Mobile menu, show/hide based on menu state." style="display: none;" class="absolute top-16 p-4 bg-white w-full z-40 shadow-md md:hidden">
        <ul class="flex flex-col list-none md:min-w-full md:mb-4">
            <x-admin.dropdown-link href="{{route('admin.dashboard')}}">
                Dashboard
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="{{route('admin.sites.liste')}}">
                Sites
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="{{route('admin.destinations.liste')}}">
                Destinations
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="{{route('admin.commandes.liste')}}">
                Réservations
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="{{route('admin.contrats.liste')}}">
                Contrats
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="{{route('admin.rappels.liste')}}">
                Rappels
            </x-admin.dropdown-link>
        </ul>
        <hr class="my-4 md:min-w-full md:hidden" />
        <ul class="list-none">
            <x-admin.dropdown-link href="/admin/edition/{{Auth::user()->id}}">
                Mon compte
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="/admin/ajout">
                Nouvel admin
            </x-admin.dropdown-link>
            <x-admin.dropdown-link href="/admin">
                Liste des admins
            </x-admin.dropdown-link>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="event.preventDefault(); this.closest('form').submit();" class="text-xs uppercase block px-2 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">Déconnexion</button>
                </form>
            </li>
        </ul>
    </div>
</nav>