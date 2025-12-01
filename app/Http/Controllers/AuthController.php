<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * 显示注册页面
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * 处理注册表单
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 创建用户（密码要加密）
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // 自动登录
        Auth::login($user);

        return redirect()->route('posts.index')
            ->with('success', 'Registration successful. You are now logged in.');
    }

    /**
     * 显示登录页面
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * 处理登录表单
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            // 防止 session fixation
            $request->session()->regenerate();

            return redirect()->intended(route('posts.index'))
                ->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('posts.index')
            ->with('success', 'Logged out successfully.');
    }
}
