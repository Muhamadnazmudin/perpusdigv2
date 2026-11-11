<div class="container-fluid">

    <h3 class="mb-4">
        📖 Surah <?= $surah->nama_latin ?> (<?= $surah->nama ?>)
    </h3>

    <?php
    // fungsi angka arab
    function angka_arab($angka) {
        $arab = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        return str_replace(range(0,9), $arab, $angka);
    }

    // ambil bookmark terakhir user
    $last_ayat = null;
    $last = $this->Quran_model->get_last_bookmark($this->user['id_user']);
    if ($last && $last->surah_id == $surah->id) {
        $last_ayat = $last->ayat_id;
    }
    ?>

    <?php foreach ($ayat as $a): ?>

        <div class="card mb-3 shadow-sm <?= ($a->nomor == $last_ayat) ? 'border border-primary' : '' ?>"
             id="ayat-<?= $a->nomor ?>">

            <div class="card-body">

                <!-- Nomor Ayat -->
                <div class="text-muted mb-2">
                    Ayat <?= $a->nomor ?>
                </div>

                <!-- Teks Arab -->
                <div style="
                    font-family: 'Amiri', serif;
                    font-size: 28px;
                    text-align: right;
                    direction: rtl;
                    line-height: 2;
                ">
                    <?= $a->arab ?>
                    <span style="
                        display:inline-block;
                        border:1px solid #999;
                        border-radius:50%;
                        padding:3px 8px;
                        font-size:16px;
                        margin-right:5px;
                    ">
                        <?= angka_arab($a->nomor) ?>
                    </span>
                </div>

                <!-- Terjemahan -->
                <div class="mt-3 text-justify">
                    <?= $a->arti ?>
                </div>

                <!-- Bookmark -->
                <div class="mt-3">
                    <a href="<?= site_url('quran/bookmark/'.$surah->id.'/'.$a->nomor) ?>"
                       class="btn btn-sm btn-outline-primary">
                        🔖 Jadikan Terakhir Dibaca
                    </a>
                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>