<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 text-gray-800">📚 <?= $title ?></h1>

        <div>
            <a href="<?= site_url('laporan/buku_excel') ?>" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="<?= site_url('laporan/buku_pdf') ?>" target="_blank" class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm mb-0">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>ISBN</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Stok Awal</th>
                            <th>Dipinjam</th>
                            <th>Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $offset + 1;
                        if (!empty($buku)):
                            foreach ($buku as $b):
                                $sisa = $b->stok_awal - $b->dipinjam;
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $b->judul ?></td>
                            <td><?= $b->isbn ?: '-' ?></td>
                            <td><?= $b->penulis ?></td>
                            <td><?= $b->penerbit ?></td>
                            <td class="text-center"><?= $b->tahun ?></td>
                            <td class="text-center font-weight-bold"><?= $b->stok_awal ?></td>
                            <td class="text-center text-warning"><?= $b->dipinjam ?></td>
                            <td class="text-center text-success font-weight-bold"><?= $sisa ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                Tidak ada data buku
                            </td>
                        </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="mt-3">
        <?= $pagination ?>
    </div>

</div>
