<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
	
	/**
     * Constructor
     * 
     * Apply the guest middleware to prevent authenticated users
     * from accessing password reset endpoints.
     */
	public function __construct()
    {
        $this->middleware('guest');
    }

	/**
     * Send a password reset link to the given email.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * This method validates the email and attempts to send a password
     * reset link using Laravel's built-in Password broker. If successful,
     * an email containing the reset URL is sent to the user.
     */
	public function sendResetLinkEmail(Request $request)
	{
		$request->validate([
			'email' => 'required|email'
		]);

		$status = Password::sendResetLink(
			$request->only('email'),
			function ($user, $token) {
				$frontendUrl = env('FRONTEND_URL');
				$resetUrl = $frontendUrl . "/?token={$token}&email={$user->email}";

				\Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($resetUrl));
			}
		);

		if ($status === Password::RESET_LINK_SENT) {
			return response()->json([
				'message' => 'Un lien de réinitialisation a été envoyé à votre email.'
			], 200);
		}

		return response()->json([
			'message' => 'Email introuvable.'
		], 404);
	}

	/**
     * Reset the user's password.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * This method validates the token, email, and password fields.
     * If the token is valid, it updates the user's password using
     * a hashed value and saves it to the database.
     */
	public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed', 
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Mot de passe réinitialisé avec succès.']);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
	
}
