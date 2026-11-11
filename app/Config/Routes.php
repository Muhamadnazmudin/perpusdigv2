<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->match(['get', 'post'], 'login/admin', 'Auth::login_admin');
$routes->match(['get', 'post'], 'auth/login', 'Auth::login');
$routes->match(['get', 'post'], 'auth/login_admin', 'Auth::login_admin');
$routes->get('auth/logout', 'Auth::logout');

$routes->get('dashboard', 'Dashboard::index');
$routes->get('dashboard/toggle_maintenance', 'Dashboard::toggle_maintenance');

// Buku Fisik routes
$routes->get('buku-fisik', 'Buku_fisik::index');
$routes->get('buku-fisik/tambah', 'Buku_fisik::tambah');
$routes->post('buku-fisik/simpan', 'Buku_fisik::simpan');
$routes->get('buku-fisik/edit/(:segment)', 'Buku_fisik::edit/$1');
$routes->post('buku-fisik/update/(:segment)', 'Buku_fisik::update/$1');
$routes->get('buku-fisik/hapus/(:segment)', 'Buku_fisik::hapus/$1');
$routes->get('buku-fisik/detail/(:segment)', 'Buku_fisik::detail/$1');
$routes->post('buku-fisik/import', 'Buku_fisik::import');
$routes->post('buku-fisik/import_cover', 'Buku_fisik::import_cover');

// Also allow underscore buku_fisik
$routes->get('buku_fisik', 'Buku_fisik::index');
$routes->get('buku_fisik/tambah', 'Buku_fisik::tambah');
$routes->post('buku_fisik/simpan', 'Buku_fisik::simpan');
$routes->get('buku_fisik/edit/(:segment)', 'Buku_fisik::edit/$1');
$routes->post('buku_fisik/update/(:segment)', 'Buku_fisik::update/$1');
$routes->get('buku_fisik/hapus/(:segment)', 'Buku_fisik::hapus/$1');
$routes->get('buku_fisik/detail/(:segment)', 'Buku_fisik::detail/$1');
$routes->post('buku_fisik/import', 'Buku_fisik::import');
$routes->post('buku_fisik/import_cover', 'Buku_fisik::import_cover');

// Peminjaman routes
$routes->get('peminjaman', 'Peminjaman::index');
$routes->get('peminjaman/tambah', 'Peminjaman::tambah');
$routes->post('peminjaman/simpan', 'Peminjaman::simpan');
$routes->get('peminjaman/kembali/(:segment)', 'Peminjaman::kembali/$1');
$routes->get('peminjaman/setujui/(:segment)', 'Peminjaman::setujui/$1');
$routes->get('peminjaman/tolak/(:segment)', 'Peminjaman::tolak/$1');
$routes->get('peminjaman/pinjam/(:segment)', 'Peminjaman::pinjam/$1');
$routes->get('peminjaman/daftar', 'Peminjaman::daftar');
$routes->get('peminjaman/riwayat', 'Peminjaman::riwayat');

// Quran routes
$routes->get('quran', 'Quran::index');
$routes->get('quran/baca/(:segment)', 'Quran::baca/$1');
$routes->post('quran/bookmark/(:segment)/(:segment)', 'Quran::bookmark/$1/$2');

// Kategori routes
$routes->get('kategori', 'Kategori::index');
$routes->post('kategori/simpan', 'Kategori::simpan');
$routes->post('kategori/update', 'Kategori::update');
$routes->get('kategori/hapus/(:segment)', 'Kategori::hapus/$1');

// Rak routes
$routes->get('rak', 'Rak::index');
$routes->post('rak/simpan', 'Rak::simpan');

// Kelas routes
$routes->get('kelas', 'Kelas::index');
$routes->post('kelas/tambah', 'Kelas::tambah');
$routes->get('kelas/hapus/(:segment)', 'Kelas::hapus/$1');

// Jurusan routes
$routes->get('jurusan', 'Jurusan::index');
$routes->post('jurusan/tambah', 'Jurusan::tambah');
$routes->get('jurusan/hapus/(:segment)', 'Jurusan::hapus/$1');

// Guru routes
$routes->get('guru', 'Guru::index');
$routes->post('guru/tambah', 'Guru::tambah');
$routes->get('guru/sync_guru_user', 'Guru::sync_guru_user');

// Siswa routes
$routes->get('siswa', 'Siswa::index');
$routes->get('siswa/index', 'Siswa::index');
$routes->get('siswa/index/(:segment)', 'Siswa::index/$1');
$routes->post('siswa/tambah', 'Siswa::tambah');
$routes->post('siswa/edit', 'Siswa::edit');
$routes->get('siswa/hapus/(:segment)', 'Siswa::hapus/$1');
$routes->post('siswa/import', 'Siswa::import');
$routes->get('siswa/kenaikan_kelas', 'Siswa::kenaikan_kelas');
$routes->post('siswa/proses_kenaikan_kelas', 'Siswa::proses_kenaikan_kelas');
$routes->post('siswa/simpan_kenaikan', 'Siswa::simpan_kenaikan');

