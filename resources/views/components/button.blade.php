<button
        {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-action']) }}>
    {{ $slot }}
</button>