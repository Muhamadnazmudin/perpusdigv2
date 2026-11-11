<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin | Perpusdig</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- STYLE SAMA PERSIS -->
    <style>
        *{box-sizing:border-box;font-family:'Segoe UI',sans-serif}
        body{
            margin:0;min-height:100vh;
            background:linear-gradient(135deg,#a8e6cf,#dcedc1);
            display:flex;justify-content:center;align-items:center
        }
        .login-card{
            background:#fff;width:100%;max-width:380px;
            padding:30px;border-radius:16px;
            box-shadow:0 10px 30px rgba(0,0,0,.15);
            animation:fadeIn .6s ease
        }
        @keyframes fadeIn{
            from{opacity:0;transform:translateY(20px)}
            to{opacity:1;transform:translateY(0)}
        }
        .login-card h2{text-align:center;margin-bottom:8px;color:#2e7d32}
        .login-card p{text-align:center;color:#666;font-size:14px;margin-bottom:25px}
        .alert-error{
            background:#ffebee;color:#c62828;
            padding:10px;border-radius:8px;
            font-size:14px;margin-bottom:15px;text-align:center
        }
        .form-group{margin-bottom:15px}
        .form-group input{
            width:100%;padding:12px 14px;
            border-radius:10px;border:1px solid #ccc;
            font-size:14px;transition:.3s
        }
        .form-group input:focus{
            outline:none;border-color:#66bb6a;
            box-shadow:0 0 0 2px rgba(102,187,106,.2)
        }
        .btn-login{
            width:100%;padding:12px;border:none;
            border-radius:12px;
            background:linear-gradient(135deg,#66bb6a,#43a047);
            color:#fff;font-size:15px;font-weight:bold;
            cursor:pointer;transition:.3s
        }
        .btn-login:hover{
            transform:translateY(-1px);
            box-shadow:0 8px 15px rgba(67,160,71,.3)
        }
        .footer-text{text-align:center;margin-top:20px;font-size:12px;color:#777}
    </style>
</head>
<body>

<div class="login-card">
    <h2>Perpusdig</h2>
    <p>Login Admin Perpustakaan Digital</p>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert-error">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('login/admin') ?>">
        <input type="hidden"
               name="<?= $this->security->get_csrf_token_name(); ?>"
               value="<?= $this->security->get_csrf_hash(); ?>">

        <div class="form-group">
            <input type="text" name="username" placeholder="Username Admin" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="btn-login">Login Admin</button>
    </form>

    <div class="footer-text">
        © <?= date('Y') ?> Perpusdig-Misdi
    </div>
</div>

</body>
</html>
