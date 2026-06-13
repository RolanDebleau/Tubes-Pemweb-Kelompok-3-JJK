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
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Login Account</title>
    <link class="styles" rel="stylesheet" href="<?=SITE_URL?>/styles/styleguide.css" />
    <link class="styles" rel="stylesheet" href="<?=SITE_URL?>/styles/styleguide.css" />
    <link class="styles" rel="stylesheet" href="<?=SITE_URL?>/styles/login.css" />
    <style>
      .alert {
        padding: 0.75rem 1rem;
        border-radius: 0.4375rem;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
      }
      .alert-danger {
        background-color: #ff4d4d;
        color: #ffffff;
      }
    </style>
  </head>
  <body>
    <main class="login-container">
      <div class="glow-effect ellipse-1" aria-hidden="true"></div>
      <div class="glow-effect ellipse-2" aria-hidden="true"></div>
      <div class="glow-effect ellipse-3" aria-hidden="true"></div>

      <section class="visual-section" aria-label="Promotional illustration">
        <div class="visual-overlay" aria-hidden="true"></div>
        <img class="visual-image" src="<?=SITE_URL?>/asset/trailer.gif" alt="Promotional Trailer" />
      </section>

      <a class="back-link" href="welcome.php" aria-label="Back to menu">Back Menu</a>
      
      <section class="form-section" aria-labelledby="login-account-title">
        <div class="glass-panel">
            <h1 class="form-title" id="login-account-title">Welcome Back!</h1>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" novalidate class="login-form">
                <input type="hidden" name="csrf_token" value="<?php echo csrfToken(); ?>" />

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email ?? ''); ?>" />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" />
                </div>
              
                <button class="btn-submit" type="submit" aria-label="Log In">
                <span>Login</span>
                </button>

                <div class="test-note">
                    Admin: <code>admin@jjk.com</code> | User: <code>yuji@jjk.com</code> (pw: <code>password</code>)
                </div>

                <p class="login-prompt">
                    Gk punya akun?😹 <a href="register.php">Buat di sini</a>
                </p>
                </form>
        </div>
      </section>
    </main>
  </body>
</html>