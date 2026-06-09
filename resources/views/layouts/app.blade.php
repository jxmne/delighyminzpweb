<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Catering Delighy')</title>

    <script>
        (function() {
            try {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = cookies[i].trim();
                    if (cookie.startsWith('theme=')) {
                        var val = cookie.substring('theme='.length);
                        if (val === 'dark') {
                            document.documentElement.classList.add('dark');
                        }
                        break;
                    }
                }
            } catch(e) {}
        })();
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <link rel="icon" href="{{ asset('image/logowarung.png') }}">

    @yield('css')
</head>
<body>

<header style="position: relative;">
    <img src="{{ asset('image/logowarung.png') }}" alt="Logo Delighy" width="60">
    <h1>Catering Delighy</h1>
    <p>Pesan dan Nikmati Pesananmu</p>
    
    <button id="theme-toggle" style="
        position: absolute;
        top: 12px;
        right: 16px;
        background: rgba(255,255,255,0.2);
        border: none;
        font-size: 20px;
        cursor: pointer;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    " title="Ganti tema">🌙</button>
</header>

<nav>
    <ul style="display: flex; align-items: center; list-style: none; margin: 0; padding: 0 16px; width: 100%; box-sizing: border-box;">

        <li style="flex: 1; display: flex; justify-content: flex-start; align-items: center; gap: 8px;">
            @guest
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Daftar</a>
            @endguest
            @auth
                @if(Auth::user()->is_admin)
                    <a href="#" onclick="event.preventDefault(); if(typeof bukaModalTambah==='function') bukaModalTambah();" style="color: #12b368; font-weight: bold;">+ Tambah Menu</a>
                @endif
            @endauth
        </li>

        <li style="display: flex; align-items: center; gap: 8px;">
            <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}">Menu</a>
            @auth
                @if (Auth::user()->is_admin)
                    <a href="{{ route('aktivitas') }}" class="{{ request()->routeIs('aktivitas') ? 'active' : '' }}">Aktivitas</a>
                @else
                    <a href="{{ route('pesanan') }}" class="{{ request()->routeIs('pesanan') ? 'active' : '' }}">Pesanan</a>
                    <a href="{{ route('aktivitas') }}" class="{{ request()->routeIs('aktivitas') ? 'active' : '' }}">Aktivitas</a>
                @endif
            @endauth
        </li>

        <li style="flex: 1; display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
            @auth
                <span style="font-weight: 500;">Halo, {{ Auth::user()->name }}</span>
                <a href="{{ route('pengaturan') }}" style="color: #888; text-decoration: none; font-size: 18px;" title="Pengaturan">⚙️</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" style="
                        background-color: #e74c3c;
                        color: white;
                        border: none;
                        padding: 8px 15px;
                        border-radius: 5px;
                        cursor: pointer;
                        font-family: inherit;
                        font-weight: bold;
                    ">Logout</button>
                </form>
            @endauth
        </li>

    </ul>
</nav>

<main>
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')
</main>

<footer>
    <section class="footer-section">
        <address>
            <p>Jl. Kuliner Nusantara No. 15, Pekanbaru, Riau</p>
            <p>0812-3456-7890</p>
            <p>info@delighycatering.com</p>
        </address>
        <p><small>&copy; 2026 Catering Delighy. Hak Cipta Dilindungi.</small></p>
    </section>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function applyTheme(theme) {
        const html = document.documentElement;
        if (theme === 'dark') {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        const themeBtn = document.getElementById('theme-toggle');
        if (themeBtn) {
            themeBtn.innerHTML = theme === 'dark' ? '☀️' : '🌙';
            themeBtn.title = theme === 'dark' ? 'Mode Terang' : 'Mode Gelap';
        }
    }

    // FIXED: hapus baris document.documentElement.classList.add('dark') yang paksa dark mode
    document.addEventListener('DOMContentLoaded', function() {
        let savedTheme = getCookie('theme') || 'light';
        applyTheme(savedTheme);
    });

    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const newTheme = isDark ? 'light' : 'dark';
            applyTheme(newTheme);
            setCookie('theme', newTheme, 7);
        });
    }
</script>

@yield('js')
@stack('scripts')

</body>
</html>