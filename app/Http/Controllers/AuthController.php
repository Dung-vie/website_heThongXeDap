<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function registerForm() {
        return view('auth.register');
    }

    function register(Request $request) {
        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/')->with('success', 'Đăng ký tải khoản thành công');
    }

    function loginForm() {
        return view('auth.login');
    }

    function login(Request $request) {
        $credential = $request->credential;
        $password = $request->password;

        if ($credential === 'admin' && $password === '12345') {
            session(['is_admin' => true]);
            return redirect('/admin');
        }

        $user = User::where('phone', $credential)->orWhere('email', $credential)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['credential' => 'Số điện thoại/email hoặc mật khẩu không đúng.']);
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
