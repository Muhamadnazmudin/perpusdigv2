<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Detail Buku</h1>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <?= $this->session->flashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        <?= $this->session->flashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>

    <div class="card shadow">
        <div class="card-body row">

            <!-- COVER -->
            <div class="col-md-4 text-center mb-3">
                <img src="<?= base_url('uploads/cover/'.$buku->cover) ?>"
                     class="img-fluid rounded shadow-sm"
                     style="max-height:350px;">
            </div>

            <!-- DETAIL -->
            <div class="col-md-8">

                <small class="text-muted d-block mb-2">
                    Maksimal <?= $this->config->item('max_pinjam') ?> buku aktif
                </small>

                <h4 class="font-weight-bold">
                    <?= htmlspecialchars($buku->judul) ?>
                </h4>

                <table class="table table-sm table-borderless mt-3">
                    <tr><th width="120">Penulis</th><td><?= htmlspecialchars($buku->penulis) ?></td></tr>
                    <tr><th>Penerbit</th><td><?= htmlspecialchars($buku->penerbit) ?></td></tr>
                    <tr><th>Tahun</th><td><?= $buku->tahun ?></td></tr>
                    <tr><th>Stok</th><td><?= $buku->stok ?></td></tr>
                </table>

                <!-- ACTION -->
                <div class="mt-4">

<?php if ($status_pengajuan === 'menunggu'): ?>

    <button class="btn btn-warning mr-2" disabled>
        <i class="fas fa-clock"></i>
        Pengajuan Sedang Diproses
    </button>

<?php elseif ($status_pengajuan === 'dipinjam'): ?>

    <button class="btn btn-primary mr-2" disabled>
        <i class="fas fa-book"></i>
        Buku Sedang Dipinjam
    </button>

<?php elseif ($total_pinjam >= $this->config->item('max_pinjam')): ?>

    <button class="btn btn-danger mr-2" disabled>
        <i class="fas fa-lock"></i>
        Limit Peminjaman Tercapai
    </button>

<?php elseif ($buku->stok > 0): ?>

    <!-- 🔥 TERMASUK KASUS: ditolak -->
    <button class="btn btn-success mr-2"
            data-toggle="modal"
            data-target="#modalPinjam">
        <i class="fas fa-book-reader"></i>
        Ajukan Peminjaman
    </button>

<?php else: ?>

    <button class="btn btn-secondary mr-2" disabled>
        <i class="fas fa-times"></i>
        Stok Habis
    </button>

<?php endif; ?>

    <a href="<?= site_url('buku') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

</div>


            </div>

        </div>
    </div>

</div>

<!-- ================= MODAL KONFIRMASI PINJAM ================= -->
<div class="modal fade" id="modalPinjam" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-book text-success"></i> Konfirmasi Peminjaman
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <p class="mb-2">
    Kamu akan <strong>mengajukan peminjaman</strong> buku:
</p>
                <h6 class="font-weight-bold">
                    “<?= htmlspecialchars($buku->judul) ?>”
                </h6>
                <p class="text-muted mt-2">
    Pengajuan akan diproses oleh admin perpustakaan 📚
</p>
            </div>

            <div class="modal-footer border-0 justify-content-center">
                <button type="button"
                        class="btn btn-light"
                        data-dismiss="modal">
                    Batal
                </button>

                <a href="<?= site_url('peminjaman/pinjam/'.$buku->id_buku) ?>"
                   class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Ajukan
                </a>
            </div>

        </div>
    </div>
</div>
