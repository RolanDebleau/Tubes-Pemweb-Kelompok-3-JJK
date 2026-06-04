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
    '🕷️','👻','🦂','🐍','💎','🌑','🔯','⭐',
    '🎪','🃏','🎲','🌟','🔱','⚜️','🏴','🎖️',
    '🩻','🦾','🌊','🔮','🎭','🌺','💀','⚡',
    '🔥','🌑','👁️','🗡️'
];
$charColors = [
    'Special Grade' => ['bg' => 'linear-gradient(135deg,rgba(240,192,64,.18),rgba(240,192,64,.04))', 'class'=>'grade-special'],
    'Semi-Grade 1'  => ['bg' => 'linear-gradient(135deg,rgba(157,77,255,.16),rgba(157,77,255,.03))', 'class'=>'grade-semi'],
    'Grade 1'       => ['bg' => 'linear-gradient(135deg,rgba(107,33,232,.15),rgba(107,33,232,.03))', 'class'=>'grade-1'],
    'Grade 2'       => ['bg' => 'linear-gradient(135deg,rgba(0,150,255,.12),rgba(0,150,255,.02))',   'class'=>'grade-2'],
    'Grade 3'       => ['bg' => 'linear-gradient(135deg,rgba(100,100,120,.14),rgba(100,100,120,.03))','class'=>'grade-3'],
    'Grade 4'       => ['bg' => 'linear-gradient(135deg,rgba(80,80,90,.12),rgba(80,80,90,.02))',     'class'=>'grade-4'],
    'Unranked'      => ['bg' => 'linear-gradient(135deg,rgba(60,60,70,.10),rgba(60,60,70,.02))',     'class'=>'grade-unranked'],
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
    --card-bg:rgba(10,8,20,.8); --nav-h:80px;
}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;} ::-webkit-scrollbar-track{background:#08060f;} ::-webkit-scrollbar-thumb{background:#3a0d7a;border-radius:3px;}

















.page-hero{padding-top:calc(var(--nav-h) + 60px);padding-bottom:60px;padding-left:40px;padding-right:40px;text-align:center;position:relative;overflow:hidden;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 0%,rgba(107,33,232,.12) 0%,transparent 60%);pointer-events:none;}
.page-tag{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:4px;color:var(--purple-glow);text-transform:uppercase;margin-bottom:16px;display:block;}
.page-title{font-family:'Cinzel Decorative',serif;font-size:clamp(2rem,4vw,3rem);background:linear-gradient(135deg,var(--text),var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:12px;}
.page-sub{color:var(--text-muted);font-size:1rem;max-width:500px;margin:0 auto;}

.filter-bar{max-width:1200px;margin:0 auto 40px;padding:0 40px;display:flex;gap:12px;flex-wrap:wrap;align-items:center;}
.search-input{flex:1;min-width:200px;background:rgba(107,33,232,.05);border:1px solid var(--border);border-radius:2px;padding:12px 16px;color:var(--text);font-family:'Rajdhani',sans-serif;font-size:1rem;outline:none;transition:all .3s;}
.search-input:focus{border-color:var(--purple-glow);background:rgba(107,33,232,.1);}
.search-input::placeholder{color:var(--text-muted);}
.filter-btn{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;padding:10px 18px;border-radius:2px;cursor:pointer;transition:all .3s;text-decoration:none;background:transparent;border:1px solid var(--border);color:var(--text-muted);}
.filter-btn:hover,.filter-btn.active{border-color:var(--purple-glow);color:var(--purple-glow);background:rgba(107,33,232,.1);}
.filter-btn.grade-special-btn.active{border-color:var(--gold);color:var(--gold);}

.main-content{max-width:1200px;margin:0 auto;padding:0 40px 80px;}
.chars-count{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);margin-bottom:24px;}

