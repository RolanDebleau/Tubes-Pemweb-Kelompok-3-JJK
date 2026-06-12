<?php
require_once 'includes/config.php';
$characters = getAllCharacters(8);
$leaderboard = getLeaderboard(5);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jujutsu Kaisen Universe — Cursed Energy Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
:root {
    --black: #03020a;
    --deep: #08060f;
    --purple: #6b21e8;
    --purple-glow: #9d4dff;
    --purple-dark: #3a0d7a;
    --blue-dark: #050a1a;
    --gold: #f0c040;
    --gold-light: #ffe888;
    --red: #cc2233;
    --red-glow: #ff3355;
    --text: #ede8f5;
    --text-muted: #7a7490;
    --border: rgba(107,33,232,0.2);
    --border-gold: rgba(240,192,64,0.2);
    --card-bg: rgba(10,8,20,0.8);
    --nav-h: 72px;
}

*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
html { scroll-behavior: smooth; }

body {
    background: var(--black);
    color: var(--text);
    font-family: 'Rajdhani', sans-serif;
    overflow-x: hidden;
}

/* SCROLLBAR */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: var(--deep); }
::-webkit-scrollbar-thumb { background: var(--purple-dark); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--purple); }

/* ========== NAVBAR ========== */
.navbar {
    position: fixed; top: 0; left: 0; right: 0;
    height: var(--nav-h);
    z-index: 100;
    display: flex; align-items: center;
    padding: 0 40px;
    background: rgba(3,2,10,0.85);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
    transition: all 0.3s;
}

.navbar.scrolled {
    background: rgba(3,2,10,0.95);
    box-shadow: 0 4px 30px rgba(107,33,232,0.15);
}

.nav-logo {
    display: flex; align-items: center; gap: 12px;
    text-decoration: none;
    flex: 1;
}

.logo-symbol {
    font-size: 1.8rem;
    background: linear-gradient(135deg, var(--purple-glow), var(--gold));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 0 10px rgba(107,33,232,0.5));
}

.logo-text {
    font-family: 'Cinzel Decorative', serif;
    font-size: 1rem;
    color: var(--text);
    line-height: 1.1;
}

.logo-sub {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.5rem;
    color: var(--text-muted);
    letter-spacing: 3px;
    display: block;
}

.nav-links {
    display: flex; align-items: center; gap: 8px;
    list-style: none;
}

.nav-links a {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.65rem;
    letter-spacing: 2px;
    color: var(--text-muted);
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 2px;
    transition: all 0.3s;
    text-transform: uppercase;
}

.nav-links a:hover, .nav-links a.active {
    color: var(--text);
    background: rgba(107,33,232,0.15);
}

.nav-actions { display:flex; align-items:center; gap:12px; margin-left:20px; }

.btn-nav {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.6rem;
    letter-spacing: 2px;
    padding: 8px 20px;
    border-radius: 2px;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-nav-outline {
    border: 1px solid var(--border);
    color: var(--text-muted);
    background: transparent;
}

.btn-nav-outline:hover {
    border-color: var(--purple-glow);
    color: var(--purple-glow);
}

.btn-nav-primary {
    background: var(--purple);
    border: 1px solid var(--purple);
    color: white;
}

.btn-nav-primary:hover {
    background: var(--purple-glow);
    box-shadow: 0 0 20px rgba(107,33,232,0.4);
}

.user-badge {
    display: flex; align-items: center; gap: 8px;
    padding: 6px 14px;
    border: 1px solid var(--border-gold);
    border-radius: 2px;
    background: rgba(240,192,64,0.05);
}

.user-badge-name {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.6rem;
    color: var(--gold);
    letter-spacing: 1px;
}

/* ========== HERO ========== */
.hero {
    position: relative;
    min-height: 100vh;
    display: flex; align-items: center;
    padding-top: var(--nav-h);
    overflow: hidden;
}

.hero-bg {
    position: absolute; inset: 0; z-index: 0;
    background:
        radial-gradient(ellipse 80% 60% at 15% 50%, rgba(107,33,232,0.18) 0%, transparent 55%),
        radial-gradient(ellipse 50% 80% at 85% 20%, rgba(204,34,51,0.1) 0%, transparent 50%),
        radial-gradient(ellipse 100% 50% at 50% 100%, rgba(157,77,255,0.08) 0%, transparent 60%),
        linear-gradient(180deg, #03020a 0%, #0a0520 50%, #03020a 100%);
}

.hero-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(107,33,232,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(107,33,232,0.04) 1px, transparent 1px);
    background-size: 60px 60px;
    mask-image: radial-gradient(ellipse 80% 60% at 20% 50%, black 0%, transparent 70%);
}

.hero-content {
    position: relative; z-index: 2;
    max-width: 1200px; margin: 0 auto;
    padding: 0 40px;
    display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;
}

.hero-left {}

.hero-tag {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.6rem; letter-spacing: 4px;
    color: var(--purple-glow);
    text-transform: uppercase;
    margin-bottom: 20px;
    padding: 6px 14px;
    border: 1px solid rgba(157,77,255,0.3);
    border-radius: 1px;
}

.hero-tag::before {
    content: '';
    width: 6px; height: 6px;
    background: var(--purple-glow);
    border-radius: 50%;
    animation: blink 2s ease-in-out infinite;
}

@keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0;} }

