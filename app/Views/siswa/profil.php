<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tentukan foto (dinamis + cache buster)
 */
$foto = !empty($siswa->foto)
    ? base_url($siswa->foto) . '?v=' . time()
    : base_url('assets/img/user.png');
?>
<style>
.idcard {
    width: 100%;
    max-width: 340px;
    border-radius: 15px;
    overflow: hidden;
    background: #ffffff;
    border: 1px solid #e0e0e0;
}

.idcard-header {
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: #fff;
    padding: 15px;
    text-align: center;
}

.idcard-header h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.idcard-body {
    padding: 15px;
}

.idcard-photo img {
    width: 100px;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #dee2e6;
}

.idcard-name {
    font-weight: 700;
    font-size: 16px;
    margin-top: 10px;
}

.idcard-role {
    font-size: 12px;
    color: #6c757d;
}

.idcard-table td {
    padding: 2px 0;
    font-size: 13px;
}

.idcard-footer {
    text-align: center;
    padding: 10px;
    background: #f8f9fc;
}

.idcard-footer img {
    width: 90px;
}
</style>
<div class="container-fluid">

    <h1 class="h4 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row justify-content-center">
        <div class="col-md-4">

            <!-- ================= ID CARD ================= -->
            <div class="card shadow idcard mb-3">

                <div class="idcard-header">
                    <h6>PERPUSTAKAAN DIGITAL SEKOLAH</h6>
                    <small>ID CARD SISWA</small>
                </div>

                <div class="idcard-body text-center">

                    <div class="idcard-photo mb-2">
                        <img src="<?= $foto ?>" alt="Foto Siswa">
                    </div>

                    <div class="idcard-name">
                        <?= htmlspecialchars($siswa->nama_siswa) ?>
                    </div>
                    <div class="idcard-role">SISWA</div>

                    <hr>

                    <table class="table table-borderless idcard-table text-left mb-2">
                        <tr>
                            <td>NIS</td>
                            <td class="text-right"><strong><?= $siswa->nis ?></strong></td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td class="text-right"><?= $siswa->nama_kelas ?></td>
                        </tr>
                        <tr>
                            <td>Jurusan</td>
                            <td class="text-right"><?= $siswa->nama_jurusan ?></td>
                        </tr>
                    </table>

                </div>

                <?php if ($siswa->qr_code): ?>
                <div class="idcard-footer">
                    <img src="<?= base_url($siswa->qr_code) ?>" alt="QR Code">
                    <div class="small text-muted mt-1">
                        Scan saat kunjungan
                    </div>
                </div>
                <?php endif; ?>

            </div>
            <!-- ================= END ID CARD ================= -->


            <!-- ================= UPLOAD FOTO ================= -->
            <div class="text-center">

                <?= form_open_multipart('profil/upload_foto', ['id' => 'formUploadFoto']) ?>

                    <label class="small font-weight-bold d-block mb-2">
                        Upload / Ganti Foto
                    </label>

                    <div class="input-group input-group-sm justify-content-center">

                        <div class="custom-file" style="max-width:220px">
                            <input type="file"
                                   class="custom-file-input"
                                   id="foto"
                                   name="foto"
                                   accept="image/*"
                                   required>
                            <label class="custom-file-label" for="foto">
                                Pilih Foto
                            </label>
                        </div>

                        <div class="input-group-append">
                            <button type="submit"
                                    class="btn btn-primary"
                                    id="btnUpload">
                                <i class="fas fa-upload"></i>
                            </button>
                        </div>

                    </div>

                    <small class="text-muted d-block mt-1">
                        JPG / PNG • Max 2MB
                    </small>

                <?= form_close() ?>

            </div>
            <!-- ================= END UPLOAD ================= -->

        </div>
    </div>
<!-- ================= MENU AKUN ================= -->
<div class="card shadow-sm mt-4">
    <div class="card-header">
        <strong>Menu Akun</strong>
    </div>

    <div class="list-group list-group-flush">

        <a href="<?= site_url('SiswaKarya') ?>"
           class="list-group-item list-group-item-action">
            <i class="fas fa-pen-nib mr-2 text-primary"></i>
            Karya Saya
            <i class="fas fa-chevron-right float-right mt-1"></i>
        </a>

        <a href="<?= site_url('peminjaman/riwayat') ?>"
           class="list-group-item list-group-item-action">
            <i class="fas fa-history mr-2 text-success"></i>
            Riwayat Pinjaman
            <i class="fas fa-chevron-right float-right mt-1"></i>
        </a>

        <a href="<?= site_url('quran') ?>"
           class="list-group-item list-group-item-action">
            <i class="fas fa-book-reader mr-2 text-info"></i>
            Baca Al-Qur'an
            <i class="fas fa-chevron-right float-right mt-1"></i>
        </a>

        <a href="<?= site_url('auth/logout') ?>"
           class="list-group-item list-group-item-action text-danger">
            <i class="fas fa-sign-out-alt mr-2"></i>
            Logout
            <i class="fas fa-chevron-right float-right mt-1"></i>
        </a>

    </div>
</div>
<!-- ================= END MENU AKUN ================= -->
</div>
<script>
const inputFoto = document.getElementById('foto');
const form = document.getElementById('formUploadFoto');
const btn = document.getElementById('btnUpload');

inputFoto.addEventListener('change', function () {
    this.nextElementSibling.innerText = this.files[0].name;
});

form.addEventListener('submit', function () {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
});
</script>
