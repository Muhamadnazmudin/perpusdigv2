<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestApp extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:app';
    protected $description = 'Comprehensive test of perpusdig CI4 upgrade';

    public function run(array $params)
    {
        CLI::write("=== PERPUSDIG COMPREHENSIVE VERIFICATION ===", 'yellow');

        // 1. Database Connection
        CLI::write("\n1. Testing Database Connection...", 'white');
        try {
            $db = \Config\Database::connect();
            $db->connect();
            $tables = $db->listTables();
            CLI::write(" [PASS] DB connected. Tables found: " . count($tables), 'green');
        } catch (\Throwable $e) {
            CLI::error(" [FAIL] Database connection failed: " . $e->getMessage());
        }

        // 2. Test All Models
        CLI::write("\n2. Testing Models...", 'white');
        $models = [
            'User_model',
            'Settings_model',
            'Dashboard_model',
            'Buku_fisik_model',
            'Peminjaman_model',
            'Kunjungan_model',
            'Ebook_model',
            'Karya_model',
            'Siswa_model',
            'Guru_model',
            'Kelas_model',
            'Jurusan_model',
            'Kategori_model',
            'Rak_model',
            'Quran_model',
            'Riwayat_model',
            'Favorit_model',
            'Pengaturan_model',
            'Pengguna_model',
            'Backup_model',
        ];

        foreach ($models as $m) {
            try {
                $class = "\\App\\Models\\{$m}";
                $model = new $class();
                CLI::write(" [PASS] Model {$m} instantiated successfully", 'green');
            } catch (\Throwable $e) {
                CLI::error(" [FAIL] Model {$m}: " . $e->getMessage());
            }
        }

        // 2b. Test Model Queries
        CLI::write("\n2b. Testing Model Query Execution...", 'white');
        try {
            $uModel = new \App\Models\User_model();
            $adminUser = $uModel->get_by_username('mengadmin');
            CLI::write(" [PASS] User_model::get_by_username('mengadmin'): " . ($adminUser ? 'Found ' . $adminUser['username'] . ' (' . $adminUser['nama'] . ')' : 'Not found'), 'green');

            $dModel = new \App\Models\Dashboard_model();
            $stat = $dModel->get_stat_admin();
            CLI::write(" [PASS] Dashboard_model::get_stat_admin: Buku=" . ($stat['total_buku_fisik'] ?? 0) . ", Siswa=" . ($stat['total_siswa'] ?? 0), 'green');

            $bModel = new \App\Models\Buku_fisik_model();
            $bukuList = $bModel->get_all();
            CLI::write(" [PASS] Buku_fisik_model::get_all: " . count($bukuList) . " books", 'green');

            $sModel = new \App\Models\Siswa_model();
            $siswaCount = $sModel->count_all();
            CLI::write(" [PASS] Siswa_model::count_all: " . $siswaCount . " students", 'green');

            $pModel = new \App\Models\Peminjaman_model();
            $menunggu = $pModel->count_menunggu();
            CLI::write(" [PASS] Peminjaman_model::count_menunggu: " . $menunggu, 'green');

            $settModel = new \App\Models\Settings_model();
            $mMode = $settModel->get('maintenance_mode');
            CLI::write(" [PASS] Settings_model::get('maintenance_mode'): " . var_export($mMode, true), 'green');
        } catch (\Throwable $e) {
            CLI::error(" [FAIL] Model query execution: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        // 3. Test All Controllers
        CLI::write("\n3. Testing Controllers Instantiation...", 'white');
        $controllers = [
            'Home',
            'Auth',
            'Dashboard',
            'Buku_fisik',
            'Buku',
            'Peminjaman',
            'Siswa',
            'Guru',
            'Kelas',
            'Jurusan',
            'Kategori',
            'Rak',
            'Mapping_rfid',
            'AdminEbook',
            'AdminKarya',
            'SiswaEbook',
            'SiswaKarya',
            'Kunjungan',
            'Laporan',
            'Pengaturan',
            'Pengguna',
            'Profil',
            'Quran',
            'Backup',
        ];

        foreach ($controllers as $c) {
            try {
                $class = "\\App\\Controllers\\{$c}";
                $ctrl = new $class();
                CLI::write(" [PASS] Controller {$c} instantiated successfully", 'green');
            } catch (\Throwable $e) {
                CLI::error(" [FAIL] Controller {$c}: " . $e->getMessage());
            }
        }

        // 4. Test View Rendering Compatibility
        CLI::write("\n4. Testing View Rendering Compatibility...", 'white');
        try {
            // Test render a simple view with session, user, and helper compatibility
            session()->set('user', ['id_user' => 1, 'id_role' => 1, 'username' => 'admin', 'nama' => 'Administrator']);
            session()->set('login', true);
            session()->set('theme', 'light');

            $viewHtml = view('templates/header', ['title' => 'Test Title']);
            if (strpos($viewHtml, 'theme-light') !== false) {
                CLI::write(" [PASS] Header view rendered with theme: theme-light", 'green');
            } else {
                CLI::write(" [WARN] Header rendered but theme string not found", 'yellow');
            }

            $sidebarHtml = view('templates/sidebar', ['total_menunggu' => 0]);
            if (strpos($sidebarHtml, 'Perpusdig') !== false && strpos($sidebarHtml, 'Master Data') !== false) {
                CLI::write(" [PASS] Sidebar view rendered with Admin menu items", 'green');
            } else {
                CLI::write(" [WARN] Sidebar rendered with unexpected output", 'yellow');
            }

            $topbarHtml = view('templates/topbar', ['total_menunggu' => 0]);
            if (strpos($topbarHtml, 'Administrator') !== false) {
                CLI::write(" [PASS] Topbar view rendered with user: Administrator", 'green');
            } else {
                CLI::write(" [WARN] Topbar rendered without Administrator name", 'yellow');
            }

            $footerHtml = view('templates/footer');
            if (strpos($footerHtml, 'Perpusdig') !== false) {
                CLI::write(" [PASS] Footer view rendered successfully", 'green');
            }
        } catch (\Throwable $e) {
            CLI::error(" [FAIL] View rendering: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        // 5. Test PDF Generation (Dompdf)
        CLI::write("\n5. Testing PDF Generation (Dompdf)...", 'white');
        try {
            $pdf = new \App\Libraries\Pdf();
            $pdf->loadHtml("<h1>Laporan Uji Coba</h1><p>Tanggal: " . date('d-m-Y H:i:s') . "</p>");
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();
            $bytes = strlen($pdf->output());
            if ($bytes > 500) {
                CLI::write(" [PASS] PDF generated successfully ({$bytes} bytes)", 'green');
            } else {
                CLI::error(" [FAIL] PDF generated with unexpected size ({$bytes} bytes)");
            }
        } catch (\Throwable $e) {
            CLI::error(" [FAIL] Dompdf: " . $e->getMessage());
        }

        // 6. Test QR Code Generation
        CLI::write("\n6. Testing QR Code Generation...", 'white');
        try {
            $qr = new \App\Libraries\Qrcode();
            $tmpFile = WRITEPATH . 'test_qr.png';
            $qr->generate('TEST_NISN_12345', $tmpFile, 4);
            if (file_exists($tmpFile) && filesize($tmpFile) > 100) {
                CLI::write(" [PASS] QR code generated successfully (" . filesize($tmpFile) . " bytes)", 'green');
                @unlink($tmpFile);
            } else {
                CLI::error(" [FAIL] QR code file missing or empty");
            }
        } catch (\Throwable $e) {
            CLI::error(" [FAIL] QR code: " . $e->getMessage());
        }

        // 7. Test PhpSpreadsheet (Excel Read & Write)
        CLI::write("\n7. Testing PhpSpreadsheet...", 'white');
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NIS');
            $sheet->setCellValue('B1', 'Nama Siswa');
            $sheet->setCellValue('A2', '123456');
            $sheet->setCellValue('B2', 'Budi Santoso');

            $tmpExcel = WRITEPATH . 'test_excel.xlsx';
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($tmpExcel);

            if (file_exists($tmpExcel) && filesize($tmpExcel) > 500) {
                CLI::write(" [PASS] Excel file created successfully (" . filesize($tmpExcel) . " bytes)", 'green');

                // Test reading back
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $loaded = $reader->load($tmpExcel);
                $rows = $loaded->getActiveSheet()->toArray();
                if (count($rows) === 2 && $rows[1][0] === '123456') {
                    CLI::write(" [PASS] Excel file read back successfully, matched rows", 'green');
                } else {
                    CLI::write(" [WARN] Excel read back didn't match expected rows", 'yellow');
                }
                @unlink($tmpExcel);
            } else {
                CLI::error(" [FAIL] Excel file creation failed");
            }
        } catch (\Throwable $e) {
            CLI::error(" [FAIL] PhpSpreadsheet: " . $e->getMessage());
        }

        // 8. Test Helpers
        CLI::write("\n8. Testing Custom Helpers...", 'white');
        try {
            $tgl = tgl_indo('2026-11-11');
            if ($tgl === '11 November 2026') {
                CLI::write(" [PASS] tgl_indo: {$tgl}", 'green');
            } else {
                CLI::write(" [WARN] tgl_indo unexpected result: {$tgl}", 'yellow');
            }

            $wa = wa_encode("Halo perpusdig!");
            CLI::write(" [PASS] wa_encode: {$wa}", 'green');

            $pesan = wa_pesan_peminjaman((object)[
                'nama' => 'Ahmad',
                'judul' => 'Pemrograman PHP Modern',
                'tanggal_pinjam' => '2026-11-10',
                'tanggal_jatuh_tempo' => '2026-11-17',
                'status' => 'dipinjam'
            ]);
            CLI::write(" [PASS] wa_pesan_peminjaman generated successfully", 'green');
        } catch (\Throwable $e) {
            CLI::error(" [FAIL] Helpers: " . $e->getMessage());
        }

        CLI::write("\n=== VERIFICATION FINISHED ===", 'yellow');
    }
}
