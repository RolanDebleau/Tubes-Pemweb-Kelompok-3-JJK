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
    --nav-h: 80px;
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

/* NAVBAR */


































/* HERO */
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
    max-width: 1300px; margin: 0 auto;
    padding: 0 40px;
    display: grid; grid-template-columns: 1fr 1.2fr; gap: 40px; align-items: center;
    overflow: hidden;
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
    font-size: clamp(2rem, 4vw, 3.5rem);
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
    font-size: 0.55em;
    background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 4px;
    margin-top: 4px;
}

.hero-desc {
    color: var(--text-muted);
    font-size: 0.95rem;
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
/* HERO RIGHT — HoloNight-style zigzag character cards */
.hero-right {
    display: flex; align-items: center; justify-content: center;
    position: relative;
    overflow: hidden;
    min-height: 500px;
}

/* ===== HCS = Hero Char Stack ===== */
.hero-chars-stack {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    position: relative;
    padding: 20px 0 40px;
}

.hcs-label {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.52rem;
    letter-spacing: 4px;
    color: var(--purple-glow);
    text-transform: uppercase;
    opacity: 0.8;
    animation: textGlow 2s ease-in-out infinite;
    margin-bottom: 4px;
}

@keyframes textGlow { 0%,100%{opacity:0.6;} 50%{opacity:1;} }

.hcs-row {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    flex-wrap: nowrap;
    justify-content: center;
    padding: 40px 10px;  /* ruang buat naik/turun card */
}

/* Individual card */
.hcs-card {
    width: 90px;
    height: 140px;
    flex-shrink: 0;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    text-decoration: none;
    display: block;
    border: 1px solid rgba(107,33,232,0.3);
    box-shadow: 0 8px 32px rgba(0,0,0,0.5);
    transition: transform 0.35s cubic-bezier(.25,.46,.45,.94),
                box-shadow 0.35s ease,
                border-color 0.35s ease;
    /* zigzag offset + tilt via CSS variable */
    transform: translateY(var(--ty, 0px)) rotate(var(--rot, 0deg));
    /* float animation offset per card */
    animation: hcsFloat var(--float-dur, 3s) ease-in-out infinite;
    animation-delay: var(--float-delay, 0s);
}

/* Alternating float directions */
.hcs-card.hcs-up   { --float-dur: 3.2s; }
.hcs-card.hcs-down { --float-dur: 3.6s; }

@keyframes hcsFloat {
    0%,100% { transform: translateY(var(--ty, 0px)) rotate(var(--rot, 0deg)); }
    50%     { transform: translateY(calc(var(--ty, 0px) - 10px)) rotate(var(--rot, 0deg)); }
}

.hcs-card.hcs-down {
    animation-name: hcsFloatDown;
}

@keyframes hcsFloatDown {
    0%,100% { transform: translateY(var(--ty, 0px)) rotate(var(--rot, 0deg)); }
    50%     { transform: translateY(calc(var(--ty, 0px) + 10px)) rotate(var(--rot, 0deg)); }
}

.hcs-card:hover {
    transform: translateY(calc(var(--ty, 0px) - 12px)) rotate(var(--rot, 0deg)) scale(1.05) !important;
    border-color: var(--glow, var(--purple-glow));
    box-shadow: 0 0 32px var(--glow, rgba(157,77,255,0.5)),
                0 16px 40px rgba(0,0,0,0.6);
    z-index: 10;
    animation-play-state: paused;
}

.hcs-card-inner {
    width: 100%;
    height: 100%;
    position: relative;
}

.hcs-card-inner img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    display: block;
    filter: brightness(0.9);
    transition: filter 0.35s ease;
}

.hcs-card:hover .hcs-card-inner img {
    filter: brightness(1.05);
}

.hcs-emoji-fallback {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.8rem;
    background: linear-gradient(135deg, rgba(107,33,232,0.2), rgba(10,8,20,0.9));
}

.hcs-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: 28px 8px 8px;
    background: linear-gradient(to top, rgba(3,2,10,0.95) 0%, transparent 100%);
}

.hcs-name {
    font-family: 'Cinzel Decorative', serif;
    font-size: 0.55rem;
    color: var(--text);
    line-height: 1.3;
    margin-bottom: 2px;
}

