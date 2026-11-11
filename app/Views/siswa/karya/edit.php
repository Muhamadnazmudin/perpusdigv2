<div class="container-fluid">

    <h1 class="h5 mb-4">✏️ Edit Karya</h1>

    <form action="<?= site_url('SiswaKarya/update/'.$karya->id_ebook) ?>"
          method="post">

        <div class="form-group">
            <label>Judul</label>
            <input type="text"
                   name="judul"
                   class="form-control"
                   value="<?= htmlspecialchars($karya->judul) ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Jenis</label>
            <select name="jenis" class="form-control" required>
                <?php foreach (['Komik','Puisi','Cerpen','Novel'] as $j): ?>
                    <option value="<?= $j ?>"
                        <?= ($karya->jenis === $j) ? 'selected' : '' ?>>
                        <?= $j ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Mapel</label>
            <input type="text"
                   name="mapel"
                   class="form-control"
                   value="<?= htmlspecialchars($karya->mapel) ?>">
        </div>

        <button class="btn btn-primary">
            Simpan Perubahan
        </button>

        <a href="<?= site_url('SiswaKarya') ?>"
           class="btn btn-secondary">
            Batal
        </a>

    </form>

</div>