<?php
require_once '../includes/config.php';
$id=(int)($_GET['id']??0);
$loc=getLocationById($id);
if(!$loc){header('Location: world.php');exit;}
$typeColors=['School'=>['a'=>'#38bdf8','bg'=>'rgba(56,189,248,.1)','glow'=>'rgba(56,189,248,.25)'],'City'=>['a'=>'#9d4dff','bg'=>'rgba(107,33,232,.1)','glow'=>'rgba(107,33,232,.25)'],'Battlefield'=>['a'=>'#ff6677','bg'=>'rgba(204,34,51,.1)','glow'=>'rgba(204,34,51,.25)'],'Landmark'=>['a'=>'#34d399','bg'=>'rgba(16,185,129,.1)','glow'=>'rgba(16,185,129,.25)'],'Clan Compound'=>['a'=>'#fcd34d','bg'=>'rgba(245,158,11,.1)','glow'=>'rgba(245,158,11,.25)'],'Hidden'=>['a'=>'#a5b4fc','bg'=>'rgba(99,102,241,.1)','glow'=>'rgba(99,102,241,.25)'],'Colony'=>['a'=>'#f0c040','bg'=>'rgba(240,192,64,.1)','glow'=>'rgba(240,192,64,.25)'],'Dimension'=>['a'=>'#f0c040','bg'=>'rgba(240,192,64,.08)','glow'=>'rgba(240,192,64,.2)']];
$tc=$typeColors[$loc['type']]??$typeColors['Landmark'];
$sigDots=round($loc['significance_level']/20);
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?=htmlspecialchars($loc['name'])?> — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
:root{--black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--card-bg:rgba(10,8,20,.85);--nav-h:80px;--accent:<?=$tc['a']?>;--accent-bg:<?=$tc['bg']?>;--accent-glow:<?=$tc['glow']?>;}
*{margin:0;padding:0;box-sizing:border-box;}body{background:transparent;color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;}::-webkit-scrollbar-track{background:#08060f;}::-webkit-scrollbar-thumb{background:#3a0d7a;border-radius:3px;}
.page-header{padding:calc(var(--nav-h)+44px) 60px 32px;border-bottom:1px solid var(--border);background:linear-gradient(180deg,<?=$tc['bg']?>,transparent);position:relative;}
.page-header::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 50%,<?=$tc['bg']?>,transparent 70%);pointer-events:none;}
.breadcrumb{display:flex;align-items:center;gap:8px;font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--text-muted);text-transform:uppercase;margin-bottom:14px;}
.breadcrumb a{color:var(--text-muted);text-decoration:none;transition:color .2s;}.breadcrumb a:hover{color:var(--accent);}
.breadcrumb span{color:var(--accent);}
.page-title{font-family:'Cinzel Decorative',serif;font-size:clamp(1.6rem,3.5vw,2.6rem);color:var(--text);margin-bottom:4px;}
.page-jp{font-size:.95rem;color:var(--text-muted);margin-bottom:6px;letter-spacing:2px;}
.page-region{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:3px;color:var(--accent);margin-bottom:14px;}
.type-chip{display:inline-flex;align-items:center;gap:8px;font-family:'Orbitron',sans-serif;font-size:.58rem;letter-spacing:3px;padding:5px 14px;border:1px solid var(--accent);color:var(--accent);background:var(--accent-bg);border-radius:2px;text-transform:uppercase;}
.wiki-wrap{max-width:1160px;margin:0 auto;padding:40px 60px 80px;display:flex;gap:36px;align-items:flex-start;}
.wiki-main{flex:1;min-width:0;}
.wiki-sidebar{width:290px;flex-shrink:0;position:sticky;top:calc(var(--nav-h)+20px);}
.ws-sec{margin-bottom:32px;}
.ws-title{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--text);padding-bottom:8px;border-bottom:1px solid var(--border);margin-bottom:14px;display:flex;align-items:center;gap:10px;}
.ws-text{font-size:.95rem;color:var(--text-muted);line-height:1.85;}
.ws-text p{margin-bottom:10px;}.ws-text p:last-child{margin-bottom:0;}
.hl-box{background:var(--accent-bg);border:1px solid var(--accent);border-left:3px solid var(--accent);border-radius:4px;padding:16px 20px;margin:14px 0;}
.hl-label{font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:3px;color:var(--accent);text-transform:uppercase;margin-bottom:6px;}
.hl-text{font-size:.9rem;color:var(--text-muted);line-height:1.75;}
.infobox{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;overflow:hidden;}
.infobox-title{background:rgba(107,33,232,.2);border-bottom:1px solid var(--border);padding:12px 16px;font-family:'Cinzel Decorative',serif;font-size:.85rem;color:var(--text);text-align:center;}
.infobox-art{height:200px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;background:linear-gradient(135deg,<?=$tc['bg']?>,rgba(3,2,10,1));border-bottom:1px solid var(--border);}
.infobox-art img{width:100%;height:100%;object-fit:cover;}
.infobox-art-fallback{position:relative;z-index:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;}
.infobox-art-fallback-kanji{font-family:'Cinzel Decorative',serif;font-size:3.6rem;font-weight:900;color:rgba(255,255,255,.14);}
.infobox-art-fallback-label{font-family:'Orbitron',sans-serif;font-size:.62rem;letter-spacing:3px;color:rgba(255,255,255,.4);text-transform:uppercase;}
.infobox-art video{width:100%;height:100%;object-fit:cover;}
.infobox-art-aura{position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 40%,<?=$tc['glow']?>,transparent 70%);pointer-events:none;}
.ib-table{width:100%;}
.ib-table tr{border-bottom:1px solid rgba(107,33,232,.08);}
.ib-table tr:last-child{border-bottom:none;}
.ib-table th{font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:2px;color:var(--text-muted);padding:10px 14px;text-align:left;background:rgba(107,33,232,.04);text-transform:uppercase;width:38%;}
.ib-table td{font-size:.88rem;color:var(--text);padding:10px 14px;line-height:1.5;}
.sig-row{display:flex;gap:4px;align-items:center;}
.sig-dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.1);}
.sig-dot.active{background:var(--accent);}
.back-btn{display:inline-flex;align-items:center;gap:8px;font-family:'Orbitron',sans-serif;font-size:.58rem;letter-spacing:2px;color:var(--text-muted);text-decoration:none;padding:8px 16px;border:1px solid var(--border);border-radius:3px;transition:all .3s;margin-bottom:24px;}
.back-btn:hover{border-color:var(--accent);color:var(--accent);}
.quick-nav{margin-top:16px;background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:16px;}
.qn-title{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--text-muted);text-transform:uppercase;margin-bottom:10px;}
.qn-link{display:block;font-size:.88rem;color:var(--text-muted);text-decoration:none;padding:8px 0;border-bottom:1px solid rgba(107,33,232,.08);transition:color .2s;}
.qn-link:last-child{border-bottom:none;}.qn-link:hover{color:var(--accent);}
@media(max-width:900px){.wiki-wrap{flex-direction:column-reverse;padding:24px 20px 60px;}.wiki-sidebar{width:100%;position:static;}.page-header{padding-left:20px;padding-right:20px;}}
</style>
</head>
<body>
<?php $currentPage='world'; $basePath='../'; include '../includes/navbar2.php'; ?>

