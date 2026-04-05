<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $categories = \App\Models\Category::orderBy('label')->get();
        return view('auth.register', compact('categories'));
    }

    /**
     * Handle an incoming registration request.ss
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'interests' => ['nullable', 'string'],
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'role' => 'user', 
                ]);

                $profile = $user->profile()->create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'interests' => $request->interests ? array_filter(explode(',', $request->interests)) : [],
                ]);

                // Sync interests to pivot table for the "Interesting" navbar/feed logic
                if ($request->interests) {
                    $interestIds = array_filter(explode(',', $request->interests));
                    $user->interests()->sync($interestIds);
                }

                event(new Registered($user));
                Auth::login($user);

                return redirect(route('home', absolute: false));
            });
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Échec de la sauvegarde : ' . $e->getMessage()]);
        }
    }
}
