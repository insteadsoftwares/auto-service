<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrl;

    /**
     * Crée une nouvelle instance du mail
     */
    public function __construct($resetUrl)
    {
        $this->resetUrl = $resetUrl;
    }

    /**
     * Construire le message
     */
    public function build()
    {
        return $this->subject('Réinitialisation de votre mot de passe')
			->view('emails.reset-password')
			->with([
				'resetUrl' => $this->resetUrl,
			]);
    }
}
