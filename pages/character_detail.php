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

$charEmojis = ['👊','🌑','🔨','♾️','💀','👁️','👋','⚡','🔮','🗡️','🌊','🔥','🌿','🦴','🐼','👁','🩸','💠','🌸','⚡','🦋','🐦','🎭','🌙','💫','🔴','🟣','⚫','🌀','🏮','🎯','💥','🕷️','👻','🦂','🐍','💎','🌑','🔯','⭐','🎪','🃏','🎲','🌟','🔱','⚜️','🏴','🎖️','🩻','🦾','🌊','🔮','🎭','🌺','💀','⚡','🔥','🌑','👁️','🗡️'];
$charEmoji  = $charEmojis[($id - 1) % count($charEmojis)];

$charColors = [
    'Special Grade' => ['bg'=>'rgba(240,192,64,.12)', 'accent'=>'#f0c040', 'class'=>'grade-special','glow'=>'rgba(240,192,64,.3)'],
    'Semi-Grade 1'  => ['bg'=>'rgba(157,77,255,.1)',  'accent'=>'#cc99ff', 'class'=>'grade-semi',   'glow'=>'rgba(157,77,255,.3)'],
    'Grade 1'       => ['bg'=>'rgba(107,33,232,.1)',  'accent'=>'#9d4dff', 'class'=>'grade-1',      'glow'=>'rgba(107,33,232,.3)'],
    'Grade 2'       => ['bg'=>'rgba(0,150,255,.08)',  'accent'=>'#4dc8ff', 'class'=>'grade-2',      'glow'=>'rgba(0,150,255,.25)'],
    'Grade 3'       => ['bg'=>'rgba(100,100,120,.1)', 'accent'=>'#aaa8c0', 'class'=>'grade-3',      'glow'=>'rgba(100,100,120,.2)'],
    'Grade 4'       => ['bg'=>'rgba(80,80,90,.08)',   'accent'=>'#888898', 'class'=>'grade-4',      'glow'=>'rgba(80,80,90,.18)'],
    'Unranked'      => ['bg'=>'rgba(60,60,70,.07)',   'accent'=>'#777788', 'class'=>'grade-unranked','glow'=>'rgba(60,60,70,.15)'],
];
$gd = $charColors[$char['grade']] ?? $charColors['Grade 3'];
$accentHex = $gd['accent'];

// Find full body image
$fullBodyImg = null;
if (!empty($char['image_url'])) {
    $base = pathinfo($char['image_url'], PATHINFO_FILENAME);
    foreach(['webp','jpg','jpeg','png'] as $ext) {
        $path = __DIR__ . '/../asset/Full/' . $base . '_Full.' . $ext;
        if (file_exists($path)) {
            $fullBodyImg = '../asset/Full/' . $base . '_Full.' . $ext;
            break;
        }
        // Try without _Full suffix  
        $path2 = __DIR__ . '/../asset/Full/' . $base . '.' . $ext;
        if (file_exists($path2)) {
            $fullBodyImg = '../asset/Full/' . $base . '.' . $ext;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=htmlspecialchars($char['name'])?> — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;
  --red:#cc2233;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);
  --border-gold:rgba(240,192,64,.2);--card-bg:rgba(10,8,20,.85);--nav-h:80px;
  --accent:<?=$accentHex?>;--accent-bg:<?=$gd['bg']?>;--accent-glow:<?=$gd['glow']?>;
}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;}::-webkit-scrollbar-track{background:#08060f;}::-webkit-scrollbar-thumb{background:#3a0d7a;border-radius:3px;}

/* NAV */
















/* PAGE HEADER */
.page-header{padding-top:calc(var(--nav-h) + 40px);padding-bottom:28px;padding-left:60px;padding-right:60px;position:relative;border-bottom:1px solid var(--border);background:linear-gradient(180deg,<?=$gd['bg']?> 0%,transparent 100%);}
.page-header::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 50%,<?=$gd['bg']?> 0%,transparent 70%);pointer-events:none;}
.breadcrumb{display:flex;align-items:center;gap:8px;font-family:'Orbitron',sans-serif;font-size:.58rem;letter-spacing:2px;color:var(--text-muted);text-transform:uppercase;margin-bottom:16px;}
.breadcrumb a{color:var(--text-muted);text-decoration:none;transition:color .2s;}
.breadcrumb a:hover{color:var(--accent);}
.breadcrumb span{color:var(--accent);}
.page-title{font-family:'Cinzel Decorative',serif;font-size:clamp(1.8rem,3.5vw,2.8rem);color:var(--text);margin-bottom:6px;}
.page-title-jp{font-size:1rem;color:var(--text-muted);margin-bottom:12px;letter-spacing:2px;}
.grade-chip{display:inline-flex;align-items:center;gap:8px;font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:3px;padding:6px 16px;border:1px solid var(--accent);color:var(--accent);background:var(--accent-bg);border-radius:2px;text-transform:uppercase;}

