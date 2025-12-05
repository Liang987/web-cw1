<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog</title>
    {{-- CSRF Token for AJAX security / 用于 AJAX 安全的 CSRF 令牌 --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap 5 CSS Framework / Bootstrap 5 CSS 框架 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Weather Background Styles / 天气背景样式 --}}
    <style>
        /* Default background (Cloudy/Overcast) / 默认背景 (多云/阴天) */
        body {
            background: linear-gradient(to bottom, #bdc3c7, #2c3e50);
            min-height: 100vh;
            transition: background 1s ease; /* Smooth transition effect / 让背景切换更丝滑 */
            color: #333;
        }

        /* ☀️ Sunny/Clear weather style (Blue Sky) / ☀️ 晴天样式 (蓝天) */
        body.weather-Clear {
            background: linear-gradient(to bottom, #2980b9, #6dd5fa, #ffffff);
        }

        /* 🌧️ Rainy weather style (Dark Grey + Blue) / 🌧️ 雨天样式 (灰暗 + 蓝) */
        body.weather-Rain {
            background: linear-gradient(to bottom, #373b44, #4286f4);
        }

        /* ❄️ Snowy weather style (Icy White) / ❄️ 雪天样式 (冰冷白) */
        body.weather-Snow {
            background: linear-gradient(to bottom, #83a4d4, #b6fbff);
        }

        /* Semi-transparent containers to show background / 让卡片和导航栏半透明，透出背景色 */
        .card, .list-group-item, .alert {
            background-color: rgba(255, 255, 255, 0.95) !important;
        }
        
        .navbar {
            background-color: rgba(33, 37, 41, 0.9) !important;
        }
    </style>
</head>

{{-- Dynamic Body Class based on Weather / 基于天气的动态 Body 类名 --}}
{{-- If controller passes $weather, apply corresponding class; otherwise default / 如果控制器传来了 $weather，就加上对应的类；否则保持默认 --}}
<body class="{{ isset($weather) ? 'weather-' . $weather['type'] : '' }}">

    <nav class="navbar navbar-expand-lg navbar-dark mb-4 shadow">
        <div class="container">
            {{-- Brand with Weather Icon / 显示天气图标的品牌栏 --}}
            <a class="navbar-brand" href="{{ route('posts.index') }}">
                My Blog
                @if(isset($weather))
                    <span class="badge bg-light text-dark ms-2" style="font-size: 0.8rem;">
                        @if($weather['type'] == 'Clear') ☀️
                        @elseif($weather['type'] == 'Rain') 🌧️
                        @elseif($weather['type'] == 'Snow') ❄️
                        @else ☁️
                        @endif
                        {{ $weather['type'] }}
                    </span>
                @endif
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="ms-auto d-flex align-items-center">
                    @auth
                        {{-- 1. Notification Link (with Badge) / 通知链接 (带小红点) --}}
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-light btn-sm me-3 position-relative">
                            Notifications
                            {{-- Red Badge: Controlled by ID and d-none class / 小红点：通过 ID 和 d-none 类控制 --}}
                            <span id="notification-badge" 
                                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'd-none' }}">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        </a>

                        {{-- 2. User Profile Link / 用户个人主页链接 --}}
                        <a href="{{ route('users.show', auth()->user()) }}" class="navbar-text me-3 text-decoration-none text-light">
                            Hello, {{ auth()->user()->name }}
                            {{-- Admin Badge / 管理员徽章 --}}
                            @if(auth()->user()->isAdmin())
                                <span class="badge bg-danger ms-1">Admin</span>
                            @endif
                        </a>

                        {{-- Logout Form / 登出表单 --}}
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                Logout
                            </button>
                        </form>
                    @else
                        {{-- Guest Links / 访客链接 --}}
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    {{-- Bootstrap JS Bundle / Bootstrap JS 包 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Real-time Notification Polling Script / 实时轮询通知脚本 --}}
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const badge = document.getElementById('notification-badge');
            
            // If badge element is missing (e.g. user not logged in), stop / 如果页面上找不到 badge (比如没登录)，就不执行
            if (!badge) return;

            // Poll the server every 3 seconds / 每 3 秒去后台轮询一次
            setInterval(() => {
                fetch("{{ route('notifications.check') }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.unread_count > 0) {
                            // Show badge and update count / 显示红点并更新数量
                            badge.innerText = data.unread_count;
                            badge.classList.remove('d-none');
                        } else {
                            // Hide badge if count is 0 / 如果数量为 0 则隐藏红点
                            badge.classList.add('d-none');
                        }
                    })
                    .catch(() => {}); // Ignore network errors silently / 忽略网络错误
            }, 3000); 
        });
    </script>
    @endauth
</body>
</html>