<?php
require_once '../includes/config.php';

$id = (int)($_GET['id'] ?? 0);
$char = getCharacterById($id);
if (!$char) { header('Location: characters.php'); exit; }

$comments = getCommentsByCharacter($id);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    if (!isLoggedIn()) {
        $error = 'Kamu harus login untuk berkomentar.';
    } elseif (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        $content = trim($_POST['content'] ?? '');
        $rating  = (int)($_POST['rating'] ?? 5);
        if (empty($content)) {
            $error = 'Komentar tidak boleh kosong.';
        } elseif ($rating < 1 || $rating > 5) {
            $error = 'Rating tidak valid.';
        } else {
            addComment($_SESSION['user_id'], $id, $content, $rating);
            $success = 'Komentar berhasil ditambahkan!';
            $comments = getCommentsByCharacter($id);
        }
    }
}

function renderStars($rating) {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $active = ($i <= $rating) ? 'active' : '';
        $html .= "<span class='star-icon {$active}'>★</span>";
    }
    return $html;
}

// Resolve full body image
$fullBodyImg = null;
if (!empty($char['image_url'])) {
    $base = pathinfo($char['image_url'], PATHINFO_FILENAME);
    foreach(['webp','jpg','jpeg','png'] as $ext) {
        $p1 = __DIR__ . '/../asset/Full/' . $base . '_Full.' . $ext;
        $p2 = __DIR__ . '/../asset/Full/' . $base . '.' . $ext;
        if (file_exists($p1)) { $fullBodyImg = SITE_URL . '/asset/Full/' . $base . '_Full.' . $ext; break; }
        if (file_exists($p2)) { $fullBodyImg = SITE_URL . '/asset/Full/' . $base . '.' . $ext; break; }
    }
}
$imagePath = $fullBodyImg ?? (SITE_URL . '/asset/' . ($char['image_url'] ?? ''));

[$firstName, $lastName] = explode(' ', $char['name'] . ' ', 2);