/* MAIN WIKI LAYOUT */
.wiki-wrap{max-width:1200px;margin:0 auto;padding:40px 60px 80px;display:flex;gap:36px;align-items:flex-start;}
.wiki-main{flex:1;min-width:0;}
.wiki-sidebar{width:300px;flex-shrink:0;position:sticky;top:calc(var(--nav-h) + 20px);}

/* ===== INFOBOX (sidebar right) ===== */
.infobox{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;overflow:hidden;}
.infobox-title{background:rgba(107,33,232,.2);border-bottom:1px solid var(--border);padding:12px 16px;font-family:'Cinzel Decorative',serif;font-size:.85rem;color:var(--text);text-align:center;}

/* Full-body portrait */
.infobox-portrait{position:relative;background:linear-gradient(180deg,<?=$gd['bg']?> 0%,rgba(3,2,10,1) 100%);min-height:380px;display:flex;align-items:flex-end;justify-content:center;overflow:hidden;border-bottom:1px solid var(--border);}
.portrait-aura{position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 30%,<?=$gd['glow']?> 0%,transparent 70%);pointer-events:none;}
.portrait-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(107,33,232,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(107,33,232,.04) 1px,transparent 1px);background-size:24px 24px;pointer-events:none;}

/* Portrait image — full body standing */
.portrait-img{position:relative;z-index:2;width:100%;height:380px;object-fit:contain;object-position:center bottom;display:block;transition:transform .4s;}
.infobox:hover .portrait-img{transform:scale(1.03);}
.portrait-emoji-fallback{position:relative;z-index:2;font-size:6rem;padding:40px 0 20px;text-align:center;width:100%;}

/* Tab switcher for image */
.portrait-tabs{position:absolute;top:10px;left:50%;transform:translateX(-50%);display:flex;gap:4px;z-index:3;}
.ptab{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:1px;padding:4px 10px;border:1px solid var(--border);background:rgba(3,2,10,.7);color:var(--text-muted);cursor:pointer;border-radius:2px;transition:all .2s;}
.ptab.active,.ptab:hover{border-color:var(--accent);color:var(--accent);background:rgba(3,2,10,.9);}

/* Infobox data table */
.infobox-table{width:100%;}
.infobox-table tr{border-bottom:1px solid rgba(107,33,232,.08);}
.infobox-table tr:last-child{border-bottom:none;}
.infobox-table th{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--text-muted);padding:10px 14px;text-align:left;background:rgba(107,33,232,.05);text-transform:uppercase;width:40%;}
.infobox-table td{font-size:.88rem;color:var(--text);padding:10px 14px;line-height:1.5;}
.infobox-grade{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;padding:3px 8px;border:1px solid var(--accent);color:var(--accent);background:var(--accent-bg);border-radius:2px;display:inline-block;}

