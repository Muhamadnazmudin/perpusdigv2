<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">

            <form action="<?= site_url('peminjaman/simpan') ?>" method="post">
                <input type="hidden"
           name="<?= $this->security->get_csrf_token_name(); ?>"
           value="<?= $this->security->get_csrf_hash(); ?>">

                <!-- PILIH SISWA -->
                <div class="form-group">
                    <label>Siswa</label>
                    <select name="id_user" class="form-control" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php foreach ($siswa as $s): ?>
                            <option value="<?= $s->id_user ?>">
                                <?= htmlspecialchars($s->nama) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- PILIH BUKU -->
                <div class="form-group">
                    <label>Buku</label>
                    <select name="id_buku" class="form-control" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php foreach ($buku as $b): ?>
                            <option value="<?= $b->id_buku ?>">
                                <?= htmlspecialchars($b->judul) ?> (stok: <?= $b->stok ?>)
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <button class="btn btn-primary">Simpan</button>
                <a href="<?= site_url('peminjaman') ?>" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>
