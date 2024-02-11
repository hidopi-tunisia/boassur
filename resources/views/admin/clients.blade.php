<div>
    <div class="flex-col space-y-4 p-4">
        <h1 class="text-2xl font-semibold text-gray-800">Clients</h1>
        <div class="flex justify-between space-x-4">
            <div class="flex w-1/3 justify-start space-x-4">
                <x-link-button wire:click="toggleShowFilters"
                               type="button">@if($showFilters)Masquer @else Voir @endif les filtres</x-link-button>
            </div>
            <div class="flex items-end space-x-4">
                <x-admin.table.per-page />
                <x-admin.table.bulk-actions delete="{{ count($selected) === 0 ? false : true }}"
                                            export />
                <x-button wire:click="create">Nouveau client</x-button>
            </div>
        </div>

        <div>
            @if($showFilters)
            <div class="w-full rounded shadow-inner relative bg-gray-100 p-4">
                <div class="flex w-full justify-between space-x-4">
                    <x-admin.form.input-group label="Nom"
                                              for="filter-nom">
                        <x-admin.form.input-text wire:model.lazy="filters.nom"
                                                 placeholder="Nom"
                                                 type="text"
                                                 id="filter-nom" />
                    </x-admin.form.input-group>
                    <x-admin.form.input-group label="Email"
                                              for="filter-email">
                        <x-admin.form.input-text wire:model.lazy="filters.email"
                                                 placeholder="Code alpha 2"
                                                 type="text"
                                                 id="filter-email" />
                    </x-admin.form.input-group>
                    <x-admin.form.input-group label="Télephone"
                                              for="filter-telephone">
                        <x-admin.form.input-text wire:model.lazy="filters.telephone"
                                                 placeholder="Téléphone"
                                                 type="text"
                                                 id="filter-telephone" />
                    </x-admin.form.input-group>
                    <x-admin.form.input-group label="Adresse"
                                              for="filter-adresse">
                        <x-admin.form.input-text wire:model.lazy="filters.adresse"
                                                 placeholder="Adresse"
                                                 type="text"
                                                 id="filter-adresse" />
                    </x-admin.form.input-group>
                </div>
                <div class="flex justify-end">
                    <x-link-button wire:click="resetFilters"
                                   type="button">Réinitialiser les filtres</x-link-button>
                </div>
            </div>
            @endif
        </div>

        <x-admin.table.table>
            <x-slot name="head">
                <x-admin.table.heading class="w-8">
                    <x-checkbox wire:model="selectPage" />
                </x-admin.table.heading>
                <x-admin.table.heading sortable
                                       wire:click="sortBy('name')"
                                       :direction="$sorts['name'] ?? null">
                    Nom
                </x-admin.table.heading>
                <x-admin.table.heading sortable
                                       wire:click="sortBy('email')"
                                       :direction="$sorts['email'] ?? null">
                    Email
                </x-admin.table.heading>
                <x-admin.table.heading sortable
                                       wire:click="sortBy('telephone')"
                                       :direction="$sorts['telephone'] ?? null">
                    Telephone
                </x-admin.table.heading>
                <x-admin.table.heading sortable
                                       wire:click="sortBy('cp')"
                                       class="w-12"
                                       :direction="$sorts['cp'] ?? null">
                    CP
                </x-admin.table.heading>
                <x-admin.table.heading sortable
                                       wire:click="sortBy('adresse')"
                                       :direction="$sorts['adresse'] ?? null">
                    Ville
                </x-admin.table.heading>
                <x-admin.table.heading>
                    Adresse
                </x-admin.table.heading>
                <x-admin.table.heading class="w-16" />
            </x-slot>
            <x-slot name="body">
                @if($selectPage)
                <x-admin.table.row>
                    <x-admin.table.cell colspan="8"
                                        class="bg-gray-100">
                        @unless($selectAll)
                        <div>
                            <span>Vous avez sélectionné <strong>{{count($selected)}}</strong> clients.
                                Voulez-vous
                                toutes les <strong>{{$clients->total()}}</strong> clients ?</span>
                            <x-button wire:click="selectAll"
                                      type="button">Sélectionner tout</x-button>
                        </div>
                        @else
                        <span>Vous avez sélectionné toutes les <strong>{{$clients->total()}}</strong>
                            clients</span>
                        @endif
                    </x-admin.table.cell>
                </x-admin.table.row>
                @endif

                @forelse($clients as $client)
                <x-admin.table.row wire:loading.class.delay="opacity-40"
                                   wire:key="row-{{$client->id}}">
                    <x-admin.table.cell>
                        <x-checkbox wire:model="selected"
                                    value="{{$client->id}}" />
                    </x-admin.table.cell>
                    <x-admin.table.cell>{{$client->name . ' ' . $client->prenom}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$client->email}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$client->telephone}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$client->cp}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$client->ville}}</x-admin.table.cell>
                    <x-admin.table.cell class="text-xs">{{$client->adresse}}<br />{{$client->adresse2}}
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        <x-button wire:click="edit({{$client->id}})">Edit</x-button>
                    </x-admin.table.cell>
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

        {{ $clients->links()}}
    </div>

    <form wire:submit.prevent="save">
        <x-dialog-modal wire:model.defer="showEditModal">
            <x-slot name="title">
                @if ($editing->nom)
                Edition du client {{ trim($editing->prenom . ' ' . $editing->name) }}
                @else
                Nouveau client
                @endif
            </x-slot>
            <x-slot name="content">
                <div class="flex flex-wrap">
                    <div class="flex w-full justify-between space-x-4">
                        <x-admin.form.input-group label="Civilité"
                                                  for="civilite"
                                                  :error="$errors->first('editing.civilite')">
                            <x-admin.form.select wire:model.lazy="editing.civilite"
                                                 class="w-32"
                                                 id="civilite">
                                <option value="">Civilité</option>
                                <option value="0">Monsieur</option>
                                <option value="1">Madame</option>
                            </x-admin.form.select>
                        </x-admin.form.input-group>
                    </div>
                    <div class="flex w-full justify-between space-x-4">
                        <x-admin.form.input-group label="Nom"
                                                  for="name"
                                                  :error="$errors->first('editing.name')">
                            <x-admin.form.input-text wire:model.lazy="editing.name"
                                                     placeholder="Nom"
                                                     type="text"
                                                     id="name" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Prénom"
                                                  for="prenom"
                                                  :error="$errors->first('editing.prenom')">
                            <x-admin.form.input-text wire:model.lazy="editing.prenom"
                                                     placeholder="Prénom"
                                                     type="text"
                                                     id="prenom" />
                        </x-admin.form.input-group>
                    </div>
                    <div class="flex w-full justify-between space-x-4">
                        <x-admin.form.input-group label="Email"
                                                  for="email"
                                                  :error="$errors->first('editing.email')">
                            <x-admin.form.input-text wire:model.lazy="editing.email"
                                                     placeholder="Email"
                                                     type="email"
                                                     id="email" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Téléphone"
                                                  for="telephone"
                                                  :error="$errors->first('editing.telephone')">
                            <x-admin.form.input-text wire:model.lazy="editing.telephone"
                                                     placeholder="Téléphone"
                                                     type="text"
                                                     id="telephone" />
                        </x-admin.form.input-group>
                    </div>
                    <div class="flex w-full justify-between space-x-4">
                        <x-admin.form.input-group label="Date de naissance"
                                                  for="anniversaire"
                                                  class="w-64"
                                                  :error="$errors->first('editing.anniversaire')">
                            <x-admin.form.datepicker wire:model="editing.anniversaire"
                                                     type="text"
                                                     id="anniversaire" />
                        </x-admin.form.input-group>
                    </div>
                    <x-admin.form.input-group label="Adresse"
                                              for="adresse"
                                              :error="$errors->first('editing.adresse')">
                        <x-admin.form.input-text wire:model.lazy="editing.adresse"
                                                 placeholder="Adresse"
                                                 type="text"
                                                 id="adresse" />
                    </x-admin.form.input-group>
                    <x-admin.form.input-group label="Adresse 2"
                                              for="adresse2">
                        <x-admin.form.input-text wire:model.lazy="editing.adresse2"
                                                 placeholder="Adresse 2"
                                                 type="text"
                                                 id="adresse2" />
                    </x-admin.form.input-group>
                    <div class="flex w-full justify-between space-x-4">
                        <x-admin.form.input-group label="Code postal"
                                                  for="cp"
                                                  class="w-64"
                                                  :error="$errors->first('editing.cp')">
                            <x-admin.form.input-text wire:model.lazy="editing.cp"
                                                     placeholder="Code postal"
                                                     type="text"
                                                     id="cp" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Ville"
                                                  for="ville"
                                                  :error="$errors->first('editing.ville')">
                            <x-admin.form.input-text wire:model.lazy="editing.ville"
                                                     placeholder="Ville"
                                                     type="text"
                                                     id="ville" />
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
                <x-danger-button wire:click="deleteSelected"
                                 class="uppercase">Supprimer</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>