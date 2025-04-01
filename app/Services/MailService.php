<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerficationCodeEmail;
use App\Mail\WelcomeEmail;

class MailService
{
    /**
     * Send email verification message.
     *
     * @param User $user
     * @return void
     */
    public function sendVerificationEmail(User $user): void
    {
        try {
            Mail::to($user->email)->send(new VerficationCodeEmail($user));
        } catch (\Exception $e) {
            Log::error('Failed to send email verification mail to: ' . $user->email . '. Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }

    /**
     * Send welcome message.
     *
     * @param User $user
     * @return void
     */
    public function sendWelcomeEmail(User $user): void
    {
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            Log::error('Failed to send welcome mail to: ' . $user->email . '. Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }
}
