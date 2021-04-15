<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register(Request $request){
        $user = User::where('email',"=",$request->email)->first();
        if($user){
            return Response::json(array("error"=>400,"Message"=>"Register: User email already exists."),400);
        }
        else if($request->password !== $request->confirm{
            return Response::json(array("error"=>400,"Message"=>"Register: 'Password' and 'Confirm Password' fields do not match."),400);
        }
        else{
            $user = new User();
            $user->name = $request->name;
            $user->email= $request->email;
            $user->provider_id = "none";
            $user->avatar = url('/images/generic-user-icon-19-png');
            $user->save();
        }
        $request->session()->regenerate();
        Auth::login($user);
        return view('profile')->with($request->email);
    }

    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request){
        $user = Socialite::driver('google')->stateless()->user();
        $this->_registerOrLoginUser($request,$user);
        return redirect(url('/'));
    }

    protected function _registerOrLoginUser(Request $request,$data){
        $user = User::where('email','=',$data->email)->first();
        if(!$user){
            $user = new User();
            $user->name = $data->name;
            $user->email= $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();
        }
        $request->session()->regenerate();
        return Auth::login($user);
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(url('/'));
    }
}