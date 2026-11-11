<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-tachometer-alt"></i> Dashboard Admin
    </h1>
    <div class="d-flex align-items-center justify-content-between mb-4 p-3 bg-white shadow-sm rounded">
    <div>
        <h6 class="mb-0 text-danger font-weight-bold">
            <i class="fas fa-tools"></i> Maintenance Mode
        </h6>
        <small class="text-muted">
            Status:
            <b class="<?= $maintenance_mode == '1' ? 'text-danger' : 'text-success' ?>">
                <?= $maintenance_mode == '1' ? 'AKTIF' : 'NONAKTIF' ?>
            </b>
        </small>
    </div>

    <a href="<?= base_url('dashboard/toggle_maintenance') ?>"
       class="btn btn-sm <?= $maintenance_mode == '1' ? 'btn-danger' : 'btn-success' ?>"
       onclick="return confirm('Yakin ubah status maintenance?')">
        <?= $maintenance_mode == '1' ? 'OFF' : 'ON' ?>
    </a>
</div>


    <div class="row">
        <!-- Buku Fisik -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Buku Fisik
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= $stat['total_buku_fisik'] ?>
                        </div>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buku Digital -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Buku Digital / E-Book
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= $stat['total_buku_digital'] ?>
                        </div>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-tablet-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Siswa -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Data Siswa
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= $stat['total_siswa'] ?>
                        </div>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guru -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Data Guru
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= $stat['total_guru'] ?>
                        </div>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rak -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Rak Buku
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= $stat['total_rak'] ?>
                        </div>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-layer-group fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Kategori Buku
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= $stat['total_kategori'] ?>
                        </div>
                    </div>
                    <div class="text-secondary">
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
