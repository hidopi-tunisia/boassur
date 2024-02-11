<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['initialValue' => '', 'id']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['initialValue' => '', 'id']); ?>
<?php foreach (array_filter((['initialValue' => '', 'id']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<?php
$initialValue = htmlspecialchars_decode($initialValue);
?>
<input id="<?php echo e($id); ?>" value="<?php echo e($initialValue); ?>" type="hidden" />
<div <?php echo e($attributes); ?> x-data @trix-blur="$dispatch('change', $event.target.value)" wire:ignore>
    <trix-editor input="<?php echo e($id); ?>" class="recap"></trix-editor>
</div><?php /**PATH /home/xhuv4202/boassur/resources/views/components/admin/form/richtext.blade.php ENDPATH**/ ?>