<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Peminjaman Buku</h1>

    <a href="<?= site_url('peminjaman/tambah') ?>" class="btn btn-primary mb-3">
        Tambah Peminjaman
    </a>

    <?php if ($total_menunggu > 0): ?>
        <div class="alert alert-warning">
            <i class="fas fa-bell"></i>
            Ada <strong><?= $total_menunggu ?></strong> pengajuan peminjaman menunggu persetujuan.
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body table-responsive">
        <form method="get" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <select name="kelas" class="form-control">
                <option value="">-- Semua Kelas --</option>
                <?php foreach ($kelas as $k): ?>
                    <option value="<?= $k->id_kelas ?>"
                        <?= $this->input->get('kelas') == $k->id_kelas ? 'selected' : '' ?>>
                        <?= $k->nama_kelas ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <input type="text"
                   name="q"
                   class="form-control"
                   placeholder="Cari nama siswa..."
                   value="<?= htmlspecialchars($this->input->get('q')) ?>">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary">
                <i class="fas fa-search"></i> Filter
            </button>
        </div>
    </div>
</form>

            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Kelas</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php $no = 1; foreach ($peminjaman as $p): ?>

                    <?php
                    // ================== NOMOR SISWA ==================
                    $no_hp = null;
                    if (!empty($p->no_hp)) {
                        $no_hp = preg_replace('/[^0-9]/', '', $p->no_hp);
                        if (substr($no_hp, 0, 1) == '0') {
                            $no_hp = '62' . substr($no_hp, 1);
                        }
                    }

                    // ================== PESAN WA (HELPER) ==================
                    $pesan = wa_pesan_peminjaman($p);
                    ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($p->nama) ?></td>
                        <td><?= htmlspecialchars($p->nama_kelas ?? '-') ?></td>
                        <td><?= htmlspecialchars($p->judul) ?></td>
                        <td><?= $p->tanggal_pinjam ?></td>
                        <td><?= $p->tanggal_jatuh_tempo ?></td>

                        <td>
                            <?php if ($p->status === 'menunggu'): ?>
                                <span class="badge badge-warning">Menunggu</span>

                            <?php elseif ($p->status === 'dipinjam'): ?>
                                <?php if ($p->hari_terlambat > 0): ?>
                                    <span class="badge badge-danger">
                                        Terlambat <?= $p->hari_terlambat ?> hari
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-success">Dipinjam</span>
                                <?php endif; ?>

                            <?php elseif ($p->status === 'ditolak'): ?>
                                <span class="badge badge-danger">Ditolak</span>

                            <?php elseif ($p->status === 'kembali'): ?>
                                <span class="badge badge-secondary">Kembali</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($p->status === 'menunggu'): ?>

                                <a href="<?= site_url('peminjaman/setujui/'.$p->id_pinjam) ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Setujui peminjaman ini?')">
                                    <i class="fas fa-check"></i>
                                </a>

                                <a href="<?= site_url('peminjaman/tolak/'.$p->id_pinjam) ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Tolak pengajuan ini?')">
                                    <i class="fas fa-times"></i>
                                </a>

                            <?php elseif ($p->status === 'dipinjam'): ?>

                                <a href="<?= site_url('peminjaman/kembali/'.$p->id_pinjam) ?>"
                                   class="btn btn-sm btn-primary"
                                   onclick="return confirm('Kembalikan buku ini?')">
                                    <i class="fas fa-undo"></i>
                                </a>

                            <?php endif; ?>

                            <?php if ($no_hp): ?>
                                <a href="https://api.whatsapp.com/send/?phone=<?= $no_hp ?>&text=<?= $pesan ?>"
                                   target="_blank"
                                   class="btn btn-sm btn-success"
                                   title="Kirim WhatsApp ke siswa">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>
                </tbody>
            </table>
        <div class="mt-3">
        <?= $pagination ?>
            </div>
        </div>
    </div>
</div>
