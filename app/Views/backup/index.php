<div class="container-fluid">

    <h1 class="h4 mb-4 text-gray-800"><?= $title ?></h1>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- ================= BACKUP ================= -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <strong><i class="fas fa-download"></i> Backup Database</strong>
        </div>
        <div class="card-body">
            <p class="text-muted">
                Download backup database Perpusdig (format ZIP).
            </p>
            <a href="<?= site_url('backup/backup') ?>" class="btn btn-primary">
                <i class="fas fa-database"></i> Backup Sekarang
            </a>
        </div>
    </div>

    <!-- ================= RESTORE ================= -->
    <div class="card shadow mb-4">
        <div class="card-header bg-warning text-dark">
            <strong><i class="fas fa-upload"></i> Restore Database</strong>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                ⚠️ <strong>Peringatan:</strong> Restore akan MENIMPA seluruh data database.
            </div>

            <form action="<?= site_url('backup/restore') ?>" method="post" enctype="multipart/form-data">
                <div class="form-row align-items-center">
                    <div class="col-md-5 mb-2">
                        <input type="file" name="file" class="form-control" accept=".sql" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-danger btn-block"
                                onclick="return confirm('Yakin restore database? Data lama akan hilang!')">
                            <i class="fas fa-exclamation-triangle"></i> Restore
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
