<span x-data="{open: false}"
      x-init="@this.on('notify-saved', () =>  { setTimeout(() => {open= false}, 2500); open= true; })"
      x-show-transition.out.duration.1000ms="open"
      style="display: none;"
      class="text-gray-600">
    Enregistrement mis à jour.
</span>