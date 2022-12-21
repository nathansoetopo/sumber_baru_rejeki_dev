<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function storeLogin()
    {
        $validator = Validator::make(request()->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);
            // return $validator;

        if($validator->fails())
        {
            return redirect('login')->withInput()->withErrors($validator);
        }
        $user = User::where('email',request('email'))->first();
        if($user)
        {
            if(Hash::check(request('password'),$user->password))
            {
                if($user->hasRole('admin'))
                {
                    Auth::login($user);
                    return redirect('admin');
                }
                return redirect('login')->with('status','Anda bukan admin');
            }
            return redirect('login')->with('status','Password anda salah');
        }
        return redirect('login')->with('status','Email tidak ditemukan');
    }

    public function logout()
    {
        $user = request()->user();
        Auth::logout($user);
        return redirect('login');
    }
}
