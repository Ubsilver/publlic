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

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view("auth.register");
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "surname" => ["required", "string", "max:255"],
            "middlename" => ["required", "string", "max:255"],
            "tel" => ["required", "string", "max:10"],
            "mail" => [
                "required",
                "string",
                "lowercase",
                "max:255",
                "unique:" . User::class,
            ],
            "email" => [
                "required",
                "string",
                "lowercase",
                "max:255",
                "unique:" . User::class,
            ],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        $user = User::create([
            "password" => Hash::make($request->password),
            "name" => $request->name,
            "surname" => $request->surname,
            "middlename" => $request->middlename,
            "tel" => $request->tel,
            "email" => $request->email,
            "mail" => $request->mail,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route("reports", absolute: false));
    }
}
