<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\VerficationCodeEmail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
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

        $user->email_verification_token = bin2hex(random_bytes(4));

        $user->save();

        // Send verification email
        try {
            Mail::to($user->email)->send(new VerficationCodeEmail($user));
        } catch (\Exception $e) {
            Log::error('Email verification mail could not be sent to user with email: ' . $user->email . '. Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }


        return redirect('/verify-email')
            ->with('success', 'Verification code sent to your email. Please check your inbox.');
    }
    public function showVerifyEmailForm(Request $request)
    {
        if($request->token){
            $data = explode('|', $request->token);
            $id = $data[0];
            $user = User::findOrFail($id);
            if($user){
                $code = $data[1];
                if($user->email_verification_token !== $code){
                    return redirect()->route('verify-email')->withErrors([
                        'error' => 'The verification code is invalid.',
                    ]);
                }
                auth()->login($user);
                $request->session()->regenerate();
            }
        }
        
        return view('auth.verify-email', [
            'title' => 'Verify Email'
        ]);
    }
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'verification_code' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->firstOrFail();
        if ($user->email_verification_token !== $request->verification_code) {
            return back()->withErrors([
                'error' => 'The verification code is invalid.',
            ]);
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        // Log the user in
        auth()->login($user);
        $request->session()->regenerate();

        // Send a success email or notification here

        // try {
        //     Mail::to($user->email)->send(new VerficationCodeEmail($user->email_verification_token));
        // } catch (\Exception $e) {
        //     Log::error('Email verification mail could not be sent to user with email: ' . $user->email . '. Error: ' . $e->getMessage(), [
        //         'exception' => $e
        //     ]);
        // }

        // Redirect to the intended page
        return redirect('/')->with('success', 'Email verified successfully. You are now logged in.');
    }
    
    public function showLoginForm()
    {
        return view('auth.login', [
            'title' => 'Login'
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
