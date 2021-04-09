<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function index(){
    }

    public function register(Request $request){
        if(($request["password"] == $request["confirm"]) && (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $request['password'])) ){
            $reg = [
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => $request['password']
            ];
            User::create($reg);
            return true;
        }
        else{
            return false;
        }
    }
}
