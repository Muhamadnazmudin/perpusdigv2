<style>
.cover-click:hover {
    opacity: 0.85;
    transition: 0.2s;
}
</style>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
    <!-- FILTER & SEARCH -->
<form method="get" action="<?= current_url() ?>" class="mb-4">
    <div class="row align-items-end">
        <div class="col-md-4">
            <label class="small font-weight-bold">Filter Kelas</label>
            <select name="kelas" class="form-control">
    <option value="">-- Semua Kelas --</option>
    <?php foreach ($kelas as $k): ?>
        <option value="<?= htmlspecialchars($k->kelas) ?>"
            <?= ($this->input->get('kelas') == $k->kelas) ? 'selected' : '' ?>>
            <?= htmlspecialchars($k->kelas) ?>
        </option>
    <?php endforeach ?>
</select>

        </div>

        <div class="col-md-5">
            <label class="small font-weight-bold">Cari Judul / Penulis</label>
            <input type="text"
                   name="q"
                   class="form-control"
                   placeholder="Ketik judul atau penulis..."
                   value="<?= htmlspecialchars($this->input->get('q')) ?>">
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-search"></i> Tampilkan
            </button>
        </div>
    </div>
</form>

    <div class="row">

        <?php foreach ($buku as $b): ?>
        <div class="col-md-3 mb-4">

            <div class="card shadow h-100">

                <!-- COVER (CLICKABLE) -->
                <img src="<?= base_url('uploads/cover/'.$b->cover) ?>"
                     class="card-img-top cover-click"
                     data-toggle="modal"
                     data-target="#cover<?= $b->id_buku ?>"
                     style="height:200px;object-fit:cover;cursor:pointer">

                <div class="card-body">
                    <h6 class="card-title font-weight-bold">
                        <?= htmlspecialchars($b->judul) ?>
                    </h6>

                    <p class="mb-1">
                        <small><?= htmlspecialchars($b->penulis) ?></small>
                    </p>

                    <span class="badge badge-<?= $b->stok > 0 ? 'success':'danger' ?>">
                        <?= $b->stok > 0 ? 'Tersedia':'Habis' ?>
                    </span>
                </div>

                <div class="card-footer text-center">
                    <a href="<?= site_url('buku/detail/'.$b->id_buku) ?>"
                       class="btn btn-sm btn-primary">
                        Detail
                    </a>
                </div>

            </div>
        </div>
        

        <!-- ================= MODAL FULL COVER ================= -->
        <div class="modal fade" id="cover<?= $b->id_buku ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content bg-dark">

                    <div class="modal-header border-0">
                        <h6 class="modal-title text-white">
                            <?= htmlspecialchars($b->judul) ?>
                        </h6>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center p-0">
                        <img src="<?= base_url('uploads/cover/'.$b->cover) ?>"
                             class="img-fluid"
                             style="max-height:80vh;">
                    </div>

                </div>
            </div>
        </div>
        <!-- ================= END MODAL ================= -->

        <?php endforeach ?>
        <?php if (!empty($pagination)): ?>
    <div class="col-12 mt-4">
        <?= $pagination ?>
    </div>
<?php endif ?>

        <?php if (empty($buku)): ?>
    <div class="col-12">
        <div class="alert alert-warning text-center">
            <i class="fas fa-book-open"></i><br>
            Buku tidak ditemukan.
        </div>
    </div>
<?php endif ?>


    </div>
</div>
