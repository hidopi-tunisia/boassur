<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
'label',
'for',
'error' => false,
'helpText' => false,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
'label',
'for',
'error' => false,
'helpText' => false,
]); ?>
<?php foreach (array_filter(([
'label',
'for',
'error' => false,
'helpText' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'relative w-full mb-4'])); ?>>
    <label class="form-label"
           for="<?php echo e($for); ?>"><?php echo e($label); ?></label>
    <?php echo e($slot); ?>

    <?php if($error): ?>
    <div class="form-error"><?php echo e($error); ?></div>
    <?php endif; ?>
    <?php if($helpText): ?>
    <div class="mt-2 text-sm text-gray-500"><?php echo e($helpText); ?></div>
    <?php endif; ?>
</div><?php /**PATH /home/xhuv4202/boassur/resources/views/components/admin/form/input-group.blade.php ENDPATH**/ ?>