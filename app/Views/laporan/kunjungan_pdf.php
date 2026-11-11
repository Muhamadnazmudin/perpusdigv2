<h3 align="center">LAPORAN KUNJUNGAN PERPUSTAKAAN</h3>
<hr>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th width="4%">No</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>NIS</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Jurusan</th>
        <th>Tujuan</th>
    </tr>

    <?php $no = 1; foreach($laporan as $l): ?>
    <tr>
        <td align="center"><?= $no++ ?></td>
        <td><?= $l->tanggal ?></td>
        <td><?= $l->jam ?></td>
        <td><?= $l->nis ?></td>
        <td><?= $l->nama_siswa ?></td>
        <td><?= $l->nama_kelas ?></td>
        <td><?= $l->nama_jurusan ?></td>
        <td><?= $l->tujuan ?></td>
    </tr>
    <?php endforeach ?>
</table>
