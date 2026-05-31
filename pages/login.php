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
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $error = 'Semua field wajib diisi.';
        } else {
            $result = loginUser($email, $password);
            if ($result['success']) {
                if ($result['role'] === 'admin') {
                    header('Location: ' . SITE_URL . '/admin/dashboard.php');
                } else {
                    header('Location: ' . SITE_URL . '/index.php');
                }
                exit;
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
<title>Login — JJK Universe</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700;900&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
:root {
    --black: #050508;
    --deep: #0a0a14;
    --purple: #6b21e8;
    --purple-glow: #9d4dff;
    --blue: #1a0533;
    --gold: #f0c040;
    --gold-light: #ffe080;
    --red: #cc2233;
    --text: #e8e0f0;
    --text-muted: #8880a0;
    --border: rgba(107,33,232,0.3);
    --glow: 0 0 30px rgba(107,33,232,0.5);
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
        radial-gradient(ellipse 60% 50% at 20% 50%, rgba(107,33,232,0.15) 0%, transparent 60%),
        radial-gradient(ellipse 40% 60% at 80% 30%, rgba(204,34,51,0.08) 0%, transparent 50%),
        radial-gradient(ellipse 80% 40% at 50% 100%, rgba(157,77,255,0.1) 0%, transparent 60%);
    animation: bgPulse 8s ease-in-out infinite alternate;
}

@keyframes bgPulse {
    0% { opacity: 0.6; }
    100% { opacity: 1; }
}

.particles {
    position: fixed; inset: 0; z-index: 0; pointer-events: none;
}

.particle {
    position: absolute;
    width: 2px; height: 2px;
    background: var(--purple-glow);
    border-radius: 50%;
    animation: float linear infinite;
    opacity: 0;
}

@keyframes float {
    0% { transform: translateY(100vh) translateX(0); opacity: 0; }
    10% { opacity: 0.8; }
    90% { opacity: 0.3; }
    100% { transform: translateY(-10vh) translateX(30px); opacity: 0; }
}

.login-wrapper {
    position: relative; z-index: 10;
    display: flex;
    gap: 0;
    width: min(900px, 95vw);
    min-height: 560px;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 0 60px rgba(107,33,232,0.3), 0 0 120px rgba(107,33,232,0.1);
    border: 1px solid var(--border);
}

.login-art {
    flex: 1;
    background: linear-gradient(135deg, #0d0020 0%, #1a0040 40%, #0a0a20 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    position: relative;
    overflow: hidden;
}

.login-art::before {
    content: '';
    position: absolute;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(107,33,232,0.3) 0%, transparent 70%);
    border-radius: 50%;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    animation: artPulse 4s ease-in-out infinite;
}

@keyframes artPulse {
    0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
    50% { transform: translate(-50%, -50%) scale(1.1); opacity: 1; }
}

.jjk-symbol {
    font-size: 8rem;
    line-height: 1;
    text-shadow: 0 0 40px rgba(107,33,232,0.8);
    animation: symbolPulse 3s ease-in-out infinite;
    position: relative; z-index: 1;
}

@keyframes symbolPulse {
    0%, 100% { text-shadow: 0 0 40px rgba(107,33,232,0.8); }
    50% { text-shadow: 0 0 80px rgba(157,77,255,1), 0 0 120px rgba(107,33,232,0.5); }
}

.art-title {
    font-family: 'Cinzel Decorative', serif;
    font-size: 1.4rem;
    color: var(--gold);
    text-align: center;
    margin-top: 20px;
    position: relative; z-index: 1;
    letter-spacing: 2px;
    text-shadow: 0 0 20px rgba(240,192,64,0.5);
}

.art-subtitle {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.65rem;
    color: var(--purple-glow);
    letter-spacing: 4px;
    text-transform: uppercase;
    margin-top: 8px;
    position: relative; z-index: 1;
}

.cursed-lines {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(45deg, transparent 48%, rgba(107,33,232,0.1) 49%, rgba(107,33,232,0.1) 51%, transparent 52%),
        linear-gradient(-45deg, transparent 48%, rgba(107,33,232,0.05) 49%, rgba(107,33,232,0.05) 51%, transparent 52%);
    background-size: 60px 60px;
}

.login-form-side {
    flex: 1;
    background: linear-gradient(180deg, #08050f 0%, #0a0814 100%);
    padding: 50px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-left: 1px solid var(--border);
}

.form-title {
    font-family: 'Cinzel Decorative', serif;
    font-size: 1.6rem;
    color: var(--text);
    margin-bottom: 6px;
}

.form-subtitle {
    font-family: 'Rajdhani', sans-serif;
    color: var(--text-muted);
    font-size: 0.95rem;
    margin-bottom: 35px;
    letter-spacing: 1px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.65rem;
    letter-spacing: 3px;
    color: var(--purple-glow);
    text-transform: uppercase;
    margin-bottom: 8px;
}

.form-input {
    width: 100%;
    background: rgba(107,33,232,0.05);
    border: 1px solid rgba(107,33,232,0.25);
    border-radius: 2px;
    padding: 14px 16px;
    color: var(--text);
    font-family: 'Rajdhani', sans-serif;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s;
    outline: none;
}

.form-input:focus {
    border-color: var(--purple-glow);
    background: rgba(107,33,232,0.1);
    box-shadow: 0 0 15px rgba(107,33,232,0.2);
}

.form-input::placeholder { color: var(--text-muted); }

.btn-login {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, var(--purple) 0%, #8b30ff 100%);
    border: none;
    border-radius: 2px;
    color: white;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 3px;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
    margin-top: 10px;
}

.btn-login::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s;
}

.btn-login:hover::before { left: 100%; }
.btn-login:hover {
    box-shadow: 0 0 30px rgba(107,33,232,0.6);
    transform: translateY(-1px);
}

.error-msg {
    background: rgba(204,34,51,0.1);
    border: 1px solid rgba(204,34,51,0.3);
    border-radius: 2px;
    padding: 12px 16px;
    color: #ff6677;
    font-size: 0.9rem;
    margin-bottom: 20px;
}

.register-link {
    text-align: center;
    margin-top: 25px;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.register-link a {
    color: var(--gold);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
}

.register-link a:hover { color: var(--gold-light); }

.divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 20px 0;
}

.divider-line { flex: 1; height: 1px; background: var(--border); }
.divider-text { color: var(--text-muted); font-size: 0.75rem; letter-spacing: 2px; }

@media (max-width: 640px) {
    .login-art { display: none; }
    .login-form-side { padding: 40px 24px; }
}
</style>
</head>
<body>
<div class="bg-curse"></div>
<div class="particles" id="particles"></div>

<div class="login-wrapper">
    <div class="login-art">
        <div class="cursed-lines"></div>
        <div class="jjk-symbol">呪</div>
        <div class="art-title">JJK Universe</div>
        <div class="art-subtitle">Cursed Energy Awaits</div>
    </div>
    
    <div class="login-form-side">
        <h1 class="form-title">Welcome Back</h1>
        <p class="form-subtitle">Masuk ke dunia kutukan & tukang sihir</p>
        
        <?php if ($error): ?>
        <div class="error-msg">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" placeholder="sorcerer@jjk.com" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn-login">MASUK ↗</button>
        </form>
        
        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar Sekarang</a>
        </div>
        
        <div class="divider">
            <div class="divider-line"></div>
            <div class="divider-text">DEMO</div>
            <div class="divider-line"></div>
        </div>
        
        <div style="font-size:0.8rem; color: var(--text-muted); text-align:center; line-height:1.8;">
            <strong style="color:var(--gold)">Admin:</strong> admin@jjk.com<br>
            <strong style="color:var(--purple-glow)">User:</strong> yuji@jjk.com<br>
            <span style="color:var(--text-muted)">Password: password</span>
        </div>
    </div>
</div>

<script>
// Generate floating curse particles
const container = document.getElementById('particles');
for (let i = 0; i < 30; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random() * 100 + 'vw';
    p.style.width = p.style.height = (Math.random() * 3 + 1) + 'px';
    p.style.animationDuration = (Math.random() * 15 + 10) + 's';
    p.style.animationDelay = (Math.random() * 15) + 's';
    if (Math.random() > 0.5) p.style.background = '#f0c040';
    container.appendChild(p);
}
</script>
</body>
</html>