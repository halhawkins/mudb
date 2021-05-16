<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerifyEmails;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class VerifyEmailController extends Controller
{
    public function verifyAccount($userID,$hash){
        $vrec = DB::table('verification')->where("userID","=",$userID)->where("verification_token","=",$hash)->first();
        $now = new \DateTime();
        if($vrec->expires_at < $now){
            // $user = Auth::user();

            $u = DB::table('users')->where("email","=",$vrec->email)->update(['email_verified_at'=>$now]);
            // $u->email_verified_at = $now;
            // $u->save();
            return array("code"=>200,"message"=>"Email verified.");
        }
        else{
            return array("code"=>400,"message"=>"token expired.");
        }
        
    }
    //
    public function SendEmail($userid,$email){
        Mail::to($email)->send(new VerifyEmail());
    }
}   
