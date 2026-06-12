<?php
require_once '../includes/config.php';
requireLogin();

$charId = isset($_GET['id']) ? intval($_GET['id']) : 1;

$character = getCharacterById($charId);
if (!$character) {
    die("Error: Karakter tidak ditemukan.");
}

$comments = getCommentsByCharacter($charId);
$currentUserId = $_SESSION['user_id'];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    if (!isLoggedIn()) {
        $error = 'Kamu harus login untuk berkomentar.';
    } elseif (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        $content = trim($_POST['content'] ?? '');
        $rating = (int)($_POST['rating'] ?? 5);
        if (empty($content)) {
            $error = 'Komentar tidak boleh kosong.';
        } elseif ($rating < 1 || $rating > 5) {
            $error = 'Rating tidak valid.';
        } else {
            addComment($_SESSION['user_id'], $charId, $content, $rating);
            $success = 'Komentar berhasil ditambahkan!';
            $comments = getCommentsByCharacter($charId);
        }
    }
}

// Helper untuk Render Bintang Dinamis
function renderStars($rating) {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $active = ($i <= $rating) ? 'active' : '';
        $html .= "<span class='star-icon {$active}'>★</span>";
    }
    return $html;
}

// Persiapan Variabel View
$imagePath = $character['image_url'] ?? 'img/placeholder.png';
[$firstName, $lastName] = explode(' ', $character['name'] . ' ', 2); 

// Setup array untuk looping statistik Power Ratings
$powerStats = [
    'Attack Power'  => $character['attack_power'],
    'Defense Power' => $character['defense_power'],
    'Speed Power'   => $character['speed_power']
];

// --- MULAI RENDER HALAMAN --- //
$pageTitle = "Characters - " . htmlspecialchars($character['name']);
$extra_css = ['character_detail'];
include '../includes/header.php'; 
?>

    <div class="page-container">
        <div class="ellipse" aria-hidden="true"></div>
        
        <div class="main-workspace">
            
            <section class="left-panel">
                <div class="scrollable-content-wrapper">
                    
                    <h1 class="character-title">
                        <span class="first-name"><?= htmlspecialchars(trim($firstName)) ?></span><br>
                        <span class="last-name"><?= htmlspecialchars(trim($lastName)) ?></span>
                    </h1>
                    <p class="character-epithet">Jujutsu Sorcerer</p>

                    <div class="metadata-card">
                        <div class="meta-row">
                            <span class="meta-label">Grade</span>
                            <span class="meta-value badge-grade"><?= htmlspecialchars($character['grade']) ?></span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Affiliation</span>
                            <span class="meta-value"><?= htmlspecialchars($character['affiliation']) ?></span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Cursed Technique</span>
                            <span class="meta-value"><?= htmlspecialchars($character['cursed_technique']) ?></span>
                        </div>
                    </div>

                    <div class="story-container">
                        <h2 class="panel-subtitle">Description & Lore</h2>
                        <p class="description-paragraph">
                            <?= nl2br(htmlspecialchars($character['description'] . " " . $character['lore'])) ?>
                        </p>
                    </div>

                    <div class="stats-container">
                        <h2 class="panel-subtitle">Power Ratings</h2>
                        <?php foreach ($powerStats as $title => $value): ?>
                            <div class="stat-item">
                                <div class="stat-header">
                                    <span class="stat-title"><?= $title ?></span>
                                    <span class="stat-val"><?= $value ?>/100</span>
                                </div>
                                <div class="bar-bg">
                                    <div class="bar-fill" style="width: <?= $value ?>%;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <form class="comment-component" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" id="comment-form">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($currentUserId) ?>">
                        <input type="hidden" name="character_id" value="<?= htmlspecialchars($charId) ?>">
                        <input type="hidden" name="rating" id="rating-value" value="5">
                        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                        <input type="hidden" name="add_comment" value="1">

                        <div class="comment-header-row">
                            <label class="comment-heading">Berikan Penilaian & Komentar</label>
                            <div class="rating-select" id="star-rating-container" aria-label="Rate 1 to 5 stars">
                                <span class="star-icon active" data-value="1">★</span>
                                <span class="star-icon active" data-value="2">★</span>
                                <span class="star-icon active" data-value="3">★</span>
                                <span class="star-icon active" data-value="4">★</span>
                                <span class="star-icon active" data-value="5">★</span>
                            </div>
                        </div>
                        
                        <div class="comment-field-group">
                            <input type="text" name="content" id="char-comment" placeholder="Tulis ulasanmu mengenai karakter ini..." required>
                            <button type="submit" class="btn-submit">Kirim</button>
                        </div>
                    </form>

                </div>
            </section>

            <section class="right-panel">
                <div class="illustration-frame">
                    <div class="portrait-card">
                        <div class="card-inner-bg">
                            <img class="character-img img-dark" src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($character['name']) ?>" onerror="this.src='https://via.placeholder.com/625x1003?text=Image+Not+Found'" />
                        </div>
                        <div class="card-inner-fg">
                            <img class="character-img" src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($character['name']) ?>" onerror="this.src='https://via.placeholder.com/625x1003?text=Image+Not+Found'" />
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <footer class="footer-comments-container">
            <h3 class="comments-section-title">Ulasan Pengguna</h3>
            <div class="horizontal-comments-scroll">
                <?php if (empty($comments)): ?>
                    <p class="empty-comments">Belum ada ulasan untuk karakter ini. Jadilah yang pertama!</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <article class="user-comment-card">
                            <div class="card-user-info">
                                <span class="user-avatar"><?= htmlspecialchars($comment['username']) ?></span>
                                <div class="user-stars">
                                    <?= renderStars($comment['rating']) ?>
                                </div>
                            </div>
                            <p class="comment-text-content"><?= htmlspecialchars($comment['content']) ?></p>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </footer>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stars = document.querySelectorAll('#star-rating-container .star-icon');
            const ratingInput = document.getElementById('rating-value');

            if(stars && stars.length > 0) {
                stars.forEach(star => {
                    star.addEventListener('mouseover', function() {
                        const hoverValue = this.getAttribute('data-value');
                        highlightStars(hoverValue);
                    });

                    star.addEventListener('mouseout', () => {
                        highlightStars(ratingInput.value);
                    });

                    star.addEventListener('click', function() {
                        const clickedValue = this.getAttribute('data-value');
                        ratingInput.value = clickedValue; 
                        highlightStars(clickedValue);
                    });
                });
            }

            function highlightStars(value) {
                stars.forEach(star => {
                    if (parseInt(star.getAttribute('data-value')) <= parseInt(value)) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            }
        });
    </script>

<?php include '../includes/footer.php'; ?>