<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\VerifyEmails;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class PasswordChangeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = DB::table('users')->where('email','=',$this->email)->first();
        if(!is_null($user)){
            $hashvalue = hash("sha256",rand());
            $vemail = new VerifyEmails;
            $vemail->userID = $user->id;
            $vemail->email = $this->email;
            $now = new \DateTime();
            $vemail->expires_at = $now->modify('+1 day');
            $vemail->verification_token = $hashvalue;
            $vemail->save();
            return $this
            ->from(env("PASSWORD_RESET_EMAIL"))
            ->view('passwordchangeemail')->with('hashvalue',$hashvalue);
        }
        else{
            return $this->view('invalidRequest');
        }

    }
}
