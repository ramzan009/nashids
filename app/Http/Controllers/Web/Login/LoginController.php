<?php

namespace App\Http\Controllers\Web\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('web.login.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::query()->where('email', $data['email'])->first();

        if ($user === null) {
            return redirect()->back()->withErrors(['message' => 'Пользователь с таким email не найден ']);
        }

        if (Hash::check($data['password'], $user->password)) {
            auth()->login($user);
            return redirect()->route('index');
        }

        return redirect()->back()->withErrors(['message' => 'Не удалось войти в аккаунт. Пароль не совпадает']);
    }

}
