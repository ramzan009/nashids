<?php

namespace App\Http\Controllers\Web\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationCreateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('web.registration.registration');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(RegistrationCreateRequest $request)
    {
        $data = $request->validated();

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        if ($user) {
            auth("web")->login($user);
        }

        return redirect(route('index'));
    }

}
