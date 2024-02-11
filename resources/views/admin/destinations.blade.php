<div>
    <div class="flex-col space-y-4 p-4">
        <h1 class="text-2xl font-semibold text-gray-800">Destinations</h1>
        <div class="flex justify-between space-x-4">
            <div class="flex w-1/3 justify-start space-x-4">
                <x-link-button wire:click="toggleShowFilters"
                               type="button">@if($showFilters)Masquer @else Voir @endif les filtres</x-link-button>
            </div>
            <div class="flex items-end space-x-4">
                <x-admin.table.per-page />
                <x-admin.table.bulk-actions delete="{{ count($selected) === 0 ? false : true }}"
                                            export />
                {{-- <x-button wire:click="create">Nouvelle destination</x-button> --}}
            </div>
        </div>

        <div>
            @if($showFilters)
            <div class="rounded shadow-inner flex flex-wrap relative bg-gray-100 p-4">
                <x-admin.form.input-group label="Nom"
                                          for="filter-nom">
                    <x-admin.form.input-text wire:model.lazy="filters.nom"
                                             placeholder="Nom"
                                             type="text"
                                             id="filter-nom" />
                </x-admin.form.input-group>

                <x-admin.form.input-group label="Code alpha 2"
                                          for="filter-alpha2">
                    <x-admin.form.input-text wire:model.lazy="filters.alpha2"
                                             placeholder="Code alpha 2"
                                             type="text"
                                             id="filter-alpha2" />
                </x-admin.form.input-group>
                <x-link-button wire:click="resetFilters"
                               type="button">Réinitialiser les filtres</x-link-button>
            </div>
            @endif
        </div>

        <x-admin.table.table>
            <x-slot name="head">
                <x-admin.table.heading class="w-8">
                    <x-checkbox wire:model="selectPage" />
                </x-admin.table.heading>
                <x-admin.table.heading class="w-24"
                                       sortable
                                       wire:click="sortBy('alpha2')"
                                       :direction="$sorts['alpha2'] ?? null">
                    Alpha 2
                </x-admin.table.heading>
                <x-admin.table.heading class="w-24">
                    Article
                </x-admin.table.heading>
                <x-admin.table.heading sortable
                                       wire:click="sortBy('nom')"
                                       :direction="$sorts['nom'] ?? null">
                    Nom
                </x-admin.table.heading>
                {{-- <x-admin.table.heading class="w-24" /> --}}
            </x-slot>
            <x-slot name="body">
                @if($selectPage)
                <x-admin.table.row>
                    <x-admin.table.cell colspan="6"
                                        class="bg-gray-100">
                        @unless($selectAll)
                        <div>
                            <span>Vous avez sélectionné <strong>{{count($selected)}}</strong> destinations.
                                Voulez-vous
                                toutes les <strong>{{$destinations->total()}}</strong> destinations ?</span>
                            <x-button wire:click="selectAll"
                                      type="button">Sélectionner tout</x-button>
                        </div>
                        @else
                        <span>Vous avez sélectionné toutes les <strong>{{$destinations->total()}}</strong>
                            destinations</span>
                        @endif
                    </x-admin.table.cell>
                </x-admin.table.row>
                @endif

                @forelse($destinations as $destination)
                <x-admin.table.row wire:loading.class.delay="opacity-40"
                                   wire:key="row-{{$destination->id}}">
                    <x-admin.table.cell>
                        <x-checkbox wire:model="selected"
                                    value="{{$destination->id}}" />
                    </x-admin.table.cell>
                    <x-admin.table.cell>{{$destination->alpha2}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$destination->article}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$destination->nom}}</x-admin.table.cell>
                    {{-- <x-admin.table.cell>
                        <x-button wire:click="edit({{$destination->id}})">Edit</x-button>
                    </x-admin.table.cell> --}}
                </x-admin.table.row>
                @empty
                <x-admin.table.row>
                    <x-admin.table.cell colspan="6">
                        <x-admin.table.not-found />
                    </x-admin.table.cell>
                </x-admin.table.row>
                @endforelse
            </x-slot>
        </x-admin.table.table>

        {{ $destinations->links()}}
    </div>

    <form wire:submit.prevent="save">
        <x-dialog-modal wire:model.defer="showEditModal">
            <x-slot name="title">
                @if ($editing->nom)
                Edition de : {{ $editing->nom }}
                @else
                Nouvelle destination
                @endif
            </x-slot>
            <x-slot name="content">
                <div class="flex flex-wrap">
                    <div class="flex w-full justify-between space-x-4">
                        <div class="flex-1">
                            <x-admin.form.input-group label="Article"
                                                      for="article">
                                <x-admin.form.input-text wire:model.lazy="editing.article"
                                                         placeholder="Article"
                                                         type="text"
                                                         id="article" />
                            </x-admin.form.input-group>
                        </div>
                        <div class="w-4/5 flex-0">
                            <x-admin.form.input-group label="Nom"
                                                      for="nom"
                                                      :error="$errors->first('editing.nom')">
                                <x-admin.form.input-text wire:model.lazy="editing.nom"
                                                         placeholder="Nom"
                                                         type="text"
                                                         id="nom" />
                            </x-admin.form.input-group>
                        </div>
                    </div>
                    <div class="flex w-full justify-between space-x-4">
                        <x-admin.form.input-group label="Alpha 2"
                                                  for="alpha2"
                                                  :error="$errors->first('editing.alpha2')">
                            <x-admin.form.input-text wire:model.lazy="editing.alpha2"
                                                     placeholder="Code alpha 2"
                                                     type="text"
                                                     id="alpha2" />
                        </x-admin.form.input-group>
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <div class="flex items-center justify-end space-x-4">
                    <x-secondary-button wire:click="$set('showEditModal', false)">Annuler</x-secondary-button>
                    <x-button type="submit">Enregistrer</x-button>
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
                <x-secondary-button wire:click="$set('showDeleteModal', false)"
                                    class="uppercase">Annuler</x-secondary-button>
                {{-- <x-danger-button wire:click="deleteSelected"
                                 class="uppercase">Supprimer</x-button> --}}
            </div>
        </x-slot>
    </x-dialog-modal>
</div>