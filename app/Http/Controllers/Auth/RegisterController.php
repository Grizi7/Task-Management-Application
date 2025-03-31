<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\VerficationCodeEmail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
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

        $user->email_verification_token = bin2hex(random_bytes(6));
        
        $user->save();

        // Send verification email
        try {
            Mail::to($user->email)->send(new VerficationCodeEmail($user->email_verification_token));
        } catch (\Exception $e) {
            Log::error('Email verification mail could not be sent to user with email: ' . $user->email);
        }
        
        
        return redirect('/verify-email')
            ->with('success', 'Verification code sent to your email. Please check your inbox.');

        // auth()->login($user);

    }
}