.hero-title {
    font-family: 'Cinzel Decorative', serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    line-height: 1.1;
    margin-bottom: 12px;
}

.hero-title-main {
    background: linear-gradient(135deg, #fff 0%, var(--text) 60%, var(--purple-glow) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: block;
}

.hero-title-jp {
    display: block;
    font-size: 0.6em;
    background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 4px;
    margin-top: 4px;
}

.hero-desc {
    color: var(--text-muted);
    font-size: 1.05rem;
    line-height: 1.7;
    margin-bottom: 36px;
    max-width: 440px;
    font-weight: 400;
}

.hero-cta {
    display: flex; gap: 16px; flex-wrap: wrap;
}

.btn-hero-primary {
    padding: 16px 36px;
    background: linear-gradient(135deg, var(--purple) 0%, #8b30ff 100%);
    border: none; border-radius: 2px;
    color: white;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.75rem; font-weight: 700; letter-spacing: 3px;
    cursor: pointer; transition: all 0.3s;
    text-decoration: none; display: inline-flex; align-items: center; gap: 10px;
    position: relative; overflow: hidden;
}

.btn-hero-primary::before {
    content: '';
    position: absolute; top:0; left:-100%; width:100%; height:100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s;
}

.btn-hero-primary:hover::before { left: 100%; }
.btn-hero-primary:hover { box-shadow: 0 0 40px rgba(107,33,232,0.5); transform: translateY(-2px); }

.btn-hero-secondary {
    padding: 16px 36px;
    background: transparent;
    border: 1px solid var(--border-gold);
    border-radius: 2px;
    color: var(--gold);
    font-family: 'Orbitron', sans-serif;
    font-size: 0.75rem; font-weight: 700; letter-spacing: 3px;
    cursor: pointer; transition: all 0.3s;
    text-decoration: none; display: inline-flex; align-items: center; gap: 10px;
}

.btn-hero-secondary:hover {
    background: rgba(240,192,64,0.08);
    box-shadow: 0 0 20px rgba(240,192,64,0.2);
    transform: translateY(-2px);
}

.hero-stats {
    display: flex; gap: 32px; margin-top: 48px;
    padding-top: 32px;
    border-top: 1px solid var(--border);
}

.stat-item { text-align: left; }
.stat-num {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.8rem; font-weight: 900;
    color: var(--gold);
    display: block;
}
.stat-label {
    font-size: 0.75rem;
    color: var(--text-muted);
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* HERO RIGHT - Character Showcase */
.hero-right {
    display: flex; align-items: center; justify-content: center;
    position: relative;
}

.char-showcase {
    position: relative; width: 400px; height: 500px;
}

.char-orbit {
    position: absolute; inset: 0;
    border-radius: 50%;
    border: 1px solid rgba(107,33,232,0.15);
    animation: orbitSpin 20s linear infinite;
}

.char-orbit-2 {
    inset: 30px;
    animation: orbitSpin 15s linear infinite reverse;
    border-color: rgba(240,192,64,0.1);
}

@keyframes orbitSpin { to { transform: rotate(360deg); } }

.char-center {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 240px; height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(107,33,232,0.3) 0%, rgba(107,33,232,0.05) 60%, transparent 100%);
    display: flex; align-items: center; justify-content: center;
    border: 1px solid rgba(107,33,232,0.3);
    box-shadow: 0 0 60px rgba(107,33,232,0.2), inset 0 0 60px rgba(107,33,232,0.05);
}

.char-emoji-main { font-size: 5rem; animation: charFloat 3s ease-in-out infinite; }
@keyframes charFloat { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-12px);} }

