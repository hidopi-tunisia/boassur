@props([ 'disabled' => false, ])

<div x-data
     x-init="
        new Pikaday({ 
            field: $refs.input,
            format: 'DD/MM/YYYY',
            toString(date) {
                return window.formatPikadayDate(date);
            },
            i18n: window.pikadayTranslations,
            firstDay: 1,
            defaultDate: window.pikadayMaxDate,
            maxDate: window.pikadayMaxDate,
        })
    "
     @change="$dispatch('input', $event.target.value)"
     class="relative flex rounded-md shadow-sm">
    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 sm:text-sm">
        <x-icons.calendar class="w-4 h-4 text-gray-400" />
    </span>

    <input x-ref="input"
           {{ $attributes }}
           {{ $disabled ? 'disabled' : '' }}
           class="form-input rounded-none rounded-r-md" />
</div>

@push('styles')
<link rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endpush