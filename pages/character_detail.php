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
        $rating = (int)($_POST['rating'] ?? 5);
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
$charEmoji = $charEmojis[($id - 1) % count($charEmojis)];
$charColors = [
    'Special Grade' => ['bg' => 'rgba(240,192,64,.1)', 'accent' => '#f0c040', 'class'=>'grade-special'],
    'Grade 1' => ['bg' => 'rgba(107,33,232,.1)', 'accent' => '#9d4dff', 'class'=>'grade-1'],
    'Grade 2' => ['bg' => 'rgba(0,150,255,.08)', 'accent' => '#4dc8ff', 'class'=>'grade-2'],
    'Grade 3' => ['bg' => 'rgba(100,100,120,.1)', 'accent' => '#aaa8c0', 'class'=>'grade-3'],
    'Semi-Grade 1' => ['bg' => 'rgba(107,33,232,.08)', 'accent' => '#9d4dff', 'class'=>'grade-1'],
];
$gd = $charColors[$char['grade']] ?? $charColors['Grade 3'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($char['name']) ?> — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root{--black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--red:#cc2233;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--border-gold:rgba(240,192,64,.2);--card-bg:rgba(10,8,20,.8);--nav-h:72px;--accent:<?= $gd['accent'] ?>;}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;} ::-webkit-scrollbar-track{background:#08060f;} ::-webkit-scrollbar-thumb{background:#3a0d7a;}
.navbar{position:fixed;top:0;left:0;right:0;height:var(--nav-h);z-index:100;display:flex;align-items:center;padding:0 40px;background:rgba(3,2,10,.9);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);}
.nav-logo{display:flex;align-items:center;gap:12px;text-decoration:none;flex:1;}
.logo-symbol{font-size:1.8rem;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.logo-text{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--text);}
.logo-sub{font-family:'Orbitron',sans-serif;font-size:.5rem;color:var(--text-muted);letter-spacing:3px;display:block;}
.nav-links{display:flex;align-items:center;gap:8px;list-style:none;}
.nav-links a{font-family:'Orbitron',sans-serif;font-size:.65rem;letter-spacing:2px;color:var(--text-muted);text-decoration:none;padding:8px 16px;border-radius:2px;transition:all .3s;text-transform:uppercase;}
.nav-links a:hover,.nav-links a.active{color:var(--text);background:rgba(107,33,232,.15);}
.nav-actions{display:flex;align-items:center;gap:12px;margin-left:20px;}
.btn-nav{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;padding:8px 20px;border-radius:2px;cursor:pointer;transition:all .3s;text-decoration:none;}
.btn-nav-outline{border:1px solid var(--border);color:var(--text-muted);background:transparent;}
.btn-nav-primary{background:var(--purple);border:1px solid var(--purple);color:white;}
.user-badge{display:flex;align-items:center;gap:8px;padding:6px 14px;border:1px solid var(--border-gold);border-radius:2px;background:rgba(240,192,64,.05);}
.user-badge-name{font-family:'Orbitron',sans-serif;font-size:.6rem;color:var(--gold);}

.char-hero{padding-top:var(--nav-h);min-height:70vh;display:flex;align-items:center;position:relative;overflow:hidden;}
.char-hero-bg{position:absolute;inset:0;background:radial-gradient(ellipse 70% 70% at 30% 50%, <?= $gd['bg'] ?> 0%, transparent 60%), linear-gradient(180deg,var(--black),#080515);}
.char-hero-content{position:relative;z-index:2;max-width:1200px;margin:0 auto;padding:60px 40px;display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;}
.char-visual{display:flex;align-items:center;justify-content:center;position:relative;}
.char-aura{width:320px;height:320px;border-radius:50%;background:radial-gradient(circle,<?= $gd['bg'] ?> 0%,transparent 70%);border:1px solid rgba(<?= implode(',', sscanf($gd['accent'],'#%02x%02x%02x')) ?>,0.3);display:flex;align-items:center;justify-content:center;position:relative;animation:auraPulse 3s ease-in-out infinite;}
.char-aura-img{width:280px;height:280px;object-fit:cover;object-position:top center;border-radius:50%;position:relative;z-index:1;filter:drop-shadow(0 10px 40px rgba(0,0,0,.6));animation:charFloat 3s ease-in-out infinite;}
@keyframes auraPulse{0%,100%{box-shadow:0 0 40px <?= $gd['bg'] ?>;} 50%{box-shadow:0 0 80px <?= $gd['bg'] ?>, 0 0 120px <?= $gd['bg'] ?>;}}
.char-emoji-big{font-size:8rem;animation:charFloat 3s ease-in-out infinite;filter:drop-shadow(0 10px 40px rgba(0,0,0,.5));}
@keyframes charFloat{0%,100%{transform:translateY(0);} 50%{transform:translateY(-15px);}}
.char-ring{position:absolute;inset:-20px;border-radius:50%;border:1px solid rgba(<?= implode(',', sscanf($gd['accent'],'#%02x%02x%02x') ?: [100,50,200]) ?>,0.15);animation:ringRotate 10s linear infinite;}
@keyframes ringRotate{to{transform:rotate(360deg);}}

.char-info{}
.char-breadcrumb{display:flex;align-items:center;gap:8px;margin-bottom:20px;font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--text-muted);}
.char-breadcrumb a{color:var(--text-muted);text-decoration:none;transition:color .3s;}
.char-breadcrumb a:hover{color:var(--accent);}
.char-breadcrumb span{color:var(--text-muted);}
.grade-badge{display:inline-block;font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;padding:5px 14px;border-radius:1px;margin-bottom:16px;border:1px solid var(--accent);color:var(--accent);background:<?= $gd['bg'] ?>;}
.char-title{font-family:'Cinzel Decorative',serif;font-size:clamp(2rem,4vw,3rem);color:var(--text);margin-bottom:8px;line-height:1.1;}
.char-affiliation{font-family:'Orbitron',sans-serif;font-size:.7rem;letter-spacing:2px;color:var(--text-muted);margin-bottom:20px;}
.char-desc{color:var(--text-muted);font-size:1rem;line-height:1.8;margin-bottom:28px;}
.char-technique-box{background:<?= $gd['bg'] ?>;border:1px solid rgba(<?= implode(',',sscanf($gd['accent'],'#%02x%02x%02x')??[100,50,200]) ?>,0.25);border-radius:2px;padding:16px 20px;margin-bottom:24px;}
.tech-label{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:3px;color:var(--text-muted);margin-bottom:6px;display:block;}
.tech-name{font-size:1.1rem;font-weight:700;color:var(--accent);}

.power-section{margin-bottom:24px;}
.power-label{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:3px;color:var(--text-muted);margin-bottom:12px;display:block;}
.power-bars-large{display:flex;flex-direction:column;gap:10px;}
.power-bar-row{display:flex;align-items:center;gap:12px;}
.pbl-label{font-family:'Orbitron',sans-serif;font-size:.6rem;color:var(--text-muted);width:40px;flex-shrink:0;}
.pbl-track{flex:1;height:6px;background:rgba(255,255,255,.06);border-radius:3px;overflow:hidden;}
.pbl-fill{height:100%;border-radius:3px;transition:width 1.2s ease;}
.fill-atk{background:linear-gradient(90deg,var(--red),#ff3355);}
.fill-def{background:linear-gradient(90deg,var(--purple),var(--purple-glow));}
.fill-spd{background:linear-gradient(90deg,#0088ff,#44ccff);}
.pbl-val{font-family:'Orbitron',sans-serif;font-size:.65rem;font-weight:700;width:28px;text-align:right;}

.char-cta{display:flex;gap:12px;flex-wrap:wrap;}
.btn-primary{padding:14px 32px;background:linear-gradient(135deg,var(--purple),#8b30ff);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.7rem;font-weight:700;letter-spacing:2px;cursor:pointer;transition:all .3s;text-decoration:none;}
.btn-primary:hover{box-shadow:0 0 30px rgba(107,33,232,.5);transform:translateY(-1px);}
.btn-secondary{padding:14px 32px;background:transparent;border:1px solid var(--border-gold);border-radius:2px;color:var(--gold);font-family:'Orbitron',sans-serif;font-size:.7rem;font-weight:700;letter-spacing:2px;cursor:pointer;transition:all .3s;text-decoration:none;}
.btn-secondary:hover{background:rgba(240,192,64,.08);transform:translateY(-1px);}

.main-content{max-width:1200px;margin:0 auto;padding:80px 40px;}
.content-grid{display:grid;grid-template-columns:2fr 1fr;gap:40px;}
.lore-card{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;padding:32px;}
.lore-title{font-family:'Cinzel Decorative',serif;font-size:1.3rem;color:var(--text);margin-bottom:20px;}
.lore-text{color:var(--text-muted);line-height:1.9;font-size:1rem;}

.comments-section{}
.section-title{font-family:'Cinzel Decorative',serif;font-size:1.5rem;color:var(--text);margin-bottom:24px;}
.comment-form{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;padding:24px;margin-bottom:24px;}
.form-label{display:block;font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:3px;color:var(--purple-glow);text-transform:uppercase;margin-bottom:8px;}
.form-textarea{width:100%;background:rgba(107,33,232,.05);border:1px solid var(--border);border-radius:2px;padding:12px 16px;color:var(--text);font-family:'Rajdhani',sans-serif;font-size:1rem;resize:vertical;min-height:100px;outline:none;transition:all .3s;}
.form-textarea:focus{border-color:var(--purple-glow);}
.rating-group{display:flex;gap:6px;margin-top:8px;}
.rating-btn{width:36px;height:36px;background:rgba(240,192,64,.08);border:1px solid rgba(240,192,64,.2);border-radius:2px;color:var(--gold);font-size:.85rem;cursor:pointer;transition:all .3s;display:flex;align-items:center;justify-content:center;}
.rating-btn:hover,.rating-btn.active{background:rgba(240,192,64,.2);border-color:var(--gold);}
.btn-submit{margin-top:12px;padding:10px 24px;background:linear-gradient(135deg,var(--purple),#8b30ff);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.65rem;letter-spacing:2px;cursor:pointer;transition:all .3s;}
.btn-submit:hover{box-shadow:0 0 20px rgba(107,33,232,.4);}
.error-msg{background:rgba(204,34,51,.1);border:1px solid rgba(204,34,51,.3);border-radius:2px;padding:10px 14px;color:#ff6677;font-size:.9rem;margin-bottom:16px;}
.success-msg{background:rgba(0,200,100,.1);border:1px solid rgba(0,200,100,.3);border-radius:2px;padding:10px 14px;color:#00cc66;font-size:.9rem;margin-bottom:16px;}

.comment-item{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;padding:20px;margin-bottom:12px;}
.comment-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;}
.comment-user{font-weight:700;color:var(--purple-glow);font-size:.9rem;}
.comment-date{font-size:.75rem;color:var(--text-muted);}
.comment-rating{color:var(--gold);}
.comment-content{color:var(--text-muted);line-height:1.7;font-size:.95rem;}
.login-prompt{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;padding:24px;text-align:center;color:var(--text-muted);}
.login-prompt a{color:var(--gold);text-decoration:none;font-weight:600;}
.no-comments{text-align:center;padding:40px;color:var(--text-muted);font-size:.9rem;}

footer{border-top:1px solid var(--border);padding:30px 40px;text-align:center;}
.footer-logo{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--gold);}
.footer-sub{font-size:.75rem;color:var(--text-muted);margin-top:6px;}

@media(max-width:900px){.char-hero-content{grid-template-columns:1fr;}.char-visual{display:none;}.content-grid{grid-template-columns:1fr;}.main-content,.char-hero-content{padding-left:20px;padding-right:20px;}.navbar{padding:0 20px;}.nav-links{display:none;}}
</style>
</head>
<body>

<nav class="navbar">
    <a href="../index.php" class="nav-logo">
        <span class="logo-symbol">呪</span>
        <div><span class="logo-text">JJK Universe</span><span class="logo-sub">Cursed Energy Portal</span></div>
    </a>
    <ul class="nav-links">
        <li><a href="../index.php">Home</a></li>
        <li><a href="characters.php" class="active">Characters</a></li>
        <li><a href="story.php">Story Arc</a></li>
        <li><a href="../game/index.php">Mini Game</a></li>
        <?php if(isLoggedIn()):?><li><a href="leaderboard.php">Leaderboard</a></li><?php if(isAdmin()):?><li><a href="../admin/dashboard.php" style="color:var(--gold)">Admin</a></li><?php endif;?>
        <?php endif;?>
    </ul>
    <div class="nav-actions">
        <?php if(isLoggedIn()):?>
        <div class="user-badge"><span class="user-badge-name">⚡ <?=htmlspecialchars($_SESSION['username'] ?? '')?></span></div>
        <a href="logout.php" class="btn-nav btn-nav-outline">Logout</a>
        <?php else:?>
        <a href="login.php" class="btn-nav btn-nav-outline">Login</a>
        <a href="register.php" class="btn-nav btn-nav-primary">Register</a>
        <?php endif;?>
    </div>
</nav>

<section class="char-hero">
    <div class="char-hero-bg"></div>
    <div class="char-hero-content">
        <div class="char-info">
            <div class="char-breadcrumb">
                <a href="../index.php">Home</a>
                <span>/</span>
                <a href="characters.php">Characters</a>
                <span>/</span>
                <span style="color:var(--accent)"><?=htmlspecialchars($char['name'])?></span>
            </div>
            <div class="grade-badge"><?=htmlspecialchars($char['grade'])?></div>
            <h1 class="char-title"><?=htmlspecialchars($char['name'])?></h1>
            <div class="char-affiliation">📍 <?=htmlspecialchars($char['affiliation']??'Unknown')?></div>
            <p class="char-desc"><?=htmlspecialchars($char['description'])?></p>
            <div class="char-technique-box">
                <span class="tech-label">CURSED TECHNIQUE</span>
                <div class="tech-name">🔮 <?=htmlspecialchars($char['cursed_technique'])?></div>
            </div>
            <div class="power-section">
                <span class="power-label">POWER STATS</span>
                <div class="power-bars-large">
                    <div class="power-bar-row">
                        <span class="pbl-label">ATTACK</span>
                        <div class="pbl-track"><div class="pbl-fill fill-atk" style="width:<?=$char['attack_power']?>%"></div></div>
                        <span class="pbl-val" style="color:#ff5566"><?=$char['attack_power']?></span>
                    </div>
                    <div class="power-bar-row">
                        <span class="pbl-label">DEFENSE</span>
                        <div class="pbl-track"><div class="pbl-fill fill-def" style="width:<?=$char['defense_power']?>%"></div></div>
                        <span class="pbl-val" style="color:var(--purple-glow)"><?=$char['defense_power']?></span>
                    </div>
                    <div class="power-bar-row">
                        <span class="pbl-label">SPEED</span>
                        <div class="pbl-track"><div class="pbl-fill fill-spd" style="width:<?=$char['speed_power']?>%"></div></div>
                        <span class="pbl-val" style="color:#44ccff"><?=$char['speed_power']?></span>
                    </div>
                </div>
            </div>
            <div class="char-cta">
                <?php if($char['is_playable']):?>
                <a href="../game/index.php?char=<?=urlencode($char['name'])?>" class="btn-primary">🎮 Mainkan</a>
                <?php endif;?>
                <a href="characters.php" class="btn-secondary">← Kembali</a>
            </div>
        </div>
        
        <div class="char-visual">
            <div class="char-aura">
                <div class="char-ring"></div>
                <?php if (!empty($char['image_url'])): ?>
                <img class="char-aura-img"
                     src="../asset/<?= htmlspecialchars($char['image_url']) ?>"
                     alt="<?= htmlspecialchars($char['name']) ?>"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                <div class="char-emoji-big" style="display:none"><?=$charEmoji?></div>
                <?php else: ?>
                <div class="char-emoji-big"><?=$charEmoji?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<div class="main-content">
    <div class="content-grid">
        <div>
            <div class="lore-card" style="margin-bottom:40px;">
                <h2 class="lore-title">📖 Lore & Backstory</h2>
                <div class="lore-text"><?=nl2br(htmlspecialchars($char['lore']??'Lore belum tersedia.'))?></div>
            </div>
        </div>
        
        <div class="comments-section">
            <h2 class="section-title">💬 Komentar (<?=count($comments)?>)</h2>
            
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
                        <textarea name="content" class="form-textarea" placeholder="Bagikan pendapatmu tentang karakter ini..." required></textarea>
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
            <?php foreach($comments as $comment):?>
            <div class="comment-item">
                <div class="comment-header">
                    <span class="comment-user">⚡ <?=htmlspecialchars($comment['username'])?></span>
                    <span class="comment-date"><?=date('d M Y', strtotime($comment['created_at']))?></span>
                </div>
                <div class="comment-rating"><?=str_repeat('⭐',$comment['rating'])?></div>
                <div class="comment-content" style="margin-top:8px;"><?=htmlspecialchars($comment['content'])?></div>
            </div>
            <?php endforeach;?>
            <?php endif;?>
        </div>
    </div>
</div>

<footer>
    <div class="footer-logo">呪 JJK Universe</div>
    <div class="footer-sub">Praktikum Pemrograman Web 2026</div>
</footer>

<script>
function setRating(val) {
    document.getElementById('rating_input').value = val;
    document.querySelectorAll('.rating-btn').forEach((btn, i) => {
        btn.classList.toggle('active', i < val);
    });
}

// Animate power bars on load
setTimeout(() => {
    document.querySelectorAll('.pbl-fill').forEach(bar => {
        const w = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => { bar.style.width = w; }, 100);
    });
}, 300);
</script>
</body>
</html>