/* Power mini bars in infobox */
.mini-bar-row{display:flex;align-items:center;gap:8px;margin-bottom:6px;}
.mini-bar-row:last-child{margin-bottom:0;}
.mini-lbl{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:1px;color:var(--text-muted);width:36px;text-transform:uppercase;}
.mini-track{flex:1;height:5px;background:rgba(255,255,255,.06);border-radius:3px;overflow:hidden;}
.mini-fill{height:100%;border-radius:3px;transition:width 1.2s ease;}
.mini-val{font-family:'Orbitron',sans-serif;font-size:.55rem;color:var(--accent);min-width:24px;text-align:right;}
.infobox-play-btn{display:block;margin:14px;padding:10px;background:var(--purple);border:none;border-radius:3px;color:white;font-family:'Orbitron',sans-serif;font-size:.62rem;letter-spacing:2px;text-align:center;text-decoration:none;transition:background .3s;cursor:pointer;}
.infobox-play-btn:hover{background:var(--purple-glow);}

/* ===== MAIN CONTENT (left) ===== */
.wiki-section{margin-bottom:36px;}
.ws-title{font-family:'Cinzel Decorative',serif;font-size:1.1rem;color:var(--text);padding-bottom:8px;border-bottom:1px solid var(--border);margin-bottom:16px;display:flex;align-items:center;gap:10px;}
.ws-title-icon{font-size:1rem;}
.ws-text{font-size:.95rem;color:var(--text-muted);line-height:1.85;}
.ws-text p{margin-bottom:12px;}
.ws-text p:last-child{margin-bottom:0;}

/* Technique highlight box */
.tech-highlight{background:var(--accent-bg);border:1px solid var(--accent);border-left:3px solid var(--accent);border-radius:4px;padding:16px 20px;margin:16px 0;}
.tech-hl-label{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:3px;color:var(--accent);text-transform:uppercase;margin-bottom:6px;}
.tech-hl-name{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--text);margin-bottom:8px;}
.tech-hl-desc{font-size:.9rem;color:var(--text-muted);line-height:1.75;}

/* Domain box */
.domain-highlight{background:rgba(107,33,232,.07);border:1px solid rgba(107,33,232,.3);border-radius:4px;padding:18px 22px;margin:16px 0;position:relative;overflow:hidden;}
.domain-highlight::before{content:'領域展開';position:absolute;right:-10px;top:-10px;font-family:'Noto Serif JP',serif;font-size:4rem;opacity:.05;color:white;line-height:1;pointer-events:none;}
.domain-label{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:3px;color:var(--purple-glow);text-transform:uppercase;margin-bottom:6px;}
.domain-name{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--purple-glow);margin-bottom:8px;}
.domain-desc{font-size:.9rem;color:var(--text-muted);line-height:1.75;}

/* Trivia list */
.trivia-list{list-style:none;display:flex;flex-direction:column;gap:10px;}
.trivia-list li{font-size:.9rem;color:var(--text-muted);line-height:1.7;padding-left:18px;position:relative;}
.trivia-list li::before{content:'•';position:absolute;left:0;color:var(--accent);}

/* Quote */
.char-quote{border-left:3px solid var(--accent);padding:14px 20px;background:var(--accent-bg);border-radius:0 4px 4px 0;margin:20px 0;}
.char-quote-text{font-size:1rem;color:var(--text);font-style:italic;line-height:1.7;margin-bottom:6px;}
.char-quote-src{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--text-muted);}

