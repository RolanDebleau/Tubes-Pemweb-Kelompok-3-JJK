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

$charEmojis = [
    '👊','🌑','🔨','♾️','💀','👁️','👋','⚡',
    '🔮','🗡️','🌊','🔥','🌿','🦴','🐼','👁',
    '🩸','💠','🌸','⚡','🦋','🐦','🎭','🌙',
    '💫','🔴','🟣','⚫','🌀','🏮','🎯','💥',
];
$charColors = [
    'Special Grade' => ['bg' => 'linear-gradient(135deg,rgba(240,192,64,.18),rgba(240,192,64,.04))', 'class'=>'grade-special', 'accent'=>'#f0c040'],
    'Semi-Grade 1'  => ['bg' => 'linear-gradient(135deg,rgba(157,77,255,.16),rgba(157,77,255,.03))', 'class'=>'grade-semi',    'accent'=>'#cc99ff'],
    'Grade 1'       => ['bg' => 'linear-gradient(135deg,rgba(107,33,232,.15),rgba(107,33,232,.03))', 'class'=>'grade-1',       'accent'=>'#9d4dff'],
    'Grade 2'       => ['bg' => 'linear-gradient(135deg,rgba(0,150,255,.12),rgba(0,150,255,.02))',   'class'=>'grade-2',       'accent'=>'#4dc8ff'],
    'Grade 3'       => ['bg' => 'linear-gradient(135deg,rgba(100,100,120,.14),rgba(100,100,120,.03))','class'=>'grade-3',      'accent'=>'#aaa8c0'],
    'Grade 4'       => ['bg' => 'linear-gradient(135deg,rgba(80,80,90,.12),rgba(80,80,90,.02))',     'class'=>'grade-4',       'accent'=>'#888888'],
    'Unranked'      => ['bg' => 'linear-gradient(135deg,rgba(60,60,70,.10),rgba(60,60,70,.02))',     'class'=>'grade-unranked', 'accent'=>'#666666'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Characters — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --black:#03020a; --purple:#6b21e8; --purple-glow:#9d4dff;
    --gold:#f0c040; --gold-light:#ffe888; --red:#cc2233;
    --text:#ede8f5; --text-muted:#7a7490;
    --border:rgba(107,33,232,.2); --border-gold:rgba(240,192,64,.2);
    --card-bg:rgba(10,8,20,.85); --nav-h:80px;
}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;} ::-webkit-scrollbar-track{background:#08060f;} ::-webkit-scrollbar-thumb{background:#3a0d7a;border-radius:3px;}

.page-hero{padding-top:calc(var(--nav-h) + 60px);padding-bottom:60px;padding-left:40px;padding-right:40px;text-align:center;position:relative;overflow:hidden;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 0%,rgba(107,33,232,.12) 0%,transparent 60%);pointer-events:none;}
.page-tag{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:4px;color:var(--purple-glow);text-transform:uppercase;margin-bottom:16px;display:block;}
.page-title{font-family:'Cinzel Decorative',serif;font-size:clamp(2rem,4vw,3rem);background:linear-gradient(135deg,var(--text),var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:12px;}
.page-sub{color:var(--text-muted);font-size:1rem;max-width:500px;margin:0 auto;}

.filter-bar{max-width:1300px;margin:0 auto 40px;padding:0 40px;display:flex;gap:12px;flex-wrap:wrap;align-items:center;}
.search-input{flex:1;min-width:200px;background:rgba(107,33,232,.05);border:1px solid var(--border);border-radius:2px;padding:12px 16px;color:var(--text);font-family:'Rajdhani',sans-serif;font-size:1rem;outline:none;transition:all .3s;}
.search-input:focus{border-color:var(--purple-glow);background:rgba(107,33,232,.1);}
.search-input::placeholder{color:var(--text-muted);}
.filter-btn{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;padding:10px 18px;border-radius:2px;cursor:pointer;transition:all .3s;text-decoration:none;background:transparent;border:1px solid var(--border);color:var(--text-muted);}
.filter-btn:hover,.filter-btn.active{border-color:var(--purple-glow);color:var(--purple-glow);background:rgba(107,33,232,.1);}
.filter-btn.grade-special-btn.active{border-color:var(--gold);color:var(--gold);}

.main-content{max-width:1300px;margin:0 auto;padding:0 40px 80px;}
.chars-count{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);margin-bottom:24px;}

/* ===== BESTIARY GRID (original style with border) ===== */
.characters-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.char-card {
    background: var(--card-bg);
    border: 1px solid color-mix(in srgb, var(--accent, var(--purple-glow)) 30%, transparent);
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(.25,.46,.45,.94);
    position: relative;
    text-decoration: none;
    color: inherit;
    display: block;
    opacity: 0;
    transform: translateY(24px);
    box-shadow: 0 2px 12px color-mix(in srgb, var(--accent, var(--purple-glow)) 8%, transparent);
}

.char-card.visible {
    opacity: 1;
    transform: translateY(0);
}

