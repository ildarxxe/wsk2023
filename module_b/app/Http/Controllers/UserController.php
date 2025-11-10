<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function Login(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            "name" => "required|string|min:1",
            "password" => "required|string"
        ]);

        $user = User::query()->where("name", $request->input("name"))->first();
        if (!$user) {
            return redirect()->back()->with(["error" => "Неверные учетные данные."]);
        }

        if (Hash::check($request->input("password"), $user->password)) {
            Auth::login($user);
            return redirect("/workspaces")->with(["message" => "Успешный вход!"]);
        }

        return redirect()->back()->with("error", "Неверные учетные данные.");
    }
}
