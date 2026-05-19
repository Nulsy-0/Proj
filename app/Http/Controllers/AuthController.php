<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(User $user, AuthRequest $request)
    {
        $user = $request->safe()->all();

        if (! Auth::attempt($user)) {
            return back()
                ->withErrors([
                    'password' => "The credencials aren't rigth!",
                ])
                ->withInput();
        }

        $request->session()->regenerate();

        return to_route('home')->with('success', 'Welcome back!');
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

    public function registerView(Request $request)
    {
        return view('auth.register');
    }

    public function register(User $user, AuthRequest $request)
    {
        $request->session()->regenerate();

        $request->safe()->all();

        $tmp = [
            'type' => $request->type,
            'boards' => []
        ];

        $user = User::create([
            'name' => $request->name,
            'password' => $request->password,
            'settings' => $tmp
        ]);

        Auth::login($user);

        return redirect()->intended('/')->with('success', 'The first time :)');
    }
}
