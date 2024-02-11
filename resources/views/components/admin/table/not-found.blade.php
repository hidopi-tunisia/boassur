@props(['message' => 'Aucun enregistrement trouvé.'])

<div {{ $attributes->merge(['class' => 'flex items-center justify-center space-x-2 text-gray-700 text-lg py-10']) }}>
    <x-icons.archive class="text-gray-300 w-7 h-7" />
    <span>{{ $message }}</span>
</div>