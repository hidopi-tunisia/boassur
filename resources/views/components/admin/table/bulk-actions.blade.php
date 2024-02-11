@props(['delete' => true, 'export' => false])

<x-dropdown>
    <x-slot name="trigger">
        <div>
            <button type="button" class="inline-flex justify-center w-full border border-gray-300 shadow-sm px-4 py-2 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="options-menu" aria-expanded="true" aria-haspopup="true">
                Actions groupées
                <x-icons.chevron-down class="-mr-1 ml-2 h-4 w-4 text-gray-500" />
            </button>
        </div>
    </x-slot>
    <x-slot name="content">
        <ul>
            @if ($export)
            <li class="py-2 px-3">
                <button wire:click="exportSelected" type="button" class="flex items-center space-x-2 text-xs">
                    <x-icons.download class="text-gray-400 h-4 w-4" /> <span>Exporter</span>
                </button>
            </li>
            @endif
            <li class="py-2 px-3">
                <button wire:click="$set('showDeleteModal', true)" {{$delete ? '' : 'disabled'}} type="button" class="flex items-center space-x-2 text-xs disabled:opacity-25">
                    <x-icons.trash class="text-gray-400 h-4 w-4" /> <span>Supprimer</span>
                </button>
            </li>
        </ul>
    </x-slot>
</x-dropdown>