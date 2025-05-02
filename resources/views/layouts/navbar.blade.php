<nav>
    <ul>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('profile.edit') }}">Profile</a></li>
        <!-- Admin route -->
        @role('admin')
            <li><a href="{{ route('admin.registrasi.index') }}">Registrasi Anggota</a></li>
            <li><a href="{{ route('admin.anggota.index') }}">Anggota</a></li>
            <li><a href="{{ route('admin.toko.index') }}">Toko</a></li>
            <li><a href="{{ route('admin.produk.index') }}">Produk</a></li>
        @endrole
        <!-- User route -->
        @role('user')
            <li><a href="{{ route('user.anggota.create') }}">Daftar Anggota</a></li>
            <li><a href="{{ route('user.toko.create') }}">Toko</a></li>
            <li><a href="{{ route('user.produk.index') }}">Produk</a></li>
        @endrole
    </ul>
</nav>
