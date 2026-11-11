<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Master Rak</h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">

                    <?= form_open('rak/simpan') ?>

                        <div class="form-group">
                            <label>Kode Rak</label>
                            <input type="text" name="kode_rak" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" class="form-control">
                        </div>

                        <button class="btn btn-primary">Simpan</button>

                    <?= form_close() ?>

                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Kode Rak</th>
                            <th>Lokasi</th>
                        </tr>
                        <?php $no=1; foreach ($rak as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($r->kode_rak) ?></td>
                            <td><?= htmlspecialchars($r->lokasi) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
