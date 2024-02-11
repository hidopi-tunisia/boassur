<div>
    <div class="flex-col space-y-4 p-4">
        <h1 class="text-2xl font-semibold text-gray-800">Sites</h1>
        <div class="flex justify-between space-x-4">
            <div class="flex w-1/3 justify-start space-x-4">
                <x-link-button wire:click="toggleShowFilters" type="button">@if($showFilters)Masquer @else Voir @endif les filtres</x-link-button>
            </div>
            <div class="flex items-end space-x-4">
                <x-admin.table.per-page />
                <x-admin.table.bulk-actions delete="{{ count($selected) === 0 ? false : true }}" export />
                <x-button wire:click="create">Nouveau site</x-button>
            </div>
        </div>

        <div>
            @if($showFilters)
            <div class="rounded shadow-inner flex flex-wrap relative bg-gray-100 p-4">
                <x-admin.form.input-group label="Nom" for="filter-nom">
                    <x-admin.form.input-text wire:model.lazy="filters.nom" placeholder="Nom" type="text" id="filter-nom" />
                </x-admin.form.input-group>
                <x-link-button wire:click="resetFilters" type="button">Réinitialiser les filtres</x-link-button>
            </div>
            @endif
        </div>

        <x-admin.table.table>
            <x-slot name="head">
                <x-admin.table.heading class="w-8">
                    <x-checkbox wire:model="selectPage" />
                </x-admin.table.heading>
                <x-admin.table.heading class="w-12" sortable wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                    ID
                </x-admin.table.heading>
                <x-admin.table.heading sortable wire:click="sortBy('nom')" :direction="$sorts['nom'] ?? null">
                    Nom
                </x-admin.table.heading>
                <x-admin.table.heading>Url</x-admin.table.heading>
                <x-admin.table.heading>Expéditeur mail</x-admin.table.heading>
                <x-admin.table.heading class="w-24" />
            </x-slot>
            <x-slot name="body">
                @if($selectPage)
                <x-admin.table.row>
                    <x-admin.table.cell colspan="6" class="bg-gray-100">
                        @unless($selectAll)
                        <div>
                            <span>Vous avez sélectionné <strong>{{count($selected)}}</strong> sites.
                                Voulez-vous
                                tous les <strong>{{$sites->total()}}</strong> sites ?</span>
                            <x-button wire:click="selectAll" type="button">Sélectionner tout</x-button>
                        </div>
                        @else
                        <span>Vous avez sélectionné tous les <strong>{{$sites->total()}}</strong>
                            sites</span>
                        @endif
                    </x-admin.table.cell>
                </x-admin.table.row>
                @endif

                @forelse($sites as $site)
                <x-admin.table.row wire:loading.class.delay="opacity-40" wire:key="row-{{$site->id}}">
                    <x-admin.table.cell>
                        <x-checkbox wire:model="selected" value="{{$site->id}}" />
                    </x-admin.table.cell>
                    <x-admin.table.cell>{{$site->id}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$site->nom}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$site->url}}</x-admin.table.cell>
                    <x-admin.table.cell>{{$site->sender}} <span class="text-indigo-600">&lt;{{$site->email}}&gt;</span></x-admin.table.cell>
                    <x-admin.table.cell>
                        <x-button wire:click="edit({{$site->id}})">Edit</x-button>
                    </x-admin.table.cell>
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

        {{ $sites->links()}}
    </div>

    <form wire:submit.prevent="save">
        <x-dialog-modal wire:model.defer="showEditModal">
            <x-slot name="title">
                @if ($editing->nom)
                Edition de : {{ $editing->nom }}
                @else
                Nouveau site
                @endif
            </x-slot>
            <x-slot name="content">
                <div class="flex flex-wrap">
                    <div class="w-4/5 flex-0">
                        <x-admin.form.input-group label="Nom" for="nom" :error="$errors->first('editing.nom')">
                            <x-admin.form.input-text wire:model.lazy="editing.nom" placeholder="Nom" type="text" id="nom" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Url" for="url" :error="$errors->first('editing.url')">
                            <x-admin.form.input-text wire:model.lazy="editing.url" placeholder="Url" type="text" id="url" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Mail admin à notifier" for="notifier" :error="$errors->first('editing.notifier')">
                            <x-admin.form.input-text wire:model.lazy="editing.notifier" placeholder="Email admin à notifier en cas d'erreur" type="text" id="notifier" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Nom expéditeur mail confirmation" for="sender" :error="$errors->first('editing.sender')">
                            <x-admin.form.input-text wire:model.lazy="editing.sender" placeholder="Nom expéditeur mail confirmation" type="text" id="sender" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Adresse expéditeur mail confirmation" for="email" :error="$errors->first('editing.email')">
                            <x-admin.form.input-text wire:model.lazy="editing.email" placeholder="Adresse expéditeur mail confirmation" type="text" id="email" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Objet du mail de confirmation" for="objet_email" :error="$errors->first('editing.objet_email')">
                            <x-admin.form.input-text wire:model.lazy="editing.objet_email" placeholder="Objet du mail de confirmation" type="text" id="objet_email" />
                        </x-admin.form.input-group>
                        <x-admin.form.input-group label="Contenu du mail de confirmation" for="site-{{$editing->id}}" :error="$errors->first('editing.contenu_email')">
                            <x-admin.form.richtext wire:model.lazy="editing.contenu_email" initial-value="{{$editing->contenu_email}}" id="site-{{$editing->id}}" />
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
                <x-secondary-button wire:click="$set('showDeleteModal', false)" class="uppercase">Annuler</x-secondary-button>
                <x-danger-button wire:click="deleteSelected" class="uppercase">Supprimer</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>