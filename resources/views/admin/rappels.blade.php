<div>
    <div class="flex-col space-y-4 p-4">
        <h1 class="text-2xl font-semibold text-gray-800">Rappels</h1>
        <div class="flex justify-between space-x-4">
            <div class="flex w-1/3 justify-start space-x-4">
                <x-link-button wire:click="toggleShowFilters" type="button">@if($showFilters)Masquer @else Voir @endif les filtres</x-link-button>
            </div>
            <div class="flex items-end space-x-4">
                <x-admin.table.per-page />
                <x-admin.table.bulk-actions delete="{{ count($selected) === 0 ? false : true }}" export />
            </div>
        </div>

        <div>
            @if($showFilters)
            <div class="w-full rounded shadow-inner relative bg-gray-100 p-4">
                <div class="flex w-full justify-between space-x-4">
                    <x-admin.form.input-group label="Nom" for="filter-nom">
                        <x-admin.form.input-text wire:model.lazy="filters.nom" placeholder="Nom" type="text" id="filter-nom" />
                    </x-admin.form.input-group>
                    <x-admin.form.input-group label="Téléphone" for="filter-telephone">
                        <x-admin.form.input-text wire:model.lazy="filters.telephone" placeholder="Numéro téléphone" type="text" id="filter-telephone" />
                    </x-admin.form.input-group>
                </div>
                <div class="flex justify-end">
                    <x-link-button wire:click="resetFilters" type="button">Réinitialiser les filtres</x-link-button>
                </div>
            </div>
            @endif
        </div>

        <x-admin.table.table>
            <x-slot name="head">
                <x-admin.table.heading class="w-8">
                    <x-checkbox wire:model="selectPage" />
                </x-admin.table.heading>
                <x-admin.table.heading sortable wire:click="sortBy('nom')" :direction="$sorts['nom'] ?? null">
                    Nom
                </x-admin.table.heading>
                <x-admin.table.heading sortable wire:click="sortBy('telephone')" :direction="$sorts['telephone'] ?? null">
                    Téléphone
                </x-admin.table.heading>
                <x-admin.table.heading sortable wire:click="sortBy('date_rappel')" :direction="$sorts['date_rappel'] ?? null">
                    Date
                </x-admin.table.heading>
                <x-admin.table.heading sortable wire:click="sortBy('heure_rappel')" :direction="$sorts['heure_rappel'] ?? null">
                    Heure
                </x-admin.table.heading>
                <x-admin.table.heading class="w-16" />
            </x-slot>
            <x-slot name="body">
                @if($selectPage)
                <x-admin.table.row>
                    <x-admin.table.cell colspan="8" class="bg-gray-100">
                        @unless($selectAll)
                        <div>
                            <span>Vous avez sélectionné <strong>{{count($selected)}}</strong> rappels.
                                Voulez-vous
                                toutes les <strong>{{$rappels->total()}}</strong> rappels ?</span>
                            <x-button wire:click="selectAll" type="button">Sélectionner tout</x-button>
                        </div>
                        @else
                        <span>Vous avez sélectionné toutes les <strong>{{$rappels->total()}}</strong>
                            rappels</span>
                        @endif
                    </x-admin.table.cell>
                </x-admin.table.row>
                @endif

                @forelse($rappels as $rappel)
                <x-admin.table.row wire:loading.class.delay="opacity-40" wire:key="row-{{$rappel->id}}">
                    <x-admin.table.cell>
                        <x-checkbox wire:model="selected" value="{{$rappel->id}}" />
                    </x-admin.table.cell>
                    <x-admin.table.cell>{{$rappel->nom}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$rappel->telephone}}</x-admin.table.cell>
                    <x-admin.table.cell>{{\Carbon\Carbon::parse($rappel->date_rappel)->format('d/m/Y')}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$rappel->heure_rappel}}</x-admin.table.cell>
                </x-admin.table.row>
                @empty
                <x-admin.table.row>
                    <x-admin.table.cell colspan="8">
                        <x-admin.table.not-found />
                    </x-admin.table.cell>
                </x-admin.table.row>
                @endforelse
            </x-slot>
        </x-admin.table.table>

        {{ $rappels->links()}}
    </div>

    <form wire:submit.prevent="save">
        <x-dialog-modal wire:model.defer="showEditModal">
            <x-slot name="title">
                Détails
            </x-slot>
            <x-slot name="content">
                <div class="flex flex-wrap">
                    <div class="flex w-full justify-between space-x-4">
                        <x-admin.form.input-group label="Nom" for="nom" class="w-full" :error="$errors->first('editing.nom')">
                            <x-admin.form.input-text wire:model.lazy="editing.nom" placeholder="Nom" type="text" id="nom" disabled />
                        </x-admin.form.input-group>
                    </div>
                    <div class="flex w-full justify-between space-x-4">
                        <x-admin.form.input-group label="Téléphone" for="telephone" class="w-full" :error="$errors->first('editing.telephone')">
                            <x-admin.form.input-text wire:model.lazy="editing.telephone" placeholder="Numéro de téléphone" type="text" id="telephone" disabled />
                        </x-admin.form.input-group>
                    </div>
                    <div class="flex w-full justify-between space-x-4">
                        <x-admin.form.input-group label="Date de rappel" for="date_rappel" class="w-64" :error="$errors->first('editing.date_rappel')">
                            <x-admin.form.input-text wire:model.lazy="editing.date_rappel" placeholder="Date de rappel" type="text" id="date_rappel" disabled />
                        </x-admin.form.input-group>
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <div class="flex items-center justify-end space-x-4">
                    <x-secondary-button wire:click="$set('showEditModal', false)">Annuler</x-secondary-button>
                </div>
            </x-slot>
        </x-dialog-modal>
    </form>

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