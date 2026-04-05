<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-slate-900">
            Supprimer le compte
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            La suppression est définitive : vos données associées à ce compte seront effacées. Sauvegardez ce dont vous avez besoin avant de continuer.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Supprimer mon compte</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900">
                Confirmer la suppression du compte ?
            </h2>

            <p class="mt-1 text-sm text-slate-600">
                Cette action est irréversible. Saisissez votre mot de passe pour confirmer.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Mot de passe" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-0 block w-full max-w-md"
                    placeholder="Mot de passe"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex flex-wrap justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>

                <x-danger-button>
                    Supprimer définitivement
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
