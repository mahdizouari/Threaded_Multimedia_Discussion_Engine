<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-slate-900">
            Delete Account
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data or information that you wish to retain before deleting your account.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Delete Account</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900">
                Are you sure you want to delete your account?
            </h2>

            <p class="mt-1 text-sm text-slate-600">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Password" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-0 block w-full max-w-md"
                    placeholder="Password"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex flex-wrap justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancel
                </x-secondary-button>

                <x-danger-button>
                    Delete Account
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
