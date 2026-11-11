<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <?php if (empty($peminjaman)): ?>
        <div class="alert alert-info">
            Belum ada riwayat peminjaman.
        </div>
    <?php else: ?>

    <div class="card shadow">
        <div class="card-body table-responsive">

            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach ($peminjaman as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($p->judul) ?></td>
                        <td><?= $p->tanggal_pinjam ?></td>
                        <td>
                            <?= $p->tanggal_kembali ?? '-' ?>
                        </td>
                        <td>
                            <span class="badge badge-<?= $p->status=='kembali'?'success':'warning' ?>">
                                <?= ucfirst($p->status) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php endif; ?>

</div>
