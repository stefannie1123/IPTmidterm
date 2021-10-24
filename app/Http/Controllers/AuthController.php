<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function registrationForm() {
        return view('register');
    }
    public function loginForm() {
        return view('login');
    }
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'gender' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $token = Str::random(24);

        $user = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'remember_token' => $token
        ]);

        Mail::send('verification', ['user'=>$user], function($mail) use ($user) {
            $mail->to($user->email);
            $mail->subject('Account Verification');
            $mail->from('stefdummy123@gmail.com', 'CheapTalk.com');
        });
        return redirect('/login')->with('Message', 'Your account has been created succesfully. Please check your email for the verification.');
    }
    public function login (Request $request){
        $request->validate([
            'email' => 'email|required',
            'password' => 'string|required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || $user->email_verified_at==null) {
            return redirect('/login')->with('Error', 'Sorry, you are not yet verified.');
        }

        $login = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if(!$login){
            return back()->with('Error', 'Invalid email or password');
        }

        return redirect ('/dashboard');
    }
    public function logout() {
        auth()->logout();
        return redirect('/login');
    }
    public function verification(User $user, $token) {
        if($user->remember_token!==$token) {
            return redirect('/login')->with('Error', 'Invalid token');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect('/login')->with('Message', 'Verification successful. You may now login');
    }
}
