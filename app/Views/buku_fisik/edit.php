<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Edit Buku Fisik</h1>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">

            <!-- ⬇️ WAJIB underscore -->
            <?= form_open_multipart('buku_fisik/update/'.$buku->id_buku) ?>

            <!-- ISBN -->
            <div class="form-group">
                <label>ISBN</label>
                <input type="text" name="isbn" class="form-control"
                       value="<?= htmlspecialchars($buku->isbn) ?>"
                       placeholder="Contoh: 978-602-1234-56-7">
                <small class="text-muted">Boleh dikosongkan jika buku lama</small>
            </div>

            <!-- Judul -->
            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul" class="form-control"
                       value="<?= htmlspecialchars($buku->judul) ?>" required>
            </div>

            <!-- Penulis -->
            <div class="form-group">
                <label>Penulis</label>
                <input type="text" name="penulis" class="form-control"
                       value="<?= htmlspecialchars($buku->penulis) ?>">
            </div>

            <!-- Penerbit -->
            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" class="form-control"
                       value="<?= htmlspecialchars($buku->penerbit) ?>">
            </div>

            <!-- Tahun -->
            <div class="form-group">
                <label>Tahun</label>
                <input type="number" name="tahun" class="form-control"
                       value="<?= $buku->tahun ?>">
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k->id_kategori ?>"
                            <?= $k->id_kategori == $buku->id_kategori ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k->nama_kategori) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Rak -->
            <div class="form-group">
                <label>Rak</label>
                <select name="id_rak" class="form-control" required>
                    <option value="">-- Pilih Rak --</option>
                    <?php foreach ($rak as $r): ?>
                        <option value="<?= $r->id_rak ?>"
                            <?= $r->id_rak == $buku->id_rak ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r->kode_rak) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
<!-- Kelas -->
<div class="form-group">
    <label>Kelas</label>
    <select name="kelas" class="form-control" required>
        <option value="">-- Pilih Kelas --</option>
        <option value="X"   <?= $buku->kelas=='X'   ? 'selected' : '' ?>>X</option>
        <option value="XI"  <?= $buku->kelas=='XI'  ? 'selected' : '' ?>>XI</option>
        <option value="XII" <?= $buku->kelas=='XII' ? 'selected' : '' ?>>XII</option>
        <option value="Umum"<?= $buku->kelas=='Umum'? 'selected' : '' ?>>Umum</option>
    </select>
    <small class="text-muted">
        Pilih <strong>Umum</strong> jika buku dapat dipinjam semua kelas
    </small>
</div>

            <!-- Stok -->
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control"
                       value="<?= $buku->stok ?>" required>
            </div>

            <!-- Cover -->
            <div class="form-group">
                <label>Cover Buku</label><br>

                <?php if (!empty($buku->cover)): ?>
                    <img src="<?= base_url('uploads/cover/'.$buku->cover) ?>"
                         width="120" class="img-thumbnail mb-2">
                <?php endif; ?>

                <input type="file" name="cover" class="form-control-file">
                <small class="text-muted">Kosongkan jika tidak diganti</small>
            </div>

            <!-- Button -->
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
            </button>

            <a href="<?= site_url('buku_fisik') ?>" class="btn btn-secondary">
                Kembali
            </a>

            <?= form_close() ?>

        </div>
    </div>

</div>
