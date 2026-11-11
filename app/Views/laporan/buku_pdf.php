<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Buku</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background: #eee; text-align: center; }
    </style>
</head>
<body>

<h3 style="text-align:center">LAPORAN BUKU</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>ISBN</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Stok Awal</th>
            <th>Dipinjam</th>
            <th>Sisa</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach ($buku as $b): 
            $sisa = $b->stok_awal - $b->dipinjam;
        ?>
        <tr>
            <td align="center"><?= $no++ ?></td>
            <td><?= $b->judul ?></td>
            <td><?= $b->isbn ?: '-' ?></td>
            <td><?= $b->penulis ?></td>
            <td><?= $b->penerbit ?></td>
            <td align="center"><?= $b->stok_awal ?></td>
            <td align="center"><?= $b->dipinjam ?></td>
            <td align="center"><?= $sisa ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

</body>
</html>
