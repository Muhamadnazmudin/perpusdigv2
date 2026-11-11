<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Tambah Buku Fisik</h1>

    <!-- Flash Error -->
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">

            <!-- TABS -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#manual">
                        <i class="fas fa-keyboard"></i> Tambah Manual
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#import">
                        <i class="fas fa-file-excel"></i> Import Excel
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                <!-- ===================== MANUAL ===================== -->
                <div class="tab-pane fade show active" id="manual">

                    <?= form_open_multipart('buku_fisik/simpan') ?>

                    <div class="form-group">
                        <label>Judul Buku</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="form-group">
    <label>ISBN</label>
    <input type="text" name="isbn" class="form-control"
           placeholder="Contoh: 978-602-1234-56-7">
    <small class="text-muted">Boleh dikosongkan jika buku lama</small>
</div>

                    <div class="form-group">
                        <label>Penulis</label>
                        <input type="text" name="penulis" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Penerbit</label>
                        <input type="text" name="penerbit" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number" name="tahun" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="id_kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= $k->id_kategori ?>">
                                    <?= htmlspecialchars($k->nama_kategori) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                        <div class="form-group">
                        <label>Kelas</label>
                        <select name="kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                            <option value="Umum">Umum</option>
                        </select>
                        <small class="text-muted">
                            Pilih <strong>Umum</strong> jika buku bisa dipinjam semua kelas
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Rak</label>
                        <select name="id_rak" class="form-control" required>
                            <option value="">-- Pilih Rak --</option>
                            <?php foreach ($rak as $r): ?>
                                <option value="<?= $r->id_rak ?>">
                                    <?= htmlspecialchars($r->kode_rak) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Cover Buku</label>
                        <input type="file" name="cover" class="form-control-file">
                        <small class="text-muted">JPG / PNG | Maks 2MB</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>

                    <a href="<?= site_url('buku_fisik') ?>" class="btn btn-secondary">
                        Kembali
                    </a>

                    <?= form_close() ?>

                </div>

                <!-- ===================== IMPORT ===================== -->
                <div class="tab-pane fade" id="import">

                    <?= form_open_multipart('buku_fisik/import') ?>

                    <div class="alert alert-info">
    <strong>Format Excel wajib:</strong><br>
    isbn | judul | penulis | penerbit | tahun | id_kategori | id_rak | stok | kelas
</div>



                    <div class="form-group">
                        <label>File Excel</label>
                        <input type="file" name="file_excel" class="form-control-file" required>
                        <small class="text-muted">Format: .xls / .xlsx</small>
                    </div>

                    <div class="form-group">
                        <label>Kategori (default jika kosong)</label>
                        <select name="default_kategori" class="form-control">
                            <option value="">-- Dari Excel --</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= $k->id_kategori ?>">
                                    <?= htmlspecialchars($k->nama_kategori) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                            <div class="form-group">
                            <label>Kelas (default jika kosong)</label>
                            <select name="default_kelas" class="form-control">
                                <option value="">-- Dari Excel --</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                                <option value="Umum">Umum</option>
                            </select>
                        </div>

                    <div class="form-group">
                        <label>Rak (default jika kosong)</label>
                        <select name="default_rak" class="form-control">
                            <option value="">-- Dari Excel --</option>
                            <?php foreach ($rak as $r): ?>
                                <option value="<?= $r->id_rak ?>">
                                    <?= htmlspecialchars($r->kode_rak) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <a href="<?= base_url('template/template_buku_fisik.xlsx') ?>"
                       class="btn btn-success btn-sm mb-3">
                        <i class="fas fa-download"></i> Download Template
                    </a>

                    <br>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-import"></i> Import Data
                    </button>

                    <a href="<?= site_url('buku_fisik') ?>" class="btn btn-secondary">
                        Kembali
                    </a>

                    <?= form_close() ?>

                </div>

            </div>

        </div>
    </div>

</div>
