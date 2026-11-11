<div class="container-fluid">

<?php
$is_edit = $this->input->get('edit');
?>

<h1 class="h3 mb-4 text-gray-800">Profil Sekolah</h1>

<?php if ($this->session->flashdata('success')) : ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')) : ?>
<div class="alert alert-danger">
    <?= $this->session->flashdata('error') ?>
</div>
<?php endif; ?>

<!-- ===================== MODE PROFIL ===================== -->
<?php if (!$is_edit && $sekolah): ?>

<div class="card shadow mb-4">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex align-items-center">

                <img src="<?= base_url(
                    $sekolah->logo_sekolah
                        ? 'assets/uploads/logo/'.$sekolah->logo_sekolah
                        : 'assets/img/logo_sekolah.png'
                ) ?>"
                style="width:80px;height:80px"
                class="mr-3">

                <div>
                    <h4 class="mb-0 font-weight-bold">
                        <?= strtoupper($sekolah->nama_sekolah) ?>
                    </h4>

                    <small class="text-muted">
                        NPSN : <?= $sekolah->npsn ?>
                    </small>

                    <div class="mt-2">
                        <span class="badge badge-success">NEGERI</span>

                        <?php if (!empty($sekolah->akreditasi)): ?>
                            <span class="badge badge-primary">
                                Akreditasi <?= strtoupper($sekolah->akreditasi) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <a href="<?= site_url('pengaturan/sekolah?edit=1') ?>"
               class="btn btn-success">
                <i class="fas fa-edit"></i> Perbaharui Profil
            </a>
        </div>

        <hr>

        <p>
            <strong>Alamat Sekolah :</strong><br>
            <?= nl2br($sekolah->alamat_sekolah) ?>
        </p>

        <p class="mb-1">
            <strong>Email :</strong> <?= $sekolah->email_sekolah ?><br>
            <strong>Telepon :</strong> <?= $sekolah->telp_sekolah ?><br>
            <strong>Website :</strong>
            <a href="<?= $sekolah->website_sekolah ?>" target="_blank">
                <?= $sekolah->website_sekolah ?>
            </a>
        </p>

        <small class="text-muted">
            Terakhir diperbarui :
            <?= date(
                'l, d F Y H:i',
                strtotime($sekolah->updated_at ?? $sekolah->created_at)
            ) ?> WIB
        </small>

    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <strong>Pejabat & Petugas Perpustakaan</strong>
    </div>
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tr>
                <th width="30%">Kepala Sekolah</th>
                <td><?= $sekolah->kepala_sekolah ?> (<?= $sekolah->nip_kepala_sekolah ?>)</td>
            </tr>
            <tr>
                <th>Kepala Perpustakaan</th>
                <td><?= $sekolah->kepala_perpustakaan ?> (<?= $sekolah->nip_kepala_perpustakaan ?>)</td>
            </tr>
            <tr>
                <th>Petugas Perpustakaan</th>
                <td><?= $sekolah->petugas_perpustakaan ?> (<?= $sekolah->nip_petugas_perpustakaan ?>)</td>
            </tr>
        </table>
    </div>
</div>

<!-- ===================== MODE EDIT ===================== -->
<?php else: ?>

<form action="<?= site_url('pengaturan/simpan_sekolah') ?>"
      method="post"
      enctype="multipart/form-data">

<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between">
        <strong>Edit Profil Sekolah</strong>
        <a href="<?= site_url('pengaturan/sekolah') ?>"
           class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Batal
        </a>
    </div>

    <div class="card-body">

        <div class="form-group">
            <label>Nama Sekolah</label>
            <input type="text" name="nama_sekolah" class="form-control"
                   value="<?= @$sekolah->nama_sekolah ?>" required>
        </div>

        <div class="form-group">
            <label>NPSN</label>
            <input type="text" name="npsn" class="form-control"
                   value="<?= @$sekolah->npsn ?>">
        </div>

        <div class="form-group">
            <label>Alamat Sekolah</label>
            <textarea name="alamat_sekolah" class="form-control"
                      rows="3"><?= @$sekolah->alamat_sekolah ?></textarea>
        </div>

        <div class="form-row">
            <div class="col-md-4">
                <label>Email</label>
                <input type="email" name="email_sekolah" class="form-control"
                       value="<?= @$sekolah->email_sekolah ?>">
            </div>
            <div class="col-md-4">
                <label>Telepon</label>
                <input type="text" name="telp_sekolah" class="form-control"
                       value="<?= @$sekolah->telp_sekolah ?>">
            </div>
            <div class="col-md-4">
                <label>Website</label>
                <input type="text" name="website_sekolah" class="form-control"
                       value="<?= @$sekolah->website_sekolah ?>">
            </div>
        </div>

        <hr>

        <div class="form-row">
            <div class="col-md-6">
                <label>Akreditasi Sekolah</label>
                <select name="akreditasi" class="form-control">
                    <option value="">-- Pilih --</option>
                    <?php foreach (['A','B','C','Belum'] as $a): ?>
                        <option value="<?= $a ?>"
                            <?= (@$sekolah->akreditasi == $a) ? 'selected' : '' ?>>
                            <?= $a ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label>Logo Sekolah</label>
                <input type="file" name="logo_sekolah" class="form-control-file">
                <small class="text-muted">
                    JPG / PNG • Maks 2MB
                </small>
            </div>
        </div>

        <hr>

        <div class="form-row">
            <div class="col-md-6">
                <label>Kepala Sekolah</label>
                <input type="text" name="kepala_sekolah" class="form-control"
                       value="<?= @$sekolah->kepala_sekolah ?>">
            </div>
            <div class="col-md-6">
                <label>NIP Kepala Sekolah</label>
                <input type="text" name="nip_kepala_sekolah" class="form-control"
                       value="<?= @$sekolah->nip_kepala_sekolah ?>">
            </div>
        </div>

        <hr>

        <div class="form-row">
            <div class="col-md-6">
                <label>Kepala Perpustakaan</label>
                <input type="text" name="kepala_perpustakaan" class="form-control"
                       value="<?= @$sekolah->kepala_perpustakaan ?>">
            </div>
            <div class="col-md-6">
                <label>NIP Kepala Perpustakaan</label>
                <input type="text" name="nip_kepala_perpustakaan" class="form-control"
                       value="<?= @$sekolah->nip_kepala_perpustakaan ?>">
            </div>
        </div>

        <hr>

        <div class="form-row">
            <div class="col-md-6">
                <label>Petugas Perpustakaan</label>
                <input type="text" name="petugas_perpustakaan" class="form-control"
                       value="<?= @$sekolah->petugas_perpustakaan ?>">
            </div>
            <div class="col-md-6">
                <label>NIP Petugas Perpustakaan</label>
                <input type="text" name="nip_petugas_perpustakaan" class="form-control"
                       value="<?= @$sekolah->nip_petugas_perpustakaan ?>">
            </div>
        </div>

    </div>

    <div class="card-footer text-right">
        <button class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </div>
</div>
</form>

<?php endif; ?>

</div>
