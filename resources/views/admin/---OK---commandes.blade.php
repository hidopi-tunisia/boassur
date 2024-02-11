<div>
    <div class="flex-col space-y-4 p-4">
        <h1 class="text-2xl font-semibold text-gray-800">Réservations</h1>
        <div class="flex justify-between space-x-4">
            <div class="flex w-1/3 justify-start space-x-4">
                <x-link-button wire:click="toggleShowFilters" type="button">@if($showFilters)Masquer @else Voir @endif les filtres</x-link-button>
            </div>
            <div class="flex items-end space-x-4">
                <x-admin.table.per-page />
            </div>
        </div>

        <div>
            @if($showFilters)
            <div class="rounded shadow-inner grid grid-cols-4 gap-3 relative bg-gray-100 p-4">
                <x-admin.form.input-group label="Assurance" for="filter-quote_id">
                    <x-admin.form.input-text wire:model.lazy="filters.quote_id" placeholder="Réf assurance" type="text" id="filter-quote_id" />
                </x-admin.form.input-group>
                <x-admin.form.input-group label="Réf Ogone" for="filter-ogone">
                    <x-admin.form.input-text wire:model.lazy="filters.ogone" placeholder="Réf Ogone" type="text" id="filter-ogone" />
                </x-admin.form.input-group>
                <x-admin.form.input-group label="Souscription" for="filter-souscription">
                    <x-admin.form.input-text wire:model.lazy="filters.souscription" placeholder="Numéro de souscription" type="text" id="filter-souscription" />
                </x-admin.form.input-group>
                <x-admin.form.input-group label="Email" for="filter-email">
                    <x-admin.form.input-text wire:model.lazy="filters.email" placeholder="Email" type="text" id="filter-email" />
                </x-admin.form.input-group>
                <x-admin.form.input-group label="Nom" for="filter-nom">
                    <x-admin.form.input-text wire:model.lazy="filters.nom" placeholder="Nom ou prénom" type="text" id="filter-nom" />
                </x-admin.form.input-group>
                <x-link-button wire:click="resetFilters" type="button">Réinitialiser les filtres</x-link-button>
            </div>
            @endif
        </div>

        <x-admin.table.table>
            <x-slot name="head">
                <x-admin.table.heading class="w-8">ID</x-admin.table.heading>
                <x-admin.table.heading class="w-12">Réf. Ogone</x-admin.table.heading>
                <x-admin.table.heading class="w-12" sortable wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null">
                    Commande
                </x-admin.table.heading>
                <x-admin.table.heading class="w-12">Site</x-admin.table.heading>
                <x-admin.table.heading class="w-12" sortable wire:click="sortBy('quote_id')" :direction="$sorts['quote_id'] ?? null">
                    Assurance
                </x-admin.table.heading>
                <x-admin.table.heading class="w-12">Souscription</x-admin.table.heading>
                <x-admin.table.heading class="w-12">Date</x-admin.table.heading>
                <x-admin.table.heading class="w-28">Statut du mail</x-admin.table.heading>
                <x-admin.table.heading sortable wire:click="sortBy('nom')" :direction="$sorts['nom'] ?? null">
                    Nom
                </x-admin.table.heading>
                <x-admin.table.heading class="w-12" />
            </x-slot>
            <x-slot name="body">
                @forelse($commandes as $commande)
                <x-admin.table.row wire:loading.class.delay="opacity-40" wire:key="row-{{$commande->id}}">
                    <x-admin.table.cell><span class="text-xs">{{$commande->id}}</span></x-admin.table.cell>
                    <x-admin.table.cell><span class="text-xs">{{optional($commande->paiement)->PAYID}}</span></x-admin.table.cell>
                    <x-admin.table.cell><span class="text-xs">{{$commande->creation}}</span></x-admin.table.cell>
                    <x-admin.table.cell class="bg-yellow-100">
                        <span class="text-xs text-yellow-700">{{$commande->site->nom}}</span>
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        <span class="text-xs font-mono">{{$commande->quote_id}}</span>
                    </x-admin.table.cell>
                    @if($commande->reservation)
                    <x-admin.table.cell class="bg-blue-50">
                        <span class="font-bold text-blue-600 text-xs font-mono">
                            {{$commande->reservation->num_souscription}}
                        </span>
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        <span class="text-xs text-blue-600">
                            {{$commande->reservation->creation}}
                        </span>
                    </x-admin.table.cell>
                    @else
                    <x-admin.table.cell class="bg-blue-50">
                        <span class="font-bold text-blue-600 text-xs font-mono"> -- </span>
                    </x-admin.table.cell>
                    <x-admin.table.cell> <span class="text-xs text-blue-600"> -- </span> </x-admin.table.cell>
                    @endif
                    @if($commande->email_statut !== null)
                    <x-admin.table.cell>
                        <span class="{{$commande->email_statut === 'delivered' ? 'text-green-600' : 'text-yellow-600'}} font-mono text-sm">{{$commande->email_statut}}</span><br />
                        <span class="text-xs text-gray-500">{{$commande->email_date}}</span>
                    </x-admin.table.cell>
                    @else
                    <x-admin.table.cell>--</x-admin.table.cell>
                    @endif
                    <x-admin.table.cell>
                        {{optional($commande->voyageur)->nom_complet}}<br>
                        <span class="text-xs text-gray-500">{{$commande->email}}</span>
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        <x-button wire:click="show({{$commande->id}})">Voir</x-button>
                    </x-admin.table.cell>
                </x-admin.table.row>
                @empty
                <x-admin.table.row>
                    <x-admin.table.cell colspan="10">
                        <x-admin.table.not-found />
                    </x-admin.table.cell>
                </x-admin.table.row>
                @endforelse
            </x-slot>
        </x-admin.table.table>

        {{ $commandes->links()}}
    </div>

    <x-dialog-modal wire:model.defer="showEditModal" maxWidth="2xl">
        <x-slot name="title">
            @if($editing->reservation)
            <span class="text-indigo-500">Réservation n°
                <span class="font-bold text-2xl text-indigo-700">{{$editing->reservation->num_souscription}}</span> du
                <span class="font-bold text-2xl text-indigo-700">{{$editing->creation}}</span>
            </span>
            @else
            <span class="text-indigo-500">Commande du
                <span class="font-bold text-2xl text-indigo-700">{{$editing->creation}}</span>
            </span>
            @endif
        </x-slot>
        <x-slot name="content">
            @if($editing->paiement)
            <div class="bg-green-50 p-3 mb-4 rounded-md">
                <div class="grid grid-cols-7 gap-3 mb-4">
                    <x-admin.form.item-group label="Réf Ogone" class="col-span-2" type="success">
                        {{$editing->paiement->PAYID}}
                    </x-admin.form.item-group>
                    <x-admin.form.item-group label="Carte" class="col-span-2" type="success">
                        {{$editing->paiement->BRAND}}
                    </x-admin.form.item-group>
                    <x-admin.form.item-group label="Numéro" class="col-span-3" type="success">
                        {{$editing->paiement->CARDNO}}
                    </x-admin.form.item-group>
                </div>
            </div>
            @else
            <div class="mb-4">
                <x-admin.form.item-group type="error">
                    Aucun paiement
                </x-admin.form.item-group>
            </div>
            @endif
            <h2 class="subtitle-edit">La souscription</h2>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <x-admin.form.item-group label="Réf" type="yellow">
                    {{$editing->quote_id}}
                </x-admin.form.item-group>
                <x-admin.form.item-group label="Nom" class="col-span-2" type="yellow">
                    {{$editing->quote_nom}}
                </x-admin.form.item-group>
            </div>
            @if($editing->reservation)
            <div class="grid grid-cols-7 gap-3 mb-4">
                <x-admin.form.item-group label="Statut" type="yellow">
                    {{$editing->reservation->statut}}
                </x-admin.form.item-group>
                <x-admin.form.item-group label="Numéro" class="col-span-2" type="yellow">
                    {{$editing->reservation->num_souscription}}
                </x-admin.form.item-group>
                <x-admin.form.item-group label="Réf" class="col-span-2" type="yellow">
                    {{$editing->reservation->ref_souscription}}
                </x-admin.form.item-group>
                <x-admin.form.item-group label="Date" class="col-span-2" type="yellow">
                    {{$editing->reservation->creation}}
                </x-admin.form.item-group>
                <div class="col-span-3 mt-3 text-gray-700 text-sm">
                    <a href="{{$editing->reservation->url}}" class="px-3 py-2 bg-white rounded-sm hover:bg-gray-100">
                        <x-icons.document-download class="w-6 h-6 text-gray-300 inline-block" /> Télécharger
                        contrat
                    </a>
                </div>
                <div class="col-span-2 mt-3 text-gray-700 text-sm">
                    @if($editing->reservation->cgv)
                    <a href="{{$editing->reservation->cgv}}" class="px-3 py-2 bg-white rounded-sm hover:bg-gray-100">
                        <x-icons.document-download class="w-6 h-6 text-gray-300 inline-block" /> CGV
                    </a>
                    @else
                    Pas de CGV
                    @endif
                </div>
                @if($editing->reservation->notified_at)
                <div class="col-span-2 mt-3 text-gray-700 text-xs">
                    <x-icons.mail class="w-6 h-6 text-gray-300 inline-block" />
                    {{$editing->reservation->notification}}
                </div>
                @endif
            </div>
            @endif
            @if($editing->destination)
            <h2 class="subtitle-edit">
                Le voyage
            </h2>
            @if (intval($editing->site_id, 10) === 2)
            <x-admin.form.item-group label="Numéro de dossier CSE LIGNES" class="mb-3">
                {{$editing->voyageur->numero}}
            </x-admin.form.item-group>
            @endif
            <div class="grid grid-cols-4 gap-3">
                <x-admin.form.item-group label="Destination" class="col-span-4">
                    {{$editing->destination->nom}}
                </x-admin.form.item-group>
                <x-admin.form.item-group label="Date de départ">
                    {{$editing->depart_formate}}
                </x-admin.form.item-group>
                <x-admin.form.item-group label="Date de retour">
                    {{$editing->retour_formate}}
                </x-admin.form.item-group>
                <x-admin.form.item-group label="Prix du voyage">
                    {!!$editing->prix_voyage_euro!!}
                </x-admin.form.item-group>
            </div>
            @endif
            @if($editing->voyageur)
            <h2 class="subtitle-edit">Les personnes</h2>
            <div class="grid grid-cols-4 gap-3 bg-gray-50 p-3 mb-4 rounded-md">
                <x-admin.form.item-group>
                    {{$editing->voyageur->salutation}}
                </x-admin.form.item-group>
                <x-admin.form.item-group class="col-span-2">
                    {{optional($editing->voyageur)->nom_complet}}
                </x-admin.form.item-group>
                <x-admin.form.item-group>
                    {{$editing->voyageur->anniversaire}}
                </x-admin.form.item-group>
                <x-admin.form.item-group class="col-span-2">
                    {{$editing->voyageur->email}}
                </x-admin.form.item-group>
                <x-admin.form.item-group class="col-span-2">
                    {{$editing->voyageur->telephone}}
                </x-admin.form.item-group>
                <x-admin.form.item-group label="Adresse" class="col-span-2">
                    {{$editing->voyageur->adresse}}
                </x-admin.form.item-group>
                <x-admin.form.item-group label="Adresse 2" class="col-span-2">
                    {{$editing->voyageur->adresse2 ?? '.'}}
                </x-admin.form.item-group>
                <x-admin.form.item-group>
                    {{$editing->voyageur->cp}}
                </x-admin.form.item-group>
                <x-admin.form.item-group class="col-span-2">
                    {{$editing->voyageur->ville}}
                </x-admin.form.item-group>
                @if($editing->voyageur->pays)
                <x-admin.form.item-group>
                    {{$editing->voyageur->pays->nom}}
                </x-admin.form.item-group>
                @endif
            </div>
            @foreach($editing->accompagnants as $accompagnant)
            <div class="grid grid-cols-5 gap-3 border-b-2 border-dashed border-gray-300 pb-2 mb-2">
                <x-admin.form.item-group class="col-span-2">
                    {{optional($accompagnant)->nom_complet}}
                </x-admin.form.item-group>
                <x-admin.form.item-group>
                    {{$accompagnant->anniversaire}}
                </x-admin.form.item-group>
            </div>
            @endforeach
            @endif
        </x-slot>
        <x-slot name="footer">
            <div class="flex items-center justify-end space-x-4">
                <x-secondary-button wire:click="$set('showEditModal', false)">OK</x-secondary-button>
            </div>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model.defer="showDeleteModal">
        <x-slot name="title">Supprimer des enregistrements</x-slot>
        <x-slot name="content">
            Êtes-vous sûr de vouloir supprimer
            @if (count($selected) === 1)
            cet enregistrement ?
            @else
            ces {{count($selected)}} enregistrements ?
            @endif
        </x-slot>
        <x-slot name="footer">
            <div class="flex items-center justify-end space-x-4">
                <x-secondary-button wire:click="$set('showDeleteModal', false)" class="uppercase">Annuler</x-secondary-button>
                <x-danger-button wire:click="deleteSelected" class="uppercase">Supprimer</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>