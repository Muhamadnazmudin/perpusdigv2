<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <?php if (!in_array((int)$this->user['id_role'], [2,3])): ?>
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
</button>
<?php endif; ?>
            <!-- Sidebar Toggle (Desktop) -->
<button id="sidebarToggle"
        class="btn btn-link d-none d-md-inline rounded-circle mr-3">
    <i class="fas fa-bars"></i>
</button>


            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

    <!-- 🔔 NOTIF PEMINJAMAN (ADMIN) -->
    <?php if ($this->user['id_role'] === 1): ?>
   <li class="nav-item no-arrow mx-1">
    <a class="nav-link" href="<?= site_url('peminjaman') ?>" title="Pengajuan Peminjaman">
        <i class="fas fa-bell fa-fw"></i>

        <?php if (!empty($total_menunggu) && $total_menunggu > 0): ?>
            <span class="badge badge-danger badge-counter">
                <?= $total_menunggu ?>
            </span>
        <?php endif; ?>
    </a>
</li>

    <?php endif; ?>

    <div class="topbar-divider d-none d-sm-block"></div>
    <!-- THEME SWITCHER -->
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="themeDropdown"
       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-palette fa-fw"></i>
        <span class="d-none d-lg-inline small">Tema</span>
    </a>

    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in p-3"
         aria-labelledby="themeDropdown"
         style="min-width: 200px;">

        <div class="d-flex justify-content-between flex-wrap">

            <?php
            $themes = [
                'light'  => '#f8f9fc',
                'green'  => '#1cc88a',
                'orange' => '#fd7e14',
                'pink'   => '#e83e8c',
                'purple' => '#6f42c1',
                'dark'   => '#1f1f1f',
            ];
            $active = $this->session->userdata('theme') ?? 'light';
            ?>

            <?php foreach ($themes as $key => $color): ?>
                <form action="<?= site_url('pengaturan/simpan_tema') ?>" method="post" style="display:inline;">
                    <input type="hidden" name="theme" value="<?= $key ?>">
                    <button type="submit"
    class="theme-dot <?= ($active == $key) ? 'active' : '' ?>"
    style="background-color: <?= $color ?>;"
    title="<?= ucfirst($key) ?>">
</button>

                </form>
            <?php endforeach; ?>

        </div>

    </div>
</li>
<!-- END THEME SWITCHER -->


    <!-- User Info -->
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                <?= $this->session->userdata('user')['nama'] ?>
            </span>
            <i class="fas fa-user-circle fa-2x"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
            <a class="dropdown-item" href="<?= site_url('auth/logout') ?>">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </li>

</ul>


        </nav>
        <!-- End of Topbar -->
