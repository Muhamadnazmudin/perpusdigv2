<div class="content-wrapper mt-4 px-4">

    <h4 class="fw-bold mb-3">📖 Baca Al-Qur'an</h4>

    <?php if ($last): ?>
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <div>
            📖 Lanjutkan bacaan terakhir:
            <strong>
                <?= $last->nama_latin ?>
                (<?= $last->surah_id ?>)
                : <?= $last->ayat_id ?>
            </strong>
        </div>

        <a href="<?= site_url('quran/baca/'.$last->surah_id.'#ayat-'.$last->ayat_id) ?>"
           class="btn btn-sm btn-primary">
           Buka
        </a>
    </div>
<?php endif; ?>

    <div class="row">
        <?php foreach ($surah as $s): ?>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-1">
                            <?= $s->id ?>. <?= $s->nama_latin ?>
                        </h6>
                        <small class="text-muted">
                            <?= $s->arti ?>
                        </small>
                        <br>
                        <span class="badge badge-secondary mt-2">
                            <?= $s->jumlah_ayat ?> Ayat
                        </span>

                        <a href="<?= site_url('quran/baca/'.$s->id) ?>" 
                           class="btn btn-sm btn-outline-primary btn-block mt-2">
                            Baca
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>