@props([ 'disabled' => false, ])

<select {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'mt-1 block flex-1 py-2 pl-3 border border-gray-300 bg-white rounded shadow-sm text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:leading-5']) }}>
    {{ $slot }}
</select>