<span x-data="{open: false}"
      x-init="window.livewire.find('<?php echo e($_instance->id); ?>').on('notify-saved', () =>  { setTimeout(() => {open= false}, 2500); open= true; })"
      x-show-transition.out.duration.1000ms="open"
      style="display: none;"
      class="text-gray-600">
    Enregistrement mis à jour.
</span><?php /**PATH /home/xhuv4202/boassur/resources/views/components/admin/form/saved-message.blade.php ENDPATH**/ ?>