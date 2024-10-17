<?php

namespace App\Http\Controllers\Web\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

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

        if(auth()->attempt($data )) {
            return  redirect()->route('index');
        }

        return redirect()->route('index');
    }

}
