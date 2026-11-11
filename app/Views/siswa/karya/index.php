<div class="container-fluid">
    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h5 text-gray-800 mb-0">
            ✍️ Karya Saya
        </h1>

        <a href="<?= site_url('SiswaKarya/tambah') ?>"
           class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Karya
        </a>
    </div>

    <?php if (!empty($karya)): ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Judul</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($karya as $k): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($k->judul) ?></strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars($k->jenis ?? 'Umum') ?>
                                </td>

                                <td>
                                    <?php if ($k->status === 'PENDING'): ?>
                                        <span class="badge badge-warning">
                                            Menunggu
                                        </span>
                                    <?php elseif ($k->status === 'APPROVED'): ?>
                                        <span class="badge badge-success">
                                            Disetujui
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">
                                            Ditolak
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= date('d M Y', strtotime($k->created_at)) ?>
                                </td>

                                <td>
                                    <?php if ($k->status === 'APPROVED'): ?>
                                        <a href="<?= site_url('SiswaEbook/baca/'.$k->id_ebook) ?>"
                                           class="btn btn-success btn-sm">
                                            Baca
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= site_url('SiswaKarya/edit/'.$k->id_ebook) ?>"
                                           class="btn btn-outline-secondary btn-sm">
                                            Edit
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    <?php else: ?>

        <div class="alert alert-info">
            Belum ada karya. Yuk mulai menulis atau berkarya 😊
        </div>

    <?php endif; ?>

</div>
