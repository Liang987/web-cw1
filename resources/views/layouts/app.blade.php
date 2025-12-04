<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog</title>
    {{-- CSRF Token for AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- 🟢 新增：天气背景样式 --}}
    <style>
        /* 默认背景 (多云/阴天) */
        body {
            background: linear-gradient(to bottom, #bdc3c7, #2c3e50);
            min-height: 100vh;
            transition: background 1s ease; /* 让背景切换更丝滑 */
            color: #333;
        }

        /* ☀️ 晴天样式 (蓝天) */
        body.weather-Clear {
            background: linear-gradient(to bottom, #2980b9, #6dd5fa, #ffffff);
        }

        /* 🌧️ 雨天样式 (灰暗 + 蓝) */
        body.weather-Rain {
            background: linear-gradient(to bottom, #373b44, #4286f4);
        }

        /* ❄️ 雪天样式 (冰冷白) */
        body.weather-Snow {
            background: linear-gradient(to bottom, #83a4d4, #b6fbff);
        }

        /* 🟢 调整：让卡片和导航栏半透明，透出背景色，更漂亮 */
        .card, .list-group-item, .alert {
            background-color: rgba(255, 255, 255, 0.95) !important;
        }
        
        .navbar {
            background-color: rgba(33, 37, 41, 0.9) !important; /* 深色半透明导航 */
        }
    </style>
</head>

{{-- 🟢 关键修改：动态 class --}}
{{-- 如果控制器传来了 $weather，就加上对应的类；否则保持默认 --}}
<body class="{{ isset($weather) ? 'weather-' . $weather['type'] : '' }}">

    <nav class="navbar navbar-expand-lg navbar-dark mb-4 shadow">
        <div class="container">
            {{-- 🟢 品牌栏显示天气图标 --}}
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
                        {{-- 1. 通知链接 (带小红点) --}}
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-light btn-sm me-3 position-relative">
                            Notifications
                            {{-- 小红点 (加了 id 和 d-none 逻辑，配合下面的 JS 实现实时刷新) --}}
                            <span id="notification-badge" 
                                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'd-none' }}">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        </a>

                        {{-- 2. 用户名 (点击去个人主页) --}}
                        <a href="{{ route('users.show', auth()->user()) }}" class="navbar-text me-3 text-decoration-none text-light">
                            Hello, {{ auth()->user()->name }}
                            @if(auth()->user()->isAdmin())
                                <span class="badge bg-danger ms-1">Admin</span>
                            @endif
                        </a>

                        {{-- 登出 --}}
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                Logout
                            </button>
                        </form>
                    @else
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

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- 🟢 3. 实时轮询脚本 (建议加上，这样通知红点会自动跳出来) --}}
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const badge = document.getElementById('notification-badge');
            // 如果页面上找不到 badge (比如没登录)，就不执行
            if (!badge) return;

            // 每 3 秒去后台问一次：“有新消息吗？”
            setInterval(() => {
                fetch("{{ route('notifications.check') }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.unread_count > 0) {
                            badge.innerText = data.unread_count;
                            badge.classList.remove('d-none'); // 显示红点
                        } else {
                            badge.classList.add('d-none'); // 隐藏红点
                        }
                    })
                    .catch(() => {}); // 忽略网络错误，不大惊小怪
            }, 3000); 
        });
    </script>
    @endauth
</body>
</html>