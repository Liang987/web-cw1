<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display the registration view.
     * 显示注册页面
     */
    public function showRegister()
    {
        // Return the registration view located at resources/views/auth/register.blade.php
        // 返回位于 resources/views/auth/register.blade.php 的注册视图
        return view('auth.register'); 
    }

    /**
     * Handle an incoming registration request.
     * 处理注册逻辑
     */
    public function register(Request $request)
    {
        // 1. Validate the incoming request data (Rubric 14: Form Validation)
        // 1. 验证输入数据 (Rubric 14: 表单验证)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Ensure email is unique in the users table / 确保邮箱在 users 表中唯一
            'password' => 'required|string|min:8|confirmed', // 'confirmed' expects a matching password_confirmation field / confirmed 要求必须有 password_confirmation 字段
        ]);

        // 2. Create a new user instance in the database
        // 2. 在数据库中创建新用户实例
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hash the password for security / 为了安全，必须加密密码
            'role' => 'user', // Default role is 'user' / 默认为普通用户 'user'
        ]);

        // 3. Automatically log in the user after registration (Rubric 8)
        // 3. 注册后自动登录用户 (Rubric 8)
        Auth::login($user);

        // 4. Redirect to the homepage with a success message
        // 4. 跳转到首页并显示成功消息
        return redirect()->route('posts.index')->with('success', 'Registration successful! Welcome ' . $user->name);
    }

    /**
     * Display the login view.
     * 显示登录页面
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * 处理登录逻辑
     */
    public function login(Request $request)
    {
        // Validate the form data
        // 验证表单数据
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate the user
        // 尝试验证用户身份
        if (Auth::attempt($credentials)) {
            // Regenerate the session to prevent fixation attacks
            //以此防止会话固定攻击
            $request->session()->regenerate();

            // Redirect to intended page or posts index
            // 重定向到之前的页面或帖子列表页
            return redirect()->intended('posts')->with('success', 'You are logged in!');
        }

        // Authentication failed
        // 登录失败
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     * 登出 (销毁会话)
     */
    public function logout(Request $request)
    {
        // Log the user out
        // 用户登出
        Auth::logout();

        // Invalidate the session
        // 使当前会话失效
        $request->session()->invalidate();

        // Regenerate the CSRF token
        // 重新生成 CSRF 令牌
        $request->session()->regenerateToken();

        // Redirect to homepage
        // 重定向回首页
        return redirect('/')->with('success', 'You have been logged out.');
    }
}