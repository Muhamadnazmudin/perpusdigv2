<div class="container-fluid">

    <h1 class="h5 mb-3">📖 Review Karya Siswa</h1>

    <div class="card mb-3">
        <div class="card-body">

            <p><strong>Judul:</strong> <?= htmlspecialchars($karya->judul) ?></p>
            <p><strong>Jenis:</strong> <?= htmlspecialchars($karya->jenis) ?></p>
            <p><strong>Mapel:</strong> <?= htmlspecialchars($karya->mapel) ?></p>
            <p><strong>Kelas:</strong> <?= htmlspecialchars($karya->kelas) ?></p>
            <p><strong>Status:</strong>
                <span class="badge badge-warning">Pending</span>
            </p>

        </div>
    </div>

    <div class="card shadow mb-3">
        <div class="card-body p-0">
            <iframe
                src="<?= base_url('assets/uploads/ebook/'.$karya->file_local) ?>#toolbar=0"
                style="width:100%; height:500px; border:none;">
            </iframe>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="<?= site_url('AdminKarya/approve/'.$karya->id_ebook) ?>"
           class="btn btn-success"
           onclick="return confirm('Setujui karya ini?')">
            ✅ Setujui
        </a>

        <a href="<?= site_url('AdminKarya/reject/'.$karya->id_ebook) ?>"
           class="btn btn-danger"
           onclick="return confirm('Tolak karya ini?')">
            ❌ Tolak
        </a>

        <a href="<?= site_url('AdminKarya') ?>"
           class="btn btn-secondary">
            Kembali
        </a>
    </div>

</div>
