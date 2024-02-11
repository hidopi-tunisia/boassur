<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([ 'disabled' => false, ]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([ 'disabled' => false, ]); ?>
<?php foreach (array_filter(([ 'disabled' => false, ]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

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
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.calendar','data' => ['class' => 'w-4 h-4 text-gray-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icons.calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-gray-400']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </span>

    <input x-ref="input"
           <?php echo e($attributes); ?>

           <?php echo e($disabled ? 'disabled' : ''); ?>

           class="form-input rounded-none rounded-r-md" />
</div>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
<?php $__env->stopPush(); ?><?php /**PATH /home/xhuv4202/boassur/resources/views/components/admin/form/datepicker.blade.php ENDPATH**/ ?>