<?php
require_once '../includes/config.php';

// Handle save score via AJAX
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

// Get playable characters
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
:root{--black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--red:#cc2233;--green:#00cc66;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--nav-h:72px;}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;overflow:hidden;height:100vh;}
::-webkit-scrollbar{width:6px;} ::-webkit-scrollbar-track{background:#08060f;} ::-webkit-scrollbar-thumb{background:#3a0d7a;}

/* NAVBAR */
.navbar{position:fixed;top:0;left:0;right:0;height:var(--nav-h);z-index:200;display:flex;align-items:center;padding:0 24px;background:rgba(3,2,10,.95);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);}
.nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none;flex:1;}
.logo-symbol{font-size:1.5rem;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.logo-text{font-family:'Cinzel Decorative',serif;font-size:.9rem;color:var(--text);}
.nav-links{display:flex;align-items:center;gap:4px;list-style:none;}
.nav-links a{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);text-decoration:none;padding:6px 12px;border-radius:2px;transition:all .3s;text-transform:uppercase;}
.nav-links a:hover,.nav-links a.active{color:var(--text);background:rgba(107,33,232,.15);}
.nav-right{display:flex;gap:8px;align-items:center;margin-left:16px;}
.btn-back{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;padding:6px 16px;border-radius:2px;border:1px solid var(--border);color:var(--text-muted);background:transparent;text-decoration:none;transition:all .3s;}
.btn-back:hover{border-color:var(--purple-glow);color:var(--purple-glow);}

/* GAME CONTAINER */
.game-container{position:fixed;inset:0;top:var(--nav-h);display:flex;flex-direction:column;}

/* CHARACTER SELECT SCREEN */
.select-screen{position:absolute;inset:0;background:linear-gradient(180deg,#03020a,#080520);display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:100;padding:20px;}
.select-title{font-family:'Cinzel Decorative',serif;font-size:clamp(1.5rem,4vw,2.5rem);text-align:center;margin-bottom:8px;}
.select-title span{background:linear-gradient(135deg,var(--gold),var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.select-sub{font-family:'Orbitron',sans-serif;font-size:.65rem;letter-spacing:3px;color:var(--text-muted);text-align:center;margin-bottom:40px;}
.char-grid{display:flex;gap:16px;flex-wrap:wrap;justify-content:center;max-width:900px;}
.char-select-card{width:160px;background:rgba(10,8,20,.9);border:1px solid var(--border);border-radius:4px;padding:20px;cursor:pointer;transition:all .3s;text-align:center;position:relative;}
.char-select-card:hover,.char-select-card.selected{border-color:var(--purple-glow);transform:translateY(-6px);box-shadow:0 15px 40px rgba(107,33,232,.3);}
.char-select-card.selected{border-color:var(--gold);box-shadow:0 15px 40px rgba(240,192,64,.2);}
.char-select-emoji{font-size:3.5rem;margin-bottom:10px;display:block;filter:drop-shadow(0 4px 15px rgba(0,0,0,.5));}
.char-select-name{font-family:'Cinzel Decorative',serif;font-size:.75rem;color:var(--text);margin-bottom:6px;line-height:1.3;}
.char-select-stats{display:flex;flex-direction:column;gap:3px;margin-top:10px;}
.cstat{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:1px;color:var(--text-muted);display:flex;justify-content:space-between;}
.cstat span:last-child{color:var(--purple-glow);}
.selected-badge{position:absolute;top:8px;right:8px;background:var(--gold);color:var(--black);font-family:'Orbitron',sans-serif;font-size:.45rem;padding:2px 6px;border-radius:1px;letter-spacing:1px;}

.btn-start{margin-top:32px;padding:16px 50px;background:linear-gradient(135deg,var(--purple),#8b30ff);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.85rem;font-weight:700;letter-spacing:4px;cursor:pointer;transition:all .3s;position:relative;overflow:hidden;}
.btn-start::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.15),transparent);transition:left .5s;}
.btn-start:hover::before{left:100%;}
.btn-start:hover{box-shadow:0 0 40px rgba(107,33,232,.6);transform:translateY(-2px);}
.btn-start:disabled{opacity:.5;cursor:not-allowed;transform:none;}

/* GAME HUD */
.game-hud{position:absolute;top:0;left:0;right:0;height:60px;background:rgba(3,2,10,.9);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 20px;gap:24px;z-index:50;}
.hud-char{display:flex;align-items:center;gap:8px;}
.hud-char-emoji{font-size:1.8rem;}
.hud-char-name{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:1px;color:var(--gold);}
.hud-stat{display:flex;flex-direction:column;gap:4px;min-width:120px;}
.hud-stat-label{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:2px;color:var(--text-muted);}
.hud-bar-track{height:8px;background:rgba(255,255,255,.06);border-radius:4px;overflow:hidden;}
.hud-bar-fill{height:100%;border-radius:4px;transition:width .3s;}
.hp-fill{background:linear-gradient(90deg,#cc2233,#ff3355);}
.ce-fill{background:linear-gradient(90deg,#6b21e8,#9d4dff);}
.hud-score{margin-left:auto;text-align:right;}
.hud-score-num{font-family:'Orbitron',sans-serif;font-size:1.2rem;font-weight:900;color:var(--gold);}
.hud-score-label{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:2px;color:var(--text-muted);}
.hud-level{font-family:'Orbitron',sans-serif;font-size:.7rem;color:var(--purple-glow);letter-spacing:2px;}
.hud-enemies{font-family:'Orbitron',sans-serif;font-size:.65rem;color:var(--text-muted);}

/* CANVAS */
#gameCanvas{display:block;cursor:none;}
.canvas-wrap{position:absolute;top:60px;left:0;right:0;bottom:0;overflow:hidden;}

/* GAME OVER / WIN SCREEN */
.end-screen{position:absolute;inset:0;background:rgba(3,2,10,.92);display:none;flex-direction:column;align-items:center;justify-content:center;z-index:150;backdrop-filter:blur(10px);}
.end-screen.show{display:flex;}
.end-icon{font-size:5rem;margin-bottom:16px;animation:endBounce .5s ease-out;}
@keyframes endBounce{0%{transform:scale(0);}60%{transform:scale(1.2);}100%{transform:scale(1);}}
.end-title{font-family:'Cinzel Decorative',serif;font-size:2.5rem;margin-bottom:8px;}
.end-score{font-family:'Orbitron',sans-serif;font-size:1.5rem;color:var(--gold);margin-bottom:24px;}
.end-stats{display:flex;gap:32px;margin-bottom:32px;}
.end-stat{text-align:center;}
.end-stat-num{font-family:'Orbitron',sans-serif;font-size:1.3rem;font-weight:900;color:var(--purple-glow);}
.end-stat-label{font-size:.8rem;color:var(--text-muted);}
.end-btns{display:flex;gap:12px;}
.btn-again{padding:14px 36px;background:linear-gradient(135deg,var(--purple),#8b30ff);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.75rem;letter-spacing:3px;cursor:pointer;transition:all .3s;}
.btn-again:hover{box-shadow:0 0 30px rgba(107,33,232,.5);}
.btn-menu{padding:14px 36px;background:transparent;border:1px solid var(--border);border-radius:2px;color:var(--text-muted);font-family:'Orbitron',sans-serif;font-size:.75rem;letter-spacing:3px;cursor:pointer;transition:all .3s;}
.btn-menu:hover{border-color:var(--purple-glow);color:var(--purple-glow);}

/* CONTROLS */
.controls-hint{position:absolute;bottom:12px;left:50%;transform:translateX(-50%);font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--text-muted);z-index:50;text-align:center;pointer-events:none;}

/* MOBILE CONTROLS */
.mobile-controls{position:absolute;bottom:60px;left:0;right:0;display:none;justify-content:space-between;padding:0 20px;z-index:50;}
.mobile-btn{width:64px;height:64px;background:rgba(107,33,232,.2);border:1px solid rgba(107,33,232,.4);border-radius:50%;color:white;font-size:1.4rem;cursor:pointer;display:flex;align-items:center;justify-content:center;user-select:none;-webkit-user-select:none;transition:all .2s;}
.mobile-btn:active{background:rgba(107,33,232,.5);transform:scale(.95);}
.mobile-right{display:flex;gap:10px;}
@media(max-width:700px){.mobile-controls{display:flex;}.controls-hint{display:none;}}

.save-status{font-family:'Orbitron',sans-serif;font-size:.65rem;letter-spacing:2px;color:var(--green);margin-top:8px;min-height:20px;}
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
        <span style="font-family:'Orbitron',sans-serif;font-size:.6rem;color:var(--gold);">⚡ <?=htmlspecialchars($_SESSION['username'] ?? '')?></span>
        <?php endif;?>
        <a href="../index.php" class="btn-back">← Menu</a>
    </div>
</nav>

<div class="game-container">

    <!-- CHARACTER SELECT -->
    <div class="select-screen" id="selectScreen">
        <div class="select-title"><span>Cursed Spirit Slayer</span></div>
        <div class="select-sub">PILIH KARAKTER UNTUK BERTARUNG</div>
        
        <div class="char-grid">
            <?php
            $emojis = ['👊','🌑','🔨','♾️'];
            foreach ($playable as $i => $p):
                $em = $emojis[$i % count($emojis)];
                $preSelected = ($preselect === $p['name']) ? 'selected' : '';
            ?>
            <div class="char-select-card <?=$preSelected?>" 
                 data-char="<?=htmlspecialchars($p['name'])?>" 
                 data-atk="<?=$p['attack_power']?>" 
                 data-def="<?=$p['defense_power']?>" 
                 data-spd="<?=$p['speed_power']?>"
                 data-emoji="<?=$em?>"
                 onclick="selectChar(this)">
                <?php if($preSelected):?><div class="selected-badge">SELECTED</div><?php endif;?>
                <span class="char-select-emoji"><?=$em?></span>
                <div class="char-select-name"><?=htmlspecialchars($p['name'])?></div>
                <div class="char-select-stats">
                    <div class="cstat"><span>ATK</span><span><?=$p['attack_power']?></span></div>
                    <div class="cstat"><span>DEF</span><span><?=$p['defense_power']?></span></div>
                    <div class="cstat"><span>SPD</span><span><?=$p['speed_power']?></span></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <button class="btn-start" id="startBtn" onclick="startGame()" <?=empty($preselect)?'disabled':''?>>
            ⚔ MULAI PERTARUNGAN
        </button>
        <div style="margin-top:16px;font-family:'Orbitron',sans-serif;font-size:.6rem;color:var(--text-muted);text-align:center;letter-spacing:2px;">
            <?php if(!isLoggedIn()):?>
            ⚠ LOGIN untuk menyimpan skor ke leaderboard
            <?php else:?>
            ✓ Skor akan otomatis tersimpan ke leaderboard
            <?php endif;?>
        </div>
    </div>

    <!-- HUD -->
    <div class="game-hud" id="gameHud" style="display:none;">
        <div class="hud-char">
            <span class="hud-char-emoji" id="hudEmoji">👊</span>
            <span class="hud-char-name" id="hudName">-</span>
        </div>
        <div class="hud-stat">
            <span class="hud-stat-label">HP</span>
            <div class="hud-bar-track"><div class="hud-bar-fill hp-fill" id="hpBar" style="width:100%"></div></div>
        </div>
        <div class="hud-stat">
            <span class="hud-stat-label">CURSED ENERGY</span>
            <div class="hud-bar-track"><div class="hud-bar-fill ce-fill" id="ceBar" style="width:100%"></div></div>
        </div>
        <div class="hud-enemies">
            🗡 <span id="killCount">0</span> defeated
        </div>
        <div class="hud-level">
            WAVE <span id="waveNum">1</span>
        </div>
        <div class="hud-score">
            <div class="hud-score-num" id="scoreNum">0</div>
            <div class="hud-score-label">SCORE</div>
        </div>
    </div>

    <div class="canvas-wrap" id="canvasWrap" style="display:none;">
        <canvas id="gameCanvas"></canvas>
    </div>

    <!-- MOBILE CONTROLS -->
    <div class="mobile-controls" id="mobileControls" style="display:none;">
        <div class="mobile-btn" id="btnLeft" ontouchstart="mKeys.left=true" ontouchend="mKeys.left=false">←</div>
        <div class="mobile-right">
            <div class="mobile-btn" id="btnSpecial" ontouchstart="mKeys.special=true" ontouchend="mKeys.special=false">🔮</div>
            <div class="mobile-btn" id="btnAttack" ontouchstart="mKeys.attack=true" ontouchend="mKeys.attack=false">⚔</div>
            <div class="mobile-btn" id="btnRight" ontouchstart="mKeys.right=true" ontouchend="mKeys.right=false">→</div>
        </div>
    </div>

    <div class="controls-hint">ARROW / WASD = Move • Z/J = Attack • X/K = Special Skill • Space = Jump • ESC = Quit</div>

    <!-- GAME OVER -->
    <div class="end-screen" id="endScreen">
        <div class="end-icon" id="endIcon">💀</div>
        <div class="end-title" id="endTitle" style="color:#cc2233">GAME OVER</div>
        <div class="end-score">Score: <span id="finalScore">0</span></div>
        <div class="end-stats">
            <div class="end-stat">
                <div class="end-stat-num" id="finalKills">0</div>
                <div class="end-stat-label">Enemies Slain</div>
            </div>
            <div class="end-stat">
                <div class="end-stat-num" id="finalWave">1</div>
                <div class="end-stat-label">Wave Reached</div>
            </div>
        </div>
        <div class="save-status" id="saveStatus"></div>
        <div class="end-btns">
            <button class="btn-again" onclick="restartGame()">↺ MAIN LAGI</button>
            <button class="btn-menu" onclick="goMenu()">☰ MENU</button>
        </div>
    </div>
</div>

<script>
// ============================================
// CURSED SPIRIT SLAYER - Game Engine
// ============================================

const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

// Game state
let G = {
    state: 'select', // select | playing | over | win
    score: 0, kills: 0, wave: 1,
    maxWave: 5,
    player: null,
    enemies: [], projectiles: [], particles: [],
    bossActive: false, boss: null,
    spawnTimer: 0, spawnInterval: 120,
    waveEnemiesLeft: 0, waveKillTarget: 8,
    selectedChar: null,
    animFrame: null
};

// Keys
const keys = {};
const mKeys = { left:false, right:false, attack:false, special:false };
window.addEventListener('keydown', e => { keys[e.code] = true; });
window.addEventListener('keyup', e => { keys[e.code] = false; });

// Canvas resize
function resizeCanvas() {
    const wrap = document.getElementById('canvasWrap');
    canvas.width = wrap.clientWidth;
    canvas.height = wrap.clientHeight;
}
window.addEventListener('resize', resizeCanvas);

// ============================================
// CLASSES
// ============================================

class Player {
    constructor(char) {
        this.x = 120; this.y = 0;
        this.w = 48; this.h = 64;
        this.vx = 0; this.vy = 0;
        this.hp = 100; this.maxHp = 100;
        this.ce = 100; this.maxCe = 100;
        this.grounded = false;
        this.dir = 1; // 1=right, -1=left
        this.attackTimer = 0;
        this.specialTimer = 0;
        this.iframeTimer = 0;
        this.isAttacking = false;
        this.isSpecial = false;
        this.char = char;
        this.emoji = char.emoji;
        this.atk = char.atk;
        this.spd = (char.spd / 100) * 6 + 2; // 2-8
        this.jumpPower = -16;
        this.ceRegen = 0.15;
        this.attackHitbox = { x:0, y:0, w:0, h:0, active:false };
        this.combo = 0; this.comboTimer = 0;
        this.animFrame = 0; this.animTimer = 0;
        this.trail = [];
    }
    
    get ground() { return canvas.height - 80; }
    
    update() {
        // Movement
        const moving = (keys['ArrowLeft']||keys['KeyA']||mKeys.left) || (keys['ArrowRight']||keys['KeyD']||mKeys.right);
        
        if ((keys['ArrowLeft']||keys['KeyA']||mKeys.left)) {
            this.vx = -this.spd; this.dir = -1;
        } else if ((keys['ArrowRight']||keys['KeyD']||mKeys.right)) {
            this.vx = this.spd; this.dir = 1;
        } else {
            this.vx *= 0.75;
        }
        
        // Jump
        if ((keys['Space']||keys['ArrowUp']||keys['KeyW']) && this.grounded) {
            this.vy = this.jumpPower;
            this.grounded = false;
            spawnParticles(this.x + this.w/2, this.y + this.h, '#9d4dff', 5, 'jump');
        }
        
        // Attack
        if ((keys['KeyZ']||keys['KeyJ']||mKeys.attack) && this.attackTimer <= 0 && !this.isSpecial) {
            this.attack();
        }
        
        // Special
        if ((keys['KeyX']||keys['KeyK']||mKeys.special) && this.specialTimer <= 0 && this.ce >= 30) {
            this.special();
        }
        
        // Gravity
        this.vy += 0.8;
        this.x += this.vx;
        this.y += this.vy;
        
        // Ground collision
        if (this.y + this.h >= this.ground) {
            this.y = this.ground - this.h;
            this.vy = 0; this.grounded = true;
        }
        
        // Wall bounds
        this.x = Math.max(0, Math.min(canvas.width - this.w, this.x));
        
        // Timers
        if (this.attackTimer > 0) this.attackTimer--;
        if (this.specialTimer > 0) this.specialTimer--;
        if (this.iframeTimer > 0) this.iframeTimer--;
        if (this.comboTimer > 0) { this.comboTimer--; } else { this.combo = 0; }
        if (this.isAttacking && this.attackTimer < 10) this.isAttacking = false;
        if (this.isSpecial && this.specialTimer < 50) this.isSpecial = false;
        
        // CE regen
        this.ce = Math.min(this.maxCe, this.ce + this.ceRegen);
        
        // Trail
        if (Math.abs(this.vx) > 2) {
            this.trail.push({x: this.x + this.w/2, y: this.y + this.h/2, life: 12});
        }
        this.trail = this.trail.filter(t => { t.life--; return t.life > 0; });
        
        // Anim
        this.animTimer++;
        if (this.animTimer % 8 === 0) this.animFrame = (this.animFrame + 1) % 4;
    }
    
    attack() {
        this.isAttacking = true;
        this.combo = Math.min(this.combo + 1, 3);
        this.comboTimer = 40;
        this.attackTimer = 18;
        
        const atkX = this.dir === 1 ? this.x + this.w : this.x - 60;
        this.attackHitbox = { x:atkX, y:this.y+10, w:60, h:44, active:true, timer:12 };
        
        const dmg = this.atk * (0.8 + this.combo * 0.2);
        G.enemies.forEach(e => {
            if (rectsOverlap(this.attackHitbox, e) && !e.dead) {
                hitEnemy(e, dmg, this.dir);
            }
        });
        if (G.boss && rectsOverlap(this.attackHitbox, G.boss) && !G.boss.dead) {
            hitEnemy(G.boss, dmg * 0.6, this.dir);
        }
        
        spawnParticles(atkX + 30, this.y + 30, '#ff5566', 6, 'slash');
        if (this.attackHitbox.timer > 0) this.attackHitbox.timer--;
        else this.attackHitbox.active = false;
    }
    
    special() {
        this.isSpecial = true;
        this.ce -= 30;
        this.specialTimer = 80;
        
        // Fire projectile
        G.projectiles.push(new Projectile(
            this.x + this.w/2, this.y + this.h/2 - 10,
            this.dir * 12, 0,
            this.atk * 1.5, 'player', '#9d4dff', 28
        ));
        
        spawnParticles(this.x + this.w/2, this.y + this.h/2, '#9d4dff', 15, 'burst');
        screenShake(3, 8);
    }
    
    takeDamage(dmg) {
        if (this.iframeTimer > 0) return;
        const actual = dmg * (1 - (this.char.def/100) * 0.4);
        this.hp = Math.max(0, this.hp - actual);
        this.iframeTimer = 30;
        spawnParticles(this.x + this.w/2, this.y + this.h/2, '#cc2233', 8, 'hurt');
        screenShake(4, 10);
        updateHUD();
    }
    
    draw() {
        const p = this;
        
        // Trail
        p.trail.forEach(t => {
            ctx.globalAlpha = t.life / 12 * 0.3;
            ctx.font = '36px serif';
            ctx.fillText(p.emoji, t.x - 20, t.y + 15);
        });
        ctx.globalAlpha = 1;
        
        // iframe flicker
        if (p.iframeTimer > 0 && Math.floor(p.iframeTimer / 4) % 2) return;
        
        // Shadow
        ctx.globalAlpha = 0.2;
        ctx.fillStyle = '#000';
        ctx.beginPath();
        ctx.ellipse(p.x + p.w/2, p.ground, p.w/2, 8, 0, 0, Math.PI*2);
        ctx.fill();
        ctx.globalAlpha = 1;
        
        // Glow when attacking
        if (p.isAttacking) {
            ctx.shadowColor = '#ff5566';
            ctx.shadowBlur = 20;
        } else if (p.isSpecial) {
            ctx.shadowColor = '#9d4dff';
            ctx.shadowBlur = 30;
        }
        
        // Character emoji
        ctx.save();
        ctx.translate(p.x + p.w/2, p.y + p.h/2);
        if (p.dir === -1) ctx.scale(-1, 1);
        
        const bob = Math.sin(p.animFrame * Math.PI / 2) * (p.grounded ? 2 : 0);
        ctx.font = `${52 + (p.isAttacking ? 6 : 0)}px serif`;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(p.emoji, 0, bob);
        ctx.restore();
        
        ctx.shadowBlur = 0;
        
        // Attack hitbox visual
        if (p.attackHitbox.active) {
            ctx.globalAlpha = 0.3;
            ctx.fillStyle = '#ff5566';
            ctx.fillRect(p.attackHitbox.x, p.attackHitbox.y, p.attackHitbox.w, p.attackHitbox.h);
            ctx.globalAlpha = 1;
        }
        
        // HP bar
        const bw = p.w + 10; const bh = 6;
        const bx = p.x - 5; const by = p.y - 14;
        ctx.fillStyle = 'rgba(0,0,0,0.6)'; ctx.fillRect(bx, by, bw, bh);
        ctx.fillStyle = p.hp > 50 ? '#00cc66' : p.hp > 25 ? '#f0c040' : '#cc2233';
        ctx.fillRect(bx, by, bw * (p.hp/p.maxHp), bh);
    }
}

class Enemy {
    constructor(x, type, wave) {
        this.type = type; // cursed | strong | fast
        this.x = x; this.y = 0;
        this.w = type === 'fast' ? 36 : 48;
        this.h = type === 'fast' ? 48 : 60;
        this.vy = 0; this.vx = 0;
        this.grounded = false;
        this.dir = -1;
        this.hp = type === 'strong' ? 80 + wave*20 : type === 'fast' ? 40 + wave*8 : 60 + wave*12;
        this.maxHp = this.hp;
        this.atk = type === 'strong' ? 15 + wave*3 : type === 'fast' ? 8 + wave : 10 + wave*2;
        this.spd = type === 'fast' ? 3.5 : type === 'strong' ? 1.5 : 2;
        this.attackTimer = 0;
        this.dead = false;
        this.dying = false; this.dyingTimer = 0;
        this.emoji = type === 'strong' ? '💀' : type === 'fast' ? '👻' : '🕷';
        this.aiTimer = Math.random() * 60;
        this.shootTimer = type === 'strong' ? 120 : 9999;
        this.color = type === 'strong' ? '#ff3355' : type === 'fast' ? '#44ccff' : '#aa44ff';
    }
    
    get ground() { return canvas.height - 80; }
    
    update(player) {
        if (this.dying) {
            this.dyingTimer++;
            this.vx *= 0.9;
            this.vy -= 0.3;
            if (this.dyingTimer > 25) this.dead = true;
            return;
        }
        
        // AI: chase player
        const dx = player.x - this.x;
        this.dir = dx > 0 ? 1 : -1;
        this.aiTimer++;
        
        // Move toward player if not close
        const dist = Math.abs(dx);
        if (dist > 60) {
            this.vx = this.dir * this.spd;
        } else {
            this.vx *= 0.8;
            // Attack
            if (this.attackTimer <= 0) {
                player.takeDamage(this.atk);
                this.attackTimer = 60;
            }
        }
        
        // Strong enemy shoots
        if (this.type === 'strong') {
            this.shootTimer--;
            if (this.shootTimer <= 0) {
                G.projectiles.push(new Projectile(
                    this.x + this.w/2, this.y + this.h/2,
                    this.dir * 6, -1, this.atk * 0.8, 'enemy', '#ff5533', 16
                ));
                this.shootTimer = 120;
            }
        }
        
        // Gravity
        this.vy += 0.8;
        this.x += this.vx; this.y += this.vy;
        
        if (this.y + this.h >= this.ground) {
            this.y = this.ground - this.h; this.vy = 0; this.grounded = true;
        }
        
        this.x = Math.max(0, Math.min(canvas.width - this.w, this.x));
        if (this.attackTimer > 0) this.attackTimer--;
    }
    
    draw() {
        if (this.dying) {
            ctx.globalAlpha = 1 - this.dyingTimer/25;
        }
        
        ctx.save();
        ctx.translate(this.x + this.w/2, this.y + this.h/2);
        if (this.dir === 1) ctx.scale(-1, 1);
        
        // Glow
        ctx.shadowColor = this.color;
        ctx.shadowBlur = 15;
        
        ctx.font = `${this.w * 0.9}px serif`;
        ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
        ctx.fillText(this.emoji, 0, 0);
        ctx.restore();
        
        ctx.shadowBlur = 0;
        ctx.globalAlpha = 1;
        
        if (this.dying) return;
        
        // HP bar
        const bw = this.w; const by = this.y - 10;
        ctx.fillStyle = 'rgba(0,0,0,0.6)'; ctx.fillRect(this.x, by, bw, 5);
        ctx.fillStyle = this.color;
        ctx.fillRect(this.x, by, bw * (this.hp/this.maxHp), 5);
    }
}

class Boss {
    constructor(wave) {
        this.w = 80; this.h = 96;
        this.x = canvas.width - 150; this.y = 0;
        this.vx = 0; this.vy = 0;
        this.grounded = false;
        this.dir = -1;
        this.hp = 300 + wave * 100;
        this.maxHp = this.hp;
        this.atk = 25 + wave * 5;
        this.spd = 1.8;
        this.dead = false; this.dying = false; this.dyingTimer = 0;
        this.emoji = wave >= 5 ? '👺' : '🦹';
        this.phase = 1;
        this.attackTimer = 0;
        this.shootTimer = 0;
        this.chargeTimer = 0;
        this.charging = false;
        this.rage = false;
        this.floatTimer = 0;
    }
    
    get ground() { return canvas.height - 80; }
    
    update(player) {
        if (this.dying) {
            this.dyingTimer++;
            this.vx *= 0.85;
            this.vy -= 0.5;
            if (this.dyingTimer > 40) this.dead = true;
            return;
        }
        
        this.floatTimer++;
        
        // Phase 2 at 50% HP
        if (this.hp < this.maxHp * 0.5 && !this.rage) {
            this.rage = true;
            this.spd = 3;
            spawnParticles(this.x + this.w/2, this.y + this.h/2, '#ff3355', 20, 'burst');
            screenShake(8, 20);
        }
        
        const dx = player.x - this.x;
        this.dir = dx > 0 ? 1 : -1;
        const dist = Math.abs(dx);
        
        // Charge attack
        this.chargeTimer--;
        if (this.chargeTimer <= 0 && !this.charging) {
            this.chargeTimer = this.rage ? 80 : 140;
            this.charging = true;
            this.vx = this.dir * 12;
        }
        
        if (this.charging) {
            if (Math.abs(this.vx) > 0.5) {
                this.vx *= 0.9;
            } else {
                this.charging = false;
            }
        } else if (dist > 80) {
            this.vx += (this.dir * this.spd - this.vx) * 0.1;
        } else {
            this.vx *= 0.8;
            if (this.attackTimer <= 0) {
                player.takeDamage(this.atk);
                this.attackTimer = this.rage ? 35 : 55;
            }
        }
        
        // Shoot projectiles
        this.shootTimer--;
        if (this.shootTimer <= 0) {
            const count = this.rage ? 3 : 1;
            for (let i = 0; i < count; i++) {
                const angle = (i - (count-1)/2) * 0.4;
                G.projectiles.push(new Projectile(
                    this.x + this.w/2, this.y + this.h/2,
                    Math.cos(angle) * this.dir * 7, Math.sin(angle) * 7 - 2,
                    this.atk * 0.7, 'enemy', '#ff3300', 20
                ));
            }
            this.shootTimer = this.rage ? 60 : 100;
        }
        
        this.vy += 0.8;
        this.x += this.vx; this.y += this.vy;
        
        // Float effect
        const floatY = Math.sin(this.floatTimer * 0.05) * 5;
        
        if (this.y + this.h >= this.ground) {
            this.y = this.ground - this.h; this.vy = 0; this.grounded = true;
        }
        
        this.x = Math.max(0, Math.min(canvas.width - this.w, this.x));
        if (this.attackTimer > 0) this.attackTimer--;
    }
    
    draw() {
        if (this.dying) ctx.globalAlpha = 1 - this.dyingTimer/40;
        
        const floatY = Math.sin(this.floatTimer * 0.05) * 5;
        
        ctx.save();
        ctx.translate(this.x + this.w/2, this.y + this.h/2 + floatY);
        if (this.dir === 1) ctx.scale(-1, 1);
        
        ctx.shadowColor = this.rage ? '#ff0000' : '#ff5533';
        ctx.shadowBlur = this.rage ? 40 : 25;
        
        ctx.font = `${this.w}px serif`;
        ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
        ctx.fillText(this.emoji, 0, 0);
        ctx.restore();
        
        ctx.shadowBlur = 0;
        ctx.globalAlpha = 1;
        
        if (this.dying) return;
        
        // Boss HP bar (top)
        const bw = canvas.width - 100; const bx = 50; const by = 8;
        ctx.fillStyle = 'rgba(0,0,0,0.8)'; ctx.fillRect(bx, by, bw, 14);
        const pct = this.hp / this.maxHp;
        ctx.fillStyle = pct > 0.5 ? '#cc2233' : '#ff0044';
        ctx.fillRect(bx, by, bw * pct, 14);
        ctx.strokeStyle = 'rgba(255,50,50,0.3)'; ctx.lineWidth = 1;
        ctx.strokeRect(bx, by, bw, 14);
        
        // Boss label
        ctx.font = 'bold 10px "Orbitron",sans-serif';
        ctx.fillStyle = '#fff'; ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
        ctx.fillText(`BOSS: ${Math.ceil(this.hp)} / ${this.maxHp}${this.rage ? ' [RAGE]' : ''}`, canvas.width/2, by + 7);
        
        // Boss name tag
        ctx.font = '10px "Orbitron",sans-serif';
        ctx.fillStyle = '#ff5533'; ctx.textAlign = 'center'; ctx.textBaseline = 'bottom';
        ctx.fillText(this.rage ? '💢 RYOMEN SUKUNA' : '👹 CURSED BOSS', this.x + this.w/2, this.y - 8);
    }
}

class Projectile {
    constructor(x, y, vx, vy, dmg, owner, color, size) {
        this.x=x; this.y=y; this.vx=vx; this.vy=vy;
        this.dmg=dmg; this.owner=owner; this.color=color;
        this.size=size||12; this.dead=false; this.life=0;
    }
    update(player) {
        this.x += this.vx; this.y += this.vy;
        this.vy += 0.1; this.life++;
        if (this.x<-50||this.x>canvas.width+50||this.y>canvas.height||this.life>120) { this.dead=true; return; }
        
        if (this.owner === 'player') {
            G.enemies.forEach(e => {
                if (!e.dead && !e.dying && rectsOverlap({x:this.x-this.size/2,y:this.y-this.size/2,w:this.size,h:this.size}, e)) {
                    hitEnemy(e, this.dmg, this.vx > 0 ? 1 : -1);
                    this.dead = true;
                }
            });
            if (G.boss && !G.boss.dead && !G.boss.dying && rectsOverlap({x:this.x-this.size/2,y:this.y-this.size/2,w:this.size,h:this.size}, G.boss)) {
                hitEnemy(G.boss, this.dmg * 0.5, this.vx > 0 ? 1 : -1);
                this.dead = true;
            }
        } else {
            if (rectsOverlap({x:this.x-this.size/2,y:this.y-this.size/2,w:this.size,h:this.size}, player)) {
                player.takeDamage(this.dmg);
                this.dead = true;
            }
        }
    }
    draw() {
        if (this.dead) return;
        ctx.save();
        ctx.shadowColor = this.color; ctx.shadowBlur = 15;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size/2, 0, Math.PI*2);
        ctx.fillStyle = this.color;
        ctx.globalAlpha = 0.9;
        ctx.fill();
        // Core
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size/4, 0, Math.PI*2);
        ctx.fillStyle = '#fff'; ctx.globalAlpha = 0.8;
        ctx.fill();
        ctx.restore();
    }
}

class Particle {
    constructor(x, y, vx, vy, color, life, size) {
        this.x=x; this.y=y; this.vx=vx; this.vy=vy;
        this.color=color; this.maxLife=life; this.life=life;
        this.size=size||4; this.dead=false;
    }
    update() {
        this.x+=this.vx; this.y+=this.vy;
        this.vy+=0.3; this.vx*=0.95;
        this.life--; if(this.life<=0) this.dead=true;
    }
    draw() {
        const alpha = this.life/this.maxLife;
        ctx.globalAlpha = alpha;
        ctx.fillStyle = this.color;
        ctx.shadowColor = this.color; ctx.shadowBlur = 8;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size * alpha, 0, Math.PI*2);
        ctx.fill();
        ctx.shadowBlur=0;
        ctx.globalAlpha=1;
    }
}

// ============================================
// HELPERS
// ============================================

function rectsOverlap(a, b) {
    return a.x < b.x+b.w && a.x+a.w > b.x && a.y < b.y+b.h && a.y+a.h > b.y;
}

function spawnParticles(x, y, color, count, type) {
    for (let i = 0; i < count; i++) {
        const angle = (Math.PI * 2 / count) * i + Math.random() * 0.5;
        const speed = type === 'burst' ? 4 + Math.random()*4 : type === 'slash' ? 3+Math.random()*3 : 2+Math.random()*2;
        G.particles.push(new Particle(x, y, Math.cos(angle)*speed, Math.sin(angle)*speed - (type==='jump'?3:0), color, 20+Math.random()*15, 2+Math.random()*3));
    }
}

let shakeX=0, shakeY=0, shakeTimer=0, shakeIntensity=0;
function screenShake(intensity, duration) {
    shakeIntensity=intensity; shakeTimer=duration;
}

function hitEnemy(e, dmg, dir) {
    if (e.dying || e.dead) return;
    const actual = dmg * (0.8 + Math.random()*0.4);
    e.hp -= actual;
    
    // Damage number
    G.particles.push({ x:e.x+e.w/2, y:e.y, vx:(Math.random()-.5)*2, vy:-3, life:35, maxLife:35, dead:false, isDmg:true, dmg:Math.ceil(actual) });
    spawnParticles(e.x+e.w/2, e.y+e.h/2, '#ff5566', 4, 'hit');
    
    if (e.hp <= 0) {
        e.dying = true;
        e.vx = dir * 3;
        e.vy = -5;
        
        const isBoss = e === G.boss;
        const pts = isBoss ? 500 + G.wave*200 : (e.type==='strong'?150:e.type==='fast'?80:50) * G.wave;
        G.score += pts;
        G.kills++;
        
        spawnParticles(e.x+e.w/2, e.y+e.h/2, isBoss?'#ff3300':'#9d4dff', 20, 'burst');
        if (isBoss) screenShake(12, 30);
        
        updateHUD();
    }
}

function updateHUD() {
    if (!G.player) return;
    const p = G.player;
    document.getElementById('hpBar').style.width = (p.hp/p.maxHp*100)+'%';
    document.getElementById('ceBar').style.width = (p.ce/p.maxCe*100)+'%';
    document.getElementById('scoreNum').textContent = G.score.toLocaleString();
    document.getElementById('killCount').textContent = G.kills;
    document.getElementById('waveNum').textContent = G.wave;
}

// ============================================
// GAME LOOP
// ============================================

let bgStars = [];
function initBg() {
    bgStars = [];
    for (let i = 0; i < 100; i++) {
        bgStars.push({x: Math.random()*canvas.width, y: Math.random()*(canvas.height*0.7), r: Math.random()*2, speed: 0.2+Math.random()*0.5, alpha: 0.2+Math.random()*0.6});
    }
}

function drawBg() {
    // Sky
    const grad = ctx.createLinearGradient(0,0,0,canvas.height);
    grad.addColorStop(0, '#03020a');
    grad.addColorStop(0.6, '#080520');
    grad.addColorStop(1, '#0a0010');
    ctx.fillStyle = grad;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Stars
    bgStars.forEach(s => {
        s.x -= s.speed;
        if (s.x < 0) s.x = canvas.width;
        ctx.globalAlpha = s.alpha * (0.5 + Math.sin(Date.now()*0.001 + s.x)*0.5);
        ctx.fillStyle = '#9d4dff';
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, Math.PI*2);
        ctx.fill();
    });
    ctx.globalAlpha = 1;
    
    // Ground
    const gGrad = ctx.createLinearGradient(0, canvas.height-80, 0, canvas.height);
    gGrad.addColorStop(0, '#1a0535');
    gGrad.addColorStop(1, '#0a0220');
    ctx.fillStyle = gGrad;
    ctx.fillRect(0, canvas.height-80, canvas.width, 80);
    
    // Ground line glow
    ctx.shadowColor = '#6b21e8';
    ctx.shadowBlur = 15;
    ctx.strokeStyle = '#3a0d7a';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(0, canvas.height-80);
    ctx.lineTo(canvas.width, canvas.height-80);
    ctx.stroke();
    ctx.shadowBlur = 0;
    
    // Curse marks on ground
    ctx.globalAlpha = 0.08;
    ctx.font = '32px serif';
    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
    for (let i = 0; i < 5; i++) {
        ctx.fillStyle = '#9d4dff';
        ctx.fillText('呪', canvas.width/5*i + canvas.width/10, canvas.height - 40);
    }
    ctx.globalAlpha = 1;
}

function spawnEnemy() {
    const types = ['cursed','cursed','fast','strong'];
    const type = G.bossActive ? null : types[Math.floor(Math.random()*types.length)];
    if (!type) return;
    
    const spawnRight = Math.random() > 0.5;
    const x = spawnRight ? canvas.width + 20 : -80;
    const e = new Enemy(x, type, G.wave);
    e.dir = spawnRight ? -1 : 1;
    e.y = canvas.height - 80 - e.h;
    G.enemies.push(e);
    G.waveEnemiesLeft--;
}

let frameCount = 0;
function gameLoop() {
    if (G.state !== 'playing') return;
    
    frameCount++;
    const p = G.player;
    
    // Screen shake
    if (shakeTimer > 0) {
        shakeX = (Math.random()-.5) * shakeIntensity;
        shakeY = (Math.random()-.5) * shakeIntensity;
        shakeTimer--;
    } else { shakeX=0; shakeY=0; }
    
    ctx.save();
    ctx.translate(shakeX, shakeY);
    
    drawBg();
    
    // Spawn logic
    if (!G.bossActive) {
        G.spawnTimer++;
        if (G.spawnTimer >= G.spawnInterval && G.waveEnemiesLeft > 0) {
            spawnEnemy();
            G.spawnTimer = 0;
        }
        
        // Wave complete
        if (G.waveEnemiesLeft <= 0 && G.enemies.every(e => e.dead || e.dying)) {
            if (G.wave < G.maxWave) {
                startBoss();
            }
        }
    }
    
    // Update entities
    p.update();
    G.enemies.forEach(e => { if (!e.dead) e.update(p); });
    G.enemies = G.enemies.filter(e => !e.dead);
    
    if (G.boss && !G.boss.dead) G.boss.update(p);
    if (G.boss && G.boss.dead) {
        G.boss = null; G.bossActive = false;
        // Next wave
        G.wave++;
        if (G.wave > G.maxWave) {
            endGame(true); return;
        }
        nextWave();
    }
    
    G.projectiles.forEach(pr => pr.update(p));
    G.projectiles = G.projectiles.filter(pr => !pr.dead);
    
    G.particles.forEach(pt => {
        if (pt.isDmg) {
            // Floating damage number
            pt.x += pt.vx; pt.y += pt.vy; pt.life--;
            if (pt.life <= 0) pt.dead = true;
        } else {
            pt.update();
        }
    });
    G.particles = G.particles.filter(pt => !pt.dead);
    
    // Draw order
    G.projectiles.forEach(pr => pr.draw());
    G.enemies.forEach(e => e.draw());
    if (G.boss) G.boss.draw();
    G.particles.forEach(pt => {
        if (pt.isDmg) {
            ctx.globalAlpha = pt.life/pt.maxLife;
            ctx.font = `bold ${14 + Math.ceil(pt.dmg/20)}px "Orbitron",sans-serif`;
            ctx.fillStyle = '#ff5566';
            ctx.textAlign = 'center';
            ctx.fillText(`-${pt.dmg}`, pt.x, pt.y);
            ctx.globalAlpha = 1;
        } else {
            pt.draw();
        }
    });
    p.draw();
    
    // Wave announce
    if (G.waveAnnounce > 0) {
        const a = Math.min(1, G.waveAnnounce > 60 ? (90-G.waveAnnounce)/15 : G.waveAnnounce/30);
        ctx.globalAlpha = a;
        ctx.font = 'bold 48px "Cinzel Decorative",serif';
        ctx.fillStyle = '#f0c040';
        ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
        ctx.shadowColor = '#f0c040'; ctx.shadowBlur = 20;
        ctx.fillText(G.waveText, canvas.width/2, canvas.height/2 - 40);
        ctx.font = 'bold 22px "Orbitron",sans-serif';
        ctx.fillStyle = '#fff'; ctx.shadowBlur = 0;
        ctx.fillText(G.waveSubText||'', canvas.width/2, canvas.height/2 + 20);
        ctx.globalAlpha = 1; ctx.shadowBlur = 0;
        G.waveAnnounce--;
    }
    
    ctx.restore();
    updateHUD();
    
    // Check player death
    if (p.hp <= 0) { endGame(false); return; }
    
    // ESC to quit
    if (keys['Escape']) { endGame(false); return; }
    
    G.animFrame = requestAnimationFrame(gameLoop);
}

function startBoss() {
    G.bossActive = true;
    G.boss = new Boss(G.wave);
    G.boss.x = canvas.width - 200;
    G.boss.y = canvas.height - 80 - G.boss.h;
    G.waveText = '👺 BOSS APPEARS!';
    G.waveSubText = 'Defeat the Cursed Boss to advance!';
    G.waveAnnounce = 90;
    screenShake(10, 30);
}

function nextWave() {
    G.waveEnemiesLeft = G.waveKillTarget + G.wave * 3;
    G.spawnInterval = Math.max(60, 120 - G.wave * 10);
    G.waveText = `WAVE ${G.wave}`;
    G.waveSubText = `${G.waveEnemiesLeft} Cursed Spirits incoming!`;
    G.waveAnnounce = 90;
    updateHUD();
}

async function endGame(won) {
    G.state = won ? 'win' : 'over';
    cancelAnimationFrame(G.animFrame);
    
    const screen = document.getElementById('endScreen');
    document.getElementById('endIcon').textContent = won ? '🏆' : '💀';
    document.getElementById('endTitle').textContent = won ? 'VICTORY!' : 'GAME OVER';
    document.getElementById('endTitle').style.color = won ? '#f0c040' : '#cc2233';
    document.getElementById('finalScore').textContent = G.score.toLocaleString();
    document.getElementById('finalKills').textContent = G.kills;
    document.getElementById('finalWave').textContent = G.wave;
    screen.classList.add('show');
    
    // Save score
    <?php if(isLoggedIn()): ?>
    document.getElementById('saveStatus').textContent = 'Menyimpan skor...';
    try {
        const resp = await fetch('index.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: `action=save_score&score=${G.score}&enemies=${G.kills}&character=${encodeURIComponent(G.selectedChar.name)}`
        });
        const data = await resp.json();
        document.getElementById('saveStatus').textContent = data.success ? '✓ Skor tersimpan ke leaderboard!' : '⚠ Gagal menyimpan skor.';
    } catch(e) {
        document.getElementById('saveStatus').textContent = '⚠ Koneksi error.';
    }
    <?php else: ?>
    document.getElementById('saveStatus').textContent = '⚠ Login untuk menyimpan skor ke leaderboard.';
    <?php endif; ?>
}

function restartGame() {
    document.getElementById('endScreen').classList.remove('show');
    initGame(G.selectedChar);
}

function goMenu() {
    document.getElementById('endScreen').classList.remove('show');
    document.getElementById('gameHud').style.display = 'none';
    document.getElementById('canvasWrap').style.display = 'none';
    document.getElementById('mobileControls').style.display = 'none';
    document.getElementById('selectScreen').style.display = 'flex';
    G.state = 'select';
}

// ============================================
// CHARACTER SELECT & GAME INIT
// ============================================

let currentSelected = null;

function selectChar(el) {
    document.querySelectorAll('.char-select-card').forEach(c => {
        c.classList.remove('selected');
        c.querySelector('.selected-badge')?.remove();
    });
    el.classList.add('selected');
    const badge = document.createElement('div');
    badge.className = 'selected-badge'; badge.textContent = 'SELECTED';
    el.appendChild(badge);
    
    currentSelected = {
        name: el.dataset.char,
        atk: +el.dataset.atk,
        def: +el.dataset.def,
        spd: +el.dataset.spd,
        emoji: el.dataset.emoji
    };
    
    document.getElementById('startBtn').disabled = false;
}

// Auto-select if preloaded
window.addEventListener('DOMContentLoaded', () => {
    const preCard = document.querySelector('.char-select-card.selected');
    if (preCard) {
        currentSelected = {
            name: preCard.dataset.char,
            atk: +preCard.dataset.atk,
            def: +preCard.dataset.def,
            spd: +preCard.dataset.spd,
            emoji: preCard.dataset.emoji
        };
    }
});

function startGame() {
    if (!currentSelected) return;
    document.getElementById('selectScreen').style.display = 'none';
    document.getElementById('gameHud').style.display = 'flex';
    document.getElementById('canvasWrap').style.display = 'block';
    document.getElementById('mobileControls').style.display = 'flex';
    resizeCanvas();
    initBg();
    initGame(currentSelected);
}

function initGame(char) {
    cancelAnimationFrame(G.animFrame);
    G = {
        state: 'playing', score: 0, kills: 0, wave: 1, maxWave: 5,
        player: null, enemies: [], projectiles: [], particles: [],
        bossActive: false, boss: null,
        spawnTimer: 0, spawnInterval: 120,
        waveEnemiesLeft: 8, waveKillTarget: 8,
        selectedChar: char,
        waveAnnounce: 90, waveText: 'WAVE 1', waveSubText: '8 Cursed Spirits incoming!',
        animFrame: null
    };
    
    const p = new Player(char);
    p.y = canvas.height - 80 - p.h;
    G.player = p;
    G.selectedChar = char;
    
    // Update HUD
    document.getElementById('hudEmoji').textContent = char.emoji;
    document.getElementById('hudName').textContent = char.name.toUpperCase();
    
    updateHUD();
    G.animFrame = requestAnimationFrame(gameLoop);
}
</script>
</body>
</html>
