<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIREMAN</title>
    <!-- Import Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Notifikasi Pesan Error atau Sukses -->
    @if(session('error')) <!-- Menampilkan pesan error jika ada -->
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success')) <!-- Menampilkan pesan sukses jika ada -->
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">
                <!-- Placeholder untuk logo aplikasi -->
                <img src="https://via.placeholder.com/50" alt="Logo" class="d-inline-block align-text-top">
                SIREMAN
            </a>
            <div>
                @if(session('user') && (session('user')->role === 'kasir')) <!-- Menampilkan menu jika user adalah 'kasir' -->
                <span class="me-3">Hello, {{ session('user')->name }}</span>
                <a href="/profile" class="btn btn-outline-primary btn-sm">Profile</a>
                <a href="/logout" class="btn btn-outline-danger btn-sm">Logout</a>
                @else
                <a href="/login" class="btn btn-outline-primary btn-sm">Login</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Kontainer Utama -->
    <div class="container">
        <h1 class="my-4">Menu</h1>

        <!-- Tombol Keranjang & List Pesanan -->
        @if(session('user') && (session('user')->role === 'kasir'))
        <a href="{{ route('pesanan.keranjang') }}" class="btn btn-primary mb-3">
            Keranjang ({{ count(session('keranjang', [])) }}) <!-- Menampilkan jumlah barang di keranjang -->
        </a>
        @endif

        <!-- Form Pencarian -->
        <form action="/" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       placeholder="Cari kode, kategori, deskripsi, atau harga..."
                       value="{{ request('search') }}"> <!-- Nilai pencarian akan dipertahankan dalam input -->
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <!-- Filter Kategori -->
        <div class="text-center mb-3">
            <button class="btn btn-primary" id="btn-makanan">Makanan</button>
            <button class="btn btn-secondary" id="btn-minuman">Minuman</button>
        </div>

        <!-- Daftar Menu -->
        <div class="row" id="menu-list">
            @if($menus->count()) <!-- Mengecek apakah ada menu -->
                @foreach($menus as $menu) <!-- Loop untuk menampilkan masing-masing menu -->
                    <div class="col-md-4 mb-3 menu-item {{ $menu->kategori }}"> <!-- Menyusun menu dalam kolom, berdasarkan kategori -->
                        <div class="card">
                            <img src="{{ $menu->gambar_menu }}" class="card-img-top"
                                 alt="{{ $menu->deskripsi }}" style="height: 200px; object-fit: cover;"> <!-- Gambar menu -->
                            <div class="card-body">
                                <h5 class="card-title">{{ $menu->deskripsi }}</h5>
                                <p class="card-text">Harga: Rp{{ number_format($menu->harga, 0, ',', '.') }}</p> <!-- Harga dalam format lokal -->
                                <p class="card-text">Kategori: {{ ucfirst($menu->kategori) }}</p> <!-- Menggunakan 'ucfirst' untuk kapitalisasi pertama karakter kategori -->
                                @if(session('user') && (session('user')->role === 'kasir')) <!-- Hanya ditampilkan untuk user dengan role 'kasir' -->
                                    <!-- Form untuk Menambahkan Pesanan -->
                                    <form action="{{ route('pesanan.tambah') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kode_menu" value="{{ $menu->kode_menu }}">
                                        <button type="submit" class="btn btn-success btn-sm">Tambah Pesanan</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Alert jika tidak ada data -->
                <div class="alert alert-warning">Tidak ada data ditemukan.</div>
            @endif
        </div>
    </div>

    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            const searchQuery = '{{ request('search') }}'; <!-- Menyimpan query pencarian -->

            // Menampilkan semua item menu jika tidak ada permintaan pencarian
            if (!searchQuery) {
                $('.menu-item').show();
                checkEmptyResults();
            } else {
                // Filter item sesuai dengan pencarian
                $('.menu-item').hide().filter(function () {
                    return $(this).text().toLowerCase().includes(searchQuery.toLowerCase());
                }).fadeIn();
                checkEmptyResults();
            }

            // Tombol untuk memfilter kategori makanan dan minuman
            $('#btn-makanan').click(() => filterMenu('makanan'));
            $('#btn-minuman').click(() => filterMenu('minuman'));

            function filterMenu(category) {
                $('.menu-item').hide(); // Sembunyikan semua menu
                $(`.${category}`).fadeIn(); // Menampilkan hanya kategori yang dipilih
                checkEmptyResults();
            }

            function checkEmptyResults() {
                if ($('.menu-item:visible').length === 0) {
                    $('.alert-warning').show(); // Menampilkan pesan jika tidak ada hasil yang ditemukan
                } else {
                    $('.alert-warning').hide(); // Menyembunyikan pesan jika ada hasil yang ditemukan
                }
            }
        });
    </script>
</body>
</html>