/* COMMENTS */
.comments-section{margin-top:40px;padding-top:32px;border-top:1px solid var(--border);}
.section-title-cmnt{font-family:'Cinzel Decorative',serif;font-size:1.1rem;color:var(--text);margin-bottom:24px;display:flex;align-items:center;gap:10px;}
.comment-form{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:24px;margin-bottom:28px;}
.form-label{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);display:block;margin-bottom:8px;text-transform:uppercase;}
.form-textarea{width:100%;background:rgba(5,3,15,.8);border:1px solid var(--border);border-radius:4px;color:var(--text);font-family:'Rajdhani',sans-serif;font-size:.95rem;padding:12px 14px;resize:vertical;min-height:90px;outline:none;transition:border-color .3s;}
.form-textarea:focus{border-color:var(--purple-glow);}
.rating-group{display:flex;gap:6px;flex-wrap:wrap;}
.rating-btn{background:rgba(107,33,232,.1);border:1px solid var(--border);border-radius:3px;padding:6px 10px;cursor:pointer;font-size:1rem;transition:all .3s;color:var(--text-muted);}
.rating-btn.active{background:rgba(240,192,64,.15);border-color:var(--gold);}
.btn-submit{margin-top:14px;padding:10px 28px;background:var(--purple);border:none;border-radius:3px;color:white;font-family:'Orbitron',sans-serif;font-size:.62rem;letter-spacing:2px;cursor:pointer;transition:background .3s;}
.btn-submit:hover{background:var(--purple-glow);}
.error-msg{background:rgba(204,34,51,.1);border:1px solid rgba(204,34,51,.3);border-radius:3px;padding:10px 14px;color:#ff6677;font-size:.88rem;margin-bottom:14px;}
.success-msg{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:3px;padding:10px 14px;color:#34d399;font-size:.88rem;margin-bottom:14px;}
.login-prompt{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:20px;text-align:center;color:var(--text-muted);font-size:.9rem;margin-bottom:20px;}
.login-prompt a{color:var(--purple-glow);text-decoration:none;}
.comment-item{background:var(--card-bg);border:1px solid var(--border);border-radius:5px;padding:18px 20px;margin-bottom:14px;transition:border-color .3s;}
.comment-item:hover{border-color:rgba(107,33,232,.4);}
.comment-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;}
.comment-user{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--gold);}
.comment-date{font-size:.78rem;color:var(--text-muted);}
.comment-rating{font-size:.85rem;margin-bottom:6px;}
.comment-content{font-size:.92rem;color:var(--text-muted);line-height:1.7;}
.no-comments{text-align:center;padding:32px;color:var(--text-muted);font-size:.9rem;background:var(--card-bg);border:1px solid var(--border);border-radius:5px;}

/* Back button */
.back-btn{display:inline-flex;align-items:center;gap:8px;font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);text-decoration:none;padding:8px 16px;border:1px solid var(--border);border-radius:3px;transition:all .3s;margin-bottom:28px;}
.back-btn:hover{border-color:var(--accent);color:var(--accent);}

/* FOOTER */
footer{background:rgba(3,2,10,.9);border-top:1px solid var(--border);padding:28px 40px;text-align:center;}
.footer-logo{font-family:'Cinzel Decorative',serif;font-size:1.1rem;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:6px;}
.footer-sub{font-size:.75rem;color:var(--text-muted);}

