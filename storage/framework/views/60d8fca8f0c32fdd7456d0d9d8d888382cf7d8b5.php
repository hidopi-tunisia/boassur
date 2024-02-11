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

<select <?php echo e($disabled ? 'disabled' : ''); ?>

        <?php echo e($attributes->merge(['class' => 'mt-1 block flex-1 py-2 pl-3 border border-gray-300 bg-white rounded shadow-sm text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:leading-5'])); ?>>
    <?php echo e($slot); ?>

</select><?php /**PATH /home/xhuv4202/boassur/resources/views/components/admin/form/select.blade.php ENDPATH**/ ?>