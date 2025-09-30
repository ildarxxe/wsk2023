<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserLogin(Request $request): Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
    {
        $data = $request->validate([
            "name" => "required|string",
            "password" => "required|string"
        ]);

        $user = User::query()->where("name", $data["name"])->first();
        if (!$user || !Hash::check($data["password"], $user->password)) {
            return back()->withInput()->withErrors([
                'name' => 'Неверное имя пользователя или пароль.',
            ]);
        }

        Auth::login($user);

        return redirect("/workspace")->with(["user" => $user]);
    }
}