@media(max-width:980px){
  .wiki-wrap{flex-direction:column-reverse;padding:24px 20px 60px;}
  .wiki-sidebar{width:100%;position:static;}
  .page-header{padding-left:20px;padding-right:20px;}
  
}
.grade-special{background:rgba(240,192,64,.2);border:1px solid rgba(240,192,64,.5);color:#f0c040;}
.grade-semi{background:rgba(157,77,255,.2);border:1px solid rgba(157,77,255,.5);color:#cc99ff;}
.grade-1{background:rgba(107,33,232,.2);border:1px solid rgba(107,33,232,.5);color:#9d4dff;}
.grade-2{background:rgba(0,150,255,.15);border:1px solid rgba(0,150,255,.4);color:#4dc8ff;}
.grade-3{background:rgba(100,100,120,.2);border:1px solid rgba(100,100,120,.5);color:#aaa8c0;}
.grade-4{background:rgba(80,80,90,.18);border:1px solid rgba(80,80,90,.45);color:#888898;}
.grade-unranked{background:rgba(60,60,70,.15);border:1px solid rgba(60,60,70,.4);color:#777788;}
</style>
</head>
<body>

<!-- NAV -->
<?php
$currentPage = 'characters';
$basePath    = '../';
require_once __DIR__ . '/../includes/navbar.php';
?>

<!-- PAGE HEADER -->
<div class="page-header">
  <div class="breadcrumb">
    <a href="../index.php">Home</a><span>/</span>
    <a href="characters.php">Characters</a><span>/</span>
    <span><?=htmlspecialchars($char['name'])?></span>
  </div>
  <h1 class="page-title"><?=htmlspecialchars($char['name'])?></h1>
  <div class="page-title-jp"><?=htmlspecialchars($char['affiliation'] ?? 'Unknown Affiliation')?></div>
  <span class="grade-chip">⭐ <?=htmlspecialchars($char['grade'])?></span>
</div>

<!-- WIKI BODY -->
<div class="wiki-wrap">

  <!-- LEFT: Main Content -->
  <div class="wiki-main">

    <a href="characters.php" class="back-btn">← Kembali ke Characters</a>

    <!-- DESKRIPSI -->
    <div class="wiki-section">
      <h2 class="ws-title"><span class="ws-title-icon">📖</span> Deskripsi</h2>
      <div class="ws-text">
        <p><?=nl2br(htmlspecialchars($char['description']))?></p>
      </div>
    </div>

    <!-- LORE / BACKSTORY -->
    <?php if(!empty($char['lore'])): ?>
    <div class="wiki-section">
      <h2 class="ws-title"><span class="ws-title-icon">🗂️</span> Latar Belakang & Lore</h2>
      <div class="ws-text">
        <?php
        // Split lore into paragraphs
        $loreParas = array_filter(explode("\n", $char['lore']));
        foreach($loreParas as $para):
          if(trim($para) !== ''):
        ?>
        <p><?=htmlspecialchars(trim($para))?></p>
        <?php endif; endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- CURSED TECHNIQUE -->
    <div class="wiki-section">
      <h2 class="ws-title"><span class="ws-title-icon">⚡</span> Kemampuan & Teknik Kutukan</h2>
      <div class="tech-highlight">
        <div class="tech-hl-label">Innate Cursed Technique</div>
        <div class="tech-hl-name">🔮 <?=htmlspecialchars($char['cursed_technique'])?></div>
        <p class="tech-hl-desc">Teknik kutukan bawaan yang menjadi ciri khas <?=htmlspecialchars($char['name'])?>. Teknik ini merupakan kemampuan unik yang mengalir dalam darah atau lahir secara alami dalam dirinya, membuatnya berbeda dari penyihir maupun kutukan lainnya.</p>
      </div>

      <!-- Domain Expansion placeholder -->
      <div class="domain-highlight">
        <div class="domain-label">Domain Expansion — 領域展開</div>
        <div class="domain-name">Jurus Pamungkas <?=htmlspecialchars($char['name'])?></div>
        <p class="domain-desc">Domain Expansion adalah wujud paling sempurna dari teknik kutukan <?=htmlspecialchars($char['name'])?>. Dengan menciptakan dimensi tersendiri, seluruh serangannya menjadi "sure-hit" yang tidak bisa dielak oleh lawan di dalam barrier domain tersebut.</p>
      </div>
    </div>

    <!-- KEKUATAN -->
    <div class="wiki-section">
      <h2 class="ws-title"><span class="ws-title-icon">📊</span> Power Rating</h2>
      <div class="ws-text" style="margin-bottom:16px;">
        <p>Berdasarkan penilaian institusi jujutsu dan rekam jejak pertempuran, berikut adalah rating kekuatan <?=htmlspecialchars($char['name'])?> dalam tiga parameter utama.</p>
      </div>

      <?php
      $stats = [
        ['ATK', $char['attack_power'],   '#ff4466', 'Kekuatan serangan dan output cursed energy dalam pertempuran langsung.'],
        ['DEF', $char['defense_power'],  '#9d4dff', 'Kemampuan bertahan, ketahanan tubuh, dan resistensi terhadap kutukan.'],
        ['SPD', $char['speed_power'],    '#38bdf8', 'Kecepatan gerak, reaksi, dan kemampuan menghindar.'],
      ];
      foreach($stats as $s): ?>
      <div style="margin-bottom:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
          <span style="font-family:'Orbitron',sans-serif;font-size:.65rem;letter-spacing:2px;color:var(--text-muted);text-transform:uppercase;"><?=$s[0]?></span>
          <span style="font-family:'Orbitron',sans-serif;font-size:.75rem;font-weight:700;color:<?=$s[2]?>"><?=$s[1]?>/100</span>
        </div>
        <div style="height:8px;background:rgba(255,255,255,.05);border-radius:4px;overflow:hidden;margin-bottom:6px;">
          <div class="stat-bar-anim" data-width="<?=$s[1]?>" style="height:100%;width:0%;background:<?=$s[2]?>;border-radius:4px;transition:width 1.2s ease;box-shadow:0 0 10px <?=$s[2]?>55;"></div>
        </div>
        <p style="font-size:.82rem;color:var(--text-muted);"><?=$s[3]?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- TRIVIA -->
    <div class="wiki-section">
      <h2 class="ws-title"><span class="ws-title-icon">💡</span> Trivia</h2>
      <ul class="trivia-list">
        <li>Nama <?=htmlspecialchars($char['name'])?> berasal dari kanji Jepang yang memiliki makna mendalam terkait dengan kekuatan dan takdir karakter ini dalam dunia jujutsu.</li>
        <li>Afiliasi: <?=htmlspecialchars($char['affiliation'] ?? 'Unknown')?> — tempat di mana karakter ini berkembang dan membentuk identitasnya sebagai penyihir.</li>
        <li>Grade <?=htmlspecialchars($char['grade'])?> menempatkan <?=htmlspecialchars($char['name'])?> dalam kategori yang menentukan jenis misi yang dapat ia tangani dan otoritasnya dalam hierarki dunia jujutsu.</li>
        <?php if($char['is_playable']):?>
        <li><?=htmlspecialchars($char['name'])?> adalah karakter yang dapat dimainkan dalam mode game interaktif — uji kemampuanmu menggunakan kekuatan karakter ini!</li>
        <?php endif;?>
      </ul>
    </div>

    <!-- COMMENTS -->
    <div class="comments-section">
      <h2 class="section-title-cmnt">💬 Komentar (<?=count($comments)?>)</h2>

      <?php if(isLoggedIn()):?>
      <div class="comment-form">
        <?php if($error):?><div class="error-msg">⚠ <?=htmlspecialchars($error)?></div><?php endif;?>
        <?php if($success):?><div class="success-msg">✓ <?=htmlspecialchars($success)?></div><?php endif;?>
        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?=csrfToken()?>">
          <input type="hidden" name="add_comment" value="1">
          <input type="hidden" name="rating" id="rating_input" value="5">
          <label class="form-label">Rating</label>
          <div class="rating-group" id="ratingGroup">
            <?php for($r=1;$r<=5;$r++):?>
            <button type="button" class="rating-btn <?=$r==5?'active':''?>" data-val="<?=$r?>" onclick="setRating(<?=$r?>)">⭐</button>
            <?php endfor;?>
          </div>
          <div style="margin-top:14px;">
            <label class="form-label">Komentar</label>
            <textarea name="content" class="form-textarea" placeholder="Bagikan pendapatmu tentang <?=htmlspecialchars($char['name'])?>..." required></textarea>
          </div>
          <button type="submit" class="btn-submit">KIRIM KOMENTAR ↗</button>
        </form>
      </div>
      <?php else:?>
      <div class="login-prompt">
        <p style="margin-bottom:8px;">Ingin berkomentar?</p>
        <p><a href="login.php">Login</a> atau <a href="register.php">Daftar</a> untuk berinteraksi.</p>
      </div>
      <?php endif;?>

      <?php if(empty($comments)):?>
      <div class="no-comments">💭 Belum ada komentar. Jadilah yang pertama!</div>
      <?php else:?>
      <?php foreach($comments as $cm):?>
      <div class="comment-item">
        <div class="comment-header">
          <span class="comment-user">⚡ <?=htmlspecialchars($cm['username'])?></span>
          <span class="comment-date"><?=date('d M Y', strtotime($cm['created_at']))?></span>
        </div>
        <div class="comment-rating"><?=str_repeat('⭐',$cm['rating'])?></div>
        <div class="comment-content"><?=htmlspecialchars($cm['content'])?></div>
      </div>
      <?php endforeach;?>
      <?php endif;?>
    </div>
  </div><!-- /wiki-main -->

  <!-- RIGHT: INFOBOX (like fandom wiki) -->
  <div class="wiki-sidebar">
    <div class="infobox">
      <div class="infobox-title"><?=htmlspecialchars($char['name'])?></div>

      <!-- Portrait with TABS (Character / Full Body) -->
      <div class="infobox-portrait" id="portraitBox">
        <div class="portrait-aura"></div>
        <div class="portrait-grid"></div>

        <?php if(!empty($char['image_url']) || $fullBodyImg): ?>
        <!-- Tab switcher -->
        <div class="portrait-tabs" id="portraitTabs">
          <button class="ptab active" onclick="switchPortrait('normal',this)">Character</button>
          <?php if($fullBodyImg): ?>
          <button class="ptab" onclick="switchPortrait('full',this)">Full Body</button>
          <?php endif; ?>
        </div>

        <!-- Normal portrait -->
        <?php if(!empty($char['image_url'])): ?>
        <img class="portrait-img" id="portraitNormal"
             src="../asset/<?=htmlspecialchars($char['image_url'])?>"
             alt="<?=htmlspecialchars($char['name'])?>"
             onerror="this.style.display='none';document.getElementById('portraitFallback').style.display='block';">
        <?php endif; ?>

        <!-- Full body portrait -->
        <?php if($fullBodyImg): ?>
        <img class="portrait-img" id="portraitFull"
             src="<?=$fullBodyImg?>"
             alt="<?=htmlspecialchars($char['name'])?> Full Body"
             style="display:none;"
             onerror="this.style.display='none';">
        <?php endif; ?>

        <div class="portrait-emoji-fallback" id="portraitFallback" style="display:none"><?=$charEmoji?></div>
        <?php else: ?>
        <div class="portrait-emoji-fallback" id="portraitFallback"><?=$charEmoji?></div>
        <?php endif; ?>
      </div>

      <!-- Biographical Info Table -->
      <table class="infobox-table">
        <tr>
          <th>Grade</th>
          <td><span class="infobox-grade"><?=htmlspecialchars($char['grade'])?></span></td>
        </tr>
        <tr>
          <th>Afiliasi</th>
          <td><?=htmlspecialchars($char['affiliation'] ?? 'Unknown')?></td>
        </tr>
        <tr>
          <th>Teknik</th>
          <td><?=htmlspecialchars($char['cursed_technique'])?></td>
        </tr>
        <tr>
          <th>Power Stats</th>
          <td>
            <div style="padding:4px 0;">
              <div class="mini-bar-row">
                <span class="mini-lbl">ATK</span>
                <div class="mini-track"><div class="mini-fill" data-w="<?=$char['attack_power']?>" style="width:0%;background:#ff4466;"></div></div>
                <span class="mini-val" style="color:#ff4466"><?=$char['attack_power']?></span>
              </div>
              <div class="mini-bar-row">
                <span class="mini-lbl">DEF</span>
                <div class="mini-track"><div class="mini-fill" data-w="<?=$char['defense_power']?>" style="width:0%;background:#9d4dff;"></div></div>
                <span class="mini-val" style="color:#9d4dff"><?=$char['defense_power']?></span>
              </div>
              <div class="mini-bar-row">
                <span class="mini-lbl">SPD</span>
                <div class="mini-track"><div class="mini-fill" data-w="<?=$char['speed_power']?>" style="width:0%;background:#38bdf8;"></div></div>
                <span class="mini-val" style="color:#38bdf8"><?=$char['speed_power']?></span>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th>Status</th>
          <td><?=$char['is_playable'] ? '<span style="color:#34d399">✓ Playable</span>' : '<span style="color:var(--text-muted)">— Non-Playable</span>'?></td>
        </tr>
      </table>

      <?php if($char['is_playable']):?>
      <a href="../game/index.php?char=<?=urlencode($char['name'])?>" class="infobox-play-btn">🎮 MAINKAN KARAKTER INI</a>
      <?php endif;?>
    </div><!-- /infobox -->

    <!-- Quick nav to other characters -->
    <div style="margin-top:16px;background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:16px;">
      <div style="font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);text-transform:uppercase;margin-bottom:12px;">Navigasi Cepat</div>
      <a href="characters.php" style="display:block;font-size:.88rem;color:var(--text-muted);text-decoration:none;padding:8px 0;border-bottom:1px solid rgba(107,33,232,.1);transition:color .2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">← Semua Karakter</a>
      <a href="world.php" style="display:block;font-size:.88rem;color:var(--text-muted);text-decoration:none;padding:8px 0;border-bottom:1px solid rgba(107,33,232,.1);transition:color .2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">🌏 Dunia JJK</a>
      <a href="jujutsu.php" style="display:block;font-size:.88rem;color:var(--text-muted);text-decoration:none;padding:8px 0;border-bottom:1px solid rgba(107,33,232,.1);transition:color .2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">⚡ Sistem Jujutsu</a>
      <a href="story.php" style="display:block;font-size:.88rem;color:var(--text-muted);text-decoration:none;padding:8px 0;transition:color .2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">📜 Story Arc</a>
    </div>

  </div><!-- /wiki-sidebar -->
</div><!-- /wiki-wrap -->

<footer>
  <div class="footer-logo">呪術廻戦 — JJK Universe</div>
  <div class="footer-sub">Jujutsu Kaisen © Gege Akutami / Shueisha · Dibuat untuk Praktikum Pemrograman Web 2026</div>
</footer>

<script>
// Animate all bars on load
window.addEventListener('load', function() {
  setTimeout(() => {
    document.querySelectorAll('.stat-bar-anim').forEach(bar => {
      bar.style.width = bar.dataset.width + '%';
    });
    document.querySelectorAll('.mini-fill').forEach(bar => {
      bar.style.width = bar.dataset.w + '%';
    });
  }, 400);
});

function setRating(val) {
  document.getElementById('rating_input').value = val;
  document.querySelectorAll('.rating-btn').forEach((btn, i) => {
    btn.classList.toggle('active', i < val);
  });
}

// Portrait tab switcher
function switchPortrait(mode, btn) {
  const normal = document.getElementById('portraitNormal');
  const full   = document.getElementById('portraitFull');
  const fallback = document.getElementById('portraitFallback');

  document.querySelectorAll('.ptab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  if (mode === 'full' && full) {
    if (normal) normal.style.display = 'none';
    full.style.display = 'block';
    full.style.objectFit = 'contain';
    full.style.objectPosition = 'center center';
    if (fallback) fallback.style.display = 'none';
    // Taller portrait for full body
    document.getElementById('portraitBox').style.minHeight = '520px';
  } else {
    if (normal) { normal.style.display = 'block'; }
    if (full)   { full.style.display = 'none'; }
    if (!normal && fallback) fallback.style.display = 'block';
    document.getElementById('portraitBox').style.minHeight = '380px';
  }
}
</script>
</body>
</html>