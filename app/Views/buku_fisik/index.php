<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Data Buku Fisik</h1>

    <a href="<?= site_url('buku-fisik/tambah') ?>" class="btn btn-primary btn-sm mb-3">
        <i class="fas fa-plus"></i> Tambah Buku
    </a>
    <div class="card shadow mb-3">
    <div class="card-body">

        <form action="<?= site_url('buku_fisik/import_cover') ?>" 
              method="post" 
              enctype="multipart/form-data">

            <div class="row align-items-center">

                <div class="col-md-5">
                    <input type="file" 
                           name="file_zip" 
                           class="form-control" 
                           accept=".zip" 
                           required>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i> Upload Cover ZIP
                    </button>
                </div>

                <div class="col-md-4 text-muted small">
                    Format: <b>1.jpg, 2.jpg, dst (sesuai ID buku)</b>
                </div>

            </div>

        </form>

    </div>
    <div class="alert alert-info mt-2">
    Upload file ZIP berisi gambar cover dengan nama sesuai ID buku.<br>
    Contoh: <code>1.jpg, 2.jpg, 3.png</code>
</div>
</div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Daftar Buku Fisik
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <form method="get" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="text"
                   name="q"
                   class="form-control"
                   placeholder="Cari judul buku..."
                   value="<?= htmlspecialchars($this->input->get('q')) ?>">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </div>
</form>


                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="40">No</th>
                            <th width="80">Cover</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Rak</th>
                            <th width="90">Kelas</th>
                            <th width="60">Stok</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (empty($buku)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                Data buku belum tersedia
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
$page = $this->input->get('page') ?? 0;
$no = $page + 1;
foreach ($buku as $row):
?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <!-- COVER -->
                            <td class="text-center">
                                <?php if (!empty($row->cover)): ?>
                                    <img src="<?= base_url('uploads/cover/'.$row->cover) ?>"
                                         alt="Cover"
                                         style="width:60px"
                                         class="img-thumbnail">
                                <?php else: ?>
                                    <img src="<?= base_url('assets/sbadmin2/img/undraw_book.svg') ?>"
                                         alt="No Cover"
                                         style="width:60px"
                                         class="img-thumbnail">
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($row->judul) ?></td>
                            <td><?= htmlspecialchars($row->penulis) ?></td>
                            <td><?= $row->nama_kategori ?? '-' ?></td>
                            <td><?= $row->kode_rak ?? '-' ?></td>

                            <!-- 🔥 KELAS -->
                            <td class="text-center">
                                <?php
                                    $badge = 'secondary';
                                    if ($row->kelas == 'X')   $badge = 'primary';
                                    if ($row->kelas == 'XI')  $badge = 'info';
                                    if ($row->kelas == 'XII') $badge = 'warning';
                                    if ($row->kelas == 'Umum')$badge = 'success';
                                ?>
                                <span class="badge badge-<?= $badge ?>">
                                    <?= $row->kelas ?>
                                </span>
                            </td>

                            <td class="text-center"><?= $row->stok ?></td>

                            <!-- AKSI -->
                            <td class="text-center">
                                <a href="<?= site_url('buku-fisik/detail/'.$row->id_buku) ?>"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>

                                <a href="<?= site_url('buku-fisik/edit/'.$row->id_buku) ?>"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <a href="<?= site_url('buku-fisik/hapus/'.$row->id_buku) ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                    Hapus
                                </a>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>

                </table>
                    <div class="mt-3">
    <?= $pagination ?>
</div>

            </div>
        </div>
    </div>

</div>
