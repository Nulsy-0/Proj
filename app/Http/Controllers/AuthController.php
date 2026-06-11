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
                    'password' => "The credencials aren't rigth!",
                ])->withInput(request()->all());
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

        $user = User::create([
            'name' => $request->name,
            'password' => $request->password,
            'state' => $request->state,
            'boards' => [],
        ]);

        if(!Auth::user() || Auth::user()->settings->type == "disabled") {
            Auth::login($user);
            return redirect()->intended('/')->with('success', 'The first time :)');
        }
    
        return to_route('admin.index')->with('success','User created!');
    }
}
