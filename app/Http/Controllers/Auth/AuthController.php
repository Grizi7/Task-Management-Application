<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\MailService;

class AuthController extends Controller
{
    protected MailService $mailService;

    /**
     * AuthController constructor.
     *
     * @param MailService $mailService
     */
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Show the registration form.
     *
     * @return View
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register', [
            'title' => 'Register'
        ]);
    }

    /**
     * Handle new user registration.
     *
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = User::create($data);

        // Generate email verification token
        $user->email_verification_token = bin2hex(random_bytes(4));
        $user->save();

        // Send verification email via MailService
        $this->mailService->sendVerificationEmail($user);

        return redirect('/verify-email')->with('success', 'Verification code sent to your email. Please check your inbox.');
    }

    /**
     * Show the email verification form.
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function showVerifyEmailForm(Request $request): RedirectResponse|View
    {
        if ($request->token) {
            $data = explode('_', $request->token);
            if (count($data) !== 2) {
                return redirect()->route('verify-email')->withErrors([
                    'error' => 'Invalid verification token format.',
                ]);
            }

            [$id, $code] = $data;

            try {
                $user = User::findOrFail($id);
            } catch (\Exception $e) {
                return redirect()->route('verify-email')->withErrors([
                    'error' => 'User not found or invalid token.',
                ]);
            }

            if ($user->email_verification_token !== $code) {
                return redirect()->route('verify-email')->withErrors([
                    'error' => 'The verification code is invalid.',
                ]);
            }

            $user->email_verified_at = now();
            $user->email_verification_token = null;
            $user->save();

            auth()->login($user);
            $request->session()->regenerate();


            // Send welcome email
            $this->SendWelcomeEmail($user);

            return redirect('/')->with('success', 'Email verified successfully. You are now logged in.');
        }

        return view('auth.verify-email', ['title' => 'Verify Email']);
    }

    /**
     * Verify user email using the verification code.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function verifyEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'verification_code' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->email_verification_token !== $request->verification_code) {
            return back()->withErrors([
                'error' => 'The verification code is invalid.',
            ]);
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        auth()->login($user);
        $request->session()->regenerate();

        // Send welcome email
        $this->SendWelcomeEmail($user);

        return redirect('/')->with('success', 'Email verified successfully. You are now logged in.');
    }

    /**
     * Show the login form.
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.login', [
            'title' => 'Login'
        ]);
    }

    /**
     * Handle user login.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
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

    /**
     * Logout the authenticated user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Sends a welcome email to the newly registered user.
     *
     * @param User $user The user who will receive the welcome email.
     * @return void
     */
    private function sendWelcomeEmail(User $user): void
    {
        $this->mailService->sendWelcomeEmail($user);
    }
}
