<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Laporan Peminjaman Buku</h1>

    <!-- FILTER + EXPORT -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">

            <form method="get" class="form-inline">

                <div class="form-group mr-2">
                    <label class="mr-2">Dari</label>
                    <input type="date" name="awal" class="form-control"
                           value="<?= $this->input->get('awal') ?>">
                </div>

                <div class="form-group mr-2">
                    <label class="mr-2">Sampai</label>
                    <input type="date" name="akhir" class="form-control"
                           value="<?= $this->input->get('akhir') ?>">
                </div>

                <button class="btn btn-primary mr-2">
                    <i class="fas fa-filter"></i> Filter
                </button>

                <a href="<?= site_url('laporan/peminjaman') ?>" class="btn btn-secondary">
                    Reset
                </a>
            </form>

            <!-- EXPORT -->
            <div class="mb-3">
    <a href="<?= site_url('laporan/peminjaman_pdf?awal='.$this->input->get('awal').'&akhir='.$this->input->get('akhir')) ?>"
       class="btn btn-danger"
       target="_blank">
        <i class="fas fa-file-pdf"></i> Cetak PDF
    </a>

    <a href="<?= site_url('laporan/peminjaman_excel?awal='.$this->input->get('awal').'&akhir='.$this->input->get('akhir')) ?>"
       class="btn btn-success ml-2">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
</div>


        </div>
    </div>

    <!-- TABEL -->
    <div class="card shadow">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Buku</th>
                        <th>Jatuh Tempo</th>
                        <th>Kembali</th>
                        <th>Status</th>
                        <th>Terlambat</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (empty($laporan)): ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted">Tidak ada data</td>
                    </tr>
                <?php endif; ?>

                <?php
                    $page = $this->input->get('page') ?? 0;
                    $no = $page + 1;
                    foreach ($laporan as $row):
                    ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d-m-Y', strtotime($row->tanggal_pinjam)) ?></td>
                        <td><?= htmlspecialchars($row->nama) ?></td>
                        <td><?= htmlspecialchars($row->nis) ?></td>
                        <td><?= htmlspecialchars($row->nama_kelas ?? '-') ?></td>
                        <td><?= htmlspecialchars($row->judul) ?></td>
                        <td><?= $row->tanggal_jatuh_tempo ? date('d-m-Y', strtotime($row->tanggal_jatuh_tempo)) : '-' ?></td>

                        <!-- KEMBALI -->
                        <td>
                            <?= ($row->status === 'kembali' && $row->tanggal_kembali)
                                ? date('d-m-Y', strtotime($row->tanggal_kembali))
                                : '-' ?>
                        </td>

                        <!-- STATUS -->
                        <td>
                            <?php if ($row->status === 'menunggu'): ?>
                                <span class="badge badge-warning">Menunggu</span>
                            <?php elseif ($row->status === 'dipinjam'): ?>
                                <span class="badge badge-primary">Dipinjam</span>
                            <?php elseif ($row->status === 'ditolak'): ?>
                                <span class="badge badge-danger">Ditolak</span>
                            <?php elseif ($row->status === 'kembali'): ?>
                                <span class="badge badge-success">Kembali</span>
                            <?php endif; ?>
                        </td>

                        <!-- TERLAMBAT -->
                        <td>
                            <?php if ($row->hari_terlambat > 0): ?>
                                <span class="badge badge-danger"><?= $row->hari_terlambat ?> hari</span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-center">
    <?= $pagination ?>
</div>


        </div>
    </div>

</div>
