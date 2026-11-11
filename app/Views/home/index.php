<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perpusdig | Perpustakaan SMKN 1 Cilimus</title>
    <!-- Open Graph / WhatsApp Preview -->
     <meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:type" content="website">
<meta property="og:url" content="https://app.smkncilimus1.sch.id/perpusdig/">
<meta property="og:title" content="Perpusdig – Perpustakaan Digital SMK Negeri 1 Cilimus">
<meta property="og:description" content="Perpusdig merupakan aplikasi perpustakaan digital yang modern, diperuntukkan untuk pengelolaan buku, peminjaman, kunjungan, dan literasi siswa secara terintegrasi.">
<meta property="og:image" content="https://app.smkncilimus1.sch.id/perpusdig/assets/img/logobispar.png">

<meta property="og:site_name" content="Perpusdig">
<meta property="og:locale" content="id_ID">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="https://app.smkncilimus1.sch.id/perpusdig/assets/img/logobispar.png">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            background: #f5fdf8;
            color: #333;
            line-height: 1.7;
        }

        h1,h2,h3 {
            margin-top: 0;
        }

        a {
            text-decoration: none;
        }

        section {
            padding: 70px 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        /* ===== HERO ===== */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #43a047, #81c784);
            color: #fff;
            display: flex;
            align-items: center;
            text-align: center;
        }

        .hero h1 {
            font-size: 42px;
        }

        .hero p {
            font-size: 18px;
            margin: 15px 0 35px;
        }

        .btn {
            padding: 14px 32px;
            background: #fff;
            color: #2e7d32;
            border-radius: 30px;
            font-weight: bold;
            transition: 0.3s;
            display: inline-block;
        }

        .btn:hover {
            background: #e8f5e9;
            transform: translateY(-2px);
        }

        /* ===== CARD & GRID ===== */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px,1fr));
            gap: 30px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
            gap: 25px;
        }

        .card {
            background: #fff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
        }

        /* ===== STAT ===== */
        .stat {
            text-align: center;
        }

        .stat h3 {
            font-size: 34px;
            color: #2e7d32;
            margin-bottom: 5px;
        }

        /* ===== TITLE ===== */
        .title {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 50px;
        }

        /* ===== FOOTER ===== */
        footer {
            background: #e8f5e9;
            text-align: center;
            padding: 25px;
            font-size: 14px;
            color: #555;
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 30px;
            }
            .hero p {
                font-size: 16px;
            }
        }
        /* === CARD HOME KHUSUS BUKU === */
.book-card {
    background: #fff;
    padding: 22px;
    border-radius: 18px;
    box-shadow: 0 10px 25px rgba(0,0,0,.08);
    display: flex;
    flex-direction: column;   /* PAKSA VERTICAL */
}

/* KOTAK COVER */
.book-cover-box {
    width: 100%;
    aspect-ratio: 1 / 1;      /* KOTAK */
    overflow: hidden;
    border-radius: 12px;
    background: #f2f2f2;
    margin-bottom: 12px;
}

/* GAMBAR */
.book-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* JUDUL */
.book-title {
    font-size: 16px;
    font-weight: 600;
    margin: 8px 0 4px;
}

/* META */
.book-meta {
    font-size: 13px;
    color: #666;
    margin-bottom: 8px;
}

/* STOK */
.book-stock {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 10px;
}

