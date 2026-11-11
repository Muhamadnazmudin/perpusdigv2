<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .kop {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop img {
            width: 80px;
            float: left;
            margin-right: 15px;
        }

        .kop .text {
            text-align: center;
        }

        .kop h2 {
            margin: 0;
            font-size: 16px;
        }

        .kop p {
            margin: 2px 0;
            font-size: 11px;
        }

        .judul {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        table th {
            background: #f0f0f0;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>
<body>

<!-- KOP -->
<div class="kop">
    <img src="<?= realpath(FCPATH . 'assets/img/logobispar.png') ?>" style="width:80px; float:left;">
    <div class="text">
        <h2>PERPUSTAKAAN SEKOLAH</h2>
        <p>Sistem Informasi Perpustakaan Digital</p>
        <p>Laporan Peminjaman Buku</p>
    </div>
    <div style="clear:both"></div>
</div>

<!-- JUDUL -->
<div class="judul">
    <strong>LAPORAN PEMINJAMAN BUKU</strong><br>
    <?php if ($awal && $akhir): ?>
        Periode <?= date('d-m-Y', strtotime($awal)) ?> s/d <?= date('d-m-Y', strtotime($akhir)) ?>
    <?php else: ?>
        Semua Periode
    <?php endif; ?>
</div>

<!-- TABEL -->
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl Pinjam</th>
            <th>Nama</th>
            <th>NIS</th>
            <th>Kelas</th>
            <th>Judul Buku</th>
            <th>Jatuh Tempo</th>
            <th>Status</th>
            <th>Terlambat</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($laporan)): ?>
            <tr>
                <td colspan="9">Tidak ada data</td>
            </tr>
        <?php endif; ?>

        <?php $no=1; foreach ($laporan as $row): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= date('d-m-Y', strtotime($row->tanggal_pinjam)) ?></td>
            <td class="text-left"><?= $row->nama ?></td>
            <td><?= $row->nis ?></td>
            <td><?= $row->nama_kelas ?? '-' ?></td>
            <td class="text-left"><?= $row->judul ?></td>
            <td><?= date('d-m-Y', strtotime($row->tanggal_jatuh_tempo)) ?></td>
            <td>
                <?= ucfirst($row->status) ?>
            </td>
            <td>
                <?= $row->hari_terlambat > 0 ? $row->hari_terlambat.' hari' : '-' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
