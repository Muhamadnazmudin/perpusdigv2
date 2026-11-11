<div class="container-fluid">

    <h1 class="h4 mb-4 text-gray-800"><?= $title ?></h1>

    <!-- ================= FILTER ================= -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <strong>Filter Laporan</strong>
        </div>
        <div class="card-body">

            <form method="get" class="form-row">

                <div class="col-md-3 mb-2">
                    <label>Kelas</label>
                    <select name="kelas" class="form-control">
                        <option value="">Semua</option>
                        <?php foreach($kelas as $k): ?>
                            <option value="<?= $k->nama_kelas ?>"
                                <?= ($this->input->get('kelas') == $k->nama_kelas) ? 'selected' : '' ?>>
                                <?= $k->nama_kelas ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <label>Jurusan</label>
                    <select name="jurusan" class="form-control">
                        <option value="">Semua</option>
                        <?php foreach($jurusan as $j): ?>
                            <option value="<?= $j->nama_jurusan ?>"
                                <?= ($this->input->get('jurusan') == $j->nama_jurusan) ? 'selected' : '' ?>>
                                <?= $j->nama_jurusan ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <label>Dari Tanggal</label>
                    <input type="date" name="from" class="form-control"
                           value="<?= $this->input->get('from') ?>">
                </div>

                <div class="col-md-2 mb-2">
                    <label>Sampai</label>
                    <input type="date" name="to" class="form-control"
                           value="<?= $this->input->get('to') ?>">
                </div>

                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button class="btn btn-primary mr-1">
                        <i class="fas fa-filter"></i>
                    </button>

                    <a href="<?= site_url('laporan/kunjungan') ?>"
                       class="btn btn-secondary mr-1">
                        <i class="fas fa-sync"></i>
                    </a>

                    <a href="<?= site_url('laporan/kunjungan_excel?'.http_build_query($_GET)) ?>"
                       class="btn btn-success mr-1">
                        <i class="fas fa-file-excel"></i>
                    </a>

                    <a href="<?= site_url('laporan/kunjungan_pdf?'.http_build_query($_GET)) ?>"
                       target="_blank"
                       class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>

            </form>

        </div>
    </div>

    <!-- ================= TABLE ================= -->
    <div class="card shadow">
        <div class="card-header">
            <strong>Data Kunjungan</strong>
        </div>
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Tujuan</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if(empty($laporan)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Data tidak ditemukan
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php
                        $page = $this->input->get('page') ?? 0;
                        $no = $page + 1;
                        foreach($laporan as $l):
                        ?>

                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $l->tanggal ?></td>
                        <td><?= $l->jam ?></td>
                        <td><?= $l->nis ?></td>
                        <td><?= $l->nama_siswa ?></td>
                        <td><?= $l->nama_kelas ?></td>
                        <td><?= $l->nama_jurusan ?></td>
                        <td>
                            <span class="badge badge-info"><?= $l->tujuan ?></span>
                        </td>
                    </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-center">
            <?= $pagination ?>
            </div>
        </div>
    </div>

</div>
