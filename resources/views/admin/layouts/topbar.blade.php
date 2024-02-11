<div class="relative pb-8 pt-16 bg-indigo-700 md:pt-28">
    <nav x-data="{ openprofile: false }"
         class="absolute top-0 left-0 w-full z-10 flex items-center pt-4 md:flex-row md:flex-no-wrap md:justify-start">
        <div class="relative w-full items-center flex justify-between md:flex-no-wrap flex-wrap md:px-6">
            <div>&nbsp;</div>
            <div class="flex-col md:flex-row items-center hidden md:flex">
                <button type="button"
                        class="w-8 h-8 text-sm text-white bg-indigo-700 inline-flex items-center justify-center rounded-full border-2 border-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-300 focus:ring-white"
                        id="user-menu"
                        @click="openprofile = !openprofile"
                        aria-haspopup="true"
                        x-bind:aria-expanded="open">
                    <span class="sr-only">Ouvrir le menu utilisateur</span>
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 text-white"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>
            </div>
            <div x-description="Sous menu utilisateur"
                 x-show="openprofile"
                 x-on:click.away="openprofile = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="origin-top-right absolute right-4 top-10 bg-white text-base z-50 float-left p-2 list-none text-left rounded shadow-lg min-w-48"
                 role="menu"
                 aria-orientation="vertical"
                 aria-labelledby="user-menu">
                <ul class="list-none w-full">
                    <x-admin.dropdown-link href="/admin/edition/{{Auth::user()->id}}">
                        Mon compte
                    </x-admin.dropdown-link>
                    <li>
                        <form method="POST"
                              action="{{ route('logout') }}">
                            @csrf
                            <button type="button"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-xs block px-2 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>