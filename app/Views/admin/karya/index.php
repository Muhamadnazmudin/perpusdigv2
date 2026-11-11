<div class="container-fluid">

    <h1 class="h5 mb-4 text-gray-800">🧑‍💼 Approval Karya Siswa</h1>

    <?php if (!empty($karya)): ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Judul</th>
                                <th>Jenis</th>
                                <th>Pembuat</th>
                                <th>Tanggal</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($karya as $k): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($k->judul) ?></strong></td>
                                <td><?= htmlspecialchars($k->jenis) ?></td>
                                <td>ID User: <?= $k->created_by ?></td>
                                <td><?= date('d M Y', strtotime($k->created_at)) ?></td>
                                <td>
    <?php if ($k->status === 'PENDING'): ?>
        <span class="badge badge-warning mb-1 d-block">
            Pending
        </span>

        <a href="<?= site_url('AdminKarya/detail/'.$k->id_ebook) ?>"
           class="btn btn-sm btn-info">
            Review
        </a>

    <?php elseif ($k->status === 'APPROVED'): ?>
        <span class="badge badge-success">
            Approved
        </span>

    <?php else: ?>
        <span class="badge badge-danger">
            Rejected
        </span>
    <?php endif; ?>
</td>

                                </td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    <?php else: ?>

        <div class="alert alert-success">
            🎉 Tidak ada karya yang menunggu persetujuan
        </div>

    <?php endif; ?>

</div>
