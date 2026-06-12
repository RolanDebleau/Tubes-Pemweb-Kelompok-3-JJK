<?php
require_once '../includes/config.php';
$currentPage = 'jujutsu'; $basePath = '../';
$search = trim($_GET['search'] ?? '');
$type_filter = $_GET['type'] ?? '';
$db = getDB();
$sql = "SELECT * FROM cursed_techniques WHERE 1=1";
$params=[]; $types='';
if (!empty($search)){
    $sql.=" AND (name LIKE ? OR user_name LIKE ?)";
    $s="%$search%"; $params=[$s,$s]; $types='ss';
}
if (!empty($type_filter)){
    $sql.=" AND type=?"; $params[]=$type_filter; $types.='s';
}
$sql.=" ORDER BY power_level DESC";
$stmt=$db->prepare($sql);
if(!empty($params)) $stmt->bind_param($types,...$params);
$stmt->execute();
$techniques=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Jujutsu — Cursed Techniques | JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
:root{--black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--red:#cc2233;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--border-gold:rgba(240,192,64,.2);--card-bg:rgba(10,8,20,.85);--nav-h:80px;}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;}::-webkit-scrollbar-track{background:#08060f;}::-webkit-scrollbar-thumb{background:#3a0d7a;border-radius:3px;}

/* PAGE HEADER */
.page-hero{padding-top:calc(var(--nav-h)+60px);padding-bottom:52px;text-align:center;position:relative;overflow:hidden;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 50% at 50% 0%,rgba(107,33,232,.15),transparent 60%);pointer-events:none;}
.hero-eyebrow{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:5px;color:var(--purple-glow);text-transform:uppercase;margin-bottom:12px;display:block;}
.hero-title{font-family:'Cinzel Decorative',serif;font-size:clamp(2rem,4vw,3rem);background:linear-gradient(135deg,#fff,var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:10px;}
.hero-sub{font-size:1rem;color:var(--text-muted);max-width:560px;margin:0 auto 28px;line-height:1.7;}
.hero-div{width:70px;height:2px;background:linear-gradient(to right,var(--purple),var(--gold));margin:0 auto;}

/* FILTER BAR */
.filter-bar{display:flex;gap:12px;align-items:center;flex-wrap:wrap;max-width:1160px;margin:0 auto 36px;padding:0 32px;}
.search-wrap{flex:1;min-width:220px;position:relative;}
.search-wrap input{width:100%;background:var(--card-bg);border:1px solid var(--border);border-radius:4px;color:var(--text);font-family:'Rajdhani',sans-serif;font-size:.95rem;padding:10px 14px 10px 38px;outline:none;transition:border-color .3s;}
.search-wrap input:focus{border-color:var(--purple-glow);}
.search-wrap::before{content:'🔍';position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.8rem;}
.filter-select{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;color:var(--text-muted);font-family:'Orbitron',sans-serif;font-size:.58rem;letter-spacing:1px;padding:10px 14px;outline:none;cursor:pointer;transition:border-color .3s;}
.filter-select:focus{border-color:var(--purple-glow);}
.results-count{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);}

/* BASARA-STYLE GRID */
.basara-grid{max-width:1160px;margin:0 auto;padding:0 32px 80px;display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:28px;align-items:start;}

/* Each card has staggered vertical offset + tilt */
.basara-card{position:relative;cursor:pointer;text-decoration:none;color:inherit;display:block;transition:transform .35s cubic-bezier(.23,1,.32,1),box-shadow .35s;will-change:transform;}
.basara-card:nth-child(4n+1){transform:rotate(-2.5deg) translateY(-14px);}
.basara-card:nth-child(4n+2){transform:rotate(2deg) translateY(10px);}
.basara-card:nth-child(4n+3){transform:rotate(-1.5deg) translateY(-6px);}
.basara-card:nth-child(4n+4){transform:rotate(2.5deg) translateY(16px);}
.basara-card:hover{transform:rotate(0deg) translateY(-18px) scale(1.06)!important;z-index:20;box-shadow:0 24px 60px rgba(107,33,232,.35),0 0 0 1px rgba(157,77,255,.5);}

/* Card inner */
.card-inner{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;overflow:hidden;transition:border-color .35s;}
.basara-card:hover .card-inner{border-color:var(--purple-glow);}

/* Art area */
.card-art{height:200px;position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;}
.card-art-img{width:100%;height:100%;object-fit:cover;object-position:center;transition:transform .5s;}
.basara-card:hover .card-art-img{transform:scale(1.08);}
.card-art-emoji{font-size:4.5rem;z-index:1;position:relative;}
.card-art-bg{position:absolute;inset:0;}
.card-art-overlay{position:absolute;bottom:0;left:0;right:0;height:60%;background:linear-gradient(to top,var(--card-bg),transparent);z-index:2;}

/* Type badge */
.type-badge{position:absolute;top:10px;right:10px;z-index:3;font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:1.5px;padding:4px 9px;border-radius:2px;text-transform:uppercase;}
.tb-innate{background:rgba(107,33,232,.3);border:1px solid var(--purple);color:var(--purple-glow);}
.tb-domain{background:rgba(240,192,64,.2);border:1px solid var(--gold);color:var(--gold);}
.tb-special{background:rgba(56,189,248,.2);border:1px solid #38bdf8;color:#38bdf8;}
.tb-shikigami{background:rgba(16,185,129,.2);border:1px solid #10b981;color:#34d399;}
.tb-noninnate{background:rgba(239,68,68,.15);border:1px solid #ef4444;color:#fca5a5;}

/* Domain star */
.domain-star{position:absolute;top:10px;left:10px;z-index:3;font-size:.9rem;}

/* Card body */
.card-body{padding:14px 16px 16px;}
.card-name{font-family:'Cinzel Decorative',serif;font-size:.85rem;color:var(--text);margin-bottom:3px;line-height:1.3;}
.card-jp{font-size:.75rem;color:var(--text-muted);margin-bottom:8px;}
.card-user{font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:1.5px;color:var(--gold);margin-bottom:8px;}
.card-desc{font-size:.82rem;color:var(--text-muted);line-height:1.6;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}
.card-power{margin-top:12px;}
.cp-row{display:flex;align-items:center;gap:8px;}
.cp-lbl{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:1px;color:var(--text-muted);width:42px;}
.cp-track{flex:1;height:4px;background:rgba(255,255,255,.05);border-radius:2px;overflow:hidden;}
.cp-fill{height:100%;border-radius:2px;background:linear-gradient(to right,var(--purple),var(--purple-glow));}
.cp-val{font-family:'Orbitron',sans-serif;font-size:.52rem;color:var(--purple-glow);min-width:24px;text-align:right;}
.card-cta{display:block;text-align:center;margin-top:12px;padding:8px;background:rgba(107,33,232,.15);border:1px solid rgba(107,33,232,.3);border-radius:3px;font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--purple-glow);transition:all .3s;}
.basara-card:hover .card-cta{background:var(--purple);border-color:var(--purple);color:white;}

/* EMPTY */
.empty-state{text-align:center;padding:80px 20px;color:var(--text-muted);grid-column:1/-1;}
.empty-icon{font-size:3rem;margin-bottom:16px;display:block;}

/* ART BG colors per type */
.bg-innate{background:linear-gradient(135deg,#0d0520,#2a0a5e);}
.bg-domain{background:linear-gradient(135deg,#1a100a,#3d1f00);}
.bg-special{background:linear-gradient(135deg,#051520,#0a3040);}
.bg-shikigami{background:linear-gradient(135deg,#051a10,#0a3520);}
.bg-noninnate{background:linear-gradient(135deg,#200a0a,#4a1010);}
</style>
</head>
<body>
<?php $currentPage='jujutsu'; $basePath='../'; include '../includes/navbar.php'; ?>

<div class="page-hero">
  <span class="hero-eyebrow">Sistem Sihir Kutukan</span>
  <h1 class="hero-title">Cursed Techniques</h1>
  <p class="hero-sub">Eksplorasi seluruh teknik kutukan, Domain Expansion, dan kemampuan spesial dari dunia Jujutsu Kaisen.</p>
  <div class="hero-div"></div>
</div>

<div class="filter-bar">
  <form method="GET" style="display:contents">
    <div class="search-wrap">
      <input type="text" name="search" placeholder="Cari teknik atau pengguna..." value="<?=htmlspecialchars($search)?>">
    </div>
    <select name="type" class="filter-select" onchange="this.form.submit()">
      <option value="">Semua Tipe</option>
      <?php foreach(['Innate Technique','Non-Innate','Domain Expansion','Special Ability','Shikigami'] as $t): ?>
      <option value="<?=$t?>" <?=$type_filter===$t?'selected':''?>><?=$t?></option>
      <?php endforeach; ?>
    </select>
  </form>
  <span class="results-count"><?=count($techniques)?> TEKNIK DITEMUKAN</span>
</div>

<div class="basara-grid">
<?php if(empty($techniques)): ?>
  <div class="empty-state"><span class="empty-icon">⚡</span><p>Tidak ada teknik yang ditemukan.</p></div>
<?php else: ?>
<?php
$typeIcons=['Innate Technique'=>'⚡','Non-Innate'=>'🔮','Domain Expansion'=>'🌐','Special Ability'=>'⭐','Shikigami'=>'🐉'];
$typeCss  =['Innate Technique'=>'tb-innate','Non-Innate'=>'tb-noninnate','Domain Expansion'=>'tb-domain','Special Ability'=>'tb-special','Shikigami'=>'tb-shikigami'];
$artBg    =['Innate Technique'=>'bg-innate','Non-Innate'=>'bg-noninnate','Domain Expansion'=>'bg-domain','Special Ability'=>'bg-special','Shikigami'=>'bg-shikigami'];
foreach($techniques as $t):
  $icon=$typeIcons[$t['type']]??'⚡';
  $tcss=$typeCss[$t['type']]??'tb-innate';
  $abg =$artBg[$t['type']]??'bg-innate';
?>
<a href="jujutsu_detail.php?id=<?=$t['id']?>" class="basara-card">
  <div class="card-inner">
    <div class="card-art">
      <div class="card-art-bg <?=$abg?>"></div>
      <?php if(!empty($t['image_url'])): ?>
      <img class="card-art-img" src="../asset/techniques/<?=htmlspecialchars($t['image_url'])?>"
           alt="<?=htmlspecialchars($t['name'])?>"
           onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
      <div class="card-art-emoji" style="display:none"><?=$icon?></div>
      <?php else: ?>
      <div class="card-art-emoji"><?=$icon?></div>
      <?php endif; ?>
      <div class="card-art-overlay"></div>
      <span class="type-badge <?=$tcss?>"><?=str_replace(' ','\n',$t['type'])?></span>
      <?php if($t['is_domain']): ?><span class="domain-star">🌐</span><?php endif; ?>
    </div>
    <div class="card-body">
      <div class="card-name"><?=htmlspecialchars($t['name'])?></div>
      <div class="card-jp"><?=htmlspecialchars($t['name_jp']??'')?></div>
      <div class="card-user">— <?=htmlspecialchars($t['user_name']??'Unknown')?></div>
      <div class="card-desc"><?=htmlspecialchars($t['description']??'')?></div>
      <div class="card-power">
        <div class="cp-row">
          <span class="cp-lbl">Power</span>
          <div class="cp-track"><div class="cp-fill" style="width:<?=$t['power_level']?>%"></div></div>
          <span class="cp-val"><?=$t['power_level']?></span>
        </div>
      </div>
      <span class="card-cta">LIHAT DETAIL →</span>
    </div>
  </div>
</a>
<?php endforeach; ?>
<?php endif; ?>
</div>

<footer style="background:rgba(3,2,10,.9);border-top:1px solid var(--border);padding:28px;text-align:center;">
  <div style="font-family:'Cinzel Decorative',serif;font-size:1rem;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:6px;">呪術廻戦</div>
  <div style="font-size:.75rem;color:var(--text-muted);">Jujutsu Kaisen © Gege Akutami / Shueisha · Praktikum Pemrograman Web 2026</div>
</footer>
</body>
</html>