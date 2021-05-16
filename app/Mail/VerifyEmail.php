<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\VerifyEmails;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $usersName;
    public $userID;
    public $userEmail;
    public $hashvalue;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = Auth::user();
        $hashvalue = hash("sha256",rand());
        $data = array("userID"=>$user["id"],"userEmail"=>$user["email"],"userName"=>$user["name"],"hashvalue"=>$hashvalue);
        $vemail = new VerifyEmails;
        $vemail->userID = $user->id;
        $vemail->email = $user->email;
        $now = new \DateTime();
        $vemail->expires_at = $now->modify('+1 day');
        $vemail->verification_token = $hashvalue;
        $vemail->save();
        // $hash = Auth::user();
        return $this
            ->from('hal@localhost.com')
            ->view('verification')->with('data',$data);
    }
}
