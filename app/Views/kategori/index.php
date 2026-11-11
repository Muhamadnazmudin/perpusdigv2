<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Master Kategori</h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="row">

        <!-- TAMBAH KATEGORI -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">

                    <?= form_open('kategori/simpan') ?>

                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text"
                                   name="nama_kategori"
                                   class="form-control"
                                   required>
                        </div>

                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Simpan
                        </button>

                    <?= form_close() ?>

                </div>
            </div>
        </div>

        <!-- LIST KATEGORI -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">

                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Kategori</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($kategori as $k): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($k->nama_kategori) ?></td>
                                <td>
                                    <!-- EDIT -->
                                    <button
                                        class="btn btn-warning btn-sm btn-edit"
                                        data-id="<?= $k->id_kategori ?>"
                                        data-nama="<?= htmlspecialchars($k->nama_kategori) ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- HAPUS -->
                                    <?php if ($k->total_buku > 0): ?>
                                        <button class="btn btn-danger btn-sm" disabled
                                                title="Kategori dipakai buku">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php else: ?>
                                        <a href="<?= site_url('kategori/hapus/'.$k->id_kategori) ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin hapus kategori ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <?= form_open('kategori/update') ?>
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id_kategori" id="edit_id">

                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text"
                           name="nama_kategori"
                           id="edit_nama"
                           class="form-control"
                           required>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Update</button>
            </div>
            <?= form_close() ?>

        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('edit_id').value   = this.dataset.id;
            document.getElementById('edit_nama').value = this.dataset.nama;
            $('#modalEdit').modal('show');
        });
    });
</script>
