<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
'disabled' => false,
'leadingAddOn' => false,
'width'
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
'disabled' => false,
'leadingAddOn' => false,
'width'
]); ?>
<?php foreach (array_filter(([
'disabled' => false,
'leadingAddOn' => false,
'width'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="flex rounded-md shadow-sm <?php echo e($width ?? ''); ?>">
    <?php if($leadingAddOn): ?>
    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 sm:text-sm">
        <?php echo e($leadingAddOn); ?>

    </span>
    <?php endif; ?>

    <input <?php echo e($disabled ? 'disabled' : ''); ?> <?php echo $attributes->merge(['class' => $leadingAddOn ? 'form-input rounded-none rounded-r-md' : 'form-input']); ?> />
</div><?php /**PATH /home/xhuv4202/boassur/resources/views/components/admin/form/input-text.blade.php ENDPATH**/ ?>