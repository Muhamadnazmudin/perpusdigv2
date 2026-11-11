<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body table-responsive">

            <!-- TOMBOL AKSI -->
<div class="mb-3 d-flex gap-2">
    <a href="<?= site_url('AdminEbook/tambah') ?>"
       class="btn btn-primary">
        + Tambah E-Book
    </a>

    <button class="btn btn-success"
            data-toggle="modal"
            data-target="#importModal">
        ⬆ Import Excel
    </button>
</div>
<form method="get" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="text"
                   name="q"
                   class="form-control"
                   placeholder="Cari judul e-book..."
                   value="<?= htmlspecialchars($this->input->get('q')) ?>">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </div>
</form>

            <table class="table table-bordered table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                    <th width="50">No</th>
                    <th width="80">Cover</th>
                    <th>Judul</th>
                    <th>Mapel</th>
                    <th>Kelas</th>
                    <th width="160">Aksi</th>
                </tr>
                </thead>
                <tbody>

                <?php if (!empty($ebook)): ?>
                    <?php
                        $page = $this->input->get('page') ?? 0;
                        $no = $page + 1;
                        foreach ($ebook as $e):
                    ?>

                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <!-- COVER -->
                            <td class="text-center">
                                <?php if (!empty($e->cover)): ?>
                                    <img src="<?= base_url('assets/uploads/cover_ebook/'.$e->cover) ?>"
                                         alt="Cover"
                                         style="height:60px"
                                         class="img-thumbnail">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- JUDUL -->
                            <td><?= htmlspecialchars($e->judul) ?></td>

                            <!-- MAPEL -->
                            <td><?= htmlspecialchars($e->mapel) ?></td>

                            <!-- KELAS -->
                            <td><?= htmlspecialchars($e->kelas) ?></td>

                            <!-- AKSI -->
                            <td>
                                <a href="<?= site_url('AdminEbook/edit/'.$e->id_ebook) ?>"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <?php if ($this->user['id_role'] == 1): ?>
                                    <a href="<?= site_url('AdminEbook/delete/'.$e->id_ebook) ?>"
                                       onclick="return confirm('Hapus e-book ini?')"
                                       class="btn btn-sm btn-danger">
                                        Hapus
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Data e-book belum tersedia
                            </td>
                        </tr>
                <?php endif; ?>

                </tbody>
            </table>
                <?php if (!empty($pagination)): ?>
    <div class="d-flex justify-content-center mt-4">
        <?= $pagination ?>
    </div>
<?php endif; ?>

        </div>
    </div>

</div>
<!-- MODAL IMPORT -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="<?= site_url('AdminEbook/import') ?>"
                  method="post"
                  enctype="multipart/form-data">

                <div class="modal-header">
                    <h5 class="modal-title">Import E-Book</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info small">
                        Format kolom:
                        <b>judul | mapel | kelas | link_drive</b>
                    </div>

                    <div class="form-group">
                        <label>File Excel (.xlsx / .csv)</label>
                        <input type="file"
                               name="file"
                               class="form-control"
                               accept=".xlsx,.csv"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn btn-success">
                        Import
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
