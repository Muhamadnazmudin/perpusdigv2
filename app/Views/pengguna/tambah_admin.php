<div class="container-fluid">
<h5>Tambah Admin</h5>

<form method="post">
<div class="form-group">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required>
</div>

<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-primary">Simpan</button>
<a href="<?= site_url('pengguna') ?>" class="btn btn-secondary">Batal</a>
</form>
</div>
