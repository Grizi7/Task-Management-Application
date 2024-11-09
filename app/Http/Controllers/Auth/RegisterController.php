<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;


class RegisterController extends Controller
{

    public function showRegistrationForm()
    {
        return view('auth.register', [
            'title' => 'Register'
        ]);
    }
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();
        
        $user = User::create($data);
        
        auth()->login($user);
        
    }
}
