<header>
    <img src="{{ asset('images/logowarung.png') }}" alt="Logo Delighy" width="60">
    <h1>Catering Delighy</h1>
    <p>Layanan Pre-Order Catering Praktis dan Lezat</p>
</header>

<nav>
    <ul>
        <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">Beranda</a></li>
        <li><a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}">Menu</a></li>
        <li><a href="{{ route('pesanan') }}" class="{{ request()->routeIs('pesanan') ? 'active' : '' }}">Pesanan</a></li>
        <li><a href="{{ route('aktivitas') }}" class="{{ request()->routeIs('aktivitas') ? 'active' : '' }}">Aktivitas</a></li>
    </ul>
</nav>