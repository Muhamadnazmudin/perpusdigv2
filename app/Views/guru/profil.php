<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$foto = !empty($guru->foto)
    ? base_url($guru->foto) . '?v=' . time()
    : base_url('assets/img/user.png');
?>

<div class="container-fluid">

    <h1 class="h4 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow mb-3">

                <div class="card-header bg-primary text-white text-center">
                    <strong>PERPUSTAKAAN DIGITAL SEKOLAH</strong><br>
                    <small>ID CARD GURU</small>
                </div>

                <div class="card-body text-center">

                    <img src="<?= $foto ?>"
                         class="img-thumbnail mb-3"
                         style="width:120px;height:140px;object-fit:cover;">

                    <h5 class="font-weight-bold">
                        <?= htmlspecialchars($guru->nama_guru) ?>
                    </h5>

                    <small class="text-muted">GURU</small>

                    <hr>

                    <table class="table table-borderless text-left">
                        <tr>
                            <td>NIP</td>
                            <td class="text-right">
                                <strong><?= $guru->nip ?></strong>
                            </td>
                        </tr>

                        <?php if (!empty($guru->email)): ?>
                        <tr>
                            <td>Email</td>
                            <td class="text-right">
                                <?= $guru->email ?>
                            </td>
                        </tr>
                        <?php endif; ?>

                    </table>

                </div>

            </div>

        </div>
    </div>

</div>