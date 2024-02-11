<div>
    <div class="rounded-t bg-indigo-100 mb-0 px-4 py-4">
        <div class="text-center flex justify-between">
            <h6 class="text-gray-800 text-xl font-bold">Nouvel admin</h6>
            <button class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    type="button">
                Settings
            </button>
        </div>
    </div>
    <div class="flex-auto py-6 px-4 lg:px-10">
        <form wire:submit.prevent="register">
            <div>
                <label for="name">Nom</label>
                <input wire:model.lazy="name"
                       type="text"
                       name="name"
                       id="name">
                @error('name')<span>{{$message}}</span>@enderror
            </div>
            <div>
                <label for="email">Email</label>
                <input wire:model.lazy="email"
                       type="text"
                       name="email"
                       id="email">
                @error('email')<span>{{$message}}</span>@enderror
            </div>
            <div>
                <label for="password">Mot de passe</label>
                <input wire:model.lazy="password"
                       type="text"
                       name="password"
                       id="password">
                @error('password')<span>{{$message}}</span>@enderror
            </div>
            <div>
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input wire:model.lazy="passwordConfirmation"
                       type="text"
                       name="passwordConfirmation"
                       id="passwordConfirmation">
                @error('passwordConfirmation')<span>{{$message}}</span>@enderror
            </div>
            <div><input type="submit"
                       value="Valider"></div>
        </form>
    </div>
</div>