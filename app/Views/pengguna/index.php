<div class="container-fluid">

    <!-- PAGE TITLE -->
    <h1 class="h5 mb-4 text-gray-800">👥 User Management</h1>

    <!-- FILTER & ACTION -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="mb-2">
            <a href="<?= site_url('pengguna') ?>" class="btn btn-secondary btn-sm">
                Semua
            </a>
            <a href="<?= site_url('pengguna/role/1') ?>" class="btn btn-primary btn-sm">
                Admin
            </a>
            <a href="<?= site_url('pengguna/role/2') ?>" class="btn btn-success btn-sm">
                Guru
            </a>
            <a href="<?= site_url('pengguna/role/3') ?>" class="btn btn-info btn-sm">
                Siswa
            </a>
        </div>

        <!-- TAMBAH ADMIN -->
        <div class="mb-2">
            <a href="<?= site_url('pengguna/tambah_admin') ?>"
               class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Admin
            </a>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive p-0">

            <table class="table table-bordered table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr class="text-center">
                        <th width="40">No</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th width="90">Role</th>
                        <th width="90">Status</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Data user tidak tersedia
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($users as $i => $u): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>

                        <td><?= htmlspecialchars($u->username) ?></td>

                        <td>
                            <?= $u->nama_guru ?? $u->nama_siswa ?? 'Admin' ?>
                        </td>

                        <td class="text-center">
                            <span class="badge badge-<?=
                                ($u->id_role == 1 ? 'primary' :
                                ($u->id_role == 2 ? 'success' : 'info'))
                            ?>">
                                <?= $u->id_role == 1
                                    ? 'Admin'
                                    : ($u->id_role == 2 ? 'Guru' : 'Siswa') ?>
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="badge badge-<?= $u->status == 'aktif' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($u->status) ?>
                            </span>
                        </td>

                        <td class="text-center">
                            <a href="<?= site_url('pengguna/edit/' . $u->id_user) ?>"
                               class="btn btn-sm btn-warning"
                               title="Edit User">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

        </div>
    </div>

    <!-- PAGINATION -->
    <?php if (!empty($pagination)): ?>
        <div class="mt-4">
            <?= $pagination ?>
        </div>
    <?php endif; ?>

</div>
