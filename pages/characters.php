<?php
require_once '../includes/config.php';

$search = trim($_GET['search'] ?? '');
$grade_filter = $_GET['grade'] ?? '';

$db = getDB();
$sql = "SELECT * FROM characters WHERE 1=1";
$params = []; $types = '';

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR cursed_technique LIKE ? OR affiliation LIKE ?)";
    $s = "%$search%"; $params = [$s,$s,$s]; $types = 'sss';
}
if (!empty($grade_filter)) {
    $sql .= " AND grade = ?";
    $params[] = $grade_filter; $types .= 's';
}
$sql .= " ORDER BY attack_power DESC";

$stmt = $db->prepare($sql);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$characters = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Characters — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/style.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/styles/navbar.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/styles/characters.css">
</head>
<body>
  <div class="app-global-container">

    <!-- NAVBAR -->
    <header class="navbar">
      <div class="nav-container">
        <nav class="nav-links" aria-label="Primary">
          <a href="<?= SITE_URL ?>" class="nav-item">Home</a>
          <a href="<?= SITE_URL ?>/pages/characters.php" class="nav-item active">Characters</a>
          <a href="<?= SITE_URL ?>/pages/jujutsu.php" class="nav-item">Jujutsu</a>
          <a href="<?= SITE_URL ?>/pages/world.php" class="nav-item">World</a>
          <a href="<?= SITE_URL ?>/game/" class="nav-item">Mini Game</a>
          <a href="<?= SITE_URL ?>/pages/leaderboard.php" class="nav-item">Leaderboard</a>
          <?php if (isLoggedIn() && isAdmin()): ?>
          <a href="<?= SITE_URL ?>/admin/dashboard.php" class="nav-item" style="color:#ffb800">Admin</a>
          <?php endif; ?>
        </nav>
        <div class="nav-user-profile">
          <span class="nav-username">Hi, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></strong></span>
          <?php if (isLoggedIn()): ?>
            <a href="<?= SITE_URL ?>/pages/logout.php" class="btn-logout">Logout</a>
          <?php else: ?>
            <a href="<?= SITE_URL ?>/pages/login.php" class="btn-logout" style="background-color:#0613a6;color:#fff;border-color:#0613a6;">Login</a>
          <?php endif; ?>
        </div>
      </div>
    </header>

    <main class="characters" data-page="characters">

      <div class="bg-wrapper" aria-hidden="true">
        <img class="bg-image" src="<?= SITE_URL ?>/asset/home_bg.jpg" alt="Background" />
        <div class="overlay-light"></div>
        <div class="overlay-dark"></div>
      </div>

      <!-- SEARCH & FILTER -->
      <section class="search-filter-section">
        <form method="GET" class="search-form">
          <input type="text" name="search" class="search-input"
            placeholder="Cari nama, grade, atau teknik kutukan..."
            value="<?= htmlspecialchars($search) ?>">
          <button type="submit" class="btn-search">Cari</button>
          <?php if (!empty($search) || !empty($grade_filter)): ?>
            <a href="characters.php" class="btn-reset">Reset</a>
          <?php endif; ?>
        </form>
        <div class="grade-filters">
          <a href="characters.php" class="grade-btn <?= (empty($grade_filter)) ? 'active' : '' ?>">Semua</a>
          <?php foreach (['Special Grade','Semi-Grade 1','Grade 1','Grade 2','Grade 3','Grade 4','Unranked'] as $g): ?>
          <a href="?grade=<?= urlencode($g) ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>"
             class="grade-btn <?= $grade_filter === $g ? 'active' : '' ?>"><?= $g ?></a>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- CHARACTER GALLERY -->
      <section class="character-gallery" aria-label="Character gallery">
        <div class="gallery-wrapper">
          <?php if (!empty($characters)): ?>
            <?php foreach ($characters as $char):
              // Resolve image
              $fullImg = null;
              if (!empty($char['image_url'])) {
                $base = pathinfo($char['image_url'], PATHINFO_FILENAME);
                foreach (['webp','jpg','png'] as $ext) {
                  foreach ([$base . '_Full.' . $ext, $base . '_Full (2).' . $ext] as $cand) {
                    if (file_exists(__DIR__ . '/../asset/Full/' . $cand)) {
                      $fullImg = SITE_URL . '/asset/Full/' . rawurlencode($cand);
                      break 2;
                    }
                  }
                }
              }
              $displayImg = $fullImg ?? (!empty($char['image_url']) ? SITE_URL . '/asset/' . $char['image_url'] : null);
            ?>
            <a href="<?= SITE_URL ?>/pages/character_detail.php?id=<?= $char['id'] ?>"
               class="card" data-character="true" title="<?= htmlspecialchars($char['name']) ?>">
              <div class="card-inner">
                <?php if ($displayImg): ?>
                <img class="character-img"
                     src="<?= htmlspecialchars($displayImg) ?>"
                     alt="<?= htmlspecialchars($char['name']) ?>"
                     onerror="this.src='https://placehold.co/300x500?text=<?= urlencode($char['name']) ?>'" />
                <?php else: ?>
                <div style="display:flex;align-items:center;justify-content:center;height:100%;font-size:4rem;">👊</div>
                <?php endif; ?>
              </div>
              <div class="card-label">
                <span class="card-name"><?= htmlspecialchars($char['name']) ?></span>
                <span class="card-grade"><?= htmlspecialchars($char['grade']) ?></span>
              </div>
            </a>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-state">
              <h2>Tidak ada karakter yang ditemukan.</h2>
              <p>Coba gunakan kata kunci lain atau reset filter.</p>
            </div>
          <?php endif; ?>
        </div>
      </section>

    </main>

  </div><!-- end app-global-container -->

  <style>
    /* Grade filter pills */
    .grade-filters {
      display: flex; gap: 0.5rem; flex-wrap: wrap;
      justify-content: center; margin-top: 0.6rem;
    }
    .grade-btn {
      font-size: 0.8rem; font-weight: 600;
      padding: 0.3rem 0.9rem; border-radius: 2rem;
      border: 1px solid rgba(255,255,255,0.2);
      color: rgba(255,255,255,0.6); text-decoration: none;
      transition: all 0.2s;
    }
    .grade-btn:hover, .grade-btn.active {
      background: #0613a6; border-color: #0613a6; color: #fff;
    }
    /* Card label overlay */
    .card { position: relative; }
    .card-label {
      position: absolute; bottom: 0; left: 0; right: 0;
      padding: 1.5rem 0.8rem 0.6rem;
      background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);
      clip-path: polygon(15% 0%, 100% 0%, 85% 100%, 0% 100%);
      opacity: 0; transition: opacity 0.3s;
      display: flex; flex-direction: column; align-items: center;
    }
    .card:hover .card-label { opacity: 1; }
    .card-name {
      font-size: 0.85rem; font-weight: 700; color: #fff;
      text-align: center; line-height: 1.2;
    }
    .card-grade {
      font-size: 0.7rem; color: #ffb800; margin-top: 2px;
    }
    /* Fix search section spacing */
    .search-filter-section {
      flex-direction: column; align-items: center; gap: 0.5rem;
    }
  </style>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const gallery = document.querySelector(".character-gallery");
      const wrapper = document.querySelector(".gallery-wrapper");
      const cards = Array.from(document.querySelectorAll(".card[data-character='true']"));
      if (!gallery || !wrapper || cards.length < 6) return;

      // Clone cards for infinite loop
      const cloneCount = Math.min(cards.length, 5);
      for (let i = 0; i < cloneCount; i++) {
        const clone = cards[i].cloneNode(true);
        clone.removeAttribute("data-character");
        wrapper.appendChild(clone);
      }
      for (let i = cards.length - 1; i >= cards.length - cloneCount; i--) {
        const clone = cards[i].cloneNode(true);
        clone.removeAttribute("data-character");
        wrapper.insertBefore(clone, wrapper.firstChild);
      }

      const getCardSpace = () => {
        const cardWidth = cards[0].getBoundingClientRect().width;
        const gap = parseFloat(window.getComputedStyle(wrapper).gap) || 0;
        return cardWidth + gap;
      };

      const updateInitialScroll = () => {
        gallery.scrollLeft = getCardSpace() * cloneCount;
      };
      setTimeout(updateInitialScroll, 50);

      // Infinite scroll loop
      gallery.addEventListener("scroll", () => {
        const singleCardSpace = getCardSpace();
        const startThreshold = singleCardSpace * cloneCount;
        if (gallery.scrollLeft <= 5) {
          gallery.scrollLeft = wrapper.scrollWidth - gallery.clientWidth - startThreshold - 10;
        } else if (gallery.scrollLeft >= wrapper.scrollWidth - gallery.clientWidth - 5) {
          gallery.scrollLeft = startThreshold + 10;
        }
      });

      // ── Auto-scroll when mouse hovers near left/right edges ──
      let autoScrollRAF = null;
      const EDGE_ZONE = 180; // px dari tepi
      const MAX_SPEED = 12;  // px per frame

      gallery.addEventListener("mousemove", (e) => {
        const rect = gallery.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const width = rect.width;
        let speed = 0;

        if (x < EDGE_ZONE) {
          // Kiri: semakin dekat tepi semakin cepat
          speed = -MAX_SPEED * (1 - x / EDGE_ZONE);
        } else if (x > width - EDGE_ZONE) {
          // Kanan
          speed = MAX_SPEED * (1 - (width - x) / EDGE_ZONE);
        }

        cancelAnimationFrame(autoScrollRAF);
        if (speed !== 0) {
          const scroll = () => {
            gallery.scrollLeft += speed;
            autoScrollRAF = requestAnimationFrame(scroll);
          };
          autoScrollRAF = requestAnimationFrame(scroll);
        }
      });

      gallery.addEventListener("mouseleave", () => {
        cancelAnimationFrame(autoScrollRAF);
        autoScrollRAF = null;
      });

      // ── Mouse-drag scroll (klik + geser) ──
      let isDragging = false;
      let dragStartX = 0;
      let scrollStart = 0;

      gallery.addEventListener("mousedown", (e) => {
        isDragging = true;
        dragStartX = e.pageX;
        scrollStart = gallery.scrollLeft;
        gallery.style.cursor = "grabbing";
        cancelAnimationFrame(autoScrollRAF);
      });

      window.addEventListener("mousemove", (e) => {
        if (!isDragging) return;
        const dx = e.pageX - dragStartX;
        gallery.scrollLeft = scrollStart - dx;
      });

      window.addEventListener("mouseup", () => {
        if (isDragging) {
          isDragging = false;
          gallery.style.cursor = "";
        }
      });
    });
  </script>
</body>
</html>
