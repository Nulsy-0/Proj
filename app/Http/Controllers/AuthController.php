<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        $user = $request->safe()->all();

        if (!Auth::attempt($user) || Auth::user()->state == "disabled") {
            Auth::logout();

            return back()
                ->withErrors([
                    'password' => "The credentials aren't right!",
                ])->withInput(request()->all());
        }

        $request->session()->regenerate();

        toast()->success('Welcome back!');
        return to_route('home');
    }

    public function loginView()
    {
        return view("auth.login");
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->regenerate();

        return to_route('loginView');
    }

    public function registerView()
    {
        if (!User::query()->where('state', 'admin')->exists()) {
            return view('auth.register');
        }

        return view('errors.404');
    }

    public function register(User $user, AuthRequest $request)
    {
        if (!User::query()->where('state', 'admin')->exists()) {
            $request->session()->regenerate();
        }

        $user = User::create([
            'name' => $request->name,
            'password' => $request->password,
            'state' => $request->state,
            'boards' => [],
        ]);

        if (!Auth::user() && $user->state != "disabled") {
            Auth::login($user);
            toast()->success('The first time :)');
            return to_route('home');
        }
    
        toast()->success('User created!');
        return to_route('admin.index');
    }
}
