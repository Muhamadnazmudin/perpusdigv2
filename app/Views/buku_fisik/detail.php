<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Detail Buku Fisik</h1>

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="row">

                <!-- COVER -->
                <div class="col-md-3 text-center">
                    <?php if (!empty($buku->cover)): ?>
                        <img src="<?= base_url('uploads/cover/'.$buku->cover) ?>"
                             class="img-fluid img-thumbnail"
                             alt="Cover Buku">
                    <?php else: ?>
                        <img src="<?= base_url('assets/sbadmin2/img/undraw_book.svg') ?>"
                             class="img-fluid img-thumbnail"
                             alt="No Cover">
                    <?php endif; ?>
                </div>

                <!-- DETAIL -->
                <div class="col-md-9">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Judul</th>
                            <td><?= htmlspecialchars($buku->judul) ?></td>
                        </tr>
                        <tr>
                            <th>Penulis</th>
                            <td><?= htmlspecialchars($buku->penulis) ?></td>
                        </tr>
                        <tr>
                            <th>Penerbit</th>
                            <td><?= htmlspecialchars($buku->penerbit) ?></td>
                        </tr>
                        <tr>
                            <th>Tahun</th>
                            <td><?= $buku->tahun ?></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td><?= $buku->nama_kategori ?? '-' ?></td>
                        </tr>
                        <tr>
                            <th>Rak</th>
                            <td><?= $buku->kode_rak ?> (<?= $buku->lokasi ?>)</td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td>
                                <span class="badge badge-success">
                                    <?= $buku->stok ?>
                                </span>
                            </td>
                        </tr>
                    </table>

                    <a href="<?= site_url('buku-fisik/edit/'.$buku->id_buku) ?>"
                       class="btn btn-warning">
                        Edit
                    </a>

                    <a href="<?= site_url('buku-fisik') ?>"
                       class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </div>

        </div>
    </div>

</div>
