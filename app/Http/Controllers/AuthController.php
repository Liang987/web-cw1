<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 显示注册页面
    public function showRegister()
    {
        return view('auth.register'); // 确保你的视图文件在 resources/views/auth/register.blade.php
    }

    // 处理注册逻辑
    public function register(Request $request)
    {
        // 1. 验证输入 (Rubric 14: 表单验证)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // 检查邮箱是否唯一
            'password' => 'required|string|min:8|confirmed', // confirmed 要求必须有 password_confirmation 字段
        ]);

        // 2. 创建用户
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // 必须加密密码
            'role' => 'user', // 默认为普通用户
        ]);

        // 3. 注册后自动登录 (Rubric 8)
        Auth::login($user);

        // 4. 跳转到首页
        return redirect()->route('posts.index')->with('success', 'Registration successful! Welcome ' . $user->name);
    }

    // 显示登录页面
    public function showLogin()
    {
        return view('auth.login');
    }

    // 处理登录逻辑
    public function login(Request $request)
    {
        // 验证
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 尝试登录
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('posts')->with('success', 'You are logged in!');
        }

        // 登录失败
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // 登出
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out.');
    }
}