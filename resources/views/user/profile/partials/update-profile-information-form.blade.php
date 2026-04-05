<section>
    <header>
        <h2 class="text-lg font-bold text-slate-900">
            Informations du profil
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            Mettez à jour le nom d’utilisateur et l’adresse e-mail de votre compte.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nom d'utilisateur" />
            <x-text-input id="name" name="name" type="text" class="mt-0 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" name="email" type="email" class="mt-0 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-slate-700">
                        Votre adresse e-mail n’est pas vérifiée.

                        <button form="send-verification" class="rounded font-medium text-forum-300 underline-offset-2 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-forum-400">
                            Renvoyer l’e-mail de vérification
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-emerald-300">
                            Un nouveau lien a été envoyé à votre adresse e-mail.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <x-primary-button>Enregistrer</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-400"
                >Modifications enregistrées.</p>
            @endif
        </div>
    </form>
</section>
