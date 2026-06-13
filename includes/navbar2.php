<?php
$currentPage ??= '';
$basePath    ??= '';
?>
<style>
/* ── Base font-size — sama persis dengan style.css (characters) ── */
@media screen and (max-width: 1919px) { html { font-size: 14px; } }
@media screen and (max-width: 1439px) { html { font-size: 12px; } }
@media screen and (max-width: 1279px) { html { font-size: 10px; } }

/* ── Background layer ── */
.page-bg-wrap{position:fixed;inset:0;z-index:-1;overflow:hidden;}
.page-bg-wrap img{width:100%;height:100%;object-fit:cover;}
.page-bg-wrap::after{content:'';position:absolute;inset:0;background:rgba(3,2,10,.75);}

/* ── Navbar — persis navbar.css (characters) ── */
.navbar {
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    height: 5.71rem;
    display: flex;
    align-items: center;
    z-index: 200;
    background: transparent;
}
.nav-container {
    width: 90%;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    font-family: 'Inter', sans-serif;
}
.nav-links-main {
    display: flex;
    gap: 2.85rem;
}
.nav-item {
    font-size: 1.14rem;
    font-weight: 600;
    color: #ffffff;
    text-decoration: none;
    transition: color 0.3s ease;
}
.nav-item:link, .nav-item:visited { color: #ffffff; }
.nav-item:hover, .nav-item.active, .nav-item.active:visited { color: #0613a6; }
.nav-user-profile {
    display: flex;
    align-items: center;
    gap: 1.28rem;
    position: absolute;
    right: 0;
}
.nav-username {
    font-size: 1.07rem;
    color: #8a99ad;
    font-family: 'Inter', sans-serif;
}
.nav-username strong { color: #ffffff; font-weight: 600; }
.btn-logout {
    font-size: 1rem;
    font-weight: 600;
    color: #dc2626;
    border: 1px solid #dc2626;
    padding: 0.5rem 1.14rem;
    border-radius: 0.35rem;
    text-decoration: none;
    transition: background-color 0.2s, color 0.1s;
    font-family: 'Inter', sans-serif;
}
.btn-logout:hover { color: #fff; background-color: #b91c1c; }
@media(max-width:900px){ .nav-links-main { display: none; } }

/* ── Search bar — persis characters.css ── */
.search-filter-section {
    position: relative;
    z-index: 10;
    width: 90%;
    margin: 6rem auto 1rem auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}
.search-form {
    display: flex;
    gap: 0.5rem;
    width: 100%;
    max-width: 600px;
    background: rgba(0,0,0,0.5);
    padding: 0.5rem;
    border-radius: 0.5rem;
    border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(5px);
}
.search-input {
    flex: 1;
    padding: 0.8rem 1rem;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 0.25rem;
    color: #fff;
    font-size: 1rem;
    outline: none;
    font-family: 'Inter', sans-serif;
}
.search-input:focus { border-color: #4f46e5; }
.btn-search, .btn-reset {
    padding: 0.8rem 1.5rem;
    font-weight: bold;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', sans-serif;
}
.btn-search { background-color: #0613a6; color: white; }
.btn-reset  { background-color: #374151; color: #d1d5db; }

/* Filter pills (untuk world & jujutsu type filter) */
.type-filters {
    display: flex; gap: 0.5rem; flex-wrap: wrap;
    justify-content: center; margin-top: 0.3rem;
}
.type-btn {
    font-size: 0.8rem; font-weight: 600;
    padding: 0.3rem 0.9rem; border-radius: 2rem;
    border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.6); text-decoration: none;
    background: transparent;
    transition: all 0.2s; cursor: pointer;
    font-family: 'Inter', sans-serif;
}
.type-btn:hover, .type-btn.active {
    background: #0613a6; border-color: #0613a6; color: #fff;
}
</style>

<!-- Background layer -->
<div class="page-bg-wrap" aria-hidden="true">
  <img src="<?= $basePath ?>asset/home_bg.jpg" alt="">
</div>

<!-- Navbar -->
<header class="navbar">
  <div class="nav-container">
    <nav class="nav-links-main" aria-label="Primary">
      <a href="<?= $basePath ?>index.php" class="nav-item<?= $currentPage==='home'?' active':'' ?>">Home</a>
      <a href="<?= $basePath ?>pages/characters.php" class="nav-item<?= $currentPage==='characters'?' active':'' ?>">Characters</a>
      <a href="<?= $basePath ?>pages/jujutsu.php" class="nav-item<?= $currentPage==='jujutsu'?' active':'' ?>">Jujutsu</a>
      <a href="<?= $basePath ?>pages/world.php" class="nav-item<?= $currentPage==='world'?' active':'' ?>">World</a>
      <a href="<?= $basePath ?>game/index.php" class="nav-item<?= $currentPage==='game'?' active':'' ?>">Mini Game</a>
      <?php if (isLoggedIn()): ?>
      <a href="<?= $basePath ?>pages/leaderboard.php" class="nav-item<?= $currentPage==='leaderboard'?' active':'' ?>">Leaderboard</a>
      <?php if (isAdmin()): ?>
      <a href="<?= $basePath ?>admin/dashboard.php" class="nav-item<?= $currentPage==='admin'?' active':'' ?>" style="color:#ffb800">Admin</a>
      <?php endif; endif; ?>
    </nav>
    <div class="nav-user-profile">
      <span class="nav-username">Hi, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></strong></span>
      <?php if (isLoggedIn()): ?>
        <a href="<?= $basePath ?>pages/logout.php" class="btn-logout">Logout</a>
      <?php else: ?>
        <a href="<?= $basePath ?>pages/login.php" class="btn-logout" style="background-color:#0613a6;color:#fff;border-color:#0613a6;">Login</a>
      <?php endif; ?>
    </div>
  </div>
</header>
