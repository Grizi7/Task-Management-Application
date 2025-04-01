<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Mail\VerficationCodeEmail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
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

    /**
     * Show the email verification form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showVerifyEmailForm(Request $request): RedirectResponse|View
    {
        if ($request->token) {
            // Validate token format
            $data = explode('_', $request->token);
            if (count($data) !== 2) {
                return redirect()->route('verify-email')->withErrors([
                    'error' => 'Invalid verification token format.',
                ]);
            }

            [$id, $code] = $data;

            // Fetch user securely
            try {
                $user = User::findOrFail($id);
            } catch (\Exception $e) {
                return redirect()->route('verify-email')->withErrors([
                    'error' => 'User not found or invalid token.',
                ]);
            }

            // Validate token
            if ($user->email_verification_token !== $code) {
                return redirect()->route('verify-email')->withErrors([
                    'error' => 'The verification code is invalid.',
                ]);
            }

            // Update user verification status
            $user->email_verified_at = now();
            $user->email_verification_token = null;
            $user->save();

            // Log in the user securely
            auth()->login($user);
            $request->session()->regenerate();

            return redirect('/')->with('success', 'Email verified successfully. You are now logged in.');
        }

        return view('auth.verify-email', [
            'title' => 'Verify Email'
        ]);
    }
    
    /**
     * Verify the email address of the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail(Request $request): RedirectResponse
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
