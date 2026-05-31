<?php
require_once '../includes/config.php';

if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (empty($username) || empty($email) || empty($password)) {
            $error = 'Semua field wajib diisi.';
        } elseif (strlen($username) < 3) {
            $error = 'Username minimal 3 karakter.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Format email tidak valid.';
        } elseif (strlen($password) < 6) {
            $error = 'Password minimal 6 karakter.';
        } elseif ($password !== $confirm) {
            $error = 'Konfirmasi password tidak cocok.';
        } else {
            $result = registerUser($username, $email, $password);
            if ($result['success']) {
                $success = 'Registrasi berhasil! Silakan login.';
            } else {
                $error = $result['message'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700;900&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
:root {
    --black: #050508;
    --purple: #6b21e8;
    --purple-glow: #9d4dff;
    --gold: #f0c040;
    --gold-light: #ffe080;
    --red: #cc2233;
    --text: #e8e0f0;
    --text-muted: #8880a0;
    --border: rgba(107,33,232,0.3);
}
* { margin:0; padding:0; box-sizing:border-box; }
body {
    background: var(--black);
    color: var(--text);
    font-family: 'Rajdhani', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}
.bg-curse {
    position: fixed; inset: 0; z-index: 0;
    background: 
        radial-gradient(ellipse 60% 50% at 80% 50%, rgba(107,33,232,0.15) 0%, transparent 60%),
        radial-gradient(ellipse 40% 60% at 20% 30%, rgba(204,34,51,0.08) 0%, transparent 50%);
    animation: bgPulse 8s ease-in-out infinite alternate;
}
@keyframes bgPulse { 0% { opacity:0.6; } 100% { opacity:1; } }
.particles { position:fixed; inset:0; z-index:0; pointer-events:none; }
.particle {
    position:absolute; width:2px; height:2px;
    background:var(--purple-glow); border-radius:50%;
    animation:float linear infinite; opacity:0;
}
@keyframes float {
    0% { transform:translateY(100vh); opacity:0; }
    10% { opacity:0.8; }
    100% { transform:translateY(-10vh); opacity:0; }
}
.register-wrapper {
    position: relative; z-index:10;
    display:flex; gap:0;
    width: min(900px, 95vw);
    min-height: 620px;
    border-radius:4px; overflow:hidden;
    box-shadow: 0 0 60px rgba(107,33,232,0.3);
    border: 1px solid var(--border);
}
.login-art {
    flex:1;
    background: linear-gradient(135deg, #0d0020 0%, #200040 40%, #0a0a20 100%);
    display:flex; flex-direction:column;
    align-items:center; justify-content:center;
    padding:40px; position:relative; overflow:hidden;
}
.login-art::before {
    content:''; position:absolute;
    width:350px; height:350px;
    background:radial-gradient(circle, rgba(204,34,51,0.25) 0%, transparent 70%);
    border-radius:50%; top:50%; left:50%;
    transform:translate(-50%,-50%);
    animation:artPulse 4s ease-in-out infinite;
}
@keyframes artPulse {
    0%,100% { transform:translate(-50%,-50%) scale(1); opacity:0.5; }
    50% { transform:translate(-50%,-50%) scale(1.1); opacity:1; }
}
.jjk-symbol { font-size:7rem; line-height:1; text-shadow:0 0 40px rgba(204,34,51,0.8); animation:symbolPulse 3s ease-in-out infinite; position:relative; z-index:1; }
@keyframes symbolPulse {
    0%,100% { text-shadow:0 0 40px rgba(204,34,51,0.8); }
    50% { text-shadow:0 0 80px rgba(255,80,100,1), 0 0 120px rgba(204,34,51,0.5); }
}
.art-title { font-family:'Cinzel Decorative',serif; font-size:1.3rem; color:var(--gold); text-align:center; margin-top:20px; position:relative; z-index:1; letter-spacing:2px; }
.art-subtitle { font-family:'Orbitron',sans-serif; font-size:0.6rem; color:#ff7088; letter-spacing:4px; text-transform:uppercase; margin-top:8px; position:relative; z-index:1; }
.cursed-lines {
    position:absolute; inset:0;
    background-image:linear-gradient(45deg, transparent 48%, rgba(204,34,51,0.08) 49%, rgba(204,34,51,0.08) 51%, transparent 52%);
    background-size:60px 60px;
}
.register-form-side {
    flex:1;
    background: linear-gradient(180deg, #08050f 0%, #0a0814 100%);
    padding:40px 40px; display:flex; flex-direction:column; justify-content:center;
    border-left: 1px solid var(--border);
}
.form-title { font-family:'Cinzel Decorative',serif; font-size:1.5rem; color:var(--text); margin-bottom:6px; }
.form-subtitle { color:var(--text-muted); font-size:0.9rem; margin-bottom:28px; }
.form-group { margin-bottom:16px; }
.form-label { display:block; font-family:'Orbitron',sans-serif; font-size:0.6rem; letter-spacing:3px; color:var(--purple-glow); text-transform:uppercase; margin-bottom:7px; }
.form-input {
    width:100%; background:rgba(107,33,232,0.05); border:1px solid rgba(107,33,232,0.25); border-radius:2px; padding:12px 16px; color:var(--text); font-family:'Rajdhani',sans-serif; font-size:1rem; font-weight:500; transition:all 0.3s; outline:none;
}
.form-input:focus { border-color:var(--purple-glow); background:rgba(107,33,232,0.1); box-shadow:0 0 15px rgba(107,33,232,0.2); }
.form-input::placeholder { color:var(--text-muted); }
.btn-register {
    width:100%; padding:15px; background:linear-gradient(135deg, #cc2233 0%, #ff3355 100%); border:none; border-radius:2px; color:white; font-family:'Orbitron',sans-serif; font-size:0.8rem; font-weight:700; letter-spacing:3px; cursor:pointer; transition:all 0.3s; margin-top:8px;
}
.btn-register:hover { box-shadow:0 0 30px rgba(204,34,51,0.6); transform:translateY(-1px); }
.error-msg { background:rgba(204,34,51,0.1); border:1px solid rgba(204,34,51,0.3); border-radius:2px; padding:12px 16px; color:#ff6677; font-size:0.9rem; margin-bottom:16px; }
.success-msg { background:rgba(0,200,100,0.1); border:1px solid rgba(0,200,100,0.3); border-radius:2px; padding:12px 16px; color:#00cc66; font-size:0.9rem; margin-bottom:16px; }
.login-link { text-align:center; margin-top:20px; color:var(--text-muted); font-size:0.9rem; }
.login-link a { color:var(--gold); text-decoration:none; font-weight:600; }
.login-link a:hover { color:var(--gold-light); }
@media (max-width:640px) { .login-art { display:none; } .register-form-side { padding:30px 20px; } }
</style>
</head>
<body>
<div class="bg-curse"></div>
<div class="particles" id="particles"></div>
<div class="register-wrapper">
    <div class="login-art">
        <div class="cursed-lines"></div>
        <div class="jjk-symbol">術</div>
        <div class="art-title">JJK Universe</div>
        <div class="art-subtitle">Join The Sorcerers</div>
    </div>
    <div class="register-form-side">
        <h1 class="form-title">Bergabung</h1>
        <p class="form-subtitle">Daftarkan dirimu sebagai Jujutsu Sorcerer</p>
        <?php if ($error): ?><div class="error-msg">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success-msg">✓ <?= htmlspecialchars($success) ?> <a href="login.php" style="color:var(--gold)">Login →</a></div><?php endif; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" placeholder="yuji_itadori" required minlength="3">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" placeholder="sorcerer@jjk.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Min. 6 karakter" required minlength="6">
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="confirm_password" class="form-input" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="btn-register">DAFTAR SEKARANG ↗</button>
        </form>
        <div class="login-link">Sudah punya akun? <a href="login.php">Login</a></div>
    </div>
</div>
<script>
const container = document.getElementById('particles');
for (let i = 0; i < 25; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random() * 100 + 'vw';
    p.style.animationDuration = (Math.random() * 15 + 10) + 's';
    p.style.animationDelay = (Math.random() * 15) + 's';
    p.style.background = Math.random() > 0.5 ? '#cc2233' : '#9d4dff';
    container.appendChild(p);
}
</script>
</body>
</html>