.char-dot {
    position: absolute;
    width: 48px; height: 48px;
    border-radius: 50%;
    background: var(--card-bg);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 0 20px rgba(0,0,0,0.5);
}

.char-dot:hover {
    transform: scale(1.2);
    border-color: var(--purple-glow);
    box-shadow: 0 0 20px rgba(107,33,232,0.4);
}

.char-dot:nth-child(3) { top: 5%; left: 50%; transform: translateX(-50%); }
.char-dot:nth-child(4) { top: 25%; right: 5%; }
.char-dot:nth-child(5) { bottom: 25%; right: 5%; }
.char-dot:nth-child(6) { bottom: 5%; left: 50%; transform: translateX(-50%); }
.char-dot:nth-child(7) { bottom: 25%; left: 5%; }
.char-dot:nth-child(8) { top: 25%; left: 5%; }

.power-bar-glow {
    position: absolute; bottom: -30px; left: 50%; transform: translateX(-50%);
    font-family: 'Orbitron', sans-serif; font-size: 0.55rem; letter-spacing: 3px;
    color: var(--purple-glow); white-space: nowrap;
    text-shadow: 0 0 20px var(--purple-glow);
    animation: textGlow 2s ease-in-out infinite;
}

@keyframes textGlow { 0%,100%{opacity:0.7;} 50%{opacity:1;} }

/* ========== SECTIONS ========== */
.section { padding: 100px 40px; max-width: 1200px; margin: 0 auto; }
.section-full { padding: 100px 0; }

.section-header { text-align: center; margin-bottom: 60px; }

.section-tag {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.6rem; letter-spacing: 4px;
    color: var(--purple-glow); text-transform: uppercase;
    margin-bottom: 16px; display: block;
}

.section-title {
    font-family: 'Cinzel Decorative', serif;
    font-size: clamp(1.8rem, 3vw, 2.8rem);
    background: linear-gradient(135deg, var(--text) 0%, var(--purple-glow) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 16px;
}

.section-subtitle {
    color: var(--text-muted);
    font-size: 1rem; max-width: 500px; margin: 0 auto;
    line-height: 1.7;
}

/* ========== STORY SECTION ========== */
.story-section {
    background: linear-gradient(180deg, transparent, rgba(107,33,232,0.03), transparent);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

.story-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 80px; align-items: center;
}

.story-visual {
    position: relative; height: 380px;
    background: linear-gradient(135deg, rgba(107,33,232,0.1), rgba(204,34,51,0.05));
    border: 1px solid var(--border);
    border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}

.story-symbol { font-size: 6rem; opacity: 0.15; position: absolute; }
.story-symbol-1 { top: 10%; left: 10%; }
.story-symbol-2 { bottom: 10%; right: 10%; transform: rotate(45deg); }
.story-symbol-3 { top: 50%; left: 50%; transform: translate(-50%,-50%) rotate(-15deg); font-size: 10rem; }

.story-text { position: relative; z-index: 1; text-align: center; padding: 20px; }
.story-jp {
    font-family: 'Cinzel Decorative', serif;
    font-size: 3rem;
    background: linear-gradient(135deg, var(--gold), var(--purple-glow));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: block; margin-bottom: 8px;
}
.story-en { font-family: 'Orbitron', sans-serif; font-size: 0.65rem; letter-spacing: 4px; color: var(--text-muted); }

.story-content-text {}
.story-label {
    font-family: 'Orbitron', sans-serif; font-size: 0.6rem;
    letter-spacing: 3px; color: var(--gold); text-transform: uppercase;
    margin-bottom: 16px; display: block;
}
.story-heading {
    font-family: 'Cinzel Decorative', serif; font-size: 2rem;
    color: var(--text); margin-bottom: 20px; line-height: 1.2;
}
.story-p {
    color: var(--text-muted); line-height: 1.8; font-size: 1rem;
    margin-bottom: 16px;
}

.story-quote {
    border-left: 2px solid var(--gold);
    padding: 16px 20px; margin: 24px 0;
    background: rgba(240,192,64,0.04);
    border-radius: 0 4px 4px 0;
}

.story-quote p {
    font-style: italic; color: var(--gold-light);
    font-size: 1rem; line-height: 1.6;
}

/* ========== CHARACTERS GRID ========== */
.characters-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
}

