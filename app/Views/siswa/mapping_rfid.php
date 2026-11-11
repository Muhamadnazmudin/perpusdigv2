<div class="container-fluid">

    <h1 class="h4 mb-4 text-gray-800"><?= $title ?></h1>

    <!-- PILIH KELAS -->
    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="form-row align-items-end">
                <div class="col-md-4">
                    <label>Pilih Kelas</label>
                    <select id="kelas" class="form-control">
                        <option value="">- Pilih Kelas -</option>
                        <?php foreach($kelas as $k): ?>
                            <option value="<?= $k->id_kelas ?>">
                                <?= $k->nama_kelas ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary" id="btnLoad">
                        Tampilkan Siswa
                    </button>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-danger" id="btnReset">
                        Reset RFID Kelas
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- AREA MAPPING -->
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            Mapping RFID (Tap Berurutan)
        </div>
        <div class="card-body">

            <input type="text"
                   id="rfid"
                   class="form-control text-center mb-3"
                   placeholder="Tap kartu RFID sekarang"
                   autofocus
                   autocomplete="off">

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>RFID</th>
                        </tr>
                    </thead>
                    <tbody id="listSiswa">
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Pilih kelas terlebih dahulu
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="notif" class="mt-3"></div>

        </div>
    </div>

</div>

<script>
let siswa = [];
let index = 0;

// load siswa
document.getElementById('btnLoad').onclick = () => {
    let kelas = document.getElementById('kelas').value;
    if (!kelas) return alert('Pilih kelas');

    fetch('<?= site_url('mapping_rfid/get_siswa') ?>', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'id_kelas='+kelas
    })
    .then(r=>r.json())
    .then(res=>{
        siswa = res;
        index = 0;
        render();
 remindingFocus();
    });
};

// render table
function render(){
    let html = '';
    siswa.forEach((s,i)=>{
        html += `
        <tr class="${i===index?'table-warning':''}">
            <td>${i+1}</td>
            <td>${s.nis}</td>
            <td>${s.nama_siswa}</td>
            <td>${s.rfid_uid ? s.rfid_uid : '<span class="text-muted">menunggu</span>'}</td>
        </tr>`;
    });
    document.getElementById('listSiswa').innerHTML = html;
}

// RFID scan
document.getElementById('rfid').addEventListener('change',function(){
    if (!siswa[index]) return;

    let uid = this.value.trim();
    this.value='';

    fetch('<?= site_url('mapping_rfid/simpan') ?>',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`id_siswa=${siswa[index].id_siswa}&rfid_uid=${uid}`
    })
    .then(r=>r.json())
    .then(res=>{
        if (!res.status){
            document.getElementById('notif').innerHTML =
                `<div class="alert alert-danger">${res.msg}</div>`;
            return;
        }

        siswa[index].rfid_uid = uid;
        index++;
        render();
    });
});

// reset kelas
document.getElementById('btnReset').onclick = ()=>{
    if (!confirm('Reset RFID semua siswa kelas ini?')) return;
    let kelas = document.getElementById('kelas').value;

    fetch('<?= site_url('mapping_rfid/reset_kelas') ?>',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'id_kelas='+kelas
    }).then(()=>location.reload());
};

function remindingFocus(){
    document.getElementById('rfid').focus();
}
</script>