.characters-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}
.char-card{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;overflow:hidden;cursor:pointer;transition:all .4s;position:relative;text-decoration:none;color:inherit;}
.char-card:hover{border-color:var(--purple-glow);transform:translateY(-8px);box-shadow:0 20px 60px rgba(107,33,232,.2);}
.char-card-art{height:200px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;font-size:5rem;}
.char-card-art-bg{position:absolute;inset:0;}
.char-card-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:top center;z-index:1;transition:transform .4s;filter:drop-shadow(0 4px 20px rgba(0,0,0,.5));}
.char-card:hover .char-card-img{transform:scale(1.08);}
.char-card-emoji{position:relative;z-index:1;filter:drop-shadow(0 4px 20px rgba(0,0,0,.5));transition:transform .3s;}
.char-card:hover .char-card-emoji{transform:scale(1.1);}
.char-grade-badge{position:absolute;top:10px;right:10px;font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:1px;padding:3px 8px;border-radius:1px;text-transform:uppercase;z-index:2;}
.grade-special{background:rgba(240,192,64,.2);border:1px solid rgba(240,192,64,.5);color:var(--gold);}
.grade-semi{background:rgba(157,77,255,.2);border:1px solid rgba(157,77,255,.5);color:#cc99ff;}
.grade-4{background:rgba(80,80,90,.18);border:1px solid rgba(80,80,90,.45);color:#888898;}
.grade-unranked{background:rgba(60,60,70,.15);border:1px solid rgba(60,60,70,.4);color:#777788;}
.grade-1{background:rgba(107,33,232,.2);border:1px solid rgba(107,33,232,.5);color:var(--purple-glow);}
.grade-2{background:rgba(0,150,255,.15);border:1px solid rgba(0,150,255,.4);color:#4dc8ff;}
.grade-3{background:rgba(100,100,120,.2);border:1px solid rgba(100,100,120,.5);color:#aaa8c0;}
.grade-4    { background:rgba(80,80,80,.15); border:1px solid rgba(80,80,80,.4); color:#888888; }
.grade-unranked { background:rgba(60,60,60,.12); border:1px solid rgba(60,60,60,.3); color:#666666; }
.char-card-info{padding:18px;}
.char-name{font-family:'Cinzel Decorative',serif;font-size:.9rem;color:var(--text);margin-bottom:4px;}
.char-affiliation{font-size:.75rem;color:var(--purple-glow);margin-bottom:6px;}
.char-technique{font-size:.75rem;color:var(--text-muted);margin-bottom:14px;line-height:1.4;}
.power-bars{display:flex;flex-direction:column;gap:6px;}
.power-bar-row{display:flex;align-items:center;gap:8px;}
.power-bar-label{font-family:'Orbitron',sans-serif;font-size:.5rem;color:var(--text-muted);width:28px;flex-shrink:0;}
.power-bar-track{flex:1;height:3px;background:rgba(255,255,255,.06);border-radius:2px;overflow:hidden;}
.power-bar-fill{height:100%;border-radius:2px;transition:width 1s ease;}
.fill-atk{background:linear-gradient(90deg,var(--red),#ff3355);}
.fill-def{background:linear-gradient(90deg,var(--purple),var(--purple-glow));}
.fill-spd{background:linear-gradient(90deg,#0088ff,#44ccff);}
.power-val{font-family:'Orbitron',sans-serif;font-size:.5rem;color:var(--text-muted);width:24px;text-align:right;}
.char-actions{margin-top:14px;display:flex;gap:8px;}
.btn-detail{flex:1;padding:8px;background:linear-gradient(135deg,var(--purple),#8b30ff);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;cursor:pointer;text-decoration:none;text-align:center;display:block;transition:all .3s;}
.btn-detail:hover{box-shadow:0 0 20px rgba(107,33,232,.4);}

.no-results{text-align:center;padding:80px 20px;color:var(--text-muted);}
.no-results-icon{font-size:4rem;margin-bottom:20px;}

footer{border-top:1px solid var(--border);padding:30px 40px;text-align:center;}
.footer-logo{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--gold);}
.footer-sub{font-size:.75rem;color:var(--text-muted);margin-top:6px;}

@media(max-width:900px){.characters-grid{grid-template-columns:repeat(2,1fr);}.filter-bar{padding:0 20px;}.main-content{padding:0 20px 60px;}}
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
        <input type="text" name="search" class="search-input" placeholder="🔍 Cari karakter, teknik, afiliasi..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="filter-btn active">CARI</button>
    </form>
    <a href="characters.php" class="filter-btn <?= empty($grade_filter) ? 'active' : '' ?>">Semua</a>
    <a href="?grade=Special+Grade" class="filter-btn grade-special-btn <?= $grade_filter==='Special Grade'?'active':'' ?>">⭐ Special</a>
    <a href="?grade=Semi-Grade+1"  class="filter-btn <?= $grade_filter==='Semi-Grade 1'?'active':'' ?>">✦ Semi-1</a>
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
        <div class="no-results-icon">🔮</div>
        <p style="font-size:1.1rem;margin-bottom:8px;">Tidak ada karakter ditemukan</p>
        <p style="font-size:.9rem;">Coba kata kunci lain atau hapus filter</p>
    </div>
    <?php else: ?>
    <div class="characters-grid">
        <?php foreach ($characters as $i => $char):
            $emoji = $charEmojis[$i % count($charEmojis)];
            $gradeData = $charColors[$char['grade']] ?? $charColors['Grade 3'];
        ?>
        <div class="char-card">
            <div class="char-card-art">
                <div class="char-card-art-bg" style="background:<?= $gradeData['bg'] ?>"></div>
                <span class="char-grade-badge <?= $gradeData['class'] ?>"><?= htmlspecialchars($char['grade']) ?></span>
                <?php if (!empty($char['image_url'])): ?>
                <img class="char-card-img"
                     src="../asset/<?= htmlspecialchars($char['image_url']) ?>"
                     alt="<?= htmlspecialchars($char['name']) ?>"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                <span class="char-card-emoji" style="display:none"><?= $emoji ?></span>
                <?php else: ?>
                <span class="char-card-emoji"><?= $emoji ?></span>
                <?php endif; ?>
            </div>
            <div class="char-card-info">
                <div class="char-name"><?= htmlspecialchars($char['name']) ?></div>
                <div class="char-affiliation">📍 <?= htmlspecialchars($char['affiliation'] ?? '-') ?></div>
                <div class="char-technique">🔮 <?= htmlspecialchars($char['cursed_technique']) ?></div>
                <div class="power-bars">
                    <div class="power-bar-row">
                        <span class="power-bar-label">ATK</span>
                        <div class="power-bar-track"><div class="power-bar-fill fill-atk" style="width:<?= $char['attack_power'] ?>%"></div></div>
                        <span class="power-val"><?= $char['attack_power'] ?></span>
                    </div>
                    <div class="power-bar-row">
                        <span class="power-bar-label">DEF</span>
                        <div class="power-bar-track"><div class="power-bar-fill fill-def" style="width:<?= $char['defense_power'] ?>%"></div></div>
                        <span class="power-val"><?= $char['defense_power'] ?></span>
                    </div>
                    <div class="power-bar-row">
                        <span class="power-bar-label">SPD</span>
                        <div class="power-bar-track"><div class="power-bar-fill fill-spd" style="width:<?= $char['speed_power'] ?>%"></div></div>
                        <span class="power-val"><?= $char['speed_power'] ?></span>
                    </div>
                </div>
                <div class="char-actions">
                    <a href="character_detail.php?id=<?= $char['id'] ?>" class="btn-detail">DETAIL →</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<footer>
    <div class="footer-logo">呪 JJK Universe</div>
    <div class="footer-sub">Praktikum Pemrograman Web 2026</div>
</footer>

<script>
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.querySelectorAll('.power-bar-fill').forEach(bar => {
                const w = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => { bar.style.width = w; }, 200);
            });
        }
    });
}, { threshold: .3 });
document.querySelectorAll('.char-card').forEach(c => observer.observe(c));
</script>
</body>
</html>