$powerStats = [
    'Attack Power'  => $char['attack_power'],
    'Defense Power' => $char['defense_power'],
    'Speed Power'   => $char['speed_power'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($char['name']) ?> — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/style.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/styles/navbar.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/styles/character_detail.css">
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

    <div class="page-container">
      <div class="ellipse" aria-hidden="true"></div>

      <div class="main-workspace">

        <!-- LEFT PANEL -->
        <section class="left-panel">
          <div class="scrollable-content-wrapper">

            <div>
              <a href="characters.php" style="font-size:0.85rem;color:#8a99ad;text-decoration:none;display:inline-flex;align-items:center;gap:0.3rem;margin-bottom:0.5rem;">← Kembali ke Characters</a>
              <h1 class="character-title">
                <span class="first-name"><?= htmlspecialchars(trim($firstName)) ?></span><br>
                <span class="last-name"><?= htmlspecialchars(trim($lastName)) ?></span>
              </h1>
              <p class="character-epithet">Jujutsu Sorcerer</p>
            </div>

            <div class="metadata-card">
              <div class="meta-row">
                <span class="meta-label">Grade</span>
                <span class="meta-value badge-grade"><?= htmlspecialchars($char['grade']) ?></span>
              </div>
              <div class="meta-row">
                <span class="meta-label">Affiliation</span>
                <span class="meta-value"><?= htmlspecialchars($char['affiliation'] ?? '-') ?></span>
              </div>
              <div class="meta-row">
                <span class="meta-label">Cursed Technique</span>
                <span class="meta-value"><?= htmlspecialchars($char['cursed_technique']) ?></span>
              </div>
            </div>

            <?php if (!empty($char['description']) || !empty($char['lore'])): ?>
            <div class="story-container">
              <h2 class="panel-subtitle">Description & Lore</h2>
              <p class="description-paragraph">
                <?= nl2br(htmlspecialchars(($char['description'] ?? '') . ' ' . ($char['lore'] ?? ''))) ?>
              </p>
            </div>
            <?php endif; ?>

            <div class="stats-container">
              <h2 class="panel-subtitle">Power Ratings</h2>
              <?php foreach ($powerStats as $title => $value): ?>
              <div class="stat-item">
                <div class="stat-header">
                  <span class="stat-title"><?= $title ?></span>
                  <span class="stat-val"><?= $value ?>/100</span>
                </div>
                <div class="bar-bg">
                  <div class="bar-fill" style="width:<?= $value ?>%"></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>

            <?php if (!empty($char['domain_expansion'])): ?>
            <div class="metadata-card">
              <h2 class="panel-subtitle" style="margin-bottom:0.5rem;">Domain Expansion</h2>
              <p class="description-paragraph"><?= htmlspecialchars($char['domain_expansion']) ?></p>
            </div>
            <?php endif; ?>

            <!-- COMMENT FORM -->
            <?php if (isLoggedIn()): ?>
            <?php if ($error): ?><div style="background:rgba(255,77,77,.15);border:1px solid #ff4d4d;border-radius:.4rem;padding:.7rem 1rem;color:#ff6677;margin-bottom:.5rem;"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div style="background:rgba(46,196,182,.15);border:1px solid #2ec4b6;border-radius:.4rem;padding:.7rem 1rem;color:#2ec4b6;margin-bottom:.5rem;"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <form class="comment-component" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?id=<?= $id ?>" method="POST">
              <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
              <input type="hidden" name="rating" id="rating-value" value="5">
              <input type="hidden" name="add_comment" value="1">
              <div class="comment-header-row">
                <label class="comment-heading">Berikan Penilaian & Komentar</label>
                <div class="rating-select" id="star-rating-container">
                  <?php for ($s=1;$s<=5;$s++): ?>
                  <span class="star-icon active" data-value="<?= $s ?>">★</span>
                  <?php endfor; ?>
                </div>
              </div>
              <div class="comment-field-group">
                <input type="text" name="content" placeholder="Tulis ulasanmu mengenai karakter ini..." required>
                <button type="submit" class="btn-submit">Kirim</button>
              </div>
            </form>
            <?php else: ?>
            <div class="comment-component login-trigger">
              <p>Silakan <a href="login.php">login</a> untuk memberikan ulasan.</p>
            </div>
            <?php endif; ?>

          </div>
        </section>

        <!-- RIGHT PANEL -->
        <section class="right-panel">
          <div class="illustration-frame">
            <div class="portrait-card">
              <div class="card-inner-bg">
                <img class="character-img img-dark"
                     src="<?= htmlspecialchars($imagePath) ?>"
                     alt="<?= htmlspecialchars($char['name']) ?>"
                     onerror="this.src='https://placehold.co/625x1003?text=<?= urlencode($char['name']) ?>'" />
              </div>
              <div class="card-inner-fg">
                <img class="character-img"
                     src="<?= htmlspecialchars($imagePath) ?>"
                     alt="<?= htmlspecialchars($char['name']) ?>"
                     onerror="this.src='https://placehold.co/625x1003?text=<?= urlencode($char['name']) ?>'" />
              </div>
            </div>
          </div>
        </section>

      </div><!-- end main-workspace -->

      <!-- COMMENTS FOOTER -->
      <footer class="footer-comments-container">
        <h3 class="comments-section-title">Ulasan Pengguna</h3>
        <div class="horizontal-comments-scroll">
          <?php if (empty($comments)): ?>
            <p class="empty-comments">Belum ada ulasan. Jadilah yang pertama!</p>
          <?php else: ?>
            <?php foreach ($comments as $comment): ?>
            <article class="user-comment-card">
              <div class="card-user-info">
                <span class="user-avatar"><?= htmlspecialchars($comment['username']) ?></span>
                <div class="user-stars"><?= renderStars($comment['rating']) ?></div>
              </div>
              <p class="comment-text-content"><?= htmlspecialchars($comment['content']) ?></p>
            </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </footer>

    </div><!-- end page-container -->

  </div><!-- end app-global-container -->

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const stars = document.querySelectorAll('#star-rating-container .star-icon');
      const ratingInput = document.getElementById('rating-value');
      if (!stars.length) return;
      stars.forEach(star => {
        star.addEventListener('mouseover', function() { highlightStars(this.getAttribute('data-value')); });
        star.addEventListener('mouseout', () => { highlightStars(ratingInput.value); });
        star.addEventListener('click', function() {
          ratingInput.value = this.getAttribute('data-value');
          highlightStars(ratingInput.value);
        });
      });
      function highlightStars(value) {
        stars.forEach(s => {
          parseInt(s.getAttribute('data-value')) <= parseInt(value)
            ? s.classList.add('active') : s.classList.remove('active');
        });
      }
    });
  </script>
</body>
</html>