.char-card:hover {
    border-color: var(--accent, var(--purple-glow));
    transform: translateY(-8px);
    box-shadow: 0 20px 60px color-mix(in srgb, var(--accent, var(--purple-glow)) 30%, transparent),
                0 0 0 1px var(--accent, rgba(157,77,255,.2)),
                0 0 30px color-mix(in srgb, var(--accent, var(--purple-glow)) 12%, transparent);
}

.char-card::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(107,33,232,.05), transparent);
    opacity: 0; transition: opacity 0.3s;
    pointer-events: none;
}
.char-card:hover::before { opacity: 1; }

/* Art area */
.char-card-art {
    height: 200px;
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
}

.char-card-art-bg { position: absolute; inset: 0; }

.char-card-emoji {
    position: relative; z-index: 1;
    font-size: 4rem;
    filter: drop-shadow(0 4px 20px rgba(0,0,0,.5));
}

.char-card-img {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover; object-position: top center;
    z-index: 1;
    transition: transform .4s ease;
    filter: brightness(0.88);
}
.char-card:hover .char-card-img {
    transform: scale(1.06);
    filter: brightness(1);
}

/* Corner accent line */
.char-card::after {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(to right, transparent, var(--accent, var(--purple-glow)), transparent);
    opacity: 0;
    transition: opacity 0.3s;
}
.char-card:hover::after { opacity: 1; }

/* Grade badge */
.char-grade-badge {
    position: absolute; top: 10px; right: 10px;
    font-family: 'Orbitron', sans-serif; font-size: .5rem; letter-spacing: 1px;
    padding: 3px 8px; border-radius: 2px;
    text-transform: uppercase; z-index: 3;
    backdrop-filter: blur(6px);
}

