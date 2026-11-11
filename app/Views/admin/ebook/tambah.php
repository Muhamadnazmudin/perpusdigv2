<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <form action="<?= site_url('AdminEbook/simpan') ?>"
          method="post"
          enctype="multipart/form-data">

        <div class="card shadow">
            <div class="card-body">

                <!-- JUDUL -->
                <div class="form-group">
                    <label>Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" required>
                </div>

                <!-- MAPEL -->
                <div class="form-group">
                    <label>Mapel</label>
                    <input type="text" name="mapel" class="form-control">
                </div>

                <!-- KELAS -->
                <div class="form-group">
                    <label>Kelas</label>
                    <select name="kelas" class="form-control">
                        <option value="">-- Pilih Kelas --</option>
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                        <option value="UMUM">Umum</option>
                    </select>
                </div>

                <!-- SUMBER FILE -->
                <div class="form-group">
                    <label>Sumber File <span class="text-danger">*</span></label>
                    <select name="source" id="source" class="form-control" required>
                        <option value="DRIVE">Google Drive</option>
                        <option value="LOCAL">Upload ke Server</option>
                    </select>
                </div>

                <!-- GOOGLE DRIVE -->
                <div class="form-group" id="drive-box">
                    <label>File ID Google Drive</label>
                    <input type="text" name="drive_link" class="form-control">
                    <small class="text-muted">
                        Contoh: <code>1mwQ6aaFLE1Oxg7iO6op7Oplu7ZFcGaYi</code>
                    </small>
                </div>

                <!-- UPLOAD PDF LOCAL -->
                <div class="form-group d-none" id="local-box">
                    <label>Upload E-Book (PDF)</label>
                    <input type="file"
                           name="file_local"
                           class="form-control-file"
                           accept=".pdf">
                    <small class="text-muted">
                        Format PDF, maksimal 10MB
                    </small>
                </div>

                <!-- COVER -->
                <div class="form-group">
                    <label>Cover E-Book</label>

                    <div class="mb-2">
                        <img src="<?= base_url('assets/img/no-cover.png') ?>"
                             id="cover-preview"
                             class="img-thumbnail"
                             style="max-height:150px">
                    </div>

                    <input type="file"
                           name="cover"
                           class="form-control-file"
                           accept="image/*"
                           onchange="previewCover(this)">

                    <small class="text-muted">
                        JPG / PNG, maksimal 2MB (opsional)
                    </small>
                </div>

            </div>

            <div class="card-footer text-right">
                <a href="<?= site_url('AdminEbook') ?>" class="btn btn-secondary">
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </div>

    </form>

</div>

<!-- TOGGLE SUMBER FILE -->
<script>
document.getElementById('source').addEventListener('change', function () {
    const isDrive = this.value === 'DRIVE';
    document.getElementById('drive-box').classList.toggle('d-none', !isDrive);
    document.getElementById('local-box').classList.toggle('d-none', isDrive);
});
</script>

<!-- PREVIEW COVER -->
<script>
function previewCover(input) {
    const preview = document.getElementById('cover-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => preview.src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
