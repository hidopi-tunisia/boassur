@props(['initialValue' => '', 'id'])
@php
$initialValue = htmlspecialchars_decode($initialValue);
@endphp
<input id="{{ $id }}" value="{{ $initialValue }}" type="hidden" />
<div {{ $attributes }} x-data @trix-blur="$dispatch('change', $event.target.value)" wire:ignore>
    <trix-editor input="{{ $id }}" class="recap"></trix-editor>
</div>