<div class="container-fluid">
<h5>Edit User</h5>

<form method="post">
<div class="form-group">
    <label>Username</label>
    <input type="text" name="username" class="form-control"
           value="<?= $user->username ?>" required>
</div>

<div class="form-group">
    <label>Password (kosongkan jika tidak diubah)</label>
    <input type="password" name="password" class="form-control">
</div>

<div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="aktif" <?= $user->status=='aktif'?'selected':'' ?>>Aktif</option>
        <option value="nonaktif" <?= $user->status=='nonaktif'?'selected':'' ?>>Nonaktif</option>
    </select>
</div>

<button class="btn btn-primary">Simpan</button>
<a href="<?= site_url('pengguna') ?>" class="btn btn-secondary">Kembali</a>
</form>
</div>
