<div>
    <div class="rounded-t bg-indigo-100 mb-0 px-4 py-4">
        <div class="text-center flex justify-between">
            <h6 class="text-gray-800 text-xl font-bold">{{ $title }}</h6>
            <a href="#"
               class="py-2 px-4 text-xs font-bold text-white rounded shadow outline-none cursor-pointer bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-offset-2 focus:ring-indigo-400"
               type="button">
                Retour à la liste
            </a>
        </div>
    </div>
    <div class="flex-auto py-6 px-4 lg:px-10">
        <form wire:submit.prevent="update">
            <div class="flex flex-wrap">
                <x-admin.form.input-group label="Nom"
                                          for="name"
                                          :error="$errors->first('name')">
                    <x-admin.form.input-text wire:model.lazy="name"
                                             type="text"
                                             id="name" />
                </x-admin.form.input-group>
                <x-admin.form.input-group label="Email"
                                          for="email"
                                          :error="$errors->first('email')">
                    <x-admin.form.input-text wire:model.lazy="email"
                                             type="text"
                                             id="email" />
                </x-admin.form.input-group>
                <x-admin.form.input-group label="Mot de passe"
                                          for="password"
                                          :error="$errors->first('password')">
                    <x-admin.form.input-text wire:model.lazy="password"
                                             type="password"
                                             id="password" />
                </x-admin.form.input-group>
                <x-admin.form.input-group label="Confirmer le mot de passe"
                                          for="passwordConfirmation">
                    <x-admin.form.input-text wire:model.lazy="passwordConfirmation"
                                             type="password"
                                             id="passwordConfirmation" />
                </x-admin.form.input-group>
            </div>
            <div class="w-full flex justify-end items-center space-x-3">
                <span>
                    <x-admin.form.saved-message />
                </span>
                <input type="submit"
                       class="form-submit"
                       value="Valider">
            </div>
        </form>
    </div>
</div>