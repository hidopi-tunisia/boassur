@props([
'disabled' => false,
'leadingAddOn' => false,
'width'
])

<div class="flex rounded-md shadow-sm {{ $width ?? '' }}">
    @if($leadingAddOn)
    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 sm:text-sm">
        {{$leadingAddOn}}
    </span>
    @endif

    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $leadingAddOn ? 'form-input rounded-none rounded-r-md' : 'form-input']) !!} />
</div>