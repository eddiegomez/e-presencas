<?php

namespace App\Http\Controllers;

use App\Mail\ContactVCardMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendEmail(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'recipientEmail' => 'required|email|max:255',
        ]);

        $vCard = "BEGIN
                VERSION:3.0
                FN:{$validatedData['name']}
                TEL:{$validatedData['phone']}
                EMAIL:{$validatedData['email']}
                END
                ";
        return $vCard;

        $mail = Mail::to($validatedData['recipientEmail'])->send(new ContactVCardMail($vCard, $validatedData['name']));

        return response()->json(['message' => 'Email sent successfully!'], 200);
    }
}
