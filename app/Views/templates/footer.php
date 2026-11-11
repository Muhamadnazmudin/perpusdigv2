        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>© <?= date('Y') ?> Perpusdig - Perpustakaan Digital Sekolah by KangCau</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->
<?php if (isset($this->user) && in_array((int)$this->user['id_role'], [2,3])): ?>

<style>
@media (max-width:768px){

    body{
        padding-bottom:75px;
    }

    #accordionSidebar{
        display:none !important;
    }

    .mobile-nav{
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
        height:65px;
        background:#fff;
        display:flex;
        box-shadow:0 -2px 10px rgba(0,0,0,.1);
        z-index:1030;
    }

    .mobile-nav a{
        flex:1;
        text-align:center;
        text-decoration:none;
        color:#6c757d;
        font-size:11px;
        padding-top:8px;
    }

    .mobile-nav a i{
        display:block;
        font-size:20px;
        margin-bottom:3px;
    }
}
</style>

<div class="mobile-nav d-md-none">

    <a href="<?= site_url('dashboard') ?>">
        <i class="fas fa-home"></i>
        Home
    </a>

    <a href="<?= site_url('buku') ?>">
        <i class="fas fa-book-open"></i>
        Buku
    </a>

    <a href="<?= site_url('SiswaEbook') ?>">
        <i class="fas fa-tablet-alt"></i>
        E-Book
    </a>

    <a href="<?= site_url('peminjaman/daftar') ?>">
        <i class="fas fa-book-reader"></i>
        Pinjam
    </a>

    <a href="<?= site_url('profil') ?>">
        <i class="fas fa-user-circle"></i>
        Akun
    </a>

</div>

<?php endif; ?>
<!-- SB Admin 2 JS -->
<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/js/sb-admin-2.min.js') ?>"></script>

</body>
</html>
