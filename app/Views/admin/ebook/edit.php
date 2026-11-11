<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<form action="<?= site_url('AdminEbook/update/'.$ebook->id_ebook) ?>"
      method="post"
      enctype="multipart/form-data">

<div class="card shadow">
<div class="card-body">

<!-- JUDUL -->
<div class="form-group">
    <label>Judul <span class="text-danger">*</span></label>
    <input type="text" name="judul"
           value="<?= htmlspecialchars($ebook->judul) ?>"
           class="form-control" required>
</div>

<!-- MAPEL -->
<div class="form-group">
    <label>Mapel</label>
    <input type="text" name="mapel"
           value="<?= htmlspecialchars($ebook->mapel) ?>"
           class="form-control">
</div>

<!-- KELAS -->
<div class="form-group">
    <label>Kelas</label>
    <select name="kelas" class="form-control">
        <option value="X"   <?= $ebook->kelas=='X'?'selected':'' ?>>X</option>
        <option value="XI"  <?= $ebook->kelas=='XI'?'selected':'' ?>>XI</option>
        <option value="XII" <?= $ebook->kelas=='XII'?'selected':'' ?>>XII</option>
        <option value="UMUM" <?= $ebook->kelas=='UMUM'?'selected':'' ?>>UMUM</option>
    </select>
</div>

<!-- SUMBER FILE -->
<div class="form-group">
    <label>Sumber File</label>
    <select name="source" id="source" class="form-control">
        <option value="DRIVE" <?= $ebook->source=='DRIVE'?'selected':'' ?>>
            Google Drive
        </option>
        <option value="LOCAL" <?= $ebook->source=='LOCAL'?'selected':'' ?>>
            Upload Server
        </option>
    </select>
</div>

<!-- GOOGLE DRIVE -->
<div class="form-group source-drive">
    <label>File ID Google Drive</label>
    <input type="text"
           name="drive_link"
           value="<?= htmlspecialchars($ebook->file_drive) ?>"
           class="form-control">
    <small class="text-muted">
        Isi jika ingin mengganti file Drive.
    </small>
</div>

<!-- FILE LOCAL -->
<div class="form-group source-local">
    <label>Upload File E-Book (PDF)</label>
    <input type="file"
           name="file_local"
           class="form-control-file"
           accept="application/pdf">
    <small class="text-muted">
        Upload jika ingin mengganti file lama.
    </small>
</div>

<!-- COVER -->
<div class="form-group">
    <label>Cover E-Book</label>

    <?php if ($ebook->cover): ?>
        <div class="mb-2">
            <img src="<?= base_url('assets/uploads/cover_ebook/'.$ebook->cover) ?>"
                 class="img-thumbnail"
                 style="max-height:150px">
        </div>
    <?php endif; ?>

    <input type="file" name="cover" class="form-control-file" accept="image/*">
</div>

</div>

<div class="card-footer text-right">
    <a href="<?= site_url('AdminEbook') ?>" class="btn btn-secondary">Kembali</a>
    <button class="btn btn-primary">Simpan</button>
</div>
</div>

</form>
</div>
<script>
function toggleSource() {
    const source = document.getElementById('source').value;
    document.querySelector('.source-drive').style.display =
        source === 'DRIVE' ? 'block' : 'none';
    document.querySelector('.source-local').style.display =
        source === 'LOCAL' ? 'block' : 'none';
}

document.getElementById('source').addEventListener('change', toggleSource);
toggleSource(); // init saat load
</script>
