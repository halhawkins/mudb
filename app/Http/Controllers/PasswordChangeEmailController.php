<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerifyEmails;
use App\Mail\PasswordChangeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class PasswordChangeEmailController extends Controller
{
    public function updatePassword($token,$newpassowrd){
        $vrec = DB::table("verification")->where("verification_token","=",$token)->first();
        $urec = DB::table("users")->where("id","=",$vrec->userID)->update(["password"=>\bcrypt($newpassowrd)]);
        return array("status"=>"success","message"=>"Password successfully changed.");
    }

    public function CheckPasswordChangeToken($token){
        $vrec = DB::table("verification")->where("verification_token","=",$token)->first();
        $now = new \DateTime();
        if(!is_null($vrec))
            $res = array("status"=>"success","message"=>"Valid password change token.");
        else{
            $res = array("status"=>"error","message"=>"Invalid password change token.");
            return $res;
        }
        if($vrec->expires_at >= $now)
            $res = array("status"=>"error","Password change token has expired.");
        return $res;

    }
    //
    public function SendEmail($email){
        Mail::to($email)->send(new PasswordChangeEmail($email));
    }
}


