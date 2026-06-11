<?php
$currentPage ??= '';
$basePath    ??= '';

function navActive(string $page, string $current): string {
    return $page === $current ? ' class="active"' : '';
}
?>
<style>
.navbar {
    position: fixed; top: 0; left: 0; right: 0;
    height: var(--nav-h);
    z-index: 100;
    display: flex; align-items: center;
    padding: 0 28px;
    background: rgba(3,2,10,.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
    flex-wrap: nowrap; overflow: hidden;
}
.nav-logo {
    display: flex; align-items: center; gap: 12px;
    text-decoration: none; flex: 1; min-width: 0;
}
.logo-symbol {
    font-size: 1.8rem;
    background: linear-gradient(135deg, var(--purple-glow), var(--gold));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    flex-shrink: 0;
}
.logo-text {
    font-family: 'Cinzel Decorative', serif;
    font-size: 1rem; color: var(--text); white-space: nowrap;
}
.logo-sub {
    font-family: 'Orbitron', sans-serif;
    font-size: .5rem; color: var(--text-muted);
    letter-spacing: 3px; display: block;
}
.nav-links {
    display: flex; align-items: center;
    gap: 4px; list-style: none; flex-shrink: 0;
}
.nav-links a {
    font-family: 'Orbitron', sans-serif;
    font-size: .6rem; letter-spacing: 1.5px;
    color: var(--text-muted); text-decoration: none;
    padding: 6px 10px; border-radius: 2px;
    transition: all .3s; text-transform: uppercase;
    white-space: nowrap;
}
.nav-links a:hover,
.nav-links a.active {
    color: var(--text);
    background: rgba(107,33,232,.15);
}
.nav-actions {
    display: flex; align-items: center;
    gap: 12px; margin-left: 20px; flex-shrink: 0;
}
.btn-nav {
    font-family: 'Orbitron', sans-serif;
    font-size: .6rem; letter-spacing: 2px;
    padding: 8px 20px; border-radius: 2px;
    cursor: pointer; transition: all .3s;
    text-decoration: none; white-space: nowrap;
}
.btn-nav-outline {
    border: 1px solid var(--border);
    color: var(--text-muted); background: transparent;
}
.btn-nav-outline:hover {
    border-color: var(--purple-glow);
    color: var(--purple-glow);
}
.btn-nav-primary {
    background: var(--purple);
    border: 1px solid var(--purple); color: white;
}
.btn-nav-primary:hover {
    background: var(--purple-glow);
    border-color: var(--purple-glow);
}
/* BACKSOUND BTN */
.sound-btn {
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(107,33,232,.15);
    border: 1px solid var(--border);
    color: var(--text-muted);
    cursor: pointer; font-size: 1rem;
    display: flex; align-items: center; justify-content: center;
    transition: all .3s; flex-shrink: 0;
    position: relative; overflow: hidden;
}
.sound-btn:hover { border-color: var(--gold); color: var(--gold); background: rgba(240,192,64,.1); }
.sound-btn.playing { border-color: var(--purple-glow); color: var(--purple-glow); animation: soundPulse 2s infinite; }
@keyframes soundPulse { 0%,100%{box-shadow:0 0 0 0 rgba(157,77,255,.4);} 50%{box-shadow:0 0 0 6px rgba(157,77,255,.0);} }
.user-badge {
    display: flex; align-items: center; gap: 8px;
    padding: 6px 14px;
    border: 1px solid var(--border-gold); border-radius: 2px;
    background: rgba(240,192,64,.05);
}
.user-badge-name {
    font-family: 'Orbitron', sans-serif;
    font-size: .6rem; color: var(--gold); letter-spacing: 1px;
}
@media (max-width: 900px) {
    .navbar { padding: 0 20px; }
    .nav-links { display: none; }
}
</style>
<script>
(function() {
    const KEY = 'jjkSoundOn';
    let soundOn = localStorage.getItem(KEY) === 'true';
    
    function initSound() {
        const audio = document.getElementById('jjkBgm');
        const btn   = document.getElementById('soundBtn');
        if (!audio || !btn) return;
        
        audio.volume = 0.25;
        
        function updateUI() {
            btn.textContent = soundOn ? '🔊' : '🔇';
            btn.classList.toggle('playing', soundOn);
        }
        updateUI();
        
        if (soundOn) {
            audio.play().catch(() => {
                // Autoplay blocked — wait for interaction
                document.addEventListener('click', function playOnce() {
                    if (soundOn) audio.play();
                    document.removeEventListener('click', playOnce);
                }, { once: true });
            });
        }
        
        window.toggleSound = function() {
            soundOn = !soundOn;
            localStorage.setItem(KEY, soundOn);
            if (soundOn) {
                audio.play();
            } else {
                audio.pause();
            }
            updateUI();
        };
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSound);
    } else {
        initSound();
    }
})();
</script>

<nav class="navbar">
    <a href="<?= $basePath ?>index.php" class="nav-logo">
        <span class="logo-symbol">呪</span>
        <span>
            <span class="logo-text">JJK Universe</span>
            <span class="logo-sub">Cursed Energy Portal</span>
        </span>
    </a>

    <ul class="nav-links">
        <li><a href="<?= $basePath ?>index.php"<?= navActive('home', $currentPage) ?>>Home</a></li>
        <li><a href="<?= $basePath ?>pages/characters.php"<?= navActive('characters', $currentPage) ?>>Characters</a></li>
        <li><a href="<?= $basePath ?>pages/world.php"<?= navActive('world', $currentPage) ?>>World</a></li>
        <li><a href="<?= $basePath ?>pages/jujutsu.php"<?= navActive('jujutsu', $currentPage) ?>>Jujutsu</a></li>
        <li><a href="<?= $basePath ?>pages/story.php"<?= navActive('story', $currentPage) ?>>Story Arc</a></li>
        <li><a href="<?= $basePath ?>game/index.php"<?= navActive('game', $currentPage) ?>>Mini Game</a></li>
        <?php if (isLoggedIn()): ?>
        <li><a href="<?= $basePath ?>pages/leaderboard.php"<?= navActive('leaderboard', $currentPage) ?>>Leaderboard</a></li>
        <?php if (isAdmin()): ?>
        <li><a href="<?= $basePath ?>admin/dashboard.php"<?= navActive('admin', $currentPage) ?> style="color:var(--gold)">Admin</a></li>
        <?php endif; ?>
        <?php endif; ?>
    </ul>

    <!-- BACKSOUND -->
    <audio id="jjkBgm" loop preload="auto">
        <!-- Uses a free loopable dark ambient track via CDN -->
        <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-7.mp3" type="audio/mpeg">
    </audio>

    <div class="nav-actions">
        <button class="sound-btn" id="soundBtn" onclick="toggleSound()" title="Toggle Backsound">🔇</button>
        <?php if (isLoggedIn()): ?>
        <div class="user-badge">
            <span class="user-badge-name">⚡ <?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
        </div>
        <a href="<?= $basePath ?>pages/logout.php" class="btn-nav btn-nav-outline">Logout</a>
        <?php else: ?>
        <a href="<?= $basePath ?>pages/login.php" class="btn-nav btn-nav-outline">Login</a>
        <a href="<?= $basePath ?>pages/register.php" class="btn-nav btn-nav-primary">Register</a>
        <?php endif; ?>
    </div>
</nav>