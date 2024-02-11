<div>
    <div class="flex-col space-y-4 p-4">
        <h1 class="text-2xl font-semibold text-gray-800">Contrats</h1>
        <div class="flex justify-between space-x-4">
            <div class="flex w-1/3 justify-start space-x-4">
            </div>
            <div class="flex items-end space-x-4">
                <x-button wire:click="create">Nouveau contrat</x-button>
            </div>
        </div>

        @if($contrats->count() > 0)
        <ul wire:sortable="updateTaskOrder">
            @foreach ($contrats as $contrat)
            <li wire:sortable.item="{{ $contrat->id }}" wire:key="contrat-{{ $contrat->id }}" class="flex items-center mb-2 shadow-sm border border-gray-100">
                <div wire:sortable.handle class="flex flex-0 justify-center items-center w-12 py-2 border-l-0 border-gray-100 cursor-pointer">
                    <x-handle-sort class="w-6" />
                </div>
                <div class="flex flex-1 cursor-pointer">
                    <div class="flex-0 w-40 p-2 bg-indigo-100 text-center text-indigo-600">{{$contrat->quote_id }}</div>
                    <div class="p-2">{{ $contrat->libelle }}</div>
                    <div class="flex items-center p-2">
                        @if(!empty($contrat->url_fiche))
                        <a href="{{env('APP_URL') . '/telecharger/' . $contrat->hashid}}" class="px-2 text-xs border border-indigo-600 text-indigo-600 rounded-full">Télécharger le DIN</a>
                        @endif
                    </div>
                    <div class="flex items-center p-2">
                        @if(!empty($contrat->url_cgv))
                        <a href="{{env('APP_URL') . '/conditions-generales/' . $contrat->hashid}}" class="px-2 text-xs border border-indigo-600 text-indigo-600 rounded-full">Télécharger le CGV</a>
                        @endif
                    </div>
                </div>
                <div class="flex-0 p-2">
                    <x-button wire:click="edit({{$contrat->id}})">Edit</x-button>
                    <x-button wire:click="confirmDelete({{ $contrat->id }})" class="bg-red-600">Supprimer</x-button>
                </div>
            </li>
            @endforeach
        </ul>
        @else
        <x-admin.table.not-found />
        @endif
    </div>

    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <x-dialog-modal wire:model.defer="showEditModal" maxWidth="2xl">
            <x-slot name="title">
                @if ($editing->quote_id)
                Edition de : {{ $editing->quote_id }}
                @else
                Nouveau contrat
                @endif
            </x-slot>
            <x-slot name="content">
                <div class="flex flex-wrap">
                    <div class="w-4/5 flex-0">
                        <x-admin.form.input-group label="Site" for="site_id" :error="$errors->first('editing.site_id')">
                            <x-admin.form.select wire:model.lazy="editing.site_id" id="site_id">
                                <option value="">Sélectionnez un site</option>
                                @foreach ($this->sites as $site)
                                <option value="{{ $site['id'] }}" {{ $site['id'] === $this->editing->site_id ? 'selected' : '' }}>{{ $site['nom'] }}</option>
                                @endforeach
                            </x-admin.form.select>
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="ID Searchquote" for="quote_id" :error="$errors->first('editing.quote_id')">
                            <x-admin.form.input-text wire:model.lazy="editing.quote_id" placeholder="ID Searchquote" type="text" id="quote_id" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Libelle" for="libelle" :error="$errors->first('editing.libelle')">
                            <x-admin.form.input-text wire:model.lazy="editing.libelle" placeholder="Libellé du contrat" type="text" id="libelle" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Description" for="{{$editing->quote_id}}" :error="$errors->first('editing.recap')">
                            <x-admin.form.richtext wire:model.lazy="editing.recap" initial-value="{{$editing->recap}}" id="{{$editing->quote_id}}" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Fichier DIN" for="url_fiche" :error="$errors->first('editing.url_fiche')">
                            <input wire:model="fiche" class="form-file w-full rounded-none cursor-pointer" type="file" />
                            @if(!empty($editing->url_fiche))
                            <div class="mt-3 space-x-4">
                                <a href="{{env('APP_URL') . '/telecharger/' . $editing->hashid}}" class="px-2 text-xs border border-indigo-600 text-indigo-600 rounded-full">Télécharger DIN</a>
                                <a wire:click="deleteFiche" class="px-2 text-xs border cursor-pointer border-red-600 text-red-600 rounded-full">Supprimer DIN</a>
                            </div>
                            @endif
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Fichier CGV" for="url_fiche" :error="$errors->first('editing.url_cgv')">
                            <input wire:model="cgv" class="form-file w-full rounded-none cursor-pointer" type="file" />
                            @if(!empty($editing->url_cgv))
                            <div class="mt-3 space-x-4">
                                <a href="{{env('APP_URL') . '/conditions-generales/' . $editing->hashid}}" class="px-2 text-xs border border-indigo-600 text-indigo-600 rounded-full">Télécharger CGV</a>
                                <a wire:click="deleteCgv" class="px-2 text-xs border cursor-pointer border-red-600 text-red-600 rounded-full">Supprimer CGV</a>
                            </div>
                            @endif
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
            Êtes-vous sûr de vouloir supprimer cet enregistrement ?
        </x-slot>
        <x-slot name="footer">
            <div class="flex items-center justify-end space-x-4">
                <x-secondary-button wire:click="$set('showDeleteModal', false)" class="uppercase">Annuler</x-secondary-button>
                <x-danger-button wire:click="deleteSelected" class="uppercase">Supprimer</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>