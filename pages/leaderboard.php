<?php
require_once '../includes/config.php';
$leaderboard = getLeaderboard(20);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leaderboard — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root{--black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--border-gold:rgba(240,192,64,.2);--card-bg:rgba(10,8,20,.85);--nav-h:80px;}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;} ::-webkit-scrollbar-track{background:#08060f;} ::-webkit-scrollbar-thumb{background:#3a0d7a;}













.page-hero{padding-top:calc(var(--nav-h)+60px);padding-bottom:60px;text-align:center;padding-left:40px;padding-right:40px;position:relative;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 50% at 50% 0%,rgba(240,192,64,.08) 0%,transparent 60%);pointer-events:none;}
.page-tag{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:4px;color:var(--gold);text-transform:uppercase;margin-bottom:16px;display:block;}
.page-title{font-family:'Cinzel Decorative',serif;font-size:clamp(2rem,4vw,3rem);background:linear-gradient(135deg,var(--gold),var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:12px;}
.page-sub{color:var(--text-muted);font-size:1rem;max-width:500px;margin:0 auto;}
.main-content{max-width:800px;margin:0 auto;padding:0 40px 80px;}
.top3{display:flex;justify-content:center;align-items:flex-end;gap:16px;margin-bottom:48px;}
.top3-item{text-align:center;background:var(--card-bg);border:1px solid var(--border);border-radius:4px;padding:20px 16px;min-width:160px;position:relative;transition:all .3s;}
.top3-item:hover{transform:translateY(-4px);border-color:var(--gold);}
.top3-item.first{min-height:200px;border-color:rgba(240,192,64,.4);background:rgba(240,192,64,.04);}
.top3-item.second,.top3-item.third{min-height:160px;}
.top3-rank{font-size:2.5rem;margin-bottom:8px;display:block;}
.top3-name{font-family:'Cinzel Decorative',serif;font-size:.8rem;color:var(--text);margin-bottom:4px;}
.top3-char{font-size:.75rem;color:var(--text-muted);margin-bottom:8px;}
.top3-score{font-family:'Orbitron',sans-serif;font-size:1.1rem;font-weight:900;color:var(--gold);}
.lb-table{width:100%;}
.lb-row{display:flex;align-items:center;gap:16px;padding:14px 20px;border:1px solid var(--border);border-radius:2px;margin-bottom:8px;background:var(--card-bg);transition:all .3s;}
.lb-row:hover{border-color:var(--purple-glow);transform:translateX(4px);}
.lb-row.me{border-color:rgba(240,192,64,.4);background:rgba(240,192,64,.04);}
.lb-rank{font-family:'Orbitron',sans-serif;font-size:1rem;font-weight:900;width:36px;text-align:center;flex-shrink:0;}
.rank-1{color:var(--gold);text-shadow:0 0 15px var(--gold);}
.rank-2{color:#c0c0c0;}
.rank-3{color:#cd7f32;}
.rank-o{color:var(--text-muted);}
.lb-info{flex:1;}
.lb-username{font-weight:700;font-size:1rem;margin-bottom:2px;}
.lb-meta{font-size:.8rem;color:var(--text-muted);}
.lb-score{font-family:'Orbitron',sans-serif;font-size:.95rem;font-weight:700;color:var(--purple-glow);}
.empty{text-align:center;padding:60px;color:var(--text-muted);}
.empty-icon{font-size:3rem;margin-bottom:16px;}
.cta-play{display:flex;justify-content:center;margin-top:32px;}
.btn-play{padding:14px 40px;background:linear-gradient(135deg,var(--purple),#8b30ff);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.75rem;font-weight:700;letter-spacing:3px;cursor:pointer;text-decoration:none;transition:all .3s;}
.btn-play:hover{box-shadow:0 0 30px rgba(107,33,232,.5);}
footer{border-top:1px solid var(--border);padding:30px 40px;text-align:center;}
.footer-logo{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--gold);}
@media(max-width:600px){.top3{flex-wrap:wrap;}.top3-item.first{order:-1;width:100%;}.main-content,.page-hero{padding-left:20px;padding-right:20px;}}
</style>
</head>
<body>
<?php
$currentPage = 'leaderboard';
$basePath    = '../';
require_once __DIR__ . '/../includes/navbar.php';
?>
<div class="page-hero">
    <span class="page-tag">Top Sorcerers</span>
    <h1 class="page-title">Papan Peringkat</h1>
    <p class="page-sub">Siapa tukang sihir terkuat yang berhasil mengalahkan paling banyak Cursed Spirits?</p>
</div>
<div class="main-content">
    <?php if (!empty($leaderboard)): ?>
    <div class="top3">
        <?php if (isset($leaderboard[1])): ?>
        <div class="top3-item second">
            <span class="top3-rank">🥈</span>
            <div class="top3-name"><?=htmlspecialchars($leaderboard[1]['username'])?></div>
            <div class="top3-char">🗡 <?=htmlspecialchars($leaderboard[1]['character_used']??'?')?></div>
            <div class="top3-score"><?=number_format($leaderboard[1]['score'])?></div>
        </div>
        <?php endif;?>
        <?php if (isset($leaderboard[0])): ?>
        <div class="top3-item first">
            <span class="top3-rank">👑</span>
            <div class="top3-name"><?=htmlspecialchars($leaderboard[0]['username'])?></div>
            <div class="top3-char">🗡 <?=htmlspecialchars($leaderboard[0]['character_used']??'?')?></div>
            <div class="top3-score"><?=number_format($leaderboard[0]['score'])?></div>
        </div>
        <?php endif;?>
        <?php if (isset($leaderboard[2])): ?>
        <div class="top3-item third">
            <span class="top3-rank">🥉</span>
            <div class="top3-name"><?=htmlspecialchars($leaderboard[2]['username'])?></div>
            <div class="top3-char">🗡 <?=htmlspecialchars($leaderboard[2]['character_used']??'?')?></div>
            <div class="top3-score"><?=number_format($leaderboard[2]['score'])?></div>
        </div>
        <?php endif;?>
    </div>
    <div class="lb-table">
        <?php foreach ($leaderboard as $i => $row): 
            $isMe = isLoggedIn() && $_SESSION['user_id'] == $row['user_id'];
            $rankClass = $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'rank-o'));
        ?>
        <div class="lb-row <?=$isMe?'me':''?>">
            <span class="lb-rank <?=$rankClass?>"><?=$i===0?'👑':($i+1)?></span>
            <div class="lb-info">
                <div class="lb-username"><?=htmlspecialchars($row['username'])?> <?=$isMe?'<span style="font-family:\'Orbitron\',sans-serif;font-size:.5rem;color:var(--gold);background:rgba(240,192,64,.12);border:1px solid rgba(240,192,64,.3);padding:1px 6px;border-radius:1px;margin-left:4px;">YOU</span>':''?></div>
                <div class="lb-meta">🗡 <?=htmlspecialchars($row['character_used']??'?')?> &nbsp;·&nbsp; <?=$row['enemies_defeated']?> enemies &nbsp;·&nbsp; <?=date('d M Y',strtotime($row['played_at']))?></div>
            </div>
            <div class="lb-score"><?=number_format($row['score'])?> pts</div>
        </div>
        <?php endforeach;?>
    </div>
    <?php else: ?>
    <div class="empty">
        <div class="empty-icon">🎮</div>
        <p style="font-size:1.1rem;margin-bottom:8px;">Leaderboard masih kosong</p>
        <p>Jadilah yang pertama bermain dan masuk papan peringkat!</p>
    </div>
    <?php endif;?>
    <div class="cta-play"><a href="../game/index.php" class="btn-play">⚔ Main Sekarang</a></div>
</div>
<footer><div class="footer-logo">呪 JJK Universe</div></footer>
</body>
</html>