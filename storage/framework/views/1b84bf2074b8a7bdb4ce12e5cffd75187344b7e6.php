<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['label' => null, 'type' => null]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['label' => null, 'type' => null]); ?>
<?php foreach (array_filter((['label' => null, 'type' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<?php
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
?>
<div <?php echo e($attributes->merge(['class' => 'relative w-full'])); ?>>
    <?php if($label): ?>
    <div class="form-label"><?php echo e($label); ?></div>
    <?php endif; ?>
    <div class="p-2 text-sm rounded <?php echo e($bg); ?>"><?php echo e($slot); ?></div>
</div><?php /**PATH /home/xhuv4202/boassur/resources/views/components/admin/form/item-group.blade.php ENDPATH**/ ?>