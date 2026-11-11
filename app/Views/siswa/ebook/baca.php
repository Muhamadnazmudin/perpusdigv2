<div class="container-fluid p-0 ebook-page">

    <!-- HEADER -->
    <div class="ebook-header px-3 py-2">
        <div class="d-flex justify-content-between align-items-center">

            <div class="ebook-title">
                <i class="fas fa-book-open mr-1"></i>
                <?= htmlspecialchars($ebook->judul); ?>
            </div>

            <div class="ebook-actions">
                <a href="<?= site_url('SiswaEbook/favorit/'.$ebook->id_ebook) ?>"
                   class="btn btn-warning btn-sm">
                    ⭐
                </a>

                <a href="<?= site_url('SiswaEbook'); ?>"
                   class="btn btn-secondary btn-sm">
                    ←
                </a>
            </div>

        </div>
    </div>

    <!-- READER -->
    <div class="ebook-reader">

        <?php if ($ebook->source === 'LOCAL'): ?>

            <!-- PDF LOCAL : FIT WIDTH ONLY -->
<iframe
    src="<?= base_url('assets/uploads/ebook/'.$ebook->file_local) ?>
        #toolbar=0
        &navpanes=0
        &view=FitH"
    class="ebook-iframe ebook-local">
</iframe>


        <?php else: ?>

            <!-- GOOGLE DRIVE (AMAN, TIDAK DIUBAH) -->
            <iframe
                src="https://drive.google.com/file/d/<?= htmlspecialchars($ebook->file_drive); ?>/preview"
                class="ebook-iframe"
                allow="autoplay">
            </iframe>

        <?php endif; ?>

    </div>

</div>

<style>
/* ================= PAGE RESET ================= */
.ebook-page {
    height: 100vh;
    display: flex;
    flex-direction: column;
    background: #111;
}

/* ================= HEADER ================= */
.ebook-header {
    background: #ffffff;
    border-bottom: 1px solid #dee2e6;
    position: sticky;
    top: 0;
    z-index: 100;
}

.ebook-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #343a40;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 70vw;
}

.ebook-actions .btn {
    padding: 4px 8px;
    font-size: 0.75rem;
}

/* ================= READER ================= */
.ebook-reader {
    flex: 1;
    background: #000;
}

/* ================= IFRAME ================= */
.ebook-iframe {
    width: 100%;
    height: 100%;
    border: none;
    background: #000;
}

/* ================= DESKTOP ================= */
@media (min-width: 768px) {

    .ebook-page {
        padding: 16px;
        background: #f1f3f5;
    }

    .ebook-reader {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        background: #000;
    }

    .ebook-header {
        border-radius: 12px 12px 0 0;
    }
    /* ===== PDF LOCAL DEFAULT ===== */
.ebook-local {
    background: #000;
}

/* ===== MOBILE: JANGAN TERLALU BESAR ===== */
@media (max-width: 576px) {
    .ebook-local {
        zoom: 1; /* biarkan natural */
    }
}

/* ===== DESKTOP: LEBIH LEGAAA ===== */
@media (min-width: 768px) {
    .ebook-local {
        zoom: 1.25; /* enak buat baca */
    }
}

}
</style>
