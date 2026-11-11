<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="card shadow mb-4">
        <div class="card-body">

            <form action="<?= site_url('guru/tambah') ?>" method="post" class="mb-4">
                <input type="hidden"
           name="<?= $this->security->get_csrf_token_name(); ?>"
           value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="form-row">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="nip" class="form-control" placeholder="NIP">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="text" name="nama_guru" class="form-control" placeholder="Nama Guru" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">No</th>
                            <th>NIP</th>
                            <th>Nama Guru</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($guru as $g): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($g->nip) ?></td>
                            <td><?= htmlspecialchars($g->nama_guru) ?></td>
                            <td><?= htmlspecialchars($g->email) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
