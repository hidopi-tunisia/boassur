@props(['label' => null, 'type' => null])
@php
$bg = 'bg-white text-gray-600 border-2 border-gray-100';
if ($type === 'error') {
$bg = 'bg-white border-2 border-red-200 text-red-700';
}
if ($type === 'success') {
$bg = 'bg-white border-2 border-green-200 text-green-700';
}
if ($type === 'yellow') {
$bg = 'bg-white border-2 border-yellow-200 text-yellow-700';
}
@endphp
<div {{ $attributes->merge(['class' => 'relative w-full']) }}>
    @if ($label)
    <div class="form-label">{{ $label }}</div>
    @endif
    <div class="p-2 text-sm rounded {{$bg}}">{{ $slot }}</div>
</div>