/* Info area */
.char-card-info { padding: 16px; }
.char-name {
    font-family: 'Cinzel Decorative', serif;
    font-size: .82rem; color: var(--text);
    margin-bottom: 4px; line-height: 1.3;
}
.char-affiliation {
    font-size: .72rem; color: var(--purple-glow);
    margin-bottom: 4px;
}
.char-technique {
    font-size: .72rem; color: var(--text-muted);
    margin-bottom: 12px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* Power bars */
.power-bars { display: flex; flex-direction: column; gap: 5px; }
.power-bar-row { display: flex; align-items: center; gap: 8px; }
.power-bar-label { font-family: 'Orbitron', sans-serif; font-size: .5rem; color: var(--text-muted); width: 28px; flex-shrink: 0; }
.power-bar-track { flex: 1; height: 3px; background: rgba(255,255,255,.06); border-radius: 2px; overflow: hidden; }
.power-bar-fill { height: 100%; border-radius: 2px; width: 0; transition: width 1s ease; }
.fill-atk { background: linear-gradient(90deg, #cc2233, #ff3355); }
.fill-def { background: linear-gradient(90deg, var(--purple), var(--purple-glow)); }
.fill-spd { background: linear-gradient(90deg, #0088ff, #44ccff); }

/* Grade colors */
.grade-special{background:rgba(240,192,64,.2);border:1px solid rgba(240,192,64,.5);color:var(--gold);}
.grade-semi{background:rgba(157,77,255,.2);border:1px solid rgba(157,77,255,.5);color:#cc99ff;}
.grade-1{background:rgba(107,33,232,.2);border:1px solid rgba(107,33,232,.5);color:var(--purple-glow);}
.grade-2{background:rgba(0,150,255,.15);border:1px solid rgba(0,150,255,.4);color:#4dc8ff;}
.grade-3{background:rgba(100,100,120,.2);border:1px solid rgba(100,100,120,.5);color:#aaa8c0;}
.grade-4{background:rgba(80,80,90,.18);border:1px solid rgba(80,80,90,.45);color:#888898;}
.grade-unranked{background:rgba(60,60,70,.15);border:1px solid rgba(60,60,70,.4);color:#777788;}

.no-results{text-align:center;padding:80px 20px;color:var(--text-muted);}
.no-results-icon{font-size:4rem;margin-bottom:20px;}

footer{border-top:1px solid var(--border);padding:30px 40px;text-align:center;}
.footer-logo{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--gold);}
.footer-sub{font-size:.75rem;color:var(--text-muted);margin-top:6px;}

@media(max-width:1200px){.characters-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:800px){.characters-grid{grid-template-columns:repeat(2,1fr);}.filter-bar{padding:0 20px;}.main-content{padding:0 20px 60px;}}
@media(max-width:500px){.characters-grid{grid-template-columns:1fr;}}
</style>
</head>
<body>

<?php
$currentPage = 'characters';
$basePath    = '../';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="page-hero">
    <span class="page-tag">Karakter</span>
    <h1 class="page-title">Para Tukang Sihir & Kutukan</h1>
    <p class="page-sub">Jelajahi semua karakter dari dunia Jujutsu Kaisen, dari murid hingga Special Grade.</p>
</div>

<div class="filter-bar">
    <form method="GET" style="display:contents;">
        <input type="text" name="search" class="search-input" placeholder=" Cari karakter, teknik, afiliasi..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="filter-btn active">CARI</button>
    </form>
    <a href="characters.php" class="filter-btn <?= empty($grade_filter) ? 'active' : '' ?>">Semua</a>
    <a href="?grade=Special+Grade" class="filter-btn grade-special-btn <?= $grade_filter==='Special Grade'?'active':'' ?>"> Special</a>
    <a href="?grade=Semi-Grade+1"  class="filter-btn <?= $grade_filter==='Semi-Grade 1'?'active':'' ?>"> Semi-1</a>
    <a href="?grade=Grade+1"       class="filter-btn <?= $grade_filter==='Grade 1'?'active':'' ?>">Grade 1</a>
    <a href="?grade=Grade+2"       class="filter-btn <?= $grade_filter==='Grade 2'?'active':'' ?>">Grade 2</a>
    <a href="?grade=Grade+3"       class="filter-btn <?= $grade_filter==='Grade 3'?'active':'' ?>">Grade 3</a>
    <a href="?grade=Grade+4"       class="filter-btn <?= $grade_filter==='Grade 4'?'active':'' ?>">Grade 4</a>
    <a href="?grade=Unranked"      class="filter-btn <?= $grade_filter==='Unranked'?'active':'' ?>">Unranked</a>
</div>

<div class="main-content">
    <div class="chars-count"><?= count($characters) ?> KARAKTER DITEMUKAN</div>
    
    <?php if (empty($characters)): ?>
    <div class="no-results">
        <div class="no-results-icon"></div>
        <p style="font-size:1.1rem;margin-bottom:8px;">Tidak ada karakter ditemukan</p>
        <p style="font-size:.9rem;">Coba kata kunci lain atau hapus filter</p>
    </div>
    <?php else: ?>
    <div class="characters-grid">
        <?php foreach ($characters as $i => $char):
            $emoji = $charEmojis[$i % count($charEmojis)];
            $gradeData = $charColors[$char['grade']] ?? $charColors['Grade 3'];

            // Half-body image
            $halfImg = null;
            if (!empty($char['image_url'])) {
                $base = pathinfo($char['image_url'], PATHINFO_FILENAME);
                foreach(['webp','jpg','png'] as $ext) {
                    if (file_exists(__DIR__ . '/../asset/Half/' . $base . '.' . $ext)) {
                        $halfImg = '../asset/Half/' . $base . '.' . $ext;
                        break;
                    }
                }
            }
            $displayImg = $halfImg ?? (!empty($char['image_url']) ? '../asset/'.$char['image_url'] : null);
        ?>
        <a href="character_detail.php?id=<?= $char['id'] ?>"
           class="char-card"
           style="--accent:<?= $gradeData['accent'] ?>; transition-delay:<?= ($i % 4) * 0.07 ?>s;">
            <div class="char-card-art">
                <div class="char-card-art-bg" style="background:<?= $gradeData['bg'] ?>"></div>
                <span class="char-grade-badge <?= $gradeData['class'] ?>"><?= htmlspecialchars($char['grade']) ?></span>
                <?php if ($displayImg): ?>
                <img class="char-card-img"
                     src="<?= htmlspecialchars($displayImg) ?>"
                     alt="<?= htmlspecialchars($char['name']) ?>"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                <span class="char-card-emoji" style="display:none"><?= $emoji ?></span>
                <?php else: ?>
                <span class="char-card-emoji"><?= $emoji ?></span>
                <?php endif; ?>
            </div>
            <div class="char-card-info">
                <div class="char-name"><?= htmlspecialchars($char['name']) ?></div>
                <div class="char-affiliation"> <?= htmlspecialchars($char['affiliation'] ?? '-') ?></div>
                <div class="char-technique"> <?= htmlspecialchars($char['cursed_technique']) ?></div>
                <div class="power-bars">
                    <div class="power-bar-row">
                        <span class="power-bar-label">ATK</span>
                        <div class="power-bar-track">
                            <div class="power-bar-fill fill-atk" data-w="<?= $char['attack_power'] ?>"></div>
                        </div>
                    </div>
                    <div class="power-bar-row">
                        <span class="power-bar-label">DEF</span>
                        <div class="power-bar-track">
                            <div class="power-bar-fill fill-def" data-w="<?= $char['defense_power'] ?>"></div>
                        </div>
                    </div>
                    <div class="power-bar-row">
                        <span class="power-bar-label">SPD</span>
                        <div class="power-bar-track">
                            <div class="power-bar-fill fill-spd" data-w="<?= $char['speed_power'] ?>"></div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<footer>
    <div class="footer-logo">呪 JJK Universe</div>
    <div class="footer-sub">Praktikum Pemrograman Web 2026</div>
</footer>

<script>
const cards = document.querySelectorAll('.char-card');
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            // Animate power bars
            e.target.querySelectorAll('.power-bar-fill').forEach(bar => {
                setTimeout(() => { bar.style.width = bar.dataset.w + '%'; }, 200);
            });
            observer.unobserve(e.target);
        }
    });
}, { threshold: 0.1 });
cards.forEach(c => observer.observe(c));
</script>
</body>
</html>
