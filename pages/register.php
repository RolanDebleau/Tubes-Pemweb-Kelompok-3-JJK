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
        $error = 'Invalid request (CSRF token mismatch).';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
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
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Create Account</title>
    <link class="styles" rel="stylesheet" href="<?=SITE_URL?>/styles/globals.css" />
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
      .alert-success {
        background-color: #2ec4b6;
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
        <img class="visual-image" src="../../asset/trailer.gif" alt="Promotional Trailer" />
      </section>

      <a class="back-link" href="welcome.php" aria-label="Back to menu">Back Menu</a>
      
      <section class="form-section" aria-labelledby="create-account-title">
        <div class="glass-panel">
            <h1 class="form-title" id="create-account-title">Create Account!</h1>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" novalidate class="login-form">
                <input type="hidden" name="csrf_token" value="<?php echo csrfToken(); ?>" />

                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" autocomplete="username" placeholder="Enter username" value="<?php echo htmlspecialchars($username ?? ''); ?>" />
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" inputmode="email" placeholder="Enter email" value="<?php echo htmlspecialchars($email ?? ''); ?>" />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Enter password" />
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" placeholder="Confirm your password" />
                </div>
              
                <button class="btn-submit" type="submit" aria-label="Sign Up">
                    <span>Sign Up</span>
                </button>

                <p class="login-prompt">
                    Udah punya akun? <a href="login.php">Login di sini</a>
                </p>
            </form>
        </div>
      </section>
    </main>
  </body>
</html>