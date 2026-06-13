<?php
require_once '../includes/config.php';
$search=trim($_GET['search']??'');
$type_filter=$_GET['type']??'';
$db=getDB();
$sql="SELECT * FROM world_locations WHERE 1=1";
$params=[];$types='';
if(!empty($search)){$sql.=" AND (name LIKE ? OR region LIKE ?)";$s="%$search%";$params=[$s,$s];$types='ss';}
if(!empty($type_filter)){$sql.=" AND type=?";$params[]=$type_filter;$types.='s';}
$sql.=" ORDER BY significance_level DESC";
$stmt=$db->prepare($sql);
if(!empty($params))$stmt->bind_param($types,...$params);
$stmt->execute();
$locations=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>World — Locations | JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
:root{--black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--red:#cc2233;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--border-gold:rgba(240,192,64,.2);--card-bg:rgba(10,8,20,.85);--nav-h:80px;}
*{margin:0;padding:0;box-sizing:border-box;}html{scroll-behavior:smooth;}
body{background:transparent;color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;}::-webkit-scrollbar-track{background:#08060f;}::-webkit-scrollbar-thumb{background:#3a0d7a;border-radius:3px;}
.page-hero{padding-top:calc(var(--nav-h)+60px);padding-bottom:52px;text-align:center;position:relative;overflow:hidden;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 50% at 50% 0%,rgba(240,192,64,.1),transparent 60%);pointer-events:none;}
.hero-eyebrow{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:5px;color:var(--gold);text-transform:uppercase;margin-bottom:12px;display:block;}
.hero-title{font-family:'Cinzel Decorative',serif;font-size:clamp(2rem,4vw,3rem);background:linear-gradient(135deg,#fff,var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:10px;}
.hero-sub{font-size:1rem;color:var(--text-muted);max-width:560px;margin:0 auto 28px;line-height:1.7;}
.hero-div{width:70px;height:2px;background:linear-gradient(to right,var(--gold),var(--purple));margin:0 auto;}
.filter-bar{display:flex;gap:12px;align-items:center;flex-wrap:wrap;max-width:1160px;margin:0 auto 36px;padding:0 32px;}
.search-wrap{flex:1;min-width:220px;position:relative;}
.search-wrap input{width:100%;background:var(--card-bg);border:1px solid var(--border);border-radius:4px;color:var(--text);font-family:'Rajdhani',sans-serif;font-size:.95rem;padding:10px 14px 10px 38px;outline:none;transition:border-color .3s;}
.search-wrap input:focus{border-color:var(--gold);}
.search-wrap::before{content:'';position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.8rem;}
.filter-select{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;color:var(--text-muted);font-family:'Orbitron',sans-serif;font-size:.58rem;letter-spacing:1px;padding:10px 14px;outline:none;cursor:pointer;transition:border-color .3s;}
.filter-select:focus{border-color:var(--gold);}
.results-count{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);}

/* BASARA GRID for locations */
.basara-grid{max-width:1160px;margin:0 auto;padding:1rem 32px 80px;display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:28px;align-items:start;}
.basara-card{position:relative;cursor:pointer;text-decoration:none;color:inherit;display:block;transition:transform .35s cubic-bezier(.23,1,.32,1),box-shadow .35s;will-change:transform;}
.basara-card:nth-child(3n+1){transform:rotate(-1.8deg) translateY(-10px);}
.basara-card:nth-child(3n+2){transform:rotate(1.5deg) translateY(8px);}
.basara-card:nth-child(3n+3){transform:rotate(-1deg) translateY(-4px);}
.basara-card:hover{transform:rotate(0deg) translateY(-16px) scale(1.04)!important;z-index:20;box-shadow:0 24px 60px rgba(240,192,64,.2),0 0 0 1px rgba(240,192,64,.4);}
.card-inner{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;overflow:hidden;transition:border-color .35s;}
.basara-card:hover .card-inner{border-color:var(--gold);}
/* Per-type subtle borders for world locations */
.basara-card[data-type="School"] .card-inner{border-width:2px;border-color:rgba(56,189,248,.5);}
.basara-card[data-type="City"] .card-inner{border-width:2px;border-color:rgba(157,77,255,.5);}
.basara-card[data-type="Battlefield"] .card-inner{border-width:2px;border-color:rgba(255,80,100,.5);}
.basara-card[data-type="Colony"] .card-inner, .basara-card[data-type="Dimension"] .card-inner{border-width:2px;border-color:rgba(240,192,64,.5);}
.card-art{height:180px;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;}
.card-art-img{width:100%;height:100%;object-fit:cover;transition:transform .5s;}
.basara-card:hover .card-art-img{transform:scale(1.08);}
.card-art-fallback{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:1;gap:6px;}
.card-art-fallback-kanji{font-family:'Cinzel Decorative',serif;font-size:3.2rem;font-weight:900;color:rgba(255,255,255,.12);letter-spacing:2px;}
.card-art-fallback-label{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:3px;color:rgba(255,255,255,.35);text-transform:uppercase;}
.card-art-bg{position:absolute;inset:0;}
.card-art-overlay{position:absolute;bottom:0;left:0;right:0;height:55%;background:linear-gradient(to top,var(--card-bg),transparent);z-index:2;}
/* Type colors */
.bg-school{background:linear-gradient(135deg,#0a1520,#0c3050);}
.bg-city{background:linear-gradient(135deg,#0a1020,#1a2040);}
.bg-battlefield{background:linear-gradient(135deg,#200a0a,#400a0a);}
.bg-landmark{background:linear-gradient(135deg,#0d1520,#1a2a10);}
.bg-clan{background:linear-gradient(135deg,#150a05,#3d1a00);}
.bg-hidden{background:linear-gradient(135deg,#05050a,#150a30);}
.bg-colony{background:linear-gradient(135deg,#1a0a20,#3d0a50);}
.bg-dimension{background:linear-gradient(135deg,#050a1a,#0a1040);}
.type-badge{position:absolute;top:10px;right:10px;z-index:3;font-family:'Orbitron',sans-serif;font-size:.48rem;letter-spacing:1.5px;padding:4px 9px;border-radius:2px;text-transform:uppercase;}
.tb-school{background:rgba(56,189,248,.2);border:1px solid #38bdf8;color:#38bdf8;}
.tb-city{background:rgba(107,33,232,.2);border:1px solid var(--purple);color:var(--purple-glow);}
.tb-battlefield{background:rgba(204,34,51,.2);border:1px solid var(--red);color:#ff6677;}
.tb-landmark{background:rgba(16,185,129,.2);border:1px solid #10b981;color:#34d399;}
.tb-clan{background:rgba(245,158,11,.2);border:1px solid #f59e0b;color:#fcd34d;}
.tb-hidden{background:rgba(99,102,241,.2);border:1px solid #6366f1;color:#a5b4fc;}
.tb-colony,.tb-dimension{background:rgba(240,192,64,.15);border:1px solid var(--gold);color:var(--gold);}
.sig-dots{position:absolute;bottom:10px;left:10px;z-index:3;display:flex;gap:3px;}
.sig-dot{width:6px;height:6px;border-radius:50%;background:rgba(255,255,255,.2);}
.sig-dot.active{background:var(--gold);}
.card-body{padding:14px 16px 16px;}
.card-name{font-family:'Cinzel Decorative',serif;font-size:.9rem;color:var(--text);margin-bottom:3px;}
.card-jp{font-size:.75rem;color:var(--text-muted);margin-bottom:6px;}
.card-region{font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:1.5px;color:var(--gold);margin-bottom:8px;}
.card-desc{font-size:.82rem;color:var(--text-muted);line-height:1.6;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}
.card-cta{display:block;text-align:center;margin-top:12px;padding:8px;background:rgba(240,192,64,.1);border:1px solid rgba(240,192,64,.3);border-radius:3px;font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--gold);transition:all .3s;}
.basara-card:hover .card-cta{background:rgba(240,192,64,.25);border-color:var(--gold);}
.empty-state{text-align:center;padding:80px 20px;color:var(--text-muted);grid-column:1/-1;}
</style>
</head>
<body>
<?php $currentPage='world'; $basePath='../'; include '../includes/navbar2.php'; ?>

<section class="search-filter-section">
  <form method="GET" class="search-form">
    <input type="text" name="search" class="search-input"
      placeholder="Cari lokasi atau region..."
      value="<?=htmlspecialchars($search)?>">
    <button type="submit" class="btn-search">Cari</button>
    <?php if(!empty($search)||!empty($type_filter)): ?>
      <a href="world.php" class="btn-reset">Reset</a>
    <?php endif; ?>
  </form>
  <div class="type-filters">
    <a href="world.php" class="type-btn <?=empty($type_filter)?'active':''?>">Semua</a>
    <?php foreach(['School','City','Battlefield','Landmark','Clan Compound','Hidden','Colony','Dimension'] as $tp): ?>
    <a href="?type=<?=urlencode($tp)?><?=!empty($search)?'&search='.urlencode($search):''?>"
       class="type-btn <?=$type_filter===$tp?'active':''?>"><?=$tp?></a>
    <?php endforeach; ?>
  </div>
</section>

<div class="basara-grid">
<?php if(empty($locations)): ?>
<div class="empty-state"><span style="font-size:3rem;display:block;margin-bottom:16px"></span><p>Tidak ada lokasi yang ditemukan.</p></div>
<?php else: ?>
<?php
$typeCss=['School'=>'tb-school','City'=>'tb-city','Battlefield'=>'tb-battlefield','Landmark'=>'tb-landmark','Clan Compound'=>'tb-clan','Hidden'=>'tb-hidden','Colony'=>'tb-colony','Dimension'=>'tb-dimension'];
$artBg=['School'=>'bg-school','City'=>'bg-city','Battlefield'=>'bg-battlefield','Landmark'=>'bg-landmark','Clan Compound'=>'bg-clan','Hidden'=>'bg-hidden','Colony'=>'bg-colony','Dimension'=>'bg-dimension'];
foreach($locations as $loc):
  $tcss=$typeCss[$loc['type']]??'tb-landmark';
  $abg=$artBg[$loc['type']]??'bg-landmark';
  $sigDots=round($loc['significance_level']/20);
?>
<a href="world_detail.php?id=<?=$loc['id']?>" class="basara-card" data-type="<?=htmlspecialchars($loc['type'])?>">
  <div class="card-inner">
    <div class="card-art">
      <div class="card-art-bg <?=$abg?>"></div>
      <?php if(!empty($loc['image_url'])): ?>
        <?php if(isVideoFile($loc['image_url'])): ?>
        <video class="card-art-img" autoplay loop muted playsinline
               src="../asset/World/<?=htmlspecialchars($loc['image_url'])?>"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"></video>
        <?php else: ?>
        <img class="card-art-img" src="../asset/World/<?=htmlspecialchars($loc['image_url'])?>"
             alt="<?=htmlspecialchars($loc['name'])?>"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
        <?php endif; ?>
      <div class="card-art-fallback" style="display:none">
        <div class="card-art-fallback-kanji">呪</div>
        <div class="card-art-fallback-label"><?=htmlspecialchars($loc['type'])?></div>
      </div>
      <?php else: ?>
      <div class="card-art-fallback">
        <div class="card-art-fallback-kanji">呪</div>
        <div class="card-art-fallback-label"><?=htmlspecialchars($loc['type'])?></div>
      </div>
      <?php endif; ?>
      <div class="card-art-overlay"></div>
      <span class="type-badge <?=$tcss?>"><?=$loc['type']?></span>
      <div class="sig-dots">
        <?php for($d=1;$d<=5;$d++): ?><div class="sig-dot <?=$d<=$sigDots?'active':''?>"></div><?php endfor; ?>
      </div>
    </div>
    <div class="card-body">
      <div class="card-name"><?=htmlspecialchars($loc['name'])?></div>
      <div class="card-jp"><?=htmlspecialchars($loc['name_jp']??'')?></div>
      <div class="card-region"> <?=htmlspecialchars($loc['region']??'Unknown')?></div>
      <div class="card-desc"><?=htmlspecialchars($loc['description']??'')?></div>
      <span class="card-cta">JELAJAHI LOKASI →</span>
    </div>
  </div>
</a>
<?php endforeach; ?>
<?php endif; ?>
</div>

<footer style="background:rgba(3,2,10,.9);border-top:1px solid var(--border);padding:28px;text-align:center;">
  <div style="font-family:'Cinzel Decorative',serif;font-size:1rem;background:linear-gradient(135deg,var(--gold),var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:6px;">呪術廻戦</div>
  <div style="font-size:.75rem;color:var(--text-muted);">Jujutsu Kaisen © Gege Akutami / Shueisha · Praktikum Pemrograman Web 2026</div>
</footer>
</body>
</html>