.stock-ok { color:#2e7d32; }
.stock-no { color:#c62828; }

.book-meta {
    font-size: 13px;
    color: #666;
    margin: 6px 0;
}

.book-meta span {
    display: block;
}

.book-stock {
    font-size: 13px;
    font-weight: bold;
    margin-top: 6px;
}

.stock-available {
    color: #2e7d32;
}

.stock-empty {
    color: #c62828;
}

    </style>
</head>
<body>

<!-- ================= HERO ================= -->
<div class="hero">
    <div class="container">
        <img src="<?= base_url('assets/img/logobispar.png') ?>" width="120" alt="Logo">
        <h1>Perpusdig</h1>
        <p>
            Perpustakaan Digital & Literasi  
            <br>SMKN 1 Cilimus
        </p>
        <a href="<?= site_url('login') ?>" class="btn">Masuk Perpustakaan</a>
    </div>
</div>

<!-- ================= TENTANG ================= -->
<section>
    <div class="container">
        <h2 class="title">Tentang Kami</h2>

        <div class="card">
            <p>
                Perpustakaan SMKN 1 Cilimus merupakan pusat sumber belajar
                yang menyediakan koleksi buku dan sumber informasi untuk
                mendukung proses pembelajaran dan kegiatan literasi.
            </p>
            <p>
                Dengan suasana yang nyaman serta pelayanan yang ramah,
                perpustakaan menjadi tempat membaca, berdiskusi,
                dan mengembangkan wawasan bagi seluruh warga sekolah.
            </p>
        </div>
    </div>
</section>

<!-- ================= STATISTIK ================= -->
<section id="statistik" style="background:#f0faf3;">
    <div class="container">
        <h2 class="title">Statistik Perpustakaan</h2>

        <div class="grid-3">
            <div class="card stat">
                <h3 class="counter" data-target="2500">0</h3>
                <p>Koleksi Buku</p>
            </div>
            <div class="card stat">
                <h3 class="counter" data-target="500">0</h3>
                <p>E-Book Digital</p>
            </div>
            <div class="card stat">
                <h3 class="counter" data-target="1000">0</h3>
                <p>Anggota Aktif</p>
            </div>
        </div>
    </div>
</section>

<!-- ================= E-BOOK ================= -->
<section style="background:#f6fff9;">
    <div class="container">
        <h2 class="title">📘 Buku Digital</h2>
        <p style="text-align:center;color:#666;">
            Jelajahi e-book pilihan. Login untuk membaca lengkap.
        </p>

        <div class="grid-3">
            <?php foreach ($ebooks as $e): ?>
                <?php
                // COVER E-BOOK
                $cover_ebook = (!empty($e->cover) &&
                    file_exists(FCPATH.'assets/uploads/cover_ebook/'.$e->cover))
                    ? base_url('assets/uploads/cover_ebook/'.$e->cover)
                    : base_url('assets/img/no-book.png');
                ?>

                <div class="book-card">
    <div class="book-cover-box"
         style="cursor:zoom-in"
         onclick="showCover('<?= $cover_ebook ?>')">
        <img src="<?= $cover_ebook ?>"
             class="book-cover-img"
             alt="<?= htmlspecialchars($e->judul) ?>">
    </div>

    <div class="book-title">
        <?= htmlspecialchars($e->judul) ?>
    </div>

    <div class="book-meta">
        ✍️ <?= htmlspecialchars($e->penulis ?? '-') ?><br>
        📘 <?= htmlspecialchars($e->mapel ?? '-') ?> | <?= htmlspecialchars($e->kelas ?? '-') ?>
    </div>

    <?php if ($this->session->userdata('login')): ?>
        <a href="<?= site_url('ebook/baca/'.$e->id_ebook) ?>" class="btn">
            Baca Sekarang
        </a>
    <?php else: ?>
        <a href="<?= site_url('login') ?>" class="btn">
            Login untuk Membaca
        </a>
    <?php endif; ?>
</div>

            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- ================= BUKU FISIK ================= -->
<section>
    <div class="container">
        <h2 class="title">📕 Buku Fisik</h2>
        <p style="text-align:center;color:#666;">
            Tersedia di perpustakaan. Login untuk melakukan peminjaman.
        </p>

        <div class="grid-3">
            <?php foreach ($buku_fisik as $b): ?>
                <?php
                // COVER BUKU FISIK (AMAN)
                $cover_fisik = (!empty($b->cover) &&
                    file_exists(FCPATH.'uploads/cover/'.$b->cover))
                    ? base_url('uploads/cover/'.$b->cover)
                    : base_url('assets/img/no-book.png');
                ?>

                <div class="book-card">
    <div class="book-cover-box"
         style="cursor:zoom-in"
         onclick="showCover('<?= $cover_fisik ?>')">
        <img src="<?= $cover_fisik ?>"
             class="book-cover-img"
             alt="<?= htmlspecialchars($b->judul) ?>">
    </div>

    <div class="book-title">
        <?= htmlspecialchars($b->judul) ?>
    </div>

    <div class="book-meta">
        ✍️ <?= htmlspecialchars($b->penulis ?? '-') ?>
    </div>

    <div class="book-stock">
        <?php if ((int)$b->stok > 0): ?>
            <span class="stock-ok">📦 Stok: <?= (int)$b->stok ?></span>
        <?php else: ?>
            <span class="stock-no">❌ Stok Habis</span>
        <?php endif; ?>
    </div>

    <?php if ($this->session->userdata('login')): ?>
        <?php if ((int)$b->stok > 0): ?>
            <a href="<?= site_url('peminjaman/ajukan/'.$b->id_buku) ?>" class="btn">
                Pinjam Buku
            </a>
        <?php else: ?>
            <span style="font-size:13px;color:#999;">
                Tidak tersedia
            </span>
        <?php endif; ?>
    <?php else: ?>
        <a href="<?= site_url('login') ?>" class="btn">
            Login untuk Meminjam
        </a>
    <?php endif; ?>
</div>

            <?php endforeach; ?>
        </div>
    </div>
</section>


<!-- ================= VISI MISI ================= -->
<section>
    <div class="container">
        <h2 class="title">Visi & Misi</h2>

        <div class="grid-2">
            <div class="card">
                <h3>Visi</h3>
                <p><em>“Membaca Membentuk Jiwa Profesionalisme”</em></p>
            </div>

            <div class="card">
                <h3>Misi</h3>
                <ul>
                    <li>Meningkatkan minat baca di lingkungan sekolah</li>
                    <li>Penunjang kegiatan belajar mengajar</li>
                    <li>Pusat informasi, edukasi, dan penelitian</li>
                    <li>Mengembangkan bahan pustaka</li>
                    <li>Meningkatkan SDM perpustakaan</li>
                    <li>Meningkatkan kualitas layanan</li>
                    <li>Menciptakan ruang baca yang nyaman</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ================= TATA TERTIB & JAM ================= -->
<section style="background:#f6fff9;">
    <div class="container">
        <h2 class="title">Tata Tertib & Jam Layanan</h2>

        <div class="grid-2">
            <div class="card">
                <h3>Tata Tertib</h3>
                <ul>
                    <li>Seluruh siswa adalah anggota perpustakaan</li>
                    <li>Peminjaman referensi wajib surat guru</li>
                    <li>Maksimal peminjaman 1 minggu</li>
                    <li>Buku wajib dijaga</li>
                    <li>Buku hilang wajib diganti</li>
                    <li>Dilarang berisik, makan & minum</li>
                </ul>
            </div>

            <div class="card">
                <h3>Jam Layanan</h3>
                <p>Senin : 07.00 – 15.05</p>
                <p>Selasa – Kamis : 07.00 – 14.25</p>
                <p>Jumat : 07.00 – 14.00</p>
            </div>
        </div>
    </div>
</section>

<!-- ================= MAPS ================= -->
<section>
    <div class="container">
        <h2 class="title">Lokasi Perpustakaan</h2>

        <div class="card">
            <iframe
                src="https://www.google.com/maps?q=SMKN+1+Cilimus&output=embed"
                width="100%"
                height="350"
                style="border-radius:15px; border:0;"
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- ================= KONTAK ================= -->
<section style="background:#e8f5e9;">
    <div class="container">
        <h2 class="title">Kontak & Alamat</h2>

        <div class="card" style="text-align:center;">
            <p>📞 (0232) 8910145</p>
            <p>📧 smkn_1cilimus@yahoo.com</p>
            <p>
                📍 Jl. Kyai Eyang Hasan Maulani  
                <br>Caracas – Cilimus, Kab. Kuningan
            </p>
        </div>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer>
    © <?= date('Y') ?> Perpusdig – Perpustakaan SMKN 1 Cilimus
</footer>
<!-- ===== MODAL FULL COVER ===== -->
<div id="coverModal" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.75);
    z-index:9999;
    align-items:center;
    justify-content:center;
">
    <span onclick="closeCover()" style="
        position:absolute;
        top:20px;
        right:30px;
        font-size:32px;
        color:#fff;
        cursor:pointer;
    ">&times;</span>

    <img id="coverModalImg" src=""
         style="
            max-width:90%;
            max-height:90%;
            border-radius:16px;
            box-shadow:0 20px 50px rgba(0,0,0,.5);
         ">
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const counters = document.querySelectorAll('.counter');
    let hasAnimated = false;

    const runCounter = () => {
        counters.forEach(counter => {
            const target = +counter.dataset.target;
            const speed = 40;

            const updateCount = () => {
                const current = +counter.innerText;
                const increment = Math.ceil(target / speed);

                if (current < target) {
                    counter.innerText = current + increment;
                    setTimeout(updateCount, 20);
                } else {
                    counter.innerText = target.toLocaleString() + '+';
                }
            };

            updateCount();
        });
    };

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !hasAnimated) {
                hasAnimated = true;
                runCounter();
            }
        });
    }, { threshold: 0.4 });

    const statSection = document.getElementById('statistik');
    if (statSection) observer.observe(statSection);
});
</script>

</body>
</html>
<script>
function showCover(src) {
    document.getElementById('coverModalImg').src = src;
    document.getElementById('coverModal').style.display = 'flex';
}

function closeCover() {
    document.getElementById('coverModal').style.display = 'none';
    document.getElementById('coverModalImg').src = '';
}
</script>