// Mapping RFID routes
$routes->get('mapping_rfid', 'Mapping_rfid::index');
$routes->post('mapping_rfid/get_siswa', 'Mapping_rfid::get_siswa');
$routes->post('mapping_rfid/simpan', 'Mapping_rfid::simpan');
$routes->post('mapping_rfid/reset_kelas', 'Mapping_rfid::reset_kelas');

// Admin Ebook routes
$routes->get('AdminEbook', 'AdminEbook::index');
$routes->get('adminEbook', 'AdminEbook::index');
$routes->get('adminebook', 'AdminEbook::index');
$routes->get('AdminEbook/tambah', 'AdminEbook::tambah');
$routes->post('AdminEbook/simpan', 'AdminEbook::simpan');
$routes->post('AdminEbook/import', 'AdminEbook::import');
$routes->get('AdminEbook/edit/(:segment)', 'AdminEbook::edit/$1');
$routes->post('AdminEbook/update/(:segment)', 'AdminEbook::update/$1');
$routes->get('AdminEbook/delete/(:segment)', 'AdminEbook::delete/$1');

// Admin Karya routes
$routes->get('AdminKarya', 'AdminKarya::index');
$routes->get('adminkarya', 'AdminKarya::index');
$routes->get('AdminKarya/detail/(:segment)', 'AdminKarya::detail/$1');
$routes->get('AdminKarya/approve/(:segment)', 'AdminKarya::approve/$1');
$routes->get('AdminKarya/reject/(:segment)', 'AdminKarya::reject/$1');

// Siswa Ebook routes
$routes->get('SiswaEbook', 'SiswaEbook::index');
$routes->get('siswaebook', 'SiswaEbook::index');
$routes->get('SiswaEbook/baca/(:segment)', 'SiswaEbook::baca/$1');
$routes->get('SiswaEbook/riwayat', 'SiswaEbook::riwayat');
$routes->get('SiswaEbook/favorit/(:segment)', 'SiswaEbook::favorit/$1');
$routes->post('SiswaEbook/update_progress', 'SiswaEbook::update_progress');

// Siswa Karya routes
$routes->get('SiswaKarya', 'SiswaKarya::index');
$routes->get('siswakarya', 'SiswaKarya::index');
$routes->get('SiswaKarya/tambah', 'SiswaKarya::tambah');
$routes->post('SiswaKarya/simpan', 'SiswaKarya::simpan');
$routes->get('SiswaKarya/edit/(:segment)', 'SiswaKarya::edit/$1');
$routes->post('SiswaKarya/update/(:segment)', 'SiswaKarya::update/$1');

// Kunjungan routes
$routes->get('kunjungan', 'Kunjungan::index');
$routes->match(['get', 'post'], 'kunjungan/scan', 'Kunjungan::scan');
$routes->match(['get', 'post'], 'kunjungan/scan_rfid', 'Kunjungan::scan_rfid');
$routes->post('kunjungan/simpan', 'Kunjungan::simpan');

// Laporan routes
$routes->get('laporan/kunjungan', 'Laporan::kunjungan');
$routes->get('laporan/kunjungan_excel', 'Laporan::kunjungan_excel');
$routes->get('laporan/kunjungan_pdf', 'Laporan::kunjungan_pdf');
$routes->get('laporan/peminjaman', 'Laporan::peminjaman');
$routes->get('laporan/peminjaman_excel', 'Laporan::peminjaman_excel');
$routes->get('laporan/peminjaman_pdf', 'Laporan::peminjaman_pdf');
$routes->get('laporan/buku', 'Laporan::buku');
$routes->get('laporan/buku_excel', 'Laporan::buku_excel');
$routes->get('laporan/buku_pdf', 'Laporan::buku_pdf');
$routes->get('laporan/lainnya', 'Laporan::lainnya');

// Pengaturan routes
$routes->get('pengaturan/sekolah', 'Pengaturan::sekolah');
$routes->post('pengaturan/simpan_sekolah', 'Pengaturan::simpan_sekolah');
$routes->post('pengaturan/simpan_tema', 'Pengaturan::simpan_tema');

// Pengguna routes
$routes->get('pengguna', 'Pengguna::index');
$routes->get('pengguna/role/(:segment)', 'Pengguna::role/$1');
$routes->match(['get', 'post'], 'pengguna/edit/(:segment)', 'Pengguna::edit/$1');
$routes->post('pengguna/tambah_admin', 'Pengguna::tambah_admin');

// Profil routes
$routes->get('profil', 'Profil::index');
$routes->post('profil', 'Profil::index');
$routes->post('profil/upload_foto', 'Profil::upload_foto');

// Backup routes
$routes->get('backup', 'Backup::index');
$routes->get('backup/backup', 'Backup::backup');
$routes->post('backup/restore', 'Backup::restore');

// Buku routes (siswa catalog)
$routes->get('buku', 'Buku::index');
$routes->get('buku/detail/(:segment)', 'Buku::detail/$1');
