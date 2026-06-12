<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/style.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/styles/navbar.css">
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css_file): ?>
            <link rel="stylesheet" href="<?= SITE_URL ?>/styles/<?= $css_file ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <div class="app-global-container">
        
        <header class="navbar">
            <div class="nav-container">
                <nav class="nav-links" aria-label="Primary">
                    <a href="<?= SITE_URL ?>" class="nav-item">Home</a>
                    <a href="<?= SITE_URL ?>/pages/characters.php" class="nav-item">Characters</a>
                    <a href="<?= SITE_URL ?>/game/" class="nav-item">Mini Game</a>
                    <a href="<?= SITE_URL ?>/pages/leaderboard.php" class="nav-item">Leaderboard</a>
                </nav>
                <div class="nav-user-profile">
                    <span class="nav-username">
                        Hi, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></strong>
                    </span>
                    <?php if (isLoggedIn()): ?>
                        <a href="<?= SITE_URL ?>/pages/logout.php" class="btn-logout">Logout</a>
                    <?php else: ?>
                        <a href="<?= SITE_URL ?>/pages/login.php" class="btn-logout" style="background-color: #0613a6;">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>