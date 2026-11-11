<?php
$role = (int) $this->user['id_role']; // 1=admin, 2=guru, 3=siswa
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('dashboard') ?>">
        <div class="sidebar-brand-icon">
            <i class="fas fa-book"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Perpusdig</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- DASHBOARD (ALL ROLE) -->
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <!-- 📖 BACA AL-QUR'AN (ALL ROLE) -->
<li class="nav-item <?= ($this->uri->segment(1) == 'quran') ? 'active' : '' ?>">
    <a class="nav-link" href="<?= site_url('quran') ?>">
        <i class="fas fa-fw fa-quran"></i>
        <span>Baca Al-Qur'an</span>
    </a>
</li>

    <!-- ================= ADMIN ================= -->
    <?php if ($role === 1): ?>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Master Data</div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuUmum">
            <i class="fas fa-fw fa-database"></i>
            <span>Data Umum</span>
        </a>
        <div id="menuUmum" class="collapse">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('kategori') ?>">Kategori</a>
                <a class="collapse-item" href="<?= site_url('rak') ?>">Rak</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuAkademik">
        <i class="fas fa-fw fa-school"></i>
        <span>Data Akademik</span>
    </a>
    <div id="menuAkademik" class="collapse">
        <div class="bg-white py-2 collapse-inner rounded">

            <a class="collapse-item" href="<?= site_url('kelas') ?>">Kelas</a>
            <a class="collapse-item" href="<?= site_url('jurusan') ?>">Jurusan</a>
            <a class="collapse-item" href="<?= site_url('guru') ?>">Guru</a>
            <a class="collapse-item" href="<?= site_url('siswa') ?>">Siswa</a>
            <!-- 🔼 KENAIKAN KELAS -->
<a class="collapse-item <?= ($this->uri->segment(2) == 'kenaikan_kelas') ? 'active font-weight-bold text-danger' : '' ?>"
   href="<?= site_url('siswa/kenaikan_kelas') ?>">
    <i class="fas fa-level-up-alt mr-1"></i> Kenaikan Kelas
</a>


            <!-- 🔥 MAPPING RFID -->
            <div class="dropdown-divider"></div>
            <a class="collapse-item font-weight-bold text-primary"
               href="<?= site_url('mapping_rfid') ?>">
                <i class="fas fa-id-card mr-1"></i> Mapping RFID Siswa
            </a>

        </div>
    </div>
</li>


    <hr class="sidebar-divider">
    <div class="sidebar-heading">Buku</div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuBuku">
            <i class="fas fa-fw fa-book"></i>
            <span>Master Buku</span>
        </a>
        <div id="menuBuku" class="collapse">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('buku-fisik') ?>">Data Buku</a>
                <a class="collapse-item" href="<?= site_url('AdminKarya') ?>">Karya Siswa</a>
                <a class="collapse-item" href="<?= site_url('buku-fisik/tambah') ?>">Tambah Buku</a>
                <!-- 🔥 E-BOOK ADMIN -->
                <a class="collapse-item" href="<?= site_url('AdminEbook') ?>">E-Book Digital</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">
<div class="sidebar-heading">Pengaturan</div>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuPengaturan"
        aria-expanded="true" aria-controls="menuPengaturan">
        <i class="fas fa-fw fa-cog"></i>
        <span>Pengaturan</span>
    </a>

    <div id="menuPengaturan"
         class="collapse <?= in_array($this->uri->segment(2), ['sekolah']) ? 'show' : '' ?>">
        <div class="bg-white py-2 collapse-inner rounded">

            <!-- Pengaturan Sekolah -->
            <a class="collapse-item <?= ($this->uri->segment(2) == 'sekolah') ? 'active' : '' ?>"
               href="<?= site_url('pengaturan/sekolah') ?>">
                <i class="fas fa-school mr-1"></i> Sekolah
            </a>

            <!-- Pengguna -->
            <a class="collapse-item <?= ($this->uri->segment(1) == 'pengguna') ? 'active' : '' ?>"
               href="<?= site_url('pengguna') ?>">
                <i class="fas fa-users mr-1"></i> Pengguna
            </a>

            <!-- Backup -->
            <a class="collapse-item <?= ($this->uri->segment(1) == 'backup') ? 'active' : '' ?>"
               href="<?= site_url('backup') ?>">
                <i class="fas fa-database mr-1"></i> Backup & Restore
            </a>

        </div>
    </div>
</li>

    <?php endif; ?>
    <!-- =============== END ADMIN =============== -->


    <!-- ================= ADMIN & GURU ================= -->
    <?php if (in_array($role, [1], true)): ?>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Transaksi & Materi</div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuPinjam">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>
    Peminjaman
    <?php if ($role === 1 && !empty($total_menunggu) && $total_menunggu > 0): ?>
        <span class="badge badge-danger ml-1">
            <?= $total_menunggu ?>
        </span>
    <?php endif; ?>
</span>

        </a>
        <div id="menuPinjam" class="collapse">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('peminjaman') ?>">Data Peminjaman</a>
                <a class="collapse-item" href="<?= site_url('peminjaman/tambah') ?>">Pinjam Buku</a>
            </div>
        </div>
    </li>

    <!-- 🔥 E-BOOK GURU -->
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('AdminEbook') ?>">
            <i class="fas fa-fw fa-tablet-alt"></i>
            <span>E-Book Digital</span>
        </a>
    </li>
<li class="nav-item <?= ($this->uri->segment(1) == 'kunjungan') ? 'active' : '' ?>">
    <a class="nav-link" href="<?= site_url('kunjungan') ?>">
        <i class="fas fa-fw fa-qrcode"></i>
        <span>Daftar Kunjungan</span>
    </a>
</li>
<?php if ($role === 1): ?> <!-- ADMIN -->

<hr class="sidebar-divider">

<div class="sidebar-heading">
    Laporan
</div>

<li class="nav-item <?= ($this->uri->segment(1) == 'laporan') ? 'active' : '' ?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan">
        <i class="fas fa-fw fa-chart-bar"></i>
        <span>Laporan</span>
    </a>

    <div id="collapseLaporan" class="collapse <?= ($this->uri->segment(1) == 'laporan') ? 'show' : '' ?>">
        <div class="bg-white py-2 collapse-inner rounded">

            <a class="collapse-item <?= ($this->uri->segment(2) == 'kunjungan') ? 'active' : '' ?>"
               href="<?= site_url('laporan/kunjungan') ?>">
                Laporan Kunjungan
            </a>

            <a class="collapse-item <?= ($this->uri->segment(2) == 'peminjaman') ? 'active' : '' ?>"
               href="<?= site_url('laporan/peminjaman') ?>">
                Laporan Peminjaman
            </a>

            <a class="collapse-item <?= ($this->uri->segment(2) == 'buku') ? 'active' : '' ?>"
               href="<?= site_url('laporan/buku') ?>">
                Laporan Total Buku
            </a>

            <a class="collapse-item <?= ($this->uri->segment(2) == 'lainnya') ? 'active' : '' ?>"
               href="<?= site_url('laporan/lainnya') ?>">
                Laporan Lain-lain
            </a>

        </div>
    </div>
</li>

<?php endif; ?>

    <?php endif; ?>
    <!-- =============== END ADMIN ================ -->


    <!-- ================= SISWA dan Guru ================= -->
<?php if (in_array($role, [2,3], true)): ?>

<hr class="sidebar-divider">
<div class="sidebar-heading">Menu Siswa</div>

<!-- 👤 AKUN -->
<li class="nav-item">
    <a class="nav-link" href="<?= site_url('profil') ?>">
        <i class="fas fa-fw fa-user-circle"></i>
        <span>Akun Saya</span>
    </a>
</li>

<!-- 📚 BUKU -->
<li class="nav-item">
    <a class="nav-link" href="<?= site_url('buku') ?>">
        <i class="fas fa-fw fa-book-open"></i>
        <span>Buku Perpus</span>
    </a>
</li>

<!-- 📖 E-BOOK -->
<li class="nav-item">
    <a class="nav-link" href="<?= site_url('SiswaEbook') ?>">
        <i class="fas fa-fw fa-tablet-alt"></i>
        <span>E-Book</span>
    </a>
</li>

<!-- 📕 PINJAMAN -->
<li class="nav-item">
    <a class="nav-link" href="<?= site_url('peminjaman/daftar') ?>">
        <i class="fas fa-fw fa-book-reader"></i>
        <span>Daftar Pinjaman</span>
    </a>
</li>

<?php endif; ?>
<!-- =============== END SISWA =============== -->


    <hr class="sidebar-divider">

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('auth/logout') ?>">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

</ul>
