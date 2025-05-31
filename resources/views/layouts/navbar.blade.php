<nav>
    <ul>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('profile.edit') }}">Profile</a></li>

        @if (auth()->check() && auth()->user()->role === 'admin')
            <li><a href="{{ route('admin.registrasi.index') }}">Registrasi Anggota</a></li>
            <li><a href="{{ route('admin.anggota.index') }}">Anggota</a></li>
            <li><a href="{{ route('admin.toko.index') }}">Toko</a></li>
            <li><a href="{{ route('admin.produk.index') }}">Produk</a></li>
        @endif

        @if (auth()->check() && auth()->user()->role === 'user')
            <li><a href="{{ route('user.anggota.create') }}">Daftar Anggota</a></li>
            <li><a href="{{ route('user.toko.index') }}">Toko</a></li>
            <li><a href="{{ route('user.produk.create') }}">Produk</a></li>
        @endif
    </ul>
</nav>