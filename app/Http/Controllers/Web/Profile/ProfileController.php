<?php

namespace App\Http\Controllers\Web\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return view('web.Profile.profile', compact('user'));
    }


    public function profileChange()
    {
        $user = Auth::user();
        return view('web.chang.ChangProfile', compact('user'));

    }

    public function profileUpdate(UpdateProfileRequest $request)
    {
        $data = $request->validated();

        $user = Auth::user();

        if (empty($data['old_password'])) {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email']
            ]);

            return redirect()->back()->with('success', 'Данные успешно изменены!');
        }

        if (!Hash::check($data['old_password'], $user->password)) {
            return redirect()->back()->withErrors(['error' => 'Старый пароль не правильный!']);
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        
        return redirect()->back()->with('success', 'Данные и пароль успешно изменены!');
    }

}
