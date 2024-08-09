<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Login Page
    public function login() {
        return view('auth.login', ['pageTitle' => "HeTi - Login"]);
    }

    // Action Login
    public function actionLogin(Request $request)
    {
        // Validasi
        $validate = Validator::make($request->all(), [
            'email' => 'required|email:dns',
            'password' => 'required|min:8',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('dashboard'))->withSuccess('Berhasil masuk');

                case 'customer-service':
                case 'agent':
                    $employeStatus = Employee::where('user_id', $user->id)->first();

                    if ($employeStatus && $employeStatus->is_active) {
                        return redirect()->intended(route('dashboard'))->withSuccess('Berhasil masuk');
                    }

                    // Keluar jika akun tidak aktif
                    Auth::logout();
                    return redirect()->back()->withErrors('Akun Anda tidak aktif.');

                default:
                    return redirect()->back()->withErrors('Peran pengguna tidak valid. Silakan periksa kredensial Anda.');
            }
        }

        return redirect()->back()->withErrors('Kredensial tidak valid. Silakan periksa email dan kata sandi Anda.');
    }

    // Logout
    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('home');
    }

}