<div class="page-header">
  <div class="breadcrumb">
    <a href="../index.php">Home</a><span>/</span>
    <a href="world.php">World</a><span>/</span>
    <span><?=htmlspecialchars($loc['name'])?></span>
  </div>
  <h1 class="page-title"><?=htmlspecialchars($loc['name'])?></h1>
  <div class="page-jp"><?=htmlspecialchars($loc['name_jp']??'')?></div>
  <div class="page-region"> <?=htmlspecialchars($loc['region']??'Unknown Location')?></div>
  <span class="type-chip"><?=htmlspecialchars($loc['type'])?></span>
</div>

<div class="wiki-wrap">
  <div class="wiki-main">
    <a href="world.php" class="back-btn">← Kembali ke World</a>
    <div class="ws-sec">
      <h2 class="ws-title">Deskripsi</h2>
      <div class="ws-text"><p><?=nl2br(htmlspecialchars($loc['description']??''))?></p></div>
    </div>
    <?php if(!empty($loc['lore'])): ?>
    <div class="ws-sec">
      <h2 class="ws-title">Sejarah & Lore</h2>
      <div class="ws-text"><?php foreach(array_filter(explode("\n",$loc['lore'])) as $p): ?><p><?=htmlspecialchars(trim($p))?></p><?php endforeach; ?></div>
    </div>
    <?php endif; ?>
    <div class="ws-sec">
      <h2 class="ws-title">Kepentingan Lokasi</h2>
      <div class="hl-box">
        <div class="hl-label">Significance Level</div>
        <div style="display:flex;gap:6px;margin:8px 0"><?php for($d=1;$d<=5;$d++): ?><div class="sig-dot <?=$d<=$sigDots?'active':''?>" style="width:12px;height:12px;border-radius:50%;background:<?=$d<=$sigDots?$tc['a']:'rgba(255,255,255,.1)'?>"></div><?php endfor; ?></div>
        <div class="hl-text"><?=$loc['significance_level']?>/100 — Lokasi ini memiliki peran <?=$loc['significance_level']>=90?'sangat krusial':($loc['significance_level']>=70?'penting':'signifikan')?> dalam alur cerita Jujutsu Kaisen.</div>
      </div>
    </div>
  </div>

  <div class="wiki-sidebar">
    <div class="infobox">
      <div class="infobox-title"><?=htmlspecialchars($loc['name'])?></div>
      <div class="infobox-art">
        <div class="infobox-art-aura"></div>
        <?php if(!empty($loc['image_url'])): ?>
          <?php if(isVideoFile($loc['image_url'])): ?>
          <video autoplay loop muted playsinline controls
                 src="../asset/World/<?=htmlspecialchars($loc['image_url'])?>"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"></video>
          <?php else: ?>
          <img src="../asset/World/<?=htmlspecialchars($loc['image_url'])?>" alt="<?=htmlspecialchars($loc['name'])?>"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
          <?php endif; ?>
        <div class="infobox-art-fallback" style="display:none">
          <div class="infobox-art-fallback-kanji">呪</div>
          <div class="infobox-art-fallback-label"><?=htmlspecialchars($loc['type'])?></div>
        </div>
        <?php else: ?>
        <div class="infobox-art-fallback">
          <div class="infobox-art-fallback-kanji">呪</div>
          <div class="infobox-art-fallback-label"><?=htmlspecialchars($loc['type'])?></div>
        </div>
        <?php endif; ?>
      </div>
      <table class="ib-table">
        <tr><th>Tipe</th><td><?=htmlspecialchars($loc['type'])?></td></tr>
        <tr><th>Region</th><td><?=htmlspecialchars($loc['region']??'-')?></td></tr>
        <tr><th>Importansi</th><td><div class="sig-row"><?php for($d=1;$d<=5;$d++): ?><div class="sig-dot <?=$d<=$sigDots?'active':''?>"></div><?php endfor; ?> <?=$loc['significance_level']?>/100</div></td></tr>
      </table>
    </div>
    <div class="quick-nav">
      <div class="qn-title">Navigasi</div>
      <a href="../index.php" class="qn-link">Home</a>
      <a href="world.php" class="qn-link">Semua Lokasi</a>
      <a href="characters.php" class="qn-link">Characters</a>
      <a href="jujutsu.php" class="qn-link">Jujutsu</a>
    </div>
  </div>
</div>

<footer style="background:rgba(3,2,10,.9);border-top:1px solid var(--border);padding:28px;text-align:center;">
  <div style="font-family:'Cinzel Decorative',serif;font-size:1rem;background:linear-gradient(135deg,var(--gold),var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:6px;">呪術廻戦</div>
  <div style="font-size:.75rem;color:var(--text-muted);">Jujutsu Kaisen © Gege Akutami / Shueisha</div>
</footer>
</body>
</html>