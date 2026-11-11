<div class="container-fluid">

    <h1 class="h4 mb-4"><?= $title ?></h1>

    <div class="row">

        <?php if (!empty($ebook)): ?>
            <?php foreach ($ebook as $e): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">

                        <!-- COVER -->
                        <?php if (!empty($e->cover)): ?>
                            <img src="<?= base_url('assets/uploads/cover_ebook/'.$e->cover) ?>"
                                 class="card-img-top"
                                 style="height:220px; object-fit:cover;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-light"
                                 style="height:220px;">
                                <span class="text-muted">No Cover</span>
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h6 class="card-title mb-1">
                                <?= htmlspecialchars($e->judul) ?>
                            </h6>
                            <small class="text-muted">
                                Terakhir dibaca:
                                <?= date('d M Y H:i', strtotime($e->last_read)) ?>
                            </small>
                        </div>

                        <div class="card-footer bg-white text-right">
                            <a href="<?= site_url('SiswaEbook/baca/'.$e->id_ebook) ?>"
                               class="btn btn-sm btn-primary">
                                Lanjutkan
                            </a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">
                Belum ada riwayat bacaan
            </div>
        <?php endif; ?>

    </div>

</div>
