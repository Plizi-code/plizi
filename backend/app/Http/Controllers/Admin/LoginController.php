<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use AdminSection;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['is_admin'] = 1;

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('admin');
        }

        return redirect()->intended('admin/login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->intended('admin/login');
    }

    public function login() {
        $content = view('admin/login')->toHtml();
        return AdminSection::view($content, 'Login');
    }

    public function dashboard() {
        $content = 'Define your dashboard here.';
        return AdminSection::view($content, 'Dashboard');
    }

    public function information() {
        $content = 'Define your information here.';
        return AdminSection::view($content, 'Information');
    }
}
