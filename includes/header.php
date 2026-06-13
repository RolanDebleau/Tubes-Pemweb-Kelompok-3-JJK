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
                    <a href="<?= SITE_URL ?>" class="nav-item<?= ($currentPage ?? 'home') === 'home' ? ' active' : '' ?>">Home</a>
                    <a href="<?= SITE_URL ?>/pages/characters.php" class="nav-item<?= ($currentPage ?? '') === 'characters' ? ' active' : '' ?>">Characters</a>
                    <a href="<?= SITE_URL ?>/pages/jujutsu.php" class="nav-item<?= ($currentPage ?? '') === 'jujutsu' ? ' active' : '' ?>">Jujutsu</a>
                    <a href="<?= SITE_URL ?>/pages/world.php" class="nav-item<?= ($currentPage ?? '') === 'world' ? ' active' : '' ?>">World</a>
                    <a href="<?= SITE_URL ?>/game/" class="nav-item<?= ($currentPage ?? '') === 'game' ? ' active' : '' ?>">Mini Game</a>
                    <?php if (isLoggedIn()): ?>
                    <a href="<?= SITE_URL ?>/pages/leaderboard.php" class="nav-item<?= ($currentPage ?? '') === 'leaderboard' ? ' active' : '' ?>">Leaderboard</a>
                    <?php if (isAdmin()): ?>
                    <a href="<?= SITE_URL ?>/admin/dashboard.php" class="nav-item<?= ($currentPage ?? '') === 'admin' ? ' active' : '' ?>" style="color:#ffb800">Admin</a>
                    <?php endif; endif; ?>
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