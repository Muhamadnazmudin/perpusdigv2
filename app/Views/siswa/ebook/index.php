<style>
    <style>
.ebook-cover:hover {
    opacity: 0.85;
    transition: 0.2s;
}
</style>

    </style>
<div class="container-fluid">

    <h1 class="h4 mb-4"><?= $title ?></h1>
<a href="<?= site_url('SiswaEbook/riwayat') ?>"
   class="btn btn-outline-info mb-3">
   📖 Riwayat Bacaan Saya
</a>

    <!-- FILTER -->
    <form method="get" class="row mb-4">

        <!-- FILTER KELAS -->
        <div class="col-md-3 mb-2">
            <select name="kelas" class="form-control">
                <option value="">Semua Kelas</option>
                <option value="X"   <?= ($filter_kelas=='X')?'selected':'' ?>>X</option>
                <option value="XI"  <?= ($filter_kelas=='XI')?'selected':'' ?>>XI</option>
                <option value="XII" <?= ($filter_kelas=='XII')?'selected':'' ?>>XII</option>
                <option value="UMUM" <?= ($filter_kelas=='UMUM')?'selected':'' ?>>UMUM</option>
            </select>
        </div>

        <!-- FILTER MAPEL (INPUT) -->
        <div class="col-md-4 mb-2 position-relative">
            <input type="text"
                   name="mapel"
                   id="mapelInput"
                   class="form-control"
                   placeholder="Ketik mapel..."
                   value="<?= htmlspecialchars($filter_mapel ?? '') ?>"
                   autocomplete="off">

            <!-- LIST MAPEL -->
            <?php if (!empty($mapel_list)): ?>
                <div id="mapelList"
                     class="list-group position-absolute w-100"
                     style="z-index:999; max-height:200px; overflow:auto; display:none;">
                    <?php foreach ($mapel_list as $m): ?>
                        <button type="button"
                                class="list-group-item list-group-item-action mapel-item"
                                data-value="<?= htmlspecialchars($m->mapel) ?>">
                            <?= htmlspecialchars($m->mapel) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- BUTTON -->
        <div class="col-md-2 mb-2">
            <button class="btn btn-primary btn-block">
                Filter
            </button>
        </div>

        <!-- RESET -->
        <div class="col-md-2 mb-2">
            <a href="<?= site_url('SiswaEbook') ?>"
               class="btn btn-outline-secondary btn-block">
                Reset
            </a>
        </div>

    </form>

    <!-- LIST EBOOK -->
    <div class="row">

        <?php if (!empty($ebook)): ?>
            <?php foreach ($ebook as $e): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">

                        <!-- COVER -->
                        <?php if (!empty($e->cover)): ?>
                            <img src="<?= base_url('assets/uploads/cover_ebook/'.$e->cover) ?>"
     class="card-img-top ebook-cover"
     style="height:220px; object-fit:cover; cursor:pointer;"
     data-cover="<?= base_url('assets/uploads/cover_ebook/'.$e->cover) ?>"
     data-title="<?= htmlspecialchars($e->judul) ?>">

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

                                <?php if (!empty($e->penulis)): ?>
                                    <div class="small text-primary mb-1">
                                        ✍️ <?= htmlspecialchars($e->penulis) ?>
                                    </div>
                                <?php endif; ?>

                                <small class="text-muted">
                                    <?= htmlspecialchars($e->mapel) ?> · Kelas <?= htmlspecialchars($e->kelas) ?>
                                </small>
                            </div>


                        <div class="card-footer bg-white text-right">
                            <a href="<?= site_url('SiswaEbook/baca/'.$e->id_ebook) ?>"
                               class="btn btn-sm btn-success">
                                Baca
                            </a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">
                Tidak ada e-book yang tersedia
            </div>
        <?php endif; ?>

    </div>

    <!-- PAGINATION -->
    <?php if (!empty($pagination)): ?>
        <div class="mt-3">
            <?= $pagination ?>
        </div>
    <?php endif; ?>

</div>
<!-- MODAL COVER -->
<div class="modal fade" id="coverModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="coverModalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <img id="coverModalImg"
                     src=""
                     class="img-fluid"
                     style="max-height:80vh;">
            </div>

        </div>
    </div>
</div>


<!-- JS MAPEL AUTOCOMPLETE -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('mapelInput');
    const list  = document.getElementById('mapelList');
    const items = document.querySelectorAll('.mapel-item');

    if (!input || !list) return;

    input.addEventListener('focus', () => {
        list.style.display = 'block';
    });

    input.addEventListener('input', () => {
        const val = input.value.toLowerCase();
        let show = false;

        items.forEach(item => {
            const text = item.dataset.value.toLowerCase();
            if (text.includes(val)) {
                item.style.display = 'block';
                show = true;
            } else {
                item.style.display = 'none';
            }
        });

        list.style.display = show ? 'block' : 'none';
    });

    items.forEach(item => {
        item.addEventListener('click', () => {
            input.value = item.dataset.value;
            list.style.display = 'none';
        });
    });

    document.addEventListener('click', e => {
        if (!e.target.closest('#mapelInput')) {
            list.style.display = 'none';
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.ebook-cover').forEach(img => {
        img.addEventListener('click', function () {

            const cover = this.dataset.cover;
            const title = this.dataset.title;

            document.getElementById('coverModalImg').src = cover;
            document.getElementById('coverModalTitle').innerText = title;

            $('#coverModal').modal('show');
        });
    });

});
</script>
