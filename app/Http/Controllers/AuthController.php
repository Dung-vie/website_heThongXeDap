<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function registerForm()
    {
        return view('auth.register');
    }

    function register(Request $request)
    {
        // Chuẩn hóa số điện thoại
        $normalizedPhone = $this->normalizePhone($request->phone);

        $request->merge([
            'phone_normalized' => $normalizedPhone
        ]);

        $request->validate([
            // Họ tên
            'name' => [
                'required',
                'regex:/^[A-Za-zÀ-ỹ]+(?:\s[A-Za-zÀ-ỹ]+){2,}$/u'
            ],

            // SĐT
            'phone' => [
                'required',
                'regex:/^(0\d{9}|84\d{9}|\+84\d{9})$/',
            ],

            // Email
            'email' => [
                'required',
                'regex:/^(?!\.)(?!.*\.\.)[a-z0-9.]{6,30}(?<!\.)@gmail\.com$/',
                'unique:users,email'
            ],

            // Password
            'password' => [
                'required',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/'
            ],

            // Confirm password
            'password_confirmation' => [
                'required',
                'same:password'
            ]
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'name.regex' => 'Họ tên không hợp lệ',

            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không hợp lệ',

            'email.required' => 'Vui lòng nhập email',
            'email.regex' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',

            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.regex' => 'Mật khẩu phải có ít nhất 8 ký tự gồm chữ hoa, chữ thường, số và ký tự đặc biệt',

            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu',
            'password_confirmation.same' => 'Mật khẩu nhập lại không khớp',
        ]);

        // Check trùng phone sau chuẩn hóa
        $existsPhone = User::all()->contains(function ($user) use ($normalizedPhone) {
            return $this->normalizePhone($user->phone) === $normalizedPhone;
        });

        if ($existsPhone) {
            return back()
                ->withErrors([
                    'phone' => 'Số điện thoại đã tồn tại'
                ])
                ->withInput();
        }

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Đăng ký tài khoản thành công');
    }

    function loginForm()
    {
        return view('auth.login');
    }

    function login(Request $request)
    {
        $request->validate([
            'credential' => 'required',
            'password' => 'required',
        ], [
            'credential.required' => 'Vui lòng nhập email hoặc số điện thoại',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $credential = $request->credential;
        $password = $request->password;

        // admin
        if ($credential === 'admin' && $password === '12345') {
            session(['is_admin' => true]);
            return redirect('/admin');
        }

        // login bằng phone hoặc email
        $user = User::where('email', $credential)
            ->orWhere('phone', $credential)
            ->first();

        // check phone chuẩn hóa
        if (!$user) {

            $normalizedInput = $this->normalizePhone($credential);

            $user = User::all()->first(function ($u) use ($normalizedInput) {
                return $this->normalizePhone($u->phone) === $normalizedInput;
            });
        }

        if (!$user || !Hash::check($password, $user->password)) {
            return back()
                ->withErrors([
                    'credential' => 'Email/số điện thoại hoặc mật khẩu không đúng'
                ])
                ->withInput();
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

    // Chuẩn hóa SĐT
    private function normalizePhone($phone)
    {
        $phone = str_replace('+84', '0', $phone);

        if (str_starts_with($phone, '84')) {
            $phone = '0' . substr($phone, 2);
        }

        return $phone;
    }
}
