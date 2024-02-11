@props([
'label',
'for',
'error' => false,
'helpText' => false,
])

<div {{ $attributes->merge(['class' => 'relative w-full mb-4']) }}>
    <label class="form-label"
           for="{{ $for }}">{{ $label }}</label>
    {{ $slot }}
    @if($error)
    <div class="form-error">{{ $error }}</div>
    @endif
    @if($helpText)
    <div class="mt-2 text-sm text-gray-500">{{ $helpText }}</div>
    @endif
</div>