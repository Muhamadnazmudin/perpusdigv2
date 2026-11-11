<div class="content-wrapper mt-4 px-4">

    <h4 class="fw-bold mb-3">Kenaikan Kelas Siswa</h4>

    <!-- ================= PILIH KELAS ================= -->
    <form method="get" class="mb-4">
        <label class="mb-1 fw-semibold">Pilih Kelas / Rombel</label>
        <select name="kelas" class="form-control" onchange="this.form.submit()">
            <option value="">-- Pilih Kelas --</option>
            <?php foreach ($kelas as $k): ?>
                <option value="<?= $k->id_kelas ?>"
                    <?= ($this->input->get('kelas') == $k->id_kelas ? 'selected' : '') ?>>
                    <?= $k->nama_kelas ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- ================= FLASH MESSAGE ================= -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- ================= TABEL SISWA ================= -->
    <?php if (!empty($siswa)): ?>

    <form method="post" action="<?= site_url('siswa/simpan_kenaikan') ?>">
        <!-- CSRF -->
        <input type="hidden"
               name="<?= $this->security->get_csrf_token_name(); ?>"
               value="<?= $this->security->get_csrf_hash(); ?>">

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Nama Siswa</th>
                        <th style="width:150px">NIS</th>
                        <th style="width:160px">Kelas Sekarang</th>
                        <th style="width:220px">Naik ke</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($siswa as $s): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $s->nama_siswa ?></td>
                        <td><?= $s->nis ?></td>
                        <td>
                            <span class="badge badge-secondary">
                                <?= $s->nama_kelas ?>
                            </span>
                        </td>
                        <td>
                            <input type="hidden" name="naik_id[]" value="<?= $s->id_siswa ?>">

                            <select name="naik[<?= $s->id_siswa ?>]"
                                    class="form-control form-control-sm">

                                <?php if (stripos($s->nama_kelas, 'XII') !== false): ?>
                                    <!-- KELAS XII -->
                                    <option value="lulus" selected>Lulus</option>
                                <?php else: ?>
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($kelas_tujuan as $kt): ?>
                                        <?php if ($kt->id_kelas != $s->id_kelas): ?>
                                            <option value="<?= $kt->id_kelas ?>">
                                                <?= $kt->nama_kelas ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- ================= TOMBOL SIMPAN ================= -->
        <div class="text-end mt-3">
            <button type="submit"
                    class="btn btn-danger"
                    onclick="return confirm('Yakin menyimpan kenaikan kelas siswa ini?')">
                <i class="fas fa-save"></i> Simpan Kenaikan
            </button>
        </div>
    </form>

    <?php else: ?>

        <div class="alert alert-info">
            Silakan pilih kelas terlebih dahulu untuk menampilkan data siswa.
        </div>

    <?php endif; ?>

</div>