.char-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.4s;
    position: relative;
    text-decoration: none; color: inherit;
}

.char-card:hover {
    border-color: var(--purple-glow);
    transform: translateY(-8px);
    box-shadow: 0 20px 60px rgba(107,33,232,0.2), 0 0 0 1px rgba(157,77,255,0.2);
}

.char-card::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(107,33,232,0.05), transparent);
    opacity: 0; transition: opacity 0.3s;
}

.char-card:hover::before { opacity: 1; }

.char-card-art {
    height: 180px;
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
    font-size: 4.5rem;
}

.char-card-art-bg {
    position: absolute; inset: 0;
}

.char-card-emoji { position: relative; z-index: 1; filter: drop-shadow(0 4px 20px rgba(0,0,0,0.5)); }

.char-grade-badge {
    position: absolute; top: 10px; right: 10px;
    font-family: 'Orbitron', sans-serif; font-size: 0.5rem; letter-spacing: 1px;
    padding: 3px 8px; border-radius: 1px;
    text-transform: uppercase; z-index: 2;
}

.grade-special { background: rgba(240,192,64,0.2); border: 1px solid rgba(240,192,64,0.5); color: var(--gold); }
.grade-1 { background: rgba(107,33,232,0.2); border: 1px solid rgba(107,33,232,0.5); color: var(--purple-glow); }
.grade-2 { background: rgba(0,150,255,0.15); border: 1px solid rgba(0,150,255,0.4); color: #4dc8ff; }
.grade-3 { background: rgba(100,100,120,0.2); border: 1px solid rgba(100,100,120,0.5); color: #aaa8c0; }

.char-card-info { padding: 16px; }
.char-name { font-family: 'Cinzel Decorative', serif; font-size: 0.85rem; color: var(--text); margin-bottom: 4px; line-height: 1.3; }
.char-technique { font-size: 0.75rem; color: var(--text-muted); margin-bottom: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.power-bars { display: flex; flex-direction: column; gap: 5px; }
.power-bar-row { display: flex; align-items: center; gap: 8px; }
.power-bar-label { font-family: 'Orbitron', sans-serif; font-size: 0.5rem; color: var(--text-muted); width: 28px; flex-shrink: 0; }
.power-bar-track { flex: 1; height: 3px; background: rgba(255,255,255,0.06); border-radius: 2px; overflow: hidden; }
.power-bar-fill { height: 100%; border-radius: 2px; transition: width 1s ease; }
.fill-atk { background: linear-gradient(90deg, var(--red), var(--red-glow)); }
.fill-def { background: linear-gradient(90deg, var(--purple), var(--purple-glow)); }
.fill-spd { background: linear-gradient(90deg, #0088ff, #44ccff); }

/* ========== MINI GAME SECTION ========== */
.game-section {
    background: linear-gradient(180deg, transparent, rgba(204,34,51,0.03), transparent);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: 100px 40px;
    text-align: center;
}

.game-preview {
    margin-top: 40px;
    border: 1px solid var(--border);
    border-radius: 4px;
    overflow: hidden;
    max-width: 800px; margin: 40px auto 0;
    position: relative;
    background: #05030f;
    box-shadow: 0 0 60px rgba(107,33,232,0.15);
}

.game-preview-overlay {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    background: rgba(0,0,0,0.7);
    z-index: 10;
    transition: all 0.3s;
}

.game-preview-overlay:hover { background: rgba(0,0,0,0.5); }

.game-preview-text {
    font-family: 'Cinzel Decorative', serif;
    font-size: 1.5rem; color: var(--text);
    margin-bottom: 8px;
}

.game-preview-sub {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.6rem; letter-spacing: 3px;
    color: var(--text-muted);
    margin-bottom: 24px;
}

.game-pseudo { height: 300px; background: linear-gradient(180deg, #05030f, #0a0520); display:flex; align-items:center; justify-content:center; font-size:3rem; }

/* ========== LEADERBOARD ========== */
.leaderboard {
    max-width: 700px; margin: 0 auto;
}

.lb-row {
    display: flex; align-items: center; gap: 16px;
    padding: 16px 20px;
    border: 1px solid var(--border);
    border-radius: 2px;
    margin-bottom: 8px;
    background: var(--card-bg);
    transition: all 0.3s;
}

.lb-row:hover { border-color: var(--purple-glow); transform: translateX(4px); }

.lb-rank {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.1rem; font-weight: 900;
    width: 32px; flex-shrink: 0; text-align: center;
}

.rank-1 { color: var(--gold); text-shadow: 0 0 15px var(--gold); }
.rank-2 { color: #c0c0c0; }
.rank-3 { color: #cd7f32; }
.rank-other { color: var(--text-muted); }

.lb-user { flex: 1; }
.lb-username { font-weight: 600; font-size: 1rem; }
.lb-char { font-size: 0.8rem; color: var(--text-muted); }
.lb-score {
    font-family: 'Orbitron', sans-serif;
    font-size: 1rem; font-weight: 700; color: var(--purple-glow);
}

/* ========== FOOTER ========== */
footer {
    border-top: 1px solid var(--border);
    padding: 40px;
    text-align: center;
    background: linear-gradient(180deg, transparent, rgba(107,33,232,0.03));
}

.footer-logo { font-family: 'Cinzel Decorative', serif; font-size: 1.2rem; color: var(--gold); margin-bottom: 8px; }
.footer-sub { font-size: 0.8rem; color: var(--text-muted); }

/* ========== ANIMATIONS ========== */
.fade-in { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
.fade-in.visible { opacity: 1; transform: translateY(0); }

@media (max-width: 900px) {
    .hero-content { grid-template-columns: 1fr; }
    .hero-right { display: none; }
    .story-grid { grid-template-columns: 1fr; }
    .characters-grid { grid-template-columns: repeat(2, 1fr); }
    .navbar { padding: 0 20px; }
    .nav-links { display: none; }
    .section { padding: 60px 20px; }
}

@media (max-width: 500px) {
    .characters-grid { grid-template-columns: 1fr; }
}

/* Curse particles bg */
.curse-particles { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
.cp { position: absolute; border-radius: 50%; animation: cpFloat linear infinite; opacity: 0; }
@keyframes cpFloat {
    0% { transform: translateY(0) scale(0); opacity: 0; }
    20% { opacity: 0.4; }
    80% { opacity: 0.2; }
    100% { transform: translateY(-100px) scale(1.5); opacity: 0; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <a href="index.php" class="nav-logo">
        <span class="logo-symbol">呪</span>
        <div>
            <span class="logo-text">JJK Universe</span>
            <span class="logo-sub">Cursed Energy Portal</span>
        </div>
    </a>
    
    <ul class="nav-links">
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="pages/characters.php">Characters</a></li>
        <li><a href="pages/story.php">Story Arc</a></li>
        <li><a href="game/index.php">Mini Game</a></li>
        <?php if (isLoggedIn()): ?>
        <li><a href="pages/leaderboard.php">Leaderboard</a></li>
        <?php if (isAdmin()): ?>
        <li><a href="admin/dashboard.php" style="color:var(--gold)">Admin</a></li>
        <?php endif; ?>
        <?php endif; ?>
    </ul>
    
    <div class="nav-actions">
        <?php if (isLoggedIn()): ?>
        <div class="user-badge">
            <span class="user-badge-name">⚡ <?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
        </div>
        <a href="pages/logout.php" class="btn-nav btn-nav-outline">Logout</a>
        <?php else: ?>
        <a href="pages/login.php" class="btn-nav btn-nav-outline">Login</a>
        <a href="pages/register.php" class="btn-nav btn-nav-pr  ary">Register</a>
        <?php endif; ?>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
    <div class="hero-content">
        <div class="hero-left">
            <div class="hero-tag">Cursed Energy Portal Active</div>
            <h1 class="hero-title">
                <span class="hero-title-main">Jujutsu Kaisen</span>
                <span class="hero-title-jp">呪術廻戦</span>
            </h1>
            <p class="hero-desc">
                Masuki dunia di mana kutukan dan energi tersembunyi bertarung melawan para tukang sihir. 
                Jelajahi karakter, pelajari teknik kutukan, dan buktikan kekuatanmu dalam arena.
            </p>
            <div class="hero-cta">
                <a href="pages/characters.php" class="btn-hero-primary">⚔ Jelajahi Karakter</a>
                <a href="game/index.php" class="btn-hero-secondary">🎮 Main Sekarang</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-num"><?= count($characters) ?>+</span>
                    <span class="stat-label">Characters</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">3</span>
                    <span class="stat-label">Story Arcs</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">∞</span>
                    <span class="stat-label">Cursed Energy</span>
                </div>
            </div>
        </div>
        
        <div class="hero-right">
            <div class="char-showcase">
                <div class="char-orbit"></div>
                <div class="char-orbit char-orbit-2"></div>
                <div class="char-center">
                    <div class="char-emoji-main">⚡</div>
                </div>
                <div class="char-dot">👊</div>
                <div class="char-dot">🌑</div>
                <div class="char-dot">🔮</div>
                <div class="char-dot">💀</div>
                <div class="char-dot">🌸</div>
                <div class="char-dot">🗡</div>
                <div class="power-bar-glow">CURSED ENERGY DETECTED</div>
            </div>
        </div>
    </div>
</section>

<!-- STORY SECTION -->
<section class="story-section">
<div class="section">
    <div class="story-grid fade-in">
        <div class="story-visual">
            <div class="story-symbol story-symbol-1">呪</div>
            <div class="story-symbol story-symbol-2">術</div>
            <div class="story-symbol story-symbol-3">廻</div>
            <div class="story-text">
                <span class="story-jp">呪術廻戦</span>
                <span class="story-en">The World of Cursed Techniques</span>
            </div>
        </div>
        <div class="story-content-text">
            <span class="story-label">Tentang Cerita</span>
            <h2 class="story-heading">Dunia di Balik<br>Kutukan & Sihir</h2>
            <p class="story-p">
                Di dunia Jujutsu Kaisen, makhluk gaib yang disebut <strong style="color:var(--purple-glow)">Cursed Spirits (Roh Kutukan)</strong> 
                terlahir dari emosi negatif manusia — rasa takut, kebencian, dan penderitaan yang terkumpul menjadi entitas berbahaya.
            </p>
            <p class="story-p">
                Para <strong style="color:var(--gold)">Jujutsu Sorcerer (Tukang Sihir Jujutsu)</strong> menggunakan 
                Cursed Energy yang mengalir dalam tubuh mereka untuk melawan dan mengeksorsisme roh-roh ini, 
                melindungi masyarakat dari ancaman yang tak terlihat.
            </p>
            <div class="story-quote">
                <p>"Aku akan meninggalkan dunia ini dengan cara yang tepat — hidup penuh dan mati dikelilingi orang-orang."</p>
            </div>
            <a href="pages/story.php" class="btn-hero-secondary" style="display:inline-flex; margin-top:10px;">Baca Selengkapnya →</a>
        </div>
    </div>
</div>
</section>

<!-- CHARACTERS SECTION -->
<section>
<div class="section fade-in">
    <div class="section-header">
        <span class="section-tag">Roster Karakter</span>
        <h2 class="section-title">Para Tukang Sihir & Kutukan</h2>
        <p class="section-subtitle">Dari murid Tokyo Jujutsu High hingga Raja Kutukan yang legendaris</p>
    </div>
    
    <div class="characters-grid">
        <?php
        $charEmojis = ['👊','🌑','🔨','♾️','💀','👁️','👋','⚡'];
        $charColors = [
            'Special Grade' => ['bg' => 'linear-gradient(135deg, rgba(240,192,64,0.15), rgba(240,192,64,0.03))', 'class' => 'grade-special'],
            'Grade 1' => ['bg' => 'linear-gradient(135deg, rgba(107,33,232,0.15), rgba(107,33,232,0.03))', 'class' => 'grade-1'],
            'Grade 2' => ['bg' => 'linear-gradient(135deg, rgba(0,150,255,0.12), rgba(0,150,255,0.02))', 'class' => 'grade-2'],
            'Grade 3' => ['bg' => 'linear-gradient(135deg, rgba(100,100,120,0.15), rgba(100,100,120,0.03))', 'class' => 'grade-3'],
            'Semi-Grade 1' => ['bg' => 'linear-gradient(135deg, rgba(107,33,232,0.1), rgba(107,33,232,0.02))', 'class' => 'grade-1'],
        ];
        
        foreach ($characters as $i => $char):
            $emoji = $charEmojis[$i % count($charEmojis)];
            $gradeData = $charColors[$char['grade']] ?? $charColors['Grade 3'];
        ?>
        <a href="pages/character_detail.php?id=<?= $char['id'] ?>" class="char-card">
            <div class="char-card-art">
                <div class="char-card-art-bg" style="background: <?= $gradeData['bg'] ?>"></div>
                <span class="char-grade-badge <?= $gradeData['class'] ?>"><?= htmlspecialchars($char['grade']) ?></span>
                <span class="char-card-emoji"><?= $emoji ?></span>
            </div>
            <div class="char-card-info">
                <div class="char-name"><?= htmlspecialchars($char['name']) ?></div>
                <div class="char-technique"><?= htmlspecialchars($char['cursed_technique']) ?></div>
                <div class="power-bars">
                    <div class="power-bar-row">
                        <span class="power-bar-label">ATK</span>
                        <div class="power-bar-track">
                            <div class="power-bar-fill fill-atk" style="width:<?= $char['attack_power'] ?>%"></div>
                        </div>
                    </div>
                    <div class="power-bar-row">
                        <span class="power-bar-label">DEF</span>
                        <div class="power-bar-track">
                            <div class="power-bar-fill fill-def" style="width:<?= $char['defense_power'] ?>%"></div>
                        </div>
                    </div>
                    <div class="power-bar-row">
                        <span class="power-bar-label">SPD</span>
                        <div class="power-bar-track">
                            <div class="power-bar-fill fill-spd" style="width:<?= $char['speed_power'] ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    
    <div style="text-align:center; margin-top:40px;">
        <a href="pages/characters.php" class="btn-hero-secondary">Lihat Semua Karakter →</a>
    </div>
</div>
</section>

<!-- MINI GAME PROMO -->
<section class="game-section fade-in">
    <span class="section-tag">Mini Game</span>
    <h2 class="section-title">Cursed Spirit Slayer</h2>
    <p class="section-subtitle">Pilih karaktermu, lawan Cursed Spirits, dan kalahkan Boss akhir! Buktikan kamu layak jadi Tukang Sihir Jujutsu.</p>
    
    <div class="game-preview">
        <div class="game-preview-overlay">
            <div class="game-preview-text">⚔ Mulai Pertempuran</div>
            <div class="game-preview-sub">Pilih Karakter → Lawan Musuh → Kalahkan Boss</div>
            <a href="game/index.php" class="btn-hero-primary">🎮 Main Sekarang</a>
        </div>
        <div class="game-pseudo">
            <span style="opacity:0.3; font-size:1rem; font-family:'Orbitron',sans-serif; letter-spacing:3px; color:#9d4dff;">CURSED SPIRIT SLAYER v1.0</span>
        </div>
    </div>
</section>

<!-- LEADERBOARD -->
<?php if (!empty($leaderboard)): ?>
<section>
<div class="section fade-in">
    <div class="section-header">
        <span class="section-tag">Top Sorcerers</span>
        <h2 class="section-title">Papan Peringkat</h2>
        <p class="section-subtitle">Para tukang sihir terkuat yang telah mengalahkan Cursed Spirits</p>
    </div>
    
    <div class="leaderboard">
        <?php foreach ($leaderboard as $i => $row): ?>
        <div class="lb-row">
            <span class="lb-rank <?= $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-other')) ?>">
                <?= $i === 0 ? '👑' : ($i+1) ?>
            </span>
            <div class="lb-user">
                <div class="lb-username"><?= htmlspecialchars($row['username']) ?></div>
                <div class="lb-char">🗡 <?= htmlspecialchars($row['character_used'] ?? 'Unknown') ?> — <?= $row['enemies_defeated'] ?> enemies</div>
            </div>
            <div class="lb-score"><?= number_format($row['score']) ?> pts</div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div style="text-align:center; margin-top:30px;">
        <a href="pages/leaderboard.php" class="btn-hero-secondary">Lihat Semua →</a>
    </div>
</div>
</section>
<?php endif; ?>

<!-- FOOTER -->
<footer>
    <div class="footer-logo">呪 JJK Universe</div>
    <div class="footer-sub">Jujutsu Kaisen Fan Web — Project Responsi Praktikum Pemrograman Web 2026</div>
    <div style="margin-top:20px; font-size:0.7rem; color: #3a3550;">
        HTML · CSS · JavaScript · PHP · MySQL · CRUD · Session
    </div>
</footer>

<script>
// Navbar scroll effect
window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// Intersection observer for fade-in
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

// Power bars animation on view
const barObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.querySelectorAll('.power-bar-fill').forEach(bar => {
                const w = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => { bar.style.width = w; }, 200);
            });
        }
    });
}, { threshold: 0.3 });

document.querySelectorAll('.char-card').forEach(c => barObserver.observe(c));
</script>
</body>
</html>