.hcs-grade {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.42rem;
    letter-spacing: 1px;
    color: var(--glow, var(--purple-glow));
    text-transform: uppercase;
}

/* Glow border bottom line on hover */
.hcs-card::after {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(to right, transparent, var(--glow, var(--purple-glow)), transparent);
    opacity: 0;
    transition: opacity 0.3s;
}
.hcs-card:hover::after { opacity: 1; }

/* SECTIONS */
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

/* STORY SECTION */
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

/* CHARACTERS GRID */         
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
.char-card-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: top center; z-index: 1; transition: transform .4s; }
.char-card:hover .char-card-img { transform: scale(1.08); }

.char-grade-badge {
    position: absolute; top: 10px; right: 10px;
    font-family: 'Orbitron', sans-serif; font-size: 0.5rem; letter-spacing: 1px;
    padding: 3px 8px; border-radius: 1px;
    text-transform: uppercase; z-index: 2;
}

.grade-special { background: rgba(240,192,64,0.2); border: 1px solid rgba(240,192,64,0.5); color: var(--gold); }
.grade-semi    { background: rgba(157,77,255,0.2);  border: 1px solid rgba(157,77,255,0.5); color: #cc99ff; }
.grade-4       { background: rgba(80,80,90,0.18);   border: 1px solid rgba(80,80,90,0.45);  color: #888898; }
.grade-unranked{ background: rgba(60,60,70,0.15);   border: 1px solid rgba(60,60,70,0.4);   color: #777788; }
.grade-1 { background: rgba(107,33,232,0.2); border: 1px solid rgba(107,33,232,0.5); color: var(--purple-glow); }
.grade-2 { background: rgba(0,150,255,0.15); border: 1px solid rgba(0,150,255,0.4); color: #4dc8ff; }
.grade-3 { background: rgba(100,100,120,0.2); border: 1px solid rgba(100,100,120,0.5); color: #aaa8c0; }
.grade-4    { background:rgba(80,80,80,.15); border:1px solid rgba(80,80,80,.4); color:#888888; }
.grade-unranked { background:rgba(60,60,60,.12); border:1px solid rgba(60,60,60,.3); color:#666666; }


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

/* MINI GAME SECTION */
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

/* LEADERBOARD */
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

/* FOOTER */
footer {
    border-top: 1px solid var(--border);
    padding: 40px;
    text-align: center;
    background: linear-gradient(180deg, transparent, rgba(107,33,232,0.03));
}

.footer-logo { font-family: 'Cinzel Decorative', serif; font-size: 1.2rem; color: var(--gold); margin-bottom: 8px; }
.footer-sub { font-size: 0.8rem; color: var(--text-muted); }

/* ANIMATIONS */
.fade-in { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
.fade-in.visible { opacity: 1; transform: translateY(0); }

@media (max-width: 900px) {
    .hero-content { grid-template-columns: 1fr; }
    .hero-right { display: none; }
    .story-grid { grid-template-columns: 1fr; }
    .characters-grid { grid-template-columns: repeat(2, 1fr); }
    
    
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

/* ========== WORLD & JUJUTSU SECTION ========== */
.world-jujutsu-section {
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    background: linear-gradient(180deg, transparent, rgba(107,33,232,.02), transparent);
}
.wj-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
.wj-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    transition: all .4s;
    display: flex;
    flex-direction: column;
}
.wj-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 60px rgba(107,33,232,.2);
    border-color: var(--purple-glow);
}
.wj-card-header {
    height: 160px;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.wj-world   { background: linear-gradient(135deg, rgba(0,100,180,.3) 0%, rgba(107,33,232,.2) 100%); }
.wj-jujutsu { background: linear-gradient(135deg, rgba(107,33,232,.35) 0%, rgba(157,77,255,.2) 100%); }
.wj-story   { background: linear-gradient(135deg, rgba(204,34,51,.3) 0%, rgba(107,33,232,.2) 100%); }
.wj-card-bg-symbols {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    pointer-events: none;
}
.wj-sym {
    position: absolute;
    font-size: 5rem;
    opacity: .07;
    font-family: 'Cinzel Decorative', serif;
}
.wj-sym-2 { right: 10%; bottom: 5%; font-size: 3.5rem; opacity: .05; }
.wj-sym-3 { left: 5%; top: 10%; font-size: 2.5rem; opacity: .06; }
.wj-card-icon {
    font-size: 3.5rem;
    position: relative; z-index: 1;
    filter: drop-shadow(0 4px 20px rgba(0,0,0,.5));
    margin-bottom: 8px;
    animation: wjFloat 3s ease-in-out infinite;
}
.wj-card:nth-child(2) .wj-card-icon { animation-delay: .5s; }
.wj-card:nth-child(3) .wj-card-icon { animation-delay: 1s; }
@keyframes wjFloat {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}
.wj-card-label {
    font-family: 'Orbitron', sans-serif;
    font-size: .55rem;
    letter-spacing: 4px;
    color: rgba(255,255,255,.5);
    position: relative; z-index: 1;
}
.wj-card-body {
    padding: 22px 24px 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.wj-card-title {
    font-family: 'Cinzel Decorative', serif;
    font-size: 1rem;
    color: var(--text);
    margin-bottom: 10px;
    line-height: 1.3;
}
.wj-card-desc {
    color: var(--text-muted);
    font-size: .9rem;
    line-height: 1.7;
    margin-bottom: 18px;
    flex: 1;
}
.wj-features {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 7px;
    margin-bottom: 20px;
}
.wj-feature {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: .82rem;
    color: var(--text-muted);
}
.wj-feat-icon { font-size: 1rem; flex-shrink: 0; }
.wj-btn {
    display: block;
    width: 100%;
    padding: 11px 0;
    text-align: center;
    background: linear-gradient(135deg, var(--purple), #8b30ff);
    border: none;
    border-radius: 4px;
    color: white;
    font-family: 'Orbitron', sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-decoration: none;
    transition: all .3s;
    margin-top: auto;
}
.wj-btn:hover {
    box-shadow: 0 0 25px rgba(107,33,232,.5);
    transform: translateY(-1px);
}
.wj-btn-gold {
    background: linear-gradient(135deg, #b8860b, var(--gold));
    color: #0a0810;
}
.wj-btn-gold:hover { box-shadow: 0 0 25px rgba(240,192,64,.4); }
.wj-btn-red {
    background: linear-gradient(135deg, var(--red), #ff3355);
}
.wj-btn-red:hover { box-shadow: 0 0 25px rgba(204,34,51,.4); }

@media(max-width:900px) {
    .wj-grid { grid-template-columns: 1fr; }
}
@media(max-width:600px) {
    .wj-features { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<?php
$currentPage = 'home';
$basePath    = '';
require_once __DIR__ . '/includes/navbar.php';
?>

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
            <div class="hero-chars-stack">
                <div class="hcs-label">CURSED ENERGY DETECTED</div>
                <div class="hcs-row">
                    <?php
                    // Show up to 6 characters from the list
                    $heroChars = array_slice($characters, 0, 6);
                    $heroEmojis = ['👊','🌑','🔮','💀','🌸','🗡'];
                    $heroGradeColors = [
                        'Special Grade' => '#f0c040',
                        'Semi-Grade 1'  => '#cc99ff',
                        'Grade 1'       => '#9d4dff',
                        'Grade 2'       => '#4dc8ff',
                        'Grade 3'       => '#aaaacc',
                        'Grade 4'       => '#888888',
                        'Unranked'      => '#666666',
                    ];
                    foreach($heroChars as $hIdx => $hChar):
                        $isUp = ($hIdx % 2 === 0); // even = up, odd = down
                        $rotDeg = $isUp ? -4 : 4;
                        $translateY = $isUp ? '-28px' : '28px';
                        $glowColor = $heroGradeColors[$hChar['grade']] ?? '#9d4dff';
                        // Find image
                        $halfImg = null;
                        if(!empty($hChar['image_url'])) {
                            $base = pathinfo($hChar['image_url'], PATHINFO_FILENAME);
                            foreach(['webp','jpg','png'] as $ext) {
                                if(file_exists(__DIR__.'/asset/Half/'.$base.'.'.$ext)) {
                                    $halfImg = 'asset/Half/'.$base.'.'.$ext;
                                    break;
                                }
                            }
                            if(!$halfImg) $halfImg = 'asset/'.$hChar['image_url'];
                        }
                    ?>
                    <?php $floatDelay = round($hIdx * 0.4, 1); ?>
                    <a href="pages/character_detail.php?id=<?= $hChar['id'] ?>"
                       class="hcs-card <?= $isUp ? 'hcs-up' : 'hcs-down' ?>"
                       style="--rot:<?= $rotDeg ?>deg; --ty:<?= $translateY ?>; --glow:<?= $glowColor ?>; --float-delay:<?= $floatDelay ?>s;"
                       data-up="<?= $isUp ? 1 : 0 ?>">
                        <div class="hcs-card-inner">
                            <?php if($halfImg): ?>
                            <img src="<?= htmlspecialchars($halfImg) ?>"
                                 alt="<?= htmlspecialchars($hChar['name']) ?>"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="hcs-emoji-fallback" style="display:none"><?= $heroEmojis[$hIdx] ?></div>
                            <?php else: ?>
                            <div class="hcs-emoji-fallback"><?= $heroEmojis[$hIdx] ?></div>
                            <?php endif; ?>
                            <div class="hcs-overlay">
                                <div class="hcs-name"><?= htmlspecialchars($hChar['name']) ?></div>
                                <div class="hcs-grade"><?= htmlspecialchars($hChar['grade']) ?></div>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
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
            'Special Grade' => ['bg' => 'linear-gradient(135deg,rgba(240,192,64,.18),rgba(240,192,64,.04))', 'class' => 'grade-special'],
            'Semi-Grade 1'  => ['bg' => 'linear-gradient(135deg,rgba(157,77,255,.16),rgba(157,77,255,.03))', 'class' => 'grade-semi'],
            'Grade 1'       => ['bg' => 'linear-gradient(135deg,rgba(107,33,232,.15),rgba(107,33,232,.03))', 'class' => 'grade-1'],
            'Grade 2'       => ['bg' => 'linear-gradient(135deg,rgba(0,150,255,.12),rgba(0,150,255,.02))',   'class' => 'grade-2'],
            'Grade 3'       => ['bg' => 'linear-gradient(135deg,rgba(100,100,120,.14),rgba(100,100,120,.03))','class' => 'grade-3'],
            'Grade 4'       => ['bg' => 'linear-gradient(135deg,rgba(80,80,90,.12),rgba(80,80,90,.02))',     'class' => 'grade-4'],
            'Unranked'      => ['bg' => 'linear-gradient(135deg,rgba(60,60,70,.10),rgba(60,60,70,.02))',     'class' => 'grade-unranked'],
        ];
        
        foreach ($characters as $i => $char):
            $emoji = $charEmojis[$i % count($charEmojis)];
            $gradeData = $charColors[$char['grade']] ?? $charColors['Grade 3'];
        ?>
        <a href="pages/character_detail.php?id=<?= $char['id'] ?>" class="char-card">
            <div class="char-card-art">
                <div class="char-card-art-bg" style="background: <?= $gradeData['bg'] ?>"></div>
                <span class="char-grade-badge <?= $gradeData['class'] ?>"><?= htmlspecialchars($char['grade']) ?></span>
                <?php if (!empty($char['image_url'])): ?>
                <img class="char-card-img"
                     src="asset/<?= htmlspecialchars($char['image_url']) ?>"
                     alt="<?= htmlspecialchars($char['name']) ?>"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <span class="char-card-emoji" style="display:none"><?= $emoji ?></span>
                <?php else: ?>
                <span class="char-card-emoji"><?= $emoji ?></span>
                <?php endif; ?>
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

<!-- WORLD & JUJUTSU PREVIEW SECTION -->
<section class="world-jujutsu-section fade-in">
<div class="section">
    <div class="section-header">
        <span class="section-tag">Pelajari Lebih Dalam</span>
        <h2 class="section-title">Dunia & Sistem Jujutsu</h2>
        <p class="section-subtitle">Pahami dunia tersembunyi di balik realita, dari asal kutukan hingga teknik para sorcerer.</p>
    </div>
    <div class="wj-grid">

        <!-- WORLD CARD -->
        <div class="wj-card">
            <div class="wj-card-header wj-world">
                <div class="wj-card-bg-symbols">
                    <span class="wj-sym">🌍</span>
                    <span class="wj-sym wj-sym-2">💀</span>
                    <span class="wj-sym wj-sym-3">🌑</span>
                </div>
                <div class="wj-card-icon">🌍</div>
                <div class="wj-card-label">DUNIA</div>
            </div>
            <div class="wj-card-body">
                <h3 class="wj-card-title">World of Cursed Energy</h3>
                <p class="wj-card-desc">Dunia Jujutsu Kaisen tampak seperti dunia modern biasa, namun di balik itu tersembunyi realita gelap penuh roh kutukan yang lahir dari emosi negatif manusia.</p>
                <div class="wj-features">
                    <div class="wj-feature"><span class="wj-feat-icon">🌐</span><span>Dunia Tersembunyi</span></div>
                    <div class="wj-feature"><span class="wj-feat-icon">👻</span><span>Cursed Spirits</span></div>
                    <div class="wj-feature"><span class="wj-feat-icon">🏫</span><span>Jujutsu Schools</span></div>
                    <div class="wj-feature"><span class="wj-feat-icon">⚔️</span><span>Klan Sorcerer</span></div>
                </div>
                <a href="pages/world.php" class="wj-btn">
                    Jelajahi Dunia →
                </a>
            </div>
        </div>

        <!-- JUJUTSU CARD -->
        <div class="wj-card">
            <div class="wj-card-header wj-jujutsu">
                <div class="wj-card-bg-symbols">
                    <span class="wj-sym">呪</span>
                    <span class="wj-sym wj-sym-2">術</span>
                    <span class="wj-sym wj-sym-3">廻</span>
                </div>
                <div class="wj-card-icon">⚡</div>
                <div class="wj-card-label">SISTEM</div>
            </div>
            <div class="wj-card-body">
                <h3 class="wj-card-title">Sistem Jujutsu</h3>
                <p class="wj-card-desc">Pelajari sistem energi kutukan, teknik-teknik berbahaya, Domain Expansion, dan hierarki grade para sorcerer dalam dunia Jujutsu Kaisen.</p>
                <div class="wj-features">
                    <div class="wj-feature"><span class="wj-feat-icon">🔮</span><span>Cursed Energy</span></div>
                    <div class="wj-feature"><span class="wj-feat-icon">🌀</span><span>Domain Expansion</span></div>
                    <div class="wj-feature"><span class="wj-feat-icon">📊</span><span>Grade System</span></div>
                    <div class="wj-feature"><span class="wj-feat-icon">💠</span><span>Cursed Techniques</span></div>
                </div>
                <a href="pages/jujutsu.php" class="wj-btn wj-btn-gold">
                    Pelajari Jujutsu →
                </a>
            </div>
        </div>

        <!-- STORY CARD -->
        <div class="wj-card">
            <div class="wj-card-header wj-story">
                <div class="wj-card-bg-symbols">
                    <span class="wj-sym">📖</span>
                    <span class="wj-sym wj-sym-2">⚔</span>
                    <span class="wj-sym wj-sym-3">🔥</span>
                </div>
                <div class="wj-card-icon">📖</div>
                <div class="wj-card-label">CERITA</div>
            </div>
            <div class="wj-card-body">
                <h3 class="wj-card-title">Alur Cerita</h3>
                <p class="wj-card-desc">Ikuti perjalanan epik Yuji Itadori dari siswa biasa menjadi sorcerer terkuat, melewati pertempuran, kehilangan, dan pengorbanan yang tak terbayangkan.</p>
                <div class="wj-features">
                    <div class="wj-feature"><span class="wj-feat-icon">👊</span><span>Cursed Womb Arc</span></div>
                    <div class="wj-feature"><span class="wj-feat-icon">🏆</span><span>Kyoto Goodwill</span></div>
                    <div class="wj-feature"><span class="wj-feat-icon">🌆</span><span>Shibuya Incident</span></div>
                    <div class="wj-feature"><span class="wj-feat-icon">🎲</span><span>Culling Game</span></div>
                </div>
                <a href="pages/story.php" class="wj-btn wj-btn-red">
                    Baca Story Arc →
                </a>
            </div>
        </div>

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