<h1 class="h4 mb-4"><?= $title ?></h1>

<div class="row">

    <div class="col-md-4">
        <div class="card shadow text-center">
            <div class="card-body">
                <h5><?= $data['total_siswa'] ?></h5>
                <small>Total Siswa</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow text-center">
            <div class="card-body">
                <h5><?= $data['total_kunjungan'] ?></h5>
                <small>Total Kunjungan</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow text-center">
            <div class="card-body">
                <h5><?= $data['total_peminjaman'] ?></h5>
                <small>Total Peminjaman</small>
            </div>
        </div>
    </div>

</div>
