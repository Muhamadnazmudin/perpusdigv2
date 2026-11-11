<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($peminjaman)): ?>
        <div class="alert alert-info">
            Kamu belum memiliki pinjaman aktif.
        </div>
    <?php else: ?>

    <div class="card shadow">
        <div class="card-body table-responsive">

            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach ($peminjaman as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($p->judul) ?></td>
                        <td><?= $p->tanggal_pinjam ?></td>
                        <td><?= $p->tanggal_jatuh_tempo ?></td>
                        <td>
    <?php if ($p->status == 'menunggu'): ?>
        <span class="badge badge-warning">Menunggu Persetujuan</span>

    <?php elseif ($p->status == 'dipinjam'): ?>

        <?php if ($p->hari_terlambat > 0): ?>
            <span class="badge badge-danger">
                Terlambat <?= $p->hari_terlambat ?> hari
            </span>
        <?php else: ?>
            <span class="badge badge-success">Dipinjam</span>
        <?php endif; ?>

    <?php elseif ($p->status == 'ditolak'): ?>
        <span class="badge badge-danger">Ditolak</span>

    <?php elseif ($p->status == 'kembali'): ?>
        <span class="badge badge-secondary">Dikembalikan</span>
    <?php endif; ?>
</td>

                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php endif; ?>

</div>
