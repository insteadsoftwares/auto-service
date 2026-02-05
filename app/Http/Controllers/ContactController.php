<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

	/**
     * Send a contact email from the user.
     *
     * @param Request $request
     */
    public function contact(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'nullable|string',
            'subject' => 'nullable|string',
            'message' => 'required|string',
        ]);

        Mail::raw(
            "Nom: {$data['name']}\nEmail: {$data['email']}\nTéléphone: {$data['phone']}\nObjet: {$data['subject']}\n\nMessage:\n{$data['message']}",
            function ($mail) use ($data) {
                $mail->to('ste.auto.service@gmail.com')->subject('Nouveau message de contact');
            }
        );
    }

	/**
     * Send a question email from the user.
     *
     * @param Request $request
     */
    public function askQuestion(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        Mail::raw(
            "Nom: {$data['name']}\nEmail: {$data['email']}\n\nMessage:\n{$data['message']}",
            function ($mail) use ($data) {
                $mail->to('ste.auto.service@gmail.com')->subject('Question depuis le site');
            }
        );
    }

}
