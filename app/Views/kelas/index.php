<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="card shadow mb-4">
        <div class="card-body">

            <form action="<?= site_url('kelas/tambah') ?>" method="post" class="form-inline mb-3">
                <input type="hidden"
           name="<?= $this->security->get_csrf_token_name(); ?>"
           value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="text" name="nama_kelas" class="form-control mr-2" placeholder="Nama Kelas" required>
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Kelas</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($kelas as $k): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($k->nama_kelas) ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('kelas/hapus/'.$k->id_kelas) ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus data ini?')">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
