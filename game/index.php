<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_score') {
    header('Content-Type: application/json');
    if (!isLoggedIn()) { echo json_encode(['success'=>false,'msg'=>'Not logged in']); exit; }
    $score = (int)($_POST['score'] ?? 0);
    $enemies = (int)($_POST['enemies'] ?? 0);
    $character = trim($_POST['character'] ?? 'Unknown');
    saveGameScore($_SESSION['user_id'], $character, $score, $enemies);
    echo json_encode(['success'=>true]);
    exit;
}

$preselect = htmlspecialchars($_GET['char'] ?? '');
$db = getDB();
$playable = $db->query("SELECT * FROM characters WHERE is_playable = 1 ORDER BY name")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cursed Spirit Slayer — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
:root{
  --black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;
  --gold:#f0c040;--red:#cc2233;--green:#00cc66;
  --text:#ede8f5;--text-muted:#7a7490;
  --border:rgba(107,33,232,.25);--nav-h:64px;
}
*{margin:0;padding:0;box-sizing:border-box;}
html,body{height:100%;overflow:hidden;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;}
::-webkit-scrollbar{width:5px;}::-webkit-scrollbar-track{background:#08060f;}::-webkit-scrollbar-thumb{background:#3a0d7a;}

/* NAV */
.navbar{position:fixed;top:0;left:0;right:0;height:var(--nav-h);z-index:300;display:flex;align-items:center;padding:0 20px;background:rgba(3,2,10,.97);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);}
.nav-logo{display:flex;align-items:center;gap:8px;text-decoration:none;margin-right:20px;}
.logo-symbol{font-size:1.4rem;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.logo-text{font-family:'Cinzel Decorative',serif;font-size:.8rem;color:var(--text);}
.nav-links{display:flex;align-items:center;gap:2px;list-style:none;flex:1;}
.nav-links a{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:1.5px;color:var(--text-muted);text-decoration:none;padding:5px 10px;border-radius:2px;transition:all .2s;text-transform:uppercase;}
.nav-links a:hover,.nav-links a.active{color:var(--text);background:rgba(107,33,232,.15);}
.nav-right{display:flex;gap:8px;align-items:center;}
.btn-back{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:1.5px;padding:5px 14px;border-radius:2px;border:1px solid var(--border);color:var(--text-muted);background:transparent;text-decoration:none;transition:all .2s;}
.btn-back:hover{border-color:var(--purple-glow);color:var(--purple-glow);}

/* GAME AREA */
.game-wrap{position:fixed;inset:0;top:var(--nav-h);}

/* SELECT SCREEN */
.select-screen{position:absolute;inset:0;background:linear-gradient(180deg,#03020a 0%,#080520 60%,#0a0010 100%);display:flex;flex-direction:column;align-items:center;z-index:100;padding:40px 20px 20px;overflow-y:auto;}
.select-title{font-family:'Cinzel Decorative',serif;font-size:clamp(1.6rem,3.5vw,2.5rem);text-align:center;margin-bottom:6px;}
.select-title span{background:linear-gradient(135deg,var(--gold),var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.select-sub{font-family:'Orbitron',sans-serif;font-size:.58rem;letter-spacing:4px;color:var(--text-muted);text-align:center;margin-bottom:28px;}
.char-grid{display:flex;gap:16px;flex-wrap:wrap;justify-content:center;max-width:1000px;}
.char-card{width:180px;background:rgba(10,8,20,.95);border:1px solid var(--border);border-radius:4px;cursor:pointer;transition:all .3s;text-align:center;position:relative;overflow:hidden;}
.char-card::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(107,33,232,.05),transparent);opacity:0;transition:opacity .3s;}
.char-card:hover,.char-card.selected{border-color:var(--purple-glow);transform:translateY(-6px);box-shadow:0 16px 40px rgba(107,33,232,.3);}
.char-card:hover::before,.char-card.selected::before{opacity:1;}
.char-card.selected{border-color:var(--gold);box-shadow:0 16px 40px rgba(240,192,64,.2);}
.char-portrait{width:100%;height:150px;position:relative;overflow:hidden;background:rgba(107,33,232,.06);}
.char-portrait img{width:100%;height:100%;object-fit:cover;object-position:top center;transition:transform .35s;}
.char-card:hover .char-portrait img,.char-card.selected .char-portrait img{transform:scale(1.05);}
.char-emoji-fallback{font-size:3.5rem;line-height:1;display:none;align-items:center;justify-content:center;width:100%;height:100%;}
.char-info{padding:12px 14px 14px;}
.char-name{font-family:'Cinzel Decorative',serif;font-size:.7rem;color:var(--text);margin-bottom:8px;line-height:1.3;}
.char-stats{display:flex;flex-direction:column;gap:4px;}
.stat-row{font-family:'Orbitron',sans-serif;font-size:.48rem;letter-spacing:1px;color:var(--text-muted);display:flex;justify-content:space-between;align-items:center;gap:6px;}
.stat-bar{flex:1;height:3px;background:rgba(255,255,255,.06);border-radius:2px;overflow:hidden;}
.stat-fill{height:100%;border-radius:2px;}
.fill-atk{background:linear-gradient(90deg,#cc2233,#ff3355);}
.fill-def{background:linear-gradient(90deg,#6b21e8,#9d4dff);}
.fill-spd{background:linear-gradient(90deg,#0088ff,#44ccff);}
.sel-badge{position:absolute;top:6px;right:6px;background:var(--gold);color:var(--black);font-family:'Orbitron',sans-serif;font-size:.4rem;padding:2px 5px;border-radius:1px;letter-spacing:1px;}
.char-ability{font-family:'Rajdhani',sans-serif;font-size:.65rem;color:var(--purple-glow);margin-top:6px;line-height:1.3;}

.btn-start{margin-top:24px;padding:14px 50px;background:linear-gradient(135deg,var(--purple),#8b30ff);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.8rem;font-weight:700;letter-spacing:3px;cursor:pointer;transition:all .25s;position:relative;overflow:hidden;}
.btn-start:hover:not(:disabled){box-shadow:0 0 35px rgba(107,33,232,.55);transform:translateY(-2px);}
.btn-start:disabled{opacity:.45;cursor:not-allowed;}

/* CONTROLS GUIDE */
.controls-guide{display:flex;gap:20px;margin-top:16px;font-family:'Orbitron',sans-serif;font-size:.52rem;color:var(--text-muted);letter-spacing:1.5px;}
.ctrl-key{background:rgba(107,33,232,.15);border:1px solid var(--border);padding:2px 6px;border-radius:2px;color:var(--purple-glow);}

/* HUD */
.hud{position:absolute;top:0;left:0;right:0;height:56px;background:rgba(3,2,10,.93);border-bottom:1px solid var(--border);display:none;align-items:center;padding:0 16px;gap:20px;z-index:50;}
.hud-char{display:flex;align-items:center;gap:8px;min-width:120px;}
.hud-emoji{font-size:1.6rem;}
.hud-name{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:1px;color:var(--gold);}
.hud-bars{display:flex;flex-direction:column;gap:4px;min-width:130px;}
.hud-bar-row{display:flex;align-items:center;gap:6px;}
.hud-bar-label{font-family:'Orbitron',sans-serif;font-size:.45rem;letter-spacing:1.5px;color:var(--text-muted);min-width:22px;}
.hud-bar-track{flex:1;height:7px;background:rgba(255,255,255,.05);border-radius:3px;overflow:hidden;}
.hud-bar-fill{height:100%;border-radius:3px;transition:width .2s;}
.hp-fill{background:linear-gradient(90deg,#cc2233,#ff3355);}
.ce-fill{background:linear-gradient(90deg,#6b21e8,#9d4dff);}
.hud-wave{font-family:'Orbitron',sans-serif;font-size:.65rem;color:var(--purple-glow);letter-spacing:2px;}
.hud-kills{font-family:'Orbitron',sans-serif;font-size:.6rem;color:var(--text-muted);}
.hud-combo{font-family:'Orbitron',sans-serif;font-size:.8rem;color:var(--gold);letter-spacing:1px;min-width:60px;text-align:center;}
.hud-score{margin-left:auto;text-align:right;}
.hud-score-num{font-family:'Orbitron',sans-serif;font-size:1.1rem;font-weight:900;color:var(--gold);}
.hud-score-lbl{font-family:'Orbitron',sans-serif;font-size:.45rem;letter-spacing:2px;color:var(--text-muted);}

/* CANVAS */
.canvas-wrap{position:absolute;top:56px;left:0;right:0;bottom:0;display:none;}
#gameCanvas{display:block;}

/* SKILL INDICATOR */
.skill-bar{position:absolute;bottom:10px;left:50%;transform:translateX(-50%);display:none;gap:10px;z-index:50;align-items:flex-end;}
.skill-slot{display:flex;flex-direction:column;align-items:center;gap:3px;}
.skill-icon{width:44px;height:44px;background:rgba(10,8,20,.9);border:2px solid rgba(107,33,232,.4);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;position:relative;transition:border-color .2s;}
.skill-icon.ready{border-color:var(--purple-glow);box-shadow:0 0 12px rgba(157,77,255,.4);}
.skill-icon.active{border-color:var(--gold);box-shadow:0 0 16px rgba(240,192,64,.5);}
.skill-cd{position:absolute;inset:0;background:rgba(0,0,0,.7);display:flex;align-items:center;justify-content:center;font-family:'Orbitron',sans-serif;font-size:.55rem;color:white;border-radius:3px;}
.skill-key{font-family:'Orbitron',sans-serif;font-size:.45rem;color:var(--text-muted);letter-spacing:1px;}

/* MOBILE CONTROLS */
.mobile-controls{position:absolute;bottom:10px;left:0;right:0;display:none;justify-content:space-between;padding:0 16px;z-index:55;}
.m-btn{width:56px;height:56px;background:rgba(107,33,232,.18);border:1px solid rgba(107,33,232,.35);border-radius:50%;color:white;font-size:1.3rem;cursor:pointer;display:flex;align-items:center;justify-content:center;user-select:none;-webkit-user-select:none;transition:all .15s;touch-action:none;}
.m-btn:active{background:rgba(107,33,232,.45);transform:scale(.93);}
.m-right{display:flex;gap:8px;}

/* END SCREEN */
.end-screen{position:absolute;inset:0;background:rgba(3,2,10,.94);display:none;flex-direction:column;align-items:center;justify-content:center;z-index:200;backdrop-filter:blur(12px);}
.end-screen.show{display:flex;}
.end-icon{font-size:5rem;margin-bottom:14px;animation:popIn .4s ease-out;}
@keyframes popIn{0%{transform:scale(0) rotate(-10deg);}70%{transform:scale(1.15) rotate(2deg);}100%{transform:scale(1) rotate(0deg);}}
.end-title{font-family:'Cinzel Decorative',serif;font-size:2.2rem;margin-bottom:6px;}
.end-score{font-family:'Orbitron',sans-serif;font-size:1.4rem;color:var(--gold);margin-bottom:20px;}
.end-stats{display:flex;gap:28px;margin-bottom:28px;}
.end-stat{text-align:center;}
.end-stat-num{font-family:'Orbitron',sans-serif;font-size:1.2rem;font-weight:900;color:var(--purple-glow);}
.end-stat-lbl{font-size:.75rem;color:var(--text-muted);}
.end-btns{display:flex;gap:10px;}
.btn-again{padding:12px 30px;background:linear-gradient(135deg,var(--purple),#8b30ff);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.7rem;letter-spacing:2px;cursor:pointer;transition:all .25s;}
.btn-again:hover{box-shadow:0 0 25px rgba(107,33,232,.5);}
.btn-menu{padding:12px 30px;background:transparent;border:1px solid var(--border);border-radius:2px;color:var(--text-muted);font-family:'Orbitron',sans-serif;font-size:.7rem;letter-spacing:2px;cursor:pointer;transition:all .25s;}
.btn-menu:hover{border-color:var(--purple-glow);color:var(--purple-glow);}
.save-status{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--green);margin-top:8px;min-height:18px;}

/* PAUSE */
.pause-hint{position:absolute;top:62px;right:12px;font-family:'Orbitron',sans-serif;font-size:.5rem;color:var(--text-muted);letter-spacing:1px;z-index:50;pointer-events:none;display:none;}

@media(max-width:700px){
  .mobile-controls{display:flex;}
  .nav-links{display:none;}
}
</style>
</head>
<body>

<nav class="navbar">
  <a href="../index.php" class="nav-logo">
    <span class="logo-symbol">呪</span>
    <span class="logo-text">JJK Universe</span>
  </a>
  <ul class="nav-links">
    <li><a href="../index.php">Home</a></li>
    <li><a href="../pages/characters.php">Characters</a></li>
    <li><a href="index.php" class="active">🎮 Mini Game</a></li>
    <li><a href="../pages/leaderboard.php">Leaderboard</a></li>
  </ul>
  <div class="nav-right">
    <?php if(isLoggedIn()):?>
    <span style="font-family:'Orbitron',sans-serif;font-size:.55rem;color:var(--gold);">⚡ <?=htmlspecialchars($_SESSION['username']??'')?></span>
    <?php endif;?>
    <a href="../index.php" class="btn-back">← Menu</a>
  </div>
</nav>

<div class="game-wrap">

  <div class="select-screen" id="selectScreen">
    <div class="select-title"><span>Cursed Spirit Slayer</span></div>
    <div class="select-sub">PILIH KARAKTER — CHOOSE YOUR FIGHTER</div>
    <div class="char-grid">
      <?php
      $charData = [
        'Yuji Itadori'  => ['emoji'=>'👊','ability'=>'Black Flash — Divergent Fist','skill1'=>'Divergent Fist','skill2'=>'Black Flash'],
        'Megumi Fushiguro'=>['emoji'=>'🐾','ability'=>'Ten Shadows — Nue & Gyokuken','skill1'=>'Nue Strike','skill2'=>'Domain Expansion'],
        'Satoru Gojo'   => ['emoji'=>'∞','ability'=>'Infinity — Hollow Purple','skill1'=>'Blue Pull','skill2'=>'Hollow Purple'],
        'Nobara Kugisaki'=>['emoji'=>'🔨','ability'=>'Straw Doll — Resonance','skill1'=>'Nail Throw','skill2'=>'Resonance'],
        'Kento Nanami'  => ['emoji'=>'🔑','ability'=>'Ratio — Breakdown','skill1'=>'Ratio Strike','skill2'=>'Breakdown'],
        'Ryomen Sukuna' => ['emoji'=>'💀','ability'=>'Malevolent Shrine — Cleave','skill1'=>'Cleave','skill2'=>'Dismantle'],
      ];
      $emojiArr = ['👊','🐾','∞','🔨','🔑','💀'];
      foreach ($playable as $i => $p):
        $cd = $charData[$p['name']] ?? ['emoji'=>$emojiArr[$i%6],'ability'=>'Cursed Technique','skill1'=>'Strike','skill2'=>'Special'];
        $em = $cd['emoji'];
        $preSel = ($preselect===$p['name'])?'selected':'';
      ?>
      <div class="char-card <?=$preSel?>"
           data-char="<?=htmlspecialchars($p['name'])?>"
           data-atk="<?=$p['attack_power']?>"
           data-def="<?=$p['defense_power']?>"
           data-spd="<?=$p['speed_power']?>"
           data-emoji="<?=$em?>"
           data-skill1="<?=htmlspecialchars($cd['skill1'])?>"
           data-skill2="<?=htmlspecialchars($cd['skill2'])?>"
           onclick="selectChar(this)">
        <?php if($preSel):?><div class="sel-badge">SELECTED</div><?php endif;?>
        <div class="char-portrait">
          <?php if(!empty($p['image_url'])):?>
          <img src="../asset/<?=htmlspecialchars($p['image_url'])?>" alt="<?=htmlspecialchars($p['name'])?>"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
          <span class="char-emoji-fallback"><?=$em?></span>
          <?php else:?>
          <span class="char-emoji-fallback" style="display:flex"><?=$em?></span>
          <?php endif;?>
        </div>
        <div class="char-info">
          <div class="char-name"><?=htmlspecialchars($p['name'])?></div>
          <div class="char-stats">
            <div class="stat-row"><span>ATK</span><div class="stat-bar"><div class="stat-fill fill-atk" style="width:<?=$p['attack_power']?>%"></div></div><span style="color:#ff5566;min-width:20px"><?=$p['attack_power']?></span></div>
            <div class="stat-row"><span>DEF</span><div class="stat-bar"><div class="stat-fill fill-def" style="width:<?=$p['defense_power']?>%"></div></div><span style="color:#9d4dff;min-width:20px"><?=$p['defense_power']?></span></div>
            <div class="stat-row"><span>SPD</span><div class="stat-bar"><div class="stat-fill fill-spd" style="width:<?=$p['speed_power']?>%"></div></div><span style="color:#44ccff;min-width:20px"><?=$p['speed_power']?></span></div>
          </div>
          <div class="char-ability"><?=htmlspecialchars($cd['ability'])?></div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <button class="btn-start" id="startBtn" onclick="startGame()" <?=empty($preselect)?'disabled':''?>>⚔ MULAI PERTARUNGAN</button>
    <div class="controls-guide">
      <span><span class="ctrl-key">A/D</span> or <span class="ctrl-key">←/→</span> Move</span>
      <span><span class="ctrl-key">W/Space</span> Jump</span>
      <span><span class="ctrl-key">J/Z</span> Attack</span>
      <span><span class="ctrl-key">K/X</span> Skill 1</span>
      <span><span class="ctrl-key">L/C</span> Skill 2 (Ultimate)</span>
      <span><span class="ctrl-key">ESC</span> Pause</span>
    </div>
    <div style="margin-top:12px;font-family:'Orbitron',sans-serif;font-size:.55rem;color:var(--text-muted);text-align:center;letter-spacing:2px;">
      <?php if(!isLoggedIn()):?>⚠ LOGIN untuk menyimpan skor ke leaderboard<?php else:?>✓ Skor otomatis tersimpan ke leaderboard<?php endif;?>
    </div>
  </div>

  <div class="hud" id="hud">
    <div class="hud-char">
      <span class="hud-emoji" id="hudEmoji">👊</span>
      <span class="hud-name" id="hudName">-</span>
    </div>
    <div class="hud-bars">
      <div class="hud-bar-row">
        <span class="hud-bar-label">HP</span>
        <div class="hud-bar-track"><div class="hud-bar-fill hp-fill" id="hpBar" style="width:100%"></div></div>
        <span id="hpNum" style="font-family:'Orbitron',sans-serif;font-size:.48rem;color:#ff5566;min-width:30px;">100</span>
      </div>
      <div class="hud-bar-row">
        <span class="hud-bar-label">CE</span>
        <div class="hud-bar-track"><div class="hud-bar-fill ce-fill" id="ceBar" style="width:100%"></div></div>
        <span id="ceNum" style="font-family:'Orbitron',sans-serif;font-size:.48rem;color:#9d4dff;min-width:30px;">100</span>
      </div>
    </div>
    <div class="hud-combo" id="hudCombo"></div>
    <div class="hud-wave">WAVE <span id="waveNum">1</span></div>
    <div class="hud-kills">🗡 <span id="killCount">0</span> defeated</div>
    <div class="hud-score">
      <div class="hud-score-num" id="scoreNum">0</div>
      <div class="hud-score-lbl">SCORE</div>
    </div>
  </div>

  <div class="canvas-wrap" id="canvasWrap">
    <canvas id="gameCanvas"></canvas>
  </div>

  <div class="skill-bar" id="skillBar">
    <div class="skill-slot">
      <div class="skill-icon ready" id="skill1Icon">🔥</div>
      <div class="skill-key">J · ATACK</div>
    </div>
    <div class="skill-slot">
      <div class="skill-icon ready" id="skill2Icon">⚡</div>
      <div class="skill-key">K · SKILL</div>
    </div>
    <div class="skill-slot">
      <div class="skill-icon" id="skill3Icon">💥</div>
      <div class="skill-key">L · ULTS</div>
    </div>
  </div>

  <div class="mobile-controls" id="mobileCtrl">
    <div class="m-btn" ontouchstart="mK.left=true;ev(event)" ontouchend="mK.left=false">←</div>
    <div class="m-right">
      <div class="m-btn" ontouchstart="mK.jump=true;ev(event)" ontouchend="mK.jump=false">↑</div>
      <div class="m-btn" ontouchstart="mK.skill2=true;ev(event)" ontouchend="mK.skill2=false">⚡</div>
      <div class="m-btn" ontouchstart="mK.skill1=true;ev(event)" ontouchend="mK.skill1=false">🔥</div>
      <div class="m-btn" ontouchstart="mK.attack=true;ev(event)" ontouchend="mK.attack=false">⚔</div>
      <div class="m-btn" ontouchstart="mK.right=true;ev(event)" ontouchend="mK.right=false">→</div>
    </div>
  </div>

  <div class="pause-hint" id="pauseHint">P · PAUSE</div>

  <div class="end-screen" id="endScreen">
    <div class="end-icon" id="endIcon">💀</div>
    <div class="end-title" id="endTitle" style="color:#cc2233">GAME OVER</div>
    <div class="end-score">Score: <span id="finalScore">0</span></div>
    <div class="end-stats">
      <div class="end-stat"><div class="end-stat-num" id="finalKills">0</div><div class="end-stat-lbl">Enemies Slain</div></div>
      <div class="end-stat"><div class="end-stat-num" id="finalWave">1</div><div class="end-stat-lbl">Wave Reached</div></div>
      <div class="end-stat"><div class="end-stat-num" id="finalCombo">0</div><div class="end-stat-lbl">Max Combo</div></div>
    </div>
    <div class="save-status" id="saveStatus"></div>
    <div class="end-btns">
      <button class="btn-again" onclick="restartGame()">↺ MAIN LAGI</button>
      <button class="btn-menu" onclick="goMenu()">☰ MENU</button>
    </div>
  </div>
</div>

<script>
// ============================================================
// CURSED SPIRIT SLAYER — Enhanced Game Engine
// ============================================================

const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
function ev(e){e.preventDefault();}

// ============================================================
// PIXEL ART SYSTEM
// ============================================================
const S = 3; // base pixel scale

// Color Palettes
const C = {
  // Yuji Itadori
  yuji:{
    H:'#1a1510',h:'#2a2018',        // hair
    S:'#f0c090',s:'#d4956a',k:'#c07850', // skin
    P:'#e8405a',p:'#c02840',        // pink marks
    U:'#0d1820',u:'#1a2d40',o:'#243848', // uniform
    W:'#f0f0f0',w:'#d0d0d0',        // white
    E:'#1a1a30',                     // eyes
    G:'#cc7722',                     // gold belt
    B:'#080a10',                     // black
    R:'#cc2233',r:'#ff3344',        // red/black flash
    X:'#1a1a4a',                     // dark blue flash
  },
  // Megumi Fushiguro
  meg:{
    H:'#0d0d12',h:'#1a1a1f',        // dark hair
    S:'#e8b080',s:'#c88860',        // skin
    U:'#0d1520',u:'#1a2535',o:'#222f3e', // dark uniform
    W:'#e0e0e8',w:'#c0c0cc',        // white
    E:'#2a1a30',                     // eyes
    B:'#040408',                     // black
    G:'#4a6080',                     // steel
    T:'#8090a0',                     // shadow tint
    // Ten shadows colors
    SH:'#1a0d2a',sl:'#2a1540',sh:'#3a2050', // shadow beast dark
    SE:'#6622cc',se:'#9944ff',      // shadow eye glow
    SW:'#cccccc',sw:'#aaaaaa',      // divine dog white
    SK:'#1a1a1a',                    // divine dog black
  },
  // Spirits
  sp:{
    D:'#100820',M:'#200a40',L:'#3a0e60',
    E:'#cc44ff',e:'#ff88ff',
    G:'#660044',B:'#060010',
    T:'#440088',
  },
  spStr:{
    D:'#1a0830',M:'#2a0c50',L:'#3a1270',
    E:'#ff2200',e:'#ff6644',
    B:'#8800cc',b:'#cc44ff',
    W:'#ddd0ff',T:'#cc1100',
  },
  spFast:{
    D:'#050820',M:'#0a1440',L:'#1020a0',
    E:'#00ccff',e:'#88eeff',
    T:'#0055cc',B:'#000830',
  },
  // Sukuna (Boss)
  suk:{
    H:'#0f0101',h:'#1a0202',
    S:'#e8a870',s:'#c0785a',k:'#a05040',
    T:'#cc1a3a',t:'#ff3a5a',p:'#ff5570',
    U:'#280606',u:'#3a0808',o:'#500a0a',
    W:'#f0d8c0',
    E:'#ff1a00',e:'#ff5500',
    G:'#cc7722',g:'#ffaa33',
    R:'#cc1a2a',r:'#ff2a3a',
    B:'#120202',
  }
};

// ============================================================
// SPRITE TEMPLATES (pixel art maps)
// ============================================================

// Yuji idle frames (12w x 18h)
const YI = [
  [
    '..HHHHHHHH..',
    '.HHHhSSSSH..',
    'HHHhSSSSSH..',
    '.hSSPSSPSS..',
    '.hSSSSSSSSh.',
    '.hSSESSESSh.',
    '..uuuuuuuu..',
    '..uWuuuuWu..',
    '..Uuuuuuuu..',
    '.oUUUUUUUUo.',
    '.oUUUUUUUUo.',
    '.oUUGGGGUUo.',
    '..UUU..UUU..',
    '..UU....UU..',
    '..UU....UU..',
    '..Uu....uU..',
    '..wu....uw..',
    '..ww....ww..',
  ],
  [
    '..HHHHHHHH..',
    '.HHHhSSSSH..',
    'HHHhSSSSSHH.',
    '.hSSPSSPSS..',
    '.hSSSSSSSSh.',
    '.hSSESSESSh.',
    '..uuuuuuuu..',
    '..uWuuuuWu..',
    '..Uuuuuuuu..',
    '.oUUUUUUUUo.',
    '.oUUUUUUUUo.',
    '.oUUGGGGUUo.',
    '..UUU..UUU..',
    '..UU....UU..',
    '..UU....UU..',
    '..Uu....uU..',
    '..wu....uw..',
    '..ww....ww..',
  ],
];

// Yuji walk (lean forward + legs)
const YW = [
  [
    '..HHHHHHHH..',
    '.HHHhSSSSH..',
    'HHHhSSSSShH.',
    '.hSSPSSPSS..',
    '.hSSSSSSSSh.',
    '.hSSESSESSh.',
    'o.uuuuuuuu..',
    'o.uWuuuuWu..',
    'o.Uuuuuuuu..',
    'oUUUUUUUUUo.',
    'oUUUUUUUUUo.',
    '.oUUGGGGUUo.',
    '.UUU...UUU..',
    'UUU.....UU..',
    'Uu......UU..',
    '.uu.....uU..',
    'wuu.....uw..',
    'ww......ww..',
  ],
  [
    '..HHHHHHHH..',
    '.HHHhSSSSH..',
    'HHHhSSSSShH.',
    '.hSSPSSPSS..',
    '.hSSSSSSSSh.',
    '.hSSESSESSh.',
    '..uuuuuuuu.o',
    '..uWuuuuWu.o',
    '..Uuuuuuuu.o',
    '.oUUUUUUUUUo',
    '.oUUUUUUUUUo',
    '.oUUGGGGUUo.',
    '..UUU..UUU..',
    '..UU....UUU.',
    '..UU.....UUU',
    '..Uu.....uU.',
    '..uu.....uuu',
    '..ww......ww',
  ],
];

// Yuji jump
const YJ = [
  [
    '..HHHHHHHH..',
    '.HHHhSSSSH..',
    'HHHhSSSSSH..',
    '.hSSPSSPSS..',
    '.hSSSSSSSSh.',
    '.hSSESSESSh.',
    'o.uuuuuuuu.o',
    'oo.uWuuuWu.o',
    '...Uuuuuuu..',
    '..oUUUUUUUo.',
    '..oUUUUUUUo.',
    '..oUUGGGUUo.',
    '.UuU...UuU..',
    'UUu.....uUU.',
    'Uu.......uU.',
    '.uu.....uu..',
    '..wu...uw...',
    '..ww...ww...',
  ],
];

// Yuji attack (punch extended)
const YA = [
  [
    '..HHHHHHHH..',
    '.HHHhSSSSH..',
    'HHHhSSSSSH..',
    '.hSSPSSPSS..',
    '.hSSSSSSSSh.',
    '.hSSESSESSh.',
    '..uuuuuuuu..',
    '..uWuuuuWu..',
    '..Uuuuuuuu..',
    '.oUUUUUUUo..',
    '.oUUUUUUUoSSs',
    '.oUUGGGUUoSs.',
    '..UUU.UUUoS..',
    '..UU...UU...',
    '..UU...UU...',
    '..Uu...uU...',
    '..wu...uw...',
    '..ww...ww...',
  ],
];

// Yuji hurt
const YH = [
  [
    '..HHHHHHHH..',
    '.HHHhSSSSH..',
    '.HHhSSSSSSHH',
    '..hSSPSSPSSh',
    '..hSSSSSSSSh',
    '..hSSESSESSh',
    '...uuuuuuuu.',
    '...uWuuuuWu.',
    '...Uuuuuuuu.',
    '..oUUUUUUUo.',
    '..oUUUUUUUo.',
    '..oUUGGGUUo.',
    '..UUU..UUU..',
    '...UU...UU..',
    '...UU..UUU..',
    '...Uu..uU...',
    '...wu..uw...',
    '...ww..ww...',
  ],
];

// Black Flash special
const YBF = [
  [
    'RRRRHHHHHHHHRRRR',
    'RRHHHhSSSSHHHRR.',
    'RHHHhSSSSSSHHHR.',
    'R.hSSPSSPSSSSS.R',
    'R.hSSSSSSSSSSh.R',
    'R.hSSESSESSSSh.R',
    'RRuuuuuuuuuuuRR.',
    'RR.uWuuuuWu.RR..',
    'R..UuuuuuuuuUR..',
    'R.oUUUUUUUUo.R..',
    '.RoUUUUUUUUoRSSS',
    '.RoUURRGRRUoRSS.',
    '..RRUUU.UURRoS..',
    '..RRU...UUoR....',
    '..RRU...UUR.....',
    '..RRu...uRR.....',
    '..RRw...wRR.....',
    '..RRR...RRR.....',
  ],
];

// ---- MEGUMI IDLE FRAMES (12w x 18h) ----
const MI = [
  [
    '..HHHHHHHH..',
    '.HHHhSSSSH..',
    'HHHhSSSSSHH.',
    '.hSSSSSSSS..',
    '.hSSESSESSh.',
    '.hSSSSSSSShh',
    '..uuuuuuuu..',
    '..uuuuuuuu..',
    '..UuuuuuuU..',
    '.oUUUUUUUUo.',
    '.oUUUUUUUUo.',
    '.oUUUUUUUUo.',
    '..UUU..UUU..',
    '..UU....UU..',
    '..UU....UU..',
    '..Uu....uU..',
    '..uu....uu..',
    '..uu....uu..',
  ],
  [
    '...HHHHHHHH.',
    '..HHHhSSSSH.',
    '.HHHhSSSSShH',
    '..hSSSSSSSS.',
    '..hSSESSESSh',
    '..hSSSSSSSShh',
    '...uuuuuuuu.',
    '...uuuuuuuu.',
    '...UuuuuuuU.',
    '..oUUUUUUUUo',
    '..oUUUUUUUUo',
    '..oUUUUUUUUo',
    '...UUU..UUU.',
    '...UU....UU.',
    '...UU....UU.',
    '...Uu....uU.',
    '...uu....uu.',
    '...uu....uu.',
  ],
];

// Megumi Ten Shadows summon pose
const MA = [
  [
    '..HHHHHHHH..',
    '.HHHhSSSSH..',
    'HHHhSSSSSHH.',
    '.hSSSSSSSSSh',
    '.hSSESSESSh.',
    '.hSSSSSSSSh.',
    '..uuuuuuuu..',
    '.SHuuuuuuSH.',
    '.SHUuuuuUSH.',
    'SHoUUUUUUoSH',
    'SHoUUUUUUoSH',
    '.SHUUUUUUSHo',
    '..SUUU.UUUS.',
    '..SHU...USH.',
    '..SHU...USH.',
    '..SHu...uSH.',
    '..SHu...uSH.',
    '..SHu...uSH.',
  ],
];

// ---- CURSED SPIRIT frames (12w x 16h) ----
const CSW = [
  [
    '....MMMM....',
    '...MDDDDM...',
    '..MDDLLDdM..',
    '..MDLEELdM..',
    '..MDLeeLdM..',
    '..MDddddM...',
    '.MMDDDDDDMM.',
    'MMMDDDDDDDMM',
    'MMMDDDDDDMMM',
    '.MMMDDDDMMM.',
    '..MM.MM.MM..',
    '..MD.DM.DM..',
    '..MD.DM.DM..',
    '..MD.DM.DM..',
    '..BB.BB.BB..',
    '............',
  ],
  [
    '....MMMM....',
    '...MDDDDM...',
    '..MDDLLDdM..',
    '..MDLEELdM..',
    '..MDLeeLdM..',
    '..MDddddM...',
    '.MMDDDDDDMM.',
    'MMMDDDDDDDMM',
    'MMMDDDDDDMMM',
    '.MMMDDDDMMM.',
    '..MMDDDDMM..',
    '.MM..DD..MM.',
    'MM...DD...MM',
    '.M...DD...M.',
    '.B...BB...B.',
    '............',
  ],
];

// ---- STRONG SPIRIT (14w x 18h) ----
const SSW = [
  [
    '..HHHLLHHHH...',
    '.HHHLLLLLHHH..',
    'HHHLLMMMLLLHH.',
    'HHLMMMDDMMMHH.',
    'HHMMMDEEEMMHH.',
    'HHMMMDeeEMMHH.',
    'HHHMMDDDDMMHH.',
    'BbHHMMMMMHHbB.',
    'BbHHMMMMMHHbB.',
    'BBHHHMMMMHHBB.',
    'BBBHHHMMHHHBB.',
    '.BB.HMMHHHbB..',
    '.BB.HMHHH.BB..',
    '....HMMHH.....',
    '....HMMHH.....',
    '....BMMBB.....',
    '....BBBB......',
    '..............',
  ],
  [
    '..HHHLLHHHH...',
    '.HHHLLLLLHHH..',
    'HHHLLMMMLLLHH.',
    'HHLMMMDDMMMHH.',
    'HHMMMDEEEMMHH.',
    'HHMMMDeeEMMHH.',
    'HHHMMDDDDMMHH.',
    'BbHHMMMMMHHbB.',
    'BbBHHMMMMHHBb.',
    'BBBHHHMMHHHBB.',
    '.BBbHHMMHHbBB.',
    '..BB.HMMH.BB..',
    '..BB.HMMH.BB..',
    '....HHMMHH....',
    '.....HMMH.....',
    '.....BMMB.....',
    '.....BBB......',
    '..............',
  ],
];

// ---- FAST SPIRIT (10w x 14h) ----
const FSW = [
  [
    '..MMMMMM..',
    '.MDDDDDDM.',
    'MDLLLLLLDM',
    'MDLEeELLDM',
    'MDLeeeeLDM.',
    'MDDDDDDDdM.',
    '.MMDDDDMM..',
    '..MDDDDM..',
    '..MDDDDM..',
    'T.MDDDM.T.',
    'TT.MDM.TT.',
    '.T..BM..T.',
    '....BB....',
    '..........',
  ],
  [
    '..MMMMMM..',
    '.MDDDDDDM.',
    'MDLLLLLLDM',
    'MDLEeELLDM',
    'MDLeeeeLDM.',
    'MDDDDDDDdM.',
    '.MMDDDDMM..',
    '..MDDDDM..',
    '..MDDDDM..',
    '.TT.DDM...',
    'TTT.DM....',
    '..T.BB.T..',
    '....BB.TT.',
    '..........',
  ],
];

// ---- SUKUNA BOSS (18w x 24h) ----
const SUKI = [
  [
    '...HHHHHHHHHH.....',
    '..HHHHHHHHHHHH....',
    '.HHHhSSSSSSSSHH...',
    '.HHhSSSSSSSSSSHH..',
    '..hSSTeSSSeSSS....',
    '..hSSSSSSSSSSSh...',
    '..hSTtStSSStSSSh..',
    '..hSSSSSSSSSSSSh..',
    '..oWuuuuuuuuWuo...',
    '.ooWuuuuuuuuWuo...',
    '.oouuuuuuuuuuoo...',
    'ooouuuuuuuuuuooo..',
    'oouuuuuTTuuuuuoo..',
    '.ouuuuuTTuuuuuo...',
    '.ouuuuuuuuuuuuo...',
    'ooUoooooooooUoo...',
    'oUUU.......UUUo...',
    'UUU.........UUU...',
    '.UU.........UU....',
    '.UU....GG....UU...',
    '..U....GG....U....',
    '..U...........U...',
    '..u...........u...',
    '..uu.........uu...',
  ],
];
const SUKA = [
  [
    '...HHHHHHHHHH.....',
    '..HHHHHHHHHHHH....',
    '.HHHhSSSSSSSSHH...',
    '.HHhSSSSSSSSSSHH..',
    '..hSSTeEEEeSSS....',
    '..hSSSSEEESSSSh...',
    '..hSTtStEEtSSSh...',
    '..hSSSSSSSSSSSSh..',
    'SSSoWuuuuuuuWuo...',
    'SSSWuuuuuuuuWuo...',
    'SSSuuuuuuuuuuoo...',
    'SSSuuuuuTTuuuooo..',
    'SSSuuuuTTTuuuuoo..',
    '.ouuuuuTTuuuuuo...',
    '.ouuuuuuuuuuuuo...',
    'ooUoooooooooUoo...',
    'oUUU.......UUUo...',
    'UUU.........UUU...',
    '.UU.........UU....',
    '.UU....GG....UU...',
    '..U....GG....U....',
    '..U...........U...',
    '..u...........u...',
    '..uu.........uu...',
  ],
];
const SUKR = [
  [
    'RRRRHHHHHHHHHRRRR.',
    'RRHHHHHHHHHHHHRRR.',
    'RHHHhSSSSSSSSHHR..',
    'RHHhSSSSSSSSSSHR..',
    'RhRSTeEEEeSSSSR...',
    'RhSSSSEEESSSSShR..',
    'RhSTtStRRRtSSSShR.',
    'RhSSSSSSRRSSSSSh..',
    'RoWuuuuuRRuuWuoR..',
    'ooWuuuuuRRuuuWoo..',
    'oouuuuuuRRuuuuoo..',
    'ooouuSSuRRuSuuooo.',
    'oSSSSSSuRRuSSSooo.',
    'SSSSSSSuRuuSSSSoo.',
    'SSSSSSuuRuuuSSSo..',
    'ooUSSoooRooUoo....',
    'oUUU...RRR.UUUo...',
    'UUU....RRR..UUU...',
    '.UU....RRR...UU...',
    '.UU...RGGR...UU...',
    '..U...RGGG....U...',
    '..U..RRR.RR...U...',
    '..u.RR.....RR.u...',
    '..uuR.......Ruu...',
  ],
];

// ============================================================
// RENDERER
// ============================================================
function drawPx(data, colors, x, y, scale, flipX) {
  const rows = data.length;
  const cols = data[0].length;
  const tw = cols * scale;
  for (let r = 0; r < rows; r++) {
    const line = data[r];
    for (let c = 0; c < line.length; c++) {
      const ch = line[c];
      if (ch === '.' || !colors[ch]) continue;
      ctx.fillStyle = colors[ch];
      const px = flipX ? tw - (c+1)*scale : c*scale;
      ctx.fillRect(Math.round(x+px), Math.round(y+r*scale), scale, scale);
    }
  }
}

const Sprites = {
  player(x, y, state, fr, dir, char, isSpecial, isHurt) {
    const fl = dir === -1;
    let data, colors;
    if (char === 'megumi') {
      colors = C.meg;
      data = (state==='attack') ? MA[0] : (state==='jump') ? MI[1] : MI[fr%2];
    } else {
      colors = C.yuji;
      if (isSpecial)            data = YBF[0];
      else if (isHurt)          data = YH[0];
      else if (state==='attack')data = YA[0];
      else if (state==='jump')  data = YJ[0];
      else if (state==='walk')  data = YW[fr%2];
      else                      data = YI[fr%2];
    }
    drawPx(data, colors, x, y, S, fl);
  },
  cursed(x, y, fr, dir) {
    drawPx(CSW[fr%2], C.sp, x, y, S, dir===1);
  },
  strong(x, y, fr, dir) {
    drawPx(SSW[fr%2], C.spStr, x, y, S, dir===1);
  },
  fast(x, y, fr, dir) {
    drawPx(FSW[fr%2], C.spFast, x, y, 2.5, dir===1);
  },
  sukuna(x, y, state, rage, dir) {
    const fl = dir===1;
    let data = rage ? SUKR[0] : (state==='attack' ? SUKA[0] : SUKI[0]);
    ctx.shadowColor = rage?'#ff1100':'#cc2233';
    ctx.shadowBlur = rage?30:15;
    drawPx(data, C.suk, x, y, 4, fl);
    ctx.shadowBlur=0;
  }
};

// ============================================================
// GAME STATE
// ============================================================
let G = {};
function freshState(char) {
  return {
    state:'playing', score:0, kills:0, maxCombo:0,
    wave:1, maxWave:5,
    player:null, enemies:[], projectiles:[], particles:[],
    bossActive:false, boss:null,
    spawnTimer:0, spawnInterval:110,
    waveLeft:10, waveKillBase:10,
    selectedChar:char,
    waveAnnounce:0, waveText:'', waveSubText:'',
    animFrameId:null, paused:false,
    frameCount:0
  };
}

// Input
const keys = {};
const mK = {left:false,right:false,attack:false,skill1:false,skill2:false,jump:false};
window.addEventListener('keydown', e => {
  if (e.code==='KeyP' && G.state==='playing') { G.paused=!G.paused; return; }
  if (e.code==='Escape' && G.state==='playing') { endGame(false); return; }
  keys[e.code]=true;
});
window.addEventListener('keyup', e => { keys[e.code]=false; });

function k(codes) { return codes.some(c=>keys[c]||false); }

// Resize
function resize() {
  const wrap=document.getElementById('canvasWrap');
  canvas.width=wrap.clientWidth;
  canvas.height=wrap.clientHeight;
  initPlatforms();
}
window.addEventListener('resize', resize);

// ============================================================
// PLATFORMS
// ============================================================
let PLATS = [];
function initPlatforms() {
  const g = canvas.height-80, cw=canvas.width;
  PLATS = [
    {x:Math.floor(cw*.06), y:g-155, w:Math.floor(cw*.18), h:14},
    {x:Math.floor(cw*.30), y:g-220, w:Math.floor(cw*.20), h:14},
    {x:Math.floor(cw*.57), y:g-165, w:Math.floor(cw*.16), h:14},
    {x:Math.floor(cw*.78), y:g-255, w:Math.floor(cw*.16), h:14},
    {x:Math.floor(cw*.16), y:g-315, w:Math.floor(cw*.13), h:14},
    {x:Math.floor(cw*.67), y:g-335, w:Math.floor(cw*.13), h:14},
  ];
}

function platCheck(obj) {
  if (obj.vy<=0) return false;
  for (const p of PLATS) {
    const prevB = obj.y+obj.h-obj.vy;
    const curB  = obj.y+obj.h;
    const cx    = obj.x+obj.w/2;
    if (prevB<=p.y+5 && curB>=p.y && cx>=p.x-6 && cx<=p.x+p.w+6) {
      obj.y=p.y-obj.h; obj.vy=0; obj.grounded=true;
      return true;
    }
  }
  return false;
}

// ============================================================
// HELPERS
// ============================================================
function overlap(a,b){return a.x<b.x+b.w&&a.x+a.w>b.x&&a.y<b.y+b.h&&a.y+a.h>b.y;}
function rnd(min,max){return min+Math.random()*(max-min);}

let shakeX=0,shakeY=0,shakeTmr=0,shakeAmp=0;
function shake(amp,dur){shakeAmp=amp;shakeTmr=dur;}

function particles(x,y,color,n,type) {
  for(let i=0;i<n;i++){
    const a = Math.PI*2/n*i + rnd(-0.3,0.3);
    const spd = type==='burst'?rnd(3,7):type==='slash'?rnd(2,5):rnd(1,3);
    const vy0 = type==='jump'?-rnd(2,5):Math.sin(a)*spd;
    G.particles.push({x,y,vx:Math.cos(a)*spd,vy:vy0,c:color,life:rnd(15,30),ml:30,s:rnd(2,5),dead:false});
  }
}

function dmgNum(x,y,dmg,crit) {
  G.particles.push({x,y,vx:rnd(-.5,.5),vy:-3,life:40,ml:40,dead:false,isDmg:true,dmg:Math.ceil(dmg),crit});
}

// ============================================================
// PLAYER CLASS
// ============================================================
class Player {
  constructor(char) {
    this.x=100; this.y=0;
    this.w=42; this.h=60;
    this.vx=0; this.vy=0;
    this.grounded=false;
    this.dir=1;
    this.hp=100; this.maxHp=100;
    this.ce=100; this.maxCe=100;
    this.ceRegen=0.12;

    this.atk = char.atk;
    this.def = char.def;
    this.spd = 2 + (char.spd/100)*5.5;
    this.jumpPow = -16;

    this.attackTmr=0; this.atkCd=16;
    this.skill1Tmr=0; this.skill1Cd=90;  this.skill1Cost=20;
    this.skill2Tmr=0; this.skill2Cd=300; this.skill2Cost=50;

    this.isAttacking=false;
    this.isSkill1=false;
    this.isSkill2=false;
    this.isHurt=false;
    this.hurtTmr=0;
    this.iframeTmr=0;

    this.combo=0; this.comboTmr=0; this.maxCombo=0;
    this.animFr=0; this.animTmr=0;
    this.trail=[];
    this.charKey = char.charKey || 'yuji';
    this.char = char;

    // skill names
    this.skill1Name = char.skill1 || 'Divergent Fist';
    this.skill2Name = char.skill2 || 'Black Flash';
  }
  get ground(){return canvas.height-80;}

  update() {
    const left  = k(['ArrowLeft','KeyA'])||mK.left;
    const right = k(['ArrowRight','KeyD'])||mK.right;
    const jump  = k(['Space','ArrowUp','KeyW'])||mK.jump;
    const atk   = k(['KeyZ','KeyJ'])||mK.attack;
    const sk1   = k(['KeyX','KeyK'])||mK.skill1;
    const sk2   = k(['KeyC','KeyL'])||mK.skill2;

    // Move
    if(left){ this.vx=-this.spd; this.dir=-1; }
    else if(right){ this.vx=this.spd; this.dir=1; }
    else { this.vx*=0.72; }

    // Jump
    if(jump && this.grounded) {
      this.vy=this.jumpPow;
      this.grounded=false;
      particles(this.x+this.w/2, this.y+this.h, '#6b21e8', 5, 'jump');
    }

    // Attack
    if(atk && this.attackTmr<=0 && !this.isSkill2) this.doAttack();
    // Skill 1
    if(sk1 && this.skill1Tmr<=0 && this.ce>=this.skill1Cost && !this.isSkill2) this.doSkill1();
    // Skill 2 (Ultimate)
    if(sk2 && this.skill2Tmr<=0 && this.ce>=this.skill2Cost) this.doSkill2();

    // Physics
    this.vy += 0.85;
    this.x += this.vx;
    this.y += this.vy;

    // Ground
    if(!platCheck(this)) {
      if(this.y+this.h>=this.ground){ this.y=this.ground-this.h; this.vy=0; this.grounded=true; }
      else { this.grounded=false; }
    }

    // Walls
    this.x=Math.max(0, Math.min(canvas.width-this.w, this.x));

    // Timers
    if(this.attackTmr>0){this.attackTmr--; if(this.attackTmr<=0){this.isAttacking=false;}}
    if(this.skill1Tmr>0){this.skill1Tmr--; if(this.skill1Tmr<=0){this.isSkill1=false;}}
    if(this.skill2Tmr>0){this.skill2Tmr--; if(this.skill2Tmr<=0){this.isSkill2=false;}}
    if(this.iframeTmr>0) this.iframeTmr--;
    if(this.hurtTmr>0){this.hurtTmr--; this.isHurt=true;} else {this.isHurt=false;}
    if(this.comboTmr>0){this.comboTmr--;}else if(this.combo>0){this.combo=0; updateComboHUD();}

    // CE regen
    this.ce = Math.min(this.maxCe, this.ce+this.ceRegen);

    // Trail
    if(Math.abs(this.vx)>2.5||!this.grounded){
      this.trail.push({x:this.x+this.w/2,y:this.y+this.h/2,life:10});
    }
    this.trail = this.trail.filter(t=>{t.life--;return t.life>0;});

    // Anim
    this.animTmr++;
    if(this.animTmr%7===0) this.animFr++;

    updateHUD();
    updateSkillBar();
  }

  doAttack() {
    this.isAttacking=true;
    this.attackTmr=this.atkCd;
    this.combo++; this.comboTmr=45;
    if(this.combo>this.maxCombo){this.maxCombo=this.combo;if(G.maxCombo<this.maxCombo)G.maxCombo=this.maxCombo;}

    const atkMult = Math.min(1+this.combo*0.15, 2.2);
    const atkX = this.dir===1 ? this.x+this.w : this.x-60;
    const hb = {x:atkX,y:this.y+8,w:60,h:46};

    this._hitEnemies(hb, this.atk*atkMult, false);
    particles(atkX+(this.dir===1?20:-20), this.y+30, '#ff5566', 5, 'slash');
    updateComboHUD();
  }

  doSkill1() {
    this.isSkill1=true;
    this.skill1Tmr=this.skill1Cd;
    this.ce-=this.skill1Cost;

    G.projectiles.push(new Proj(
      this.x+this.w/2, this.y+this.h/2-8,
      this.dir*14, -1,
      this.atk*1.8, 'player', '#9d4dff', 24
    ));
    particles(this.x+this.w/2, this.y+this.h/2, '#9d4dff', 10, 'burst');
    shake(3,6);
  }

  doSkill2() {
    this.isSkill2=true;
    this.skill2Tmr=this.skill2Cd;
    this.ce-=this.skill2Cost;

    const hb = {x:0,y:this.y-20,w:canvas.width,h:this.h+60};
    this._hitEnemies(hb, this.atk*4, true);

    for(let i=-2;i<=2;i++){
      const vy = i*0.6;
      G.projectiles.push(new Proj(
        this.x+this.w/2, this.y+this.h/2,
        this.dir*16, vy,
        this.atk*2.5, 'player', '#f0c040', 30
      ));
    }
    particles(this.x+this.w/2, this.y+this.h/2, '#f0c040', 20, 'burst');
    particles(this.x+this.w/2, this.y+this.h/2, '#ff5566', 15, 'burst');
    shake(10,20);
  }

  _hitEnemies(hb, dmg, isCrit) {
    G.enemies.forEach(e=>{
      if(!e.dead&&!e.dying&&overlap(hb,e)) hitEnemy(e,dmg,this.dir,isCrit);
    });
    if(G.boss&&!G.boss.dead&&!G.boss.dying&&overlap(hb,G.boss)){
      hitEnemy(G.boss,dmg*0.6,this.dir,isCrit);
    }
  }

  takeDamage(dmg) {
    if(this.iframeTmr>0) return;
    const actual = dmg*(1-(this.def/100)*0.45);
    this.hp=Math.max(0,this.hp-actual);
    this.iframeTmr=35;
    this.hurtTmr=12;
    this.combo=0;
    particles(this.x+this.w/2,this.y+this.h/2,'#cc2233',8,'hurt');
    shake(5,12);
    updateHUD();
    updateComboHUD();
  }

  draw() {
    this.trail.forEach(t=>{
      ctx.globalAlpha=t.life/10*0.25;
      ctx.fillStyle='#9d4dff';
      ctx.fillRect(t.x-8,t.y-12,16,24);
    });
    ctx.globalAlpha=1;

    if(this.iframeTmr>0&&Math.floor(this.iframeTmr/4)%2) return;

    ctx.globalAlpha=0.15;
    ctx.fillStyle='#000';
    ctx.beginPath();
    ctx.ellipse(this.x+this.w/2,this.ground,this.w/2,7,0,0,Math.PI*2);
    ctx.fill();
    ctx.globalAlpha=1;

    if(this.isSkill2){ctx.shadowColor='#f0c040';ctx.shadowBlur=30;}
    else if(this.isSkill1){ctx.shadowColor='#9d4dff';ctx.shadowBlur=20;}
    else if(this.isAttacking){ctx.shadowColor='#ff5566';ctx.shadowBlur=14;}

    let pState='idle';
    if(!this.grounded) pState='jump';
    else if(Math.abs(this.vx)>1) pState='walk';
    if(this.isAttacking||this.isSkill1) pState='attack';

    Sprites.player(this.x,this.y,pState,this.animFr,this.dir,this.charKey,this.isSkill2,this.isHurt);
    ctx.shadowBlur=0;

    const bw=this.w+8,bh=5,bx=this.x-4,by=this.y-13;
    ctx.fillStyle='rgba(0,0,0,.6)';ctx.fillRect(bx,by,bw,bh);
    ctx.fillStyle=this.hp>60?'#00cc66':this.hp>30?'#f0c040':'#cc2233';
    ctx.fillRect(bx,by,bw*(this.hp/this.maxHp),bh);

    ctx.fillStyle='rgba(0,0,0,.5)';ctx.fillRect(bx,by+6,bw,3);
    ctx.fillStyle='#6b21e8';
    ctx.fillRect(bx,by+6,bw*(this.ce/this.maxCe),3);
  }
}

// ============================================================
// ENEMY CLASS
// ============================================================
class Enemy {
  constructor(x,type,wave) {
    this.type=type;
    this.x=x; this.y=0;
    this.w = type==='fast'?34:type==='strong'?52:44;
    this.h = type==='fast'?44:type==='strong'?64:56;
    this.vx=0; this.vy=0;
    this.grounded=false; this.dir=-1;
    this.hp = type==='strong'?90+wave*25:type==='fast'?35+wave*8:55+wave*14;
    this.maxHp=this.hp;
    this.atk = type==='strong'?18+wave*4:type==='fast'?8+wave:11+wave*2;
    this.spd = type==='fast'?3.8:type==='strong'?1.6:2.1;
    this.attackTmr=Math.random()*60;
    this.shootTmr=type==='strong'?rnd(80,130):99999;
    this.dead=false; this.dying=false; this.dyingTmr=0;
    this.aiTmr=Math.random()*40;
    this.animFr=0; this.animTmr=0;
    this.color=type==='strong'?'#ff3355':type==='fast'?'#44ccff':'#aa44ff';
    this.kbx=0; this.kby=0;
  }
  get ground(){return canvas.height-80;}
  update(player) {
    if(this.dying){
      this.dyingTmr++;
      this.vx*=0.88; this.vy-=0.25;
      if(this.dyingTmr>28) this.dead=true;
      return;
    }

    const dx = player.x-this.x;
    const dist=Math.abs(dx);
    this.dir = dx>0?1:-1;
    this.aiTmr++;

    if(Math.abs(this.kbx)>0.1){this.vx=this.kbx;this.kbx*=0.75;}
    else if(dist>65){this.vx=this.dir*this.spd;}
    else{
      this.vx*=0.8;
      if(this.attackTmr<=0){
        player.takeDamage(this.atk);
        this.attackTmr=58;
      }
    }

    if(this.type==='strong'){
      this.shootTmr--;
      if(this.shootTmr<=0){
        G.projectiles.push(new Proj(this.x+this.w/2,this.y+this.h/2,this.dir*5,-0.5,this.atk*.7,'enemy','#ff4422',16));
        this.shootTmr=rnd(100,140);
      }
    }

    this.vy+=0.85; this.x+=this.vx; this.y+=this.vy;
    if(!platCheck(this)){
      if(this.y+this.h>=this.ground){this.y=this.ground-this.h;this.vy=0;this.grounded=true;}
    }
    this.x=Math.max(0,Math.min(canvas.width-this.w,this.x));
    if(this.attackTmr>0)this.attackTmr--;
    this.animTmr++;
    if(this.animTmr%9===0)this.animFr++;
  }
  draw() {
    if(this.dying) ctx.globalAlpha=Math.max(0,1-this.dyingTmr/28);

    if(this.type==='strong'){
      ctx.shadowColor='#cc2233';ctx.shadowBlur=8;
      Sprites.strong(this.x,this.y,this.animFr,this.dir);
    } else if(this.type==='fast'){
      ctx.shadowColor='#44ccff';ctx.shadowBlur=6;
      Sprites.fast(this.x,this.y+8,this.animFr,this.dir);
    } else {
      ctx.shadowColor='#9933cc';ctx.shadowBlur=8;
      Sprites.cursed(this.x,this.y,this.animFr,this.dir);
    }
    ctx.shadowBlur=0; ctx.globalAlpha=1;
    if(this.dying) return;

    ctx.fillStyle='rgba(0,0,0,.6)';ctx.fillRect(this.x,this.y-10,this.w,5);
    ctx.fillStyle=this.color;
    ctx.fillRect(this.x,this.y-10,this.w*(this.hp/this.maxHp),5);
  }
}

// ============================================================
// BOSS CLASS
// ============================================================
class Boss {
  constructor(wave) {
    this.w=84; this.h=100;
    this.x=canvas.width-200; this.y=0;
    this.vx=0; this.vy=0;
    this.grounded=false; this.dir=-1;
    this.hp=350+wave*120; this.maxHp=this.hp;
    this.atk=28+wave*6; this.spd=2;
    this.dead=false; this.dying=false; this.dyingTmr=0;
    this.phase=1; this.attackTmr=0; this.shootTmr=0;
    this.chargeTmr=180; this.charging=false;
    this.rage=false; this.floatT=0;
    this.animFr=0; this.animTmr=0;
    this.state='idle';
  }
  get ground(){return canvas.height-80;}
  update(player){
    if(this.dying){this.dyingTmr++;this.vx*=0.85;this.vy-=0.4;if(this.dyingTmr>45)this.dead=true;return;}
    this.floatT++;
    this.animTmr++;if(this.animTmr%10===0)this.animFr++;

    if(this.hp<this.maxHp*0.5&&!this.rage){
      this.rage=true;this.spd=3.2;
      particles(this.x+this.w/2,this.y+this.h/2,'#ff3355',25,'burst');
      shake(10,25);
    }

    const dx=player.x-this.x;
    this.dir=dx>0?1:-1;
    const dist=Math.abs(dx);

    this.chargeTmr--;
    if(this.chargeTmr<=0&&!this.charging){
      this.chargeTmr=this.rage?70:130;
      this.charging=true;this.state='attack';
      this.vx=this.dir*14;
      shake(5,8);
    }
    if(this.charging){
      if(Math.abs(this.vx)>0.5)this.vx*=0.88;
      else{this.charging=false;this.state='idle';}
    } else if(dist>90){
      this.vx+=(this.dir*this.spd-this.vx)*0.1;
    } else {
      this.vx*=0.8;
      if(this.attackTmr<=0){player.takeDamage(this.atk);this.attackTmr=this.rage?32:52;}
    }

    this.shootTmr--;
    if(this.shootTmr<=0){
      const cnt=this.rage?4:2;
      for(let i=0;i<cnt;i++){
        const ang=(i-(cnt-1)/2)*0.35;
        G.projectiles.push(new Proj(
          this.x+this.w/2,this.y+this.h/2-10,
          Math.cos(ang)*this.dir*8, Math.sin(ang)*8-2,
          this.atk*.65,'enemy','#ff3300',22
        ));
      }
      this.shootTmr=this.rage?55:90;
    }

    this.vy+=0.85; this.x+=this.vx; this.y+=this.vy;
    if(this.y+this.h>=this.ground){this.y=this.ground-this.h;this.vy=0;this.grounded=true;}
    this.x=Math.max(0,Math.min(canvas.width-this.w,this.x));
    if(this.attackTmr>0)this.attackTmr--;
  }
  draw(){
    if(this.dying)ctx.globalAlpha=Math.max(0,1-this.dyingTmr/45);
    const floatOff=Math.round(Math.sin(this.floatT*.05)*4);
    Sprites.sukuna(this.x,this.y+floatOff,this.state,this.rage,this.dir);
    ctx.globalAlpha=1;
    if(this.dying)return;

    const bw=canvas.width-100,bx=50,by=8;
    ctx.fillStyle='rgba(0,0,0,.85)';ctx.fillRect(bx,by,bw,16);
    const pct=this.hp/this.maxHp;
    const grad=ctx.createLinearGradient(bx,0,bx+bw*pct,0);
    grad.addColorStop(0,this.rage?'#ff0044':'#cc1133');
    grad.addColorStop(1,this.rage?'#ff6600':'#ff2255');
    ctx.fillStyle=grad;
    ctx.fillRect(bx,by,bw*pct,16);
    ctx.strokeStyle='rgba(255,50,50,.3)';ctx.lineWidth=1;
    ctx.strokeRect(bx,by,bw,16);

    ctx.font='bold 9px "Orbitron",sans-serif';
    ctx.fillStyle='#fff';ctx.textAlign='center';ctx.textBaseline='middle';
    ctx.fillText(`${this.rage?'💢 ':''} RYOMEN SUKUNA — ${Math.ceil(this.hp)}/${this.maxHp}`,canvas.width/2,by+8);

    ctx.font='9px "Orbitron",sans-serif';
    ctx.fillStyle=this.rage?'#ff6600':'#ff4455';
    ctx.textAlign='center';ctx.textBaseline='bottom';
    ctx.fillText(this.rage?'👹 MALEVOLENT SHRINE':'👺 RYOMEN SUKUNA',this.x+this.w/2,this.y+floatOff-6);
  }
}

// ============================================================
// PROJECTILE
// ============================================================
class Proj {
  constructor(x,y,vx,vy,dmg,owner,color,size){
    this.x=x;this.y=y;this.vx=vx;this.vy=vy;
    this.dmg=dmg;this.owner=owner;this.color=color;
    this.size=size||14;this.dead=false;this.life=0;
    this.trail=[];
  }
  update(player){
    this.trail.push({x:this.x,y:this.y,life:6});
    this.trail=this.trail.filter(t=>{t.life--;return t.life>0;});
    this.x+=this.vx;this.y+=this.vy;
    this.vy+=0.08;this.life++;
    if(this.x<-60||this.x>canvas.width+60||this.y>canvas.height+60||this.life>130){this.dead=true;return;}
    const hb={x:this.x-this.size/2,y:this.y-this.size/2,w:this.size,h:this.size};
    if(this.owner==='player'){
      G.enemies.forEach(e=>{
        if(!e.dead&&!e.dying&&overlap(hb,e)){hitEnemy(e,this.dmg,this.vx>0?1:-1,false);this.dead=true;}
      });
      if(G.boss&&!G.boss.dead&&!G.boss.dying&&overlap(hb,G.boss)){
        hitEnemy(G.boss,this.dmg*.5,this.vx>0?1:-1,false);this.dead=true;
      }
    } else {
      if(overlap(hb,player)){player.takeDamage(this.dmg);this.dead=true;}
    }
  }
  draw(){
    if(this.dead)return;
    this.trail.forEach(t=>{
      ctx.globalAlpha=t.life/6*.35;
      ctx.fillStyle=this.color;
      ctx.beginPath();ctx.arc(t.x,t.y,this.size*.3,0,Math.PI*2);ctx.fill();
    });
    ctx.globalAlpha=1;
    ctx.shadowColor=this.color;ctx.shadowBlur=18;
    ctx.beginPath();ctx.arc(this.x,this.y,this.size/2,0,Math.PI*2);
    ctx.fillStyle=this.color;ctx.globalAlpha=.92;ctx.fill();
    ctx.beginPath();ctx.arc(this.x,this.y,this.size/3.5,0,Math.PI*2);
    ctx.fillStyle='#fff';ctx.globalAlpha=.7;ctx.fill();
    ctx.shadowBlur=0;ctx.globalAlpha=1;
  }
}

// ============================================================
// COMBAT
// ============================================================
function hitEnemy(e,dmg,dir,isCrit){
  if(e.dying||e.dead)return;
  const actual=dmg*(0.8+Math.random()*.5)*(isCrit?1.5:1);
  e.hp-=actual;
  if(e.kbx!==undefined) e.kbx=dir*4;

  dmgNum(e.x+e.w/2, e.y-5, actual, isCrit);
  particles(e.x+e.w/2,e.y+e.h*.4,'#ff5566',4,'slash');

  if(e.hp<=0){
    e.dying=true; e.vx=dir*3; e.vy=-4.5;
    const isBoss=e===G.boss;
    const pts=isBoss?600+G.wave*250:(e.type==='strong'?160:e.type==='fast'?90:55)*G.wave;
    G.score+=pts;
    G.kills++;
    particles(e.x+e.w/2,e.y+e.h/2,isBoss?'#ff3300':'#9d4dff',isBoss?30:15,'burst');
    if(isBoss)shake(14,35);
    updateHUD();
  }
}

// ============================================================
// HUD MANAGEMENT
// ============================================================
function updateHUD(){
  if(!G.player)return;
  const p=G.player;
  const hpPct=p.hp/p.maxHp*100;
  document.getElementById('hpBar').style.width=hpPct+'%';
  document.getElementById('hpNum').textContent=Math.ceil(p.hp);
  document.getElementById('ceBar').style.width=(p.ce/p.maxCe*100)+'%';
  document.getElementById('ceNum').textContent=Math.ceil(p.ce);
  document.getElementById('scoreNum').textContent=G.score.toLocaleString();
  document.getElementById('killCount').textContent=G.kills;
  document.getElementById('waveNum').textContent=G.wave;
}

function updateComboHUD(){
  if(!G.player)return;
  const el=document.getElementById('hudCombo');
  const c=G.player.combo;
  if(c>=2){
    el.textContent=c+'x COMBO';
    el.style.color=c>=8?'#ff3355':c>=5?'#ff9900':c>=3?'#f0c040':'#9d4dff';
    el.style.fontSize=Math.min(1+c*.05,1.4)+'rem';
  } else { el.textContent=''; }
}

function updateSkillBar(){
  if(!G.player)return;
  const p=G.player;

  const s1=document.getElementById('skill1Icon');
  const s2=document.getElementById('skill2Icon');
  const s3=document.getElementById('skill3Icon');

  s1.className='skill-icon'+(p.skill1Tmr<=0&&p.ce>=p.skill1Cost?' ready':p.isSkill1?' active':'');
  s1.innerHTML = p.skill1Tmr>0?`<div class="skill-cd">${Math.ceil(p.skill1Tmr/60*p.skill1Cd/60+p.skill1Tmr/10)}</div>⚡`:'⚡';

  s3.className='skill-icon'+(p.skill2Tmr<=0&&p.ce>=p.skill2Cost?' ready':p.isSkill2?' active':'');
  const s2cd=Math.ceil(p.skill2Tmr/10);
  s3.innerHTML = p.skill2Tmr>0?`<div class="skill-cd">${s2cd}s</div>💥`:'💥';
}

// ============================================================
// BACKGROUND ART EFFECTS
// ============================================================
let bgStars=[];
function initBg(){
  bgStars=[];
  for(let i=0;i<120;i++){
    bgStars.push({x:rnd(0,canvas.width),y:rnd(0,canvas.height*.7),r:rnd(.4,2),spd:rnd(.1,.5),a:rnd(.15,.7),phase:rnd(0,Math.PI*2)});
  }
}

let bgT=0;
function drawBg(){
  bgT++;
  const grad=ctx.createLinearGradient(0,0,0,canvas.height);
  grad.addColorStop(0,'#03020a');
  grad.addColorStop(.55,'#080420');
  grad.addColorStop(1,'#0a0012');
  ctx.fillStyle=grad; ctx.fillRect(0,0,canvas.width,canvas.height);

  bgStars.forEach(s=>{
    s.x-=s.spd; if(s.x<0)s.x=canvas.width;
    ctx.globalAlpha=s.a*(0.5+Math.sin(bgT*.008+s.phase)*.5);
    ctx.fillStyle='#9d4dff';
    ctx.beginPath();ctx.arc(s.x,s.y,s.r,0,Math.PI*2);ctx.fill();
  });
  ctx.globalAlpha=1;

  for(let i=0;i<3;i++){
    const cx=canvas.width*(i+1)/4;
    const a=Math.sin(bgT*.012+i*2)*.04+0.03;
    ctx.globalAlpha=a;
    ctx.strokeStyle='#6b21e8'; ctx.lineWidth=60;
    ctx.beginPath();
    ctx.moveTo(cx,canvas.height);
    ctx.bezierCurveTo(cx+Math.sin(bgT*.01+i)*50,canvas.height*.6,cx-Math.sin(bgT*.008+i)*40,canvas.height*.3,cx+Math.sin(bgT*.015)*30,0);
    ctx.stroke();
  }
  ctx.globalAlpha=1;

  const gg=ctx.createLinearGradient(0,canvas.height-80,0,canvas.height);
  gg.addColorStop(0,'#1a0535');gg.addColorStop(1,'#08021a');
  ctx.fillStyle=gg; ctx.fillRect(0,canvas.height-80,canvas.width,80);

  ctx.shadowColor='#6b21e8';ctx.shadowBlur=18;
  ctx.strokeStyle='#3a0d7a';ctx.lineWidth=2;
  ctx.beginPath();ctx.moveTo(0,canvas.height-80);ctx.lineTo(canvas.width,canvas.height-80);ctx.stroke();
  ctx.shadowBlur=0;

  ctx.globalAlpha=.06; ctx.font='28px serif'; ctx.textAlign='center';ctx.textBaseline='middle';
  for(let i=0;i<6;i++){ctx.fillStyle='#9d4dff';ctx.fillText('呪',canvas.width/6*i+canvas.width/12,canvas.height-40);}
  ctx.globalAlpha=1;

  PLATS.forEach(p=>{
    const pg=ctx.createLinearGradient(p.x,p.y,p.x,p.y+p.h);
    pg.addColorStop(0,'#4a1080');pg.addColorStop(1,'#1a0535');
    ctx.fillStyle=pg; ctx.fillRect(p.x,p.y,p.w,p.h);
    ctx.shadowColor='#9d4dff';ctx.shadowBlur=10;
    ctx.strokeStyle='#9d4dff';ctx.lineWidth=2;
    ctx.beginPath();ctx.moveTo(p.x,p.y);ctx.lineTo(p.x+p.w,p.y);ctx.stroke();
    ctx.shadowBlur=0;
    ctx.globalAlpha=.12;ctx.font='9px serif';ctx.fillStyle='#cc88ff';ctx.textAlign='center';
    ctx.fillText('呪',p.x+p.w/2,p.y+p.h+8);
    ctx.globalAlpha=1;
  });
}

// ============================================================
// SPAWN LOGIC & MAP SYSTEM
// ============================================================
function spawnEnemy(){
  const pool=['cursed','cursed','fast','strong'];
  const type=pool[Math.floor(Math.random()*pool.length)];
  const right=Math.random()>.5;
  const e=new Enemy(right?canvas.width+30:-90,type,G.wave);
  e.dir=right?-1:1;
  e.y=canvas.height-80-e.h;
  G.enemies.push(e);
  G.waveLeft--;
}

function startBoss(){
  G.bossActive=true;
  G.boss=new Boss(G.wave);
  G.boss.y=canvas.height-80-G.boss.h;
  G.waveText='👺 BOSS APPEARS!';
  G.waveSubText='Ryomen Sukuna — The King of Curses!';
  G.waveAnnounce=90;
  shake(12,30);
}

function nextWave(){
  G.waveLeft=G.waveKillBase+G.wave*3;
  G.spawnInterval=Math.max(55,110-G.wave*10);
  G.waveText=`WAVE ${G.wave}`;
  G.waveSubText=`${G.waveLeft} Cursed Spirits incoming!`;
  G.waveAnnounce=90;
  updateHUD();
}

// ============================================================
// CORE ENGINE ENGINE LOOP
// ============================================================
function gameLoop(){
  if(G.state!=='playing') return;
  if(G.paused){
    ctx.fillStyle='rgba(3,2,10,.75)';ctx.fillRect(0,0,canvas.width,canvas.height);
    ctx.font='bold 36px "Cinzel Decorative",serif';
    ctx.fillStyle='#f0c040';ctx.textAlign='center';ctx.textBaseline='middle';
    ctx.fillText('PAUSED',canvas.width/2,canvas.height/2-20);
    ctx.font='12px "Orbitron",sans-serif';
    ctx.fillStyle='#7a7490';
    ctx.fillText('Press P to Resume',canvas.width/2,canvas.height/2+30);
    G.animFrameId=requestAnimationFrame(gameLoop);
    return;
  }

  G.frameCount++;

  if(shakeTmr>0){shakeX=rnd(-shakeAmp,shakeAmp);shakeY=rnd(-shakeAmp,shakeAmp);shakeTmr--;}
  else{shakeX=0;shakeY=0;}

  ctx.save();ctx.translate(shakeX,shakeY);

  drawBg();

  if(!G.bossActive){
    G.spawnTimer++;
    if(G.spawnTimer>=G.spawnInterval&&G.waveLeft>0){
      spawnEnemy(); G.spawnTimer=0;
    }
    if(G.waveLeft<=0&&G.enemies.every(e=>e.dead||e.dying)){
      startBoss();
    }
  }

  G.player.update();
  G.enemies.forEach(e=>{if(!e.dead)e.update(G.player);});
  G.enemies=G.enemies.filter(e=>!e.dead);

  if(G.boss&&!G.boss.dead) G.boss.update(G.player);
  if(G.boss&&G.boss.dead){
    G.boss=null; G.bossActive=false;
    G.wave++;
    if(G.wave>G.maxWave){endGame(true);return;}
    nextWave();
  }

  G.projectiles.forEach(p=>p.update(G.player));
  G.projectiles=G.projectiles.filter(p=>!p.dead);

  G.particles.forEach(pt=>{
    if(pt.isDmg){pt.x+=pt.vx;pt.y+=pt.vy;pt.life--; if(pt.life<=0)pt.dead=true;}
    else{pt.x+=pt.vx;pt.y+=pt.vy;pt.vy+=0.25;pt.vx*=.93;pt.life--;if(pt.life<=0)pt.dead=true;}
  });
  G.particles=G.particles.filter(p=>!p.dead);

  G.projectiles.forEach(p=>p.draw());
  G.enemies.forEach(e=>e.draw());
  if(G.boss)G.boss.draw();

  G.particles.forEach(pt=>{
    if(pt.isDmg){
      ctx.globalAlpha=pt.life/pt.ml;
      ctx.font=`bold ${pt.crit?18:13}px "Orbitron",sans-serif`;
      ctx.fillStyle=pt.crit?'#f0c040':'#ff5566';
      ctx.textAlign='center';ctx.textBaseline='middle';
      ctx.shadowColor=pt.crit?'#f0c040':'#ff5566';ctx.shadowBlur=pt.crit?12:0;
      ctx.fillText((pt.crit?'✦ ':'-')+Math.ceil(pt.dmg),pt.x,pt.y);
      ctx.shadowBlur=0;ctx.globalAlpha=1;
    } else {
      ctx.globalAlpha=pt.life/pt.ml;
      ctx.fillStyle=pt.c;
      ctx.shadowColor=pt.c;ctx.shadowBlur=6;
      ctx.beginPath();ctx.arc(pt.x,pt.y,pt.s*(pt.life/pt.ml),0,Math.PI*2);ctx.fill();
      ctx.shadowBlur=0;ctx.globalAlpha=1;
    }
  });

  G.player.draw();

  if(G.waveAnnounce>0){
    const fade=G.waveAnnounce>70?(90-G.waveAnnounce)/20:G.waveAnnounce/35;
    ctx.globalAlpha=Math.min(1,fade);
    ctx.font='bold 44px "Cinzel Decorative",serif';
    ctx.fillStyle='#f0c040';ctx.textAlign='center';ctx.textBaseline='middle';
    ctx.shadowColor='#f0c040';ctx.shadowBlur=25;
    ctx.fillText(G.waveText,canvas.width/2,canvas.height/2-35);
    ctx.font='bold 18px "Orbitron",sans-serif';
    ctx.fillStyle='#ede8f5';ctx.shadowBlur=0;
    ctx.fillText(G.waveSubText,canvas.width/2,canvas.height/2+20);
    ctx.globalAlpha=1;ctx.shadowBlur=0;
    G.waveAnnounce--;
  }

  ctx.restore();

  if(G.player.hp<=0){endGame(false);return;}
  G.animFrameId=requestAnimationFrame(gameLoop);
}

// ============================================================
// GAME LIFECYCLE CONTROLLERS
// ============================================================
async function endGame(won){
  G.state=won?'win':'over';
  cancelAnimationFrame(G.animFrameId);

  const sc=document.getElementById('endScreen');
  document.getElementById('endIcon').textContent=won?'🏆':'💀';
  document.getElementById('endTitle').textContent=won?'VICTORY!':'GAME OVER';
  document.getElementById('endTitle').style.color=won?'#f0c040':'#cc2233';
  document.getElementById('finalScore').textContent=G.score.toLocaleString();
  document.getElementById('finalKills').textContent=G.kills;
  document.getElementById('finalWave').textContent=G.wave;
  document.getElementById('finalCombo').textContent=G.maxCombo;
  sc.classList.add('show');

  <?php if(isLoggedIn()):?>
  document.getElementById('saveStatus').textContent='Menyimpan skor...';
  try{
    const r=await fetch('index.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`action=save_score&score=${G.score}&enemies=${G.kills}&character=${encodeURIComponent(G.selectedChar.name)}`});
    const d=await r.json();
    document.getElementById('saveStatus').textContent=d.success?'✓ Skor tersimpan ke leaderboard!':'⚠ Gagal menyimpan skor.';
  }catch(e){document.getElementById('saveStatus').textContent='⚠ Koneksi error.';}
  <?php else:?>
  document.getElementById('saveStatus').textContent='⚠ Login untuk menyimpan skor ke leaderboard.';
  <?php endif;?>
}

function restartGame(){
  document.getElementById('endScreen').classList.remove('show');
  initGame(G.selectedChar);
}

function goMenu(){
  document.getElementById('endScreen').classList.remove('show');
  document.getElementById('hud').style.display='none';
  document.getElementById('canvasWrap').style.display='none';
  document.getElementById('mobileCtrl').style.display='none';
  document.getElementById('skillBar').style.display='none';
  document.getElementById('pauseHint').style.display='none';
  document.getElementById('selectScreen').style.display='flex';
  G.state='select';
}

// ============================================================
// CHARACTER SELECT REGISTRATION
// ============================================================
let currentChar=null;

function selectChar(el){
  document.querySelectorAll('.char-card').forEach(c=>{c.classList.remove('selected');c.querySelector('.sel-badge')?.remove();});
  el.classList.add('selected');
  const badge=document.createElement('div');badge.className='sel-badge';badge.textContent='SELECTED';el.appendChild(badge);
  const name=el.dataset.char;
  const cmap={'Megumi Fushiguro':'megumi','Satoru Gojo':'gojo','Yuji Itadori':'yuji','Nobara Kugisaki':'nobara','Kento Nanami':'nanami','Ryomen Sukuna':'sukuna'};
  currentChar={
    name,charKey:cmap[name]||'yuji',
    atk:+el.dataset.atk,def:+el.dataset.def,spd:+el.dataset.spd,
    emoji:el.dataset.emoji,
    skill1:el.dataset.skill1,skill2:el.dataset.skill2
  };
  document.getElementById('startBtn').disabled=false;
}

window.addEventListener('DOMContentLoaded',()=>{
  const pre=document.querySelector('.char-card.selected');
  if(pre){
    const name=pre.dataset.char;
    const cmap={'Megumi Fushiguro':'megumi','Satoru Gojo':'gojo','Yuji Itadori':'yuji'};
    currentChar={name,charKey:cmap[name]||'yuji',atk:+pre.dataset.atk,def:+pre.dataset.def,spd:+pre.dataset.spd,emoji:pre.dataset.emoji,skill1:pre.dataset.skill1,skill2:pre.dataset.skill2};
  }
});

function startGame(){
  if(!currentChar)return;
  document.getElementById('selectScreen').style.display='none';
  document.getElementById('hud').style.display='flex';
  document.getElementById('canvasWrap').style.display='block';
  document.getElementById('mobileCtrl').style.display='flex';
  document.getElementById('skillBar').style.display='flex';
  document.getElementById('pauseHint').style.display='block';
  resize();
  initBg();
  initGame(currentChar);
}

function initGame(char){
  cancelAnimationFrame(G.animFrameId);
  G=freshState(char);
  initPlatforms();
  const p=new Player(char);
  p.y=canvas.height-80-p.h;
  G.player=p;
  G.selectedChar=char;

  document.getElementById('hudEmoji').textContent=char.emoji;
  document.getElementById('hudName').textContent=char.name.toUpperCase();
  document.getElementById('skill1Icon').textContent='⚡';
  document.getElementById('skill2Icon').textContent='🔥';
  document.getElementById('skill3Icon').textContent='💥';

  G.waveText='WAVE 1';
  G.waveSubText='10 Cursed Spirits incoming!';
  G.waveAnnounce=90;

  updateHUD(); updateSkillBar();
  G.animFrameId=requestAnimationFrame(gameLoop);
}
</script>
</body>
</html>