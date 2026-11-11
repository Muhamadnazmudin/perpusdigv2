<div class="container-fluid">

    <!-- JUDUL -->
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-user-graduate"></i>
        Dashboard Siswa
    </h1>

    <div class="row">

        <!-- BUKU DIPINJAM -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Buku Sedang Dipinjam
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            <?= $stat['dipinjam'] ?? 0 ?>
                        </div>
                    </div>
                    <i class="fas fa-book-reader fa-2x text-primary"></i>
                </div>
            </div>
        </div>

        <!-- TERLAMBAT -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Peminjaman Terlambat
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            <?= $stat['terlambat'] ?? 0 ?>
                        </div>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                </div>
            </div>
        </div>

        <!-- RIWAYAT -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Riwayat Peminjaman
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            <?= $stat['riwayat'] ?? 0 ?>
                        </div>
                    </div>
                    <i class="fas fa-history fa-2x text-success"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- QUICK ACTION -->
    <div class="row mt-4">

        <div class="col-md-4 mb-3">
            <a href="<?= site_url('buku') ?>" class="btn btn-primary btn-block shadow">
                <i class="fas fa-book-open"></i> Jelajahi Buku Perpustakaan
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="<?= site_url('peminjaman/daftar') ?>" class="btn btn-info btn-block shadow">
                <i class="fas fa-list"></i> Buku Sedang Dipinjam
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="<?= site_url('peminjaman/riwayat') ?>" class="btn btn-success btn-block shadow">
                <i class="fas fa-clock"></i> Riwayat Peminjaman
            </a>
        </div>

    </div>

    <!-- EBOOK -->
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card shadow border-left-secondary">
                <div class="card-body text-center">
                    <h5 class="font-weight-bold text-secondary mb-2">
                        <i class="fas fa-tablet-alt"></i> E-Book Digital
                    </h5>
                    <p class="text-muted mb-3">
                        Akses koleksi buku digital sekolah kapan saja dan di mana saja.
                    </p>
                    <a href="<?= site_url('SiswaEbook') ?>" class="btn btn-outline-secondary">
                        Buka E-Book
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
