<div class="container-fluid">

    <h1 class="h4 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row">

        <!-- ================= KOLOM KIRI : SCAN ================= -->
        <div class="col-md-4">

            <!-- PILIH MODE -->
            <div class="mb-3 text-center">
                <button class="btn btn-success btn-sm mr-2" id="btnRfid">
                    <i class="fas fa-id-card"></i> RFID
                </button>
                <button class="btn btn-secondary btn-sm" id="btnQr">
                    <i class="fas fa-qrcode"></i> QR
                </button>
            </div>

            <!-- ===== RFID ===== -->
            <div id="boxRfid">
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        Scan RFID
                    </div>
                    <div class="card-body text-center">

                        <input type="text"
                               id="rfid"
                               class="form-control text-center"
                               placeholder="Tempel kartu RFID"
                               autofocus
                               autocomplete="off">

                        <div id="notifRfid" class="mt-3"></div>

                        <small class="text-muted d-block mt-2">
                            Klik kolom lalu tempel kartu
                        </small>

                    </div>
                </div>
            </div>

            <!-- ===== QR ===== -->
            <div id="boxQr" style="display:none">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        Scan QR via Kamera
                    </div>
                    <div class="card-body text-center">

                        <div id="reader" style="width:100%;"></div>
                        <div id="notif" class="mt-3"></div>

                        <small class="text-muted d-block mt-2">
                            Arahkan kamera ke QR Code siswa
                        </small>

                    </div>
                </div>
            </div>

        </div>

        <!-- ================= KOLOM KANAN : TABEL ================= -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    Kunjungan Hari Ini
                </div>
                <div class="card-body table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Tujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($kunjungan as $k): ?>
                            <tr>
                                <td><?= $k->jam ?></td>
                                <td><?= $k->nis ?></td>
                                <td><?= $k->nama_siswa ?></td>
                                <td><?= $k->nama_kelas ?></td>
                                <td><?= $k->nama_jurusan ?></td>
                                <td><?= $k->tujuan ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>

</div>

<!-- ================= MODAL KONFIRMASI ================= -->
<div class="modal fade" id="modalKunjungan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Pilih Tujuan Kunjungan</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body text-center">

        <form id="formKunjungan">
            <input type="hidden" name="id_siswa" id="id_siswa">
            <input type="hidden" name="jam" id="jam">
            <input type="hidden" name="tujuan" id="tujuan">

            <div class="mb-2">
                <strong id="nama"></strong><br>
                <small id="kelas"></small>
            </div>

            <hr>

            <!-- TOMBOL TUJUAN -->
            <div class="mt-3">
    <button type="button" class="btn btn-outline-primary tujuan-btn" data-tujuan="Pinjam Buku">
        📚 PINJAM BUKU
    </button>

    <button type="button" class="btn btn-outline-success tujuan-btn" data-tujuan="Baca di Tempat">
        📖 BACA DI TEMPAT
    </button>

    <button type="button" class="btn btn-outline-warning tujuan-btn" data-tujuan="Mengembalikan Buku">
        🔄 MENGEMBALIKAN BUKU
    </button>

    <button type="button" class="btn btn-outline-secondary tujuan-btn" data-tujuan="Lainnya">
        ✍️ LAINNYA
    </button>
</div>

        </form>

        <div id="modalNotif" class="mt-3"></div>
      </div>

    </div>
  </div>
</div>
<style>
.tujuan-btn {
    width: 100%;
    padding: 18px 16px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 14px;
    text-align: center;
}

.tujuan-btn:not(:last-child) {
    margin-bottom: 12px;
}

@media (min-width: 768px) {
    .tujuan-btn {
        font-size: 1.2rem;
        padding: 22px 18px;
    }
}
</style>

<!-- ================= QR LIB ================= -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
/* ========= QR CAMERA CONTROL ========= */
let html5QrCode = null;
let cameraActive = false;
let lastScan = '';

function onScanSuccess(decodedText) {
    if (decodedText === lastScan) return;
    lastScan = decodedText;

    fetch('<?= site_url('kunjungan/scan') ?>', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'token=' + encodeURIComponent(decodedText)
    })
    .then(r => r.json())
    .then(res => {
        if (!res.status) {
            lastScan = '';
            document.getElementById('notif').innerHTML =
                `<div class="alert alert-danger">QR tidak valid</div>`;
            return;
        }
        isiModal(res);
        stopCamera();
    });
}

function startCamera() {
    if (cameraActive) return;
    html5QrCode = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then(cams => {
        if (!cams.length) return;
        html5QrCode.start(
            cams[0].id,
            { fps: 10, qrbox: 220 },
            onScanSuccess
        ).then(() => cameraActive = true);
    });
}

function stopCamera() {
    if (!cameraActive || !html5QrCode) return;
    html5QrCode.stop().then(() => {
        html5QrCode.clear();
        cameraActive = false;
    });
}

/* ========= RFID ========= */
let lastRfid = '';
document.getElementById('rfid').addEventListener('change', function () {
    let uid = this.value.trim();
    if (!uid || uid === lastRfid) return;
    lastRfid = uid;
    this.value = '';

    fetch('<?= site_url('kunjungan/scan_rfid') ?>', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'rfid_uid=' + encodeURIComponent(uid)
    })
    .then(r => r.json())
    .then(res => {
        if (!res.status) {
            document.getElementById('notifRfid').innerHTML =
                `<div class="alert alert-danger">${res.msg}</div>`;
            return;
        }
        isiModal(res);
    });
});

/* ========= MODAL ========= */
function isiModal(res) {
    document.getElementById('id_siswa').value = res.siswa.id_siswa;
    document.getElementById('jam').value      = res.jam;
    document.getElementById('nama').innerText = res.siswa.nama_siswa;
    document.getElementById('kelas').innerText= res.siswa.nama_kelas + ' - ' + res.siswa.nama_jurusan;
    $('#modalKunjungan').modal('show');
}

/* ========= PILIH TUJUAN ========= */
document.querySelectorAll('.tujuan-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('tujuan').value = this.dataset.tujuan;

        let form = new FormData(document.getElementById('formKunjungan'));
        fetch('<?= site_url('kunjungan/simpan') ?>', {
            method: 'POST',
            body: form
        })
        .then(r => r.json())
        .then(res => {
            document.getElementById('modalNotif').innerHTML =
                `<div class="alert ${res.status?'alert-success':'alert-danger'}">${res.msg}</div>`;
            if (res.status) setTimeout(()=>location.reload(),700);
        });
    });
});

/* ========= TOGGLE MODE ========= */
document.getElementById('btnRfid').onclick = () => {
    boxRfid.style.display = 'block';
    boxQr.style.display   = 'none';
    stopCamera();
    document.getElementById('rfid').focus();
};
document.getElementById('btnQr').onclick = () => {
    boxRfid.style.display = 'none';
    boxQr.style.display   = 'block';
    startCamera();
};
</script>
