<?php
require_once '../includes/config.php';
$id=(int)($_GET['id']??0);
$t=getTechniqueById($id);
if(!$t){header('Location: jujutsu.php');exit;}
$typeColors=['Innate Technique'=>['a'=>'#9d4dff','bg'=>'rgba(107,33,232,.12)','glow'=>'rgba(107,33,232,.3)'],
  'Non-Innate'=>['a'=>'#fca5a5','bg'=>'rgba(239,68,68,.1)','glow'=>'rgba(239,68,68,.25)'],
  'Domain Expansion'=>['a'=>'#f0c040','bg'=>'rgba(240,192,64,.1)','glow'=>'rgba(240,192,64,.3)'],
  'Special Ability'=>['a'=>'#38bdf8','bg'=>'rgba(56,189,248,.1)','glow'=>'rgba(56,189,248,.25)'],
  'Shikigami'=>['a'=>'#34d399','bg'=>'rgba(16,185,129,.1)','glow'=>'rgba(16,185,129,.25)']];
$tc=$typeColors[$t['type']]??$typeColors['Innate Technique'];
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?=htmlspecialchars($t['name'])?> — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
:root{--black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--border-gold:rgba(240,192,64,.2);--card-bg:rgba(10,8,20,.85);--nav-h:80px;--accent:<?=$tc['a']?>;--accent-bg:<?=$tc['bg']?>;--accent-glow:<?=$tc['glow']?>;}
*{margin:0;padding:0;box-sizing:border-box;}body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;}::-webkit-scrollbar-track{background:#08060f;}::-webkit-scrollbar-thumb{background:#3a0d7a;border-radius:3px;}
.page-header{padding:calc(var(--nav-h)+44px) 60px 32px;border-bottom:1px solid var(--border);background:linear-gradient(180deg,<?=$tc['bg']?>,transparent);}
.page-header::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 50%,<?=$tc['bg']?>,transparent 70%);pointer-events:none;position:absolute;}
.breadcrumb{display:flex;align-items:center;gap:8px;font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--text-muted);text-transform:uppercase;margin-bottom:14px;}
.breadcrumb a{color:var(--text-muted);text-decoration:none;transition:color .2s;}.breadcrumb a:hover{color:var(--accent);}
.breadcrumb span{color:var(--accent);}
.page-title{font-family:'Cinzel Decorative',serif;font-size:clamp(1.6rem,3.5vw,2.6rem);color:var(--text);margin-bottom:4px;}
.page-jp{font-size:.95rem;color:var(--text-muted);margin-bottom:14px;letter-spacing:2px;}
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
/* INFOBOX */
.infobox{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;overflow:hidden;}
.infobox-title{background:rgba(107,33,232,.2);border-bottom:1px solid var(--border);padding:12px 16px;font-family:'Cinzel Decorative',serif;font-size:.85rem;color:var(--text);text-align:center;}
.infobox-art{height:240px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;background:linear-gradient(135deg,<?=$tc['bg']?>,rgba(3,2,10,1));border-bottom:1px solid var(--border);}
.infobox-art img{width:100%;height:100%;object-fit:cover;transition:transform .4s;}
.infobox:hover .infobox-art img{transform:scale(1.05);}
.infobox-art-fallback{position:relative;z-index:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;}
.infobox-art-fallback-kanji{font-family:'Cinzel Decorative',serif;font-size:3.6rem;font-weight:900;color:rgba(255,255,255,.14);}
.infobox-art-fallback-label{font-family:'Orbitron',sans-serif;font-size:.62rem;letter-spacing:3px;color:rgba(255,255,255,.4);text-transform:uppercase;}
.infobox-art-aura{position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 40%,<?=$tc['glow']?>,transparent 70%);pointer-events:none;}
.infobox-art-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(107,33,232,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(107,33,232,.04) 1px,transparent 1px);background-size:24px 24px;}
.ib-table{width:100%;}
.ib-table tr{border-bottom:1px solid rgba(107,33,232,.08);}
.ib-table tr:last-child{border-bottom:none;}
.ib-table th{font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:2px;color:var(--text-muted);padding:10px 14px;text-align:left;background:rgba(107,33,232,.04);text-transform:uppercase;width:38%;}
.ib-table td{font-size:.88rem;color:var(--text);padding:10px 14px;line-height:1.5;}
.ib-type{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:1.5px;padding:3px 8px;border:1px solid var(--accent);color:var(--accent);background:var(--accent-bg);border-radius:2px;display:inline-block;}
.mini-bar{display:flex;align-items:center;gap:8px;}
.mini-track{flex:1;height:5px;background:rgba(255,255,255,.05);border-radius:3px;overflow:hidden;}
.mini-fill{height:100%;border-radius:3px;}
.mini-val{font-family:'Orbitron',sans-serif;font-size:.55rem;color:var(--accent);}
.domain-badge{display:inline-block;padding:4px 10px;background:rgba(240,192,64,.15);border:1px solid var(--gold);border-radius:2px;font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:2px;color:var(--gold);}
/* QUICK NAV */
.quick-nav{margin-top:16px;background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:16px;}
.qn-title{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--text-muted);text-transform:uppercase;margin-bottom:10px;}
.qn-link{display:block;font-size:.88rem;color:var(--text-muted);text-decoration:none;padding:8px 0;border-bottom:1px solid rgba(107,33,232,.08);transition:color .2s;}
.qn-link:last-child{border-bottom:none;}.qn-link:hover{color:var(--accent);}
/* BACK */
.back-btn{display:inline-flex;align-items:center;gap:8px;font-family:'Orbitron',sans-serif;font-size:.58rem;letter-spacing:2px;color:var(--text-muted);text-decoration:none;padding:8px 16px;border:1px solid var(--border);border-radius:3px;transition:all .3s;margin-bottom:24px;}
.back-btn:hover{border-color:var(--accent);color:var(--accent);}
@media(max-width:900px){.wiki-wrap{flex-direction:column-reverse;padding:24px 20px 60px;}.wiki-sidebar{width:100%;position:static;}.page-header{padding-left:20px;padding-right:20px;}}
</style>
</head>
<body>
<?php $currentPage='jujutsu'; $basePath='../'; include '../includes/navbar.php'; ?>

<div class="page-header" style="position:relative">
  <div class="breadcrumb">
    <a href="../index.php">Home</a><span>/</span>
    <a href="jujutsu.php">Jujutsu</a><span>/</span>
    <span><?=htmlspecialchars($t['name'])?></span>
  </div>
  <h1 class="page-title"><?=htmlspecialchars($t['name'])?></h1>
  <div class="page-jp"><?=htmlspecialchars($t['name_jp']??'')?></div>
  <span class="type-chip"><?=htmlspecialchars($t['type'])?><?=$t['is_domain']?' · DOMAIN EXPANSION':''?></span>
</div>

<div class="wiki-wrap">
  <div class="wiki-main">
    <a href="jujutsu.php" class="back-btn">← Kembali ke Jujutsu</a>
    <div class="ws-sec">
      <h2 class="ws-title"> Deskripsi</h2>
      <div class="ws-text"><p><?=nl2br(htmlspecialchars($t['description']??''))?></p></div>
    </div>
    <?php if(!empty($t['lore'])): ?>
    <div class="ws-sec">
      <h2 class="ws-title"> Lore & Detail Mendalam</h2>
      <div class="ws-text"><?php foreach(array_filter(explode("\n",$t['lore'])) as $p): ?><p><?=htmlspecialchars(trim($p))?></p><?php endforeach; ?></div>
    </div>
    <?php endif; ?>
    <div class="ws-sec">
      <h2 class="ws-title"> Cara Kerja Teknik</h2>
      <div class="hl-box">
        <div class="hl-label">Pengguna Utama</div>
        <div style="font-family:'Cinzel Decorative',serif;font-size:.95rem;color:var(--text);margin-bottom:6px;"><?=htmlspecialchars($t['user_name']??'Unknown')?></div>
        <div class="hl-text"><?=htmlspecialchars($t['affiliation']??'')?></div>
      </div>
      <?php if($t['is_domain']): ?>
      <div class="hl-box" style="border-color:var(--gold);background:rgba(240,192,64,.08);">
        <div class="hl-label" style="color:var(--gold);">Domain Expansion — 領域展開</div>
        <div class="hl-text">Teknik ini merupakan Domain Expansion — puncak dari cursed technique pengguna. Di dalam domain, semua serangan bersifat sure-hit (pasti mengenai). Mengaktifkan domain membutuhkan cursed energy yang sangat besar.</div>
      </div>
      <?php endif; ?>
    </div>
    <div class="ws-sec">
      <h2 class="ws-title"> Power Rating</h2>
      <?php
      $stats=[
        ['Power Level',$t['power_level'],'#9d4dff','Kekuatan destruktif teknik dalam pertarungan.'],
        ['Difficulty',$t['difficulty'],'#38bdf8','Tingkat kesulitan penguasaan teknik ini.'],
      ];
      foreach($stats as $s):?>
      <div style="margin-bottom:18px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
          <span style="font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);"><?=$s[0]?></span>
          <span style="font-family:'Orbitron',sans-serif;font-size:.68rem;font-weight:700;color:<?=$s[2]?>"><?=$s[1]?>/100</span>
        </div>
        <div style="height:7px;background:rgba(255,255,255,.05);border-radius:4px;overflow:hidden;margin-bottom:5px;">
          <div style="height:100%;width:<?=$s[1]?>%;background:<?=$s[2]?>;border-radius:4px;box-shadow:0 0 10px <?=$s[2]?>55;transition:width 1.2s ease;" class="stat-anim" data-w="<?=$s[1]?>"></div>
        </div>
        <p style="font-size:.82rem;color:var(--text-muted);"><?=$s[3]?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- INFOBOX RIGHT -->
  <div class="wiki-sidebar">
    <div class="infobox">
      <div class="infobox-title"><?=htmlspecialchars($t['name'])?></div>
      <div class="infobox-art">
        <div class="infobox-art-aura"></div>
        <div class="infobox-art-grid"></div>
        <?php
          $jAsset = null;
          if (!empty($t['image_url'])) {
            $folders = ['Domain Expansions','Innate Techniques','Non-Innate Techniques'];
            foreach ($folders as $jf) {
              $jcheck = __DIR__ . '/../asset/Jujutsu/' . $jf . '/' . $t['image_url'];
              if (file_exists($jcheck)) { $jAsset = '../asset/Jujutsu/' . $jf . '/' . $t['image_url']; break; }
            }
            // Also try direct path (if image_url already has subfolder)
            if (!$jAsset) {
              $jcheck2 = __DIR__ . '/../asset/Jujutsu/' . $t['image_url'];
              if (file_exists($jcheck2)) $jAsset = '../asset/Jujutsu/' . $t['image_url'];
            }
          }
          $jIsVideo = $jAsset && preg_match('/\.mp4$/i', $jAsset);
        ?>
        <?php if ($jAsset): ?>
          <?php if ($jIsVideo): ?>
          <video autoplay loop muted playsinline style="width:100%;height:100%;object-fit:cover;"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <source src="<?=$jAsset?>" type="video/mp4">
          </video>
          <div class="infobox-art-fallback" style="display:none">
          <div class="infobox-art-fallback-kanji">術</div>
          <div class="infobox-art-fallback-label"><?=htmlspecialchars($t['type'])?></div>
        </div>
          <?php else: ?>
          <img src="<?=$jAsset?>" alt="<?=htmlspecialchars($t['name'])?>"
               style="width:100%;height:100%;object-fit:cover;"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
          <div class="infobox-art-fallback" style="display:none">
          <div class="infobox-art-fallback-kanji">術</div>
          <div class="infobox-art-fallback-label"><?=htmlspecialchars($t['type'])?></div>
        </div>
          <?php endif; ?>
        <?php else: ?>
        <div class="infobox-art-fallback">
          <div class="infobox-art-fallback-kanji">術</div>
          <div class="infobox-art-fallback-label"><?=htmlspecialchars($t['type'])?></div>
        </div>
        <?php endif; ?>
      </div>
      <table class="ib-table">
        <tr><th>Tipe</th><td><span class="ib-type"><?=htmlspecialchars($t['type'])?></span></td></tr>
        <tr><th>Pengguna</th><td><?=htmlspecialchars($t['user_name']??'Unknown')?></td></tr>
        <tr><th>Afiliasi</th><td><?=htmlspecialchars($t['affiliation']??'-')?></td></tr>
        <tr><th>Domain</th><td><?=$t['is_domain']?'<span class="domain-badge">Ya</span>':'Bukan Domain'?></td></tr>
        <tr><th>Power</th><td>
          <div class="mini-bar"><div class="mini-track"><div class="mini-fill" style="width:<?=$t['power_level']?>%;background:var(--accent);"></div></div><span class="mini-val"><?=$t['power_level']?></span></div>
        </td></tr>
        <tr><th>Difficulty</th><td>
          <div class="mini-bar"><div class="mini-track"><div class="mini-fill" style="width:<?=$t['difficulty']?>%;background:#38bdf8;"></div></div><span class="mini-val" style="color:#38bdf8"><?=$t['difficulty']?></span></div>
        </td></tr>
      </table>
    </div>
    <div class="quick-nav">
      <div class="qn-title">Navigasi</div>
      <a href="../index.php" class="qn-link"> Home</a>
      <a href="characters.php" class="qn-link"> Characters</a>
      <a href="jujutsu.php" class="qn-link"> Semua Teknik</a>
      <a href="world.php" class="qn-link"> World</a>
      <a href="story.php" class="qn-link"> Story Arc</a>
    </div>
  </div>
</div>

<footer style="background:rgba(3,2,10,.9);border-top:1px solid var(--border);padding:28px;text-align:center;">
  <div style="font-family:'Cinzel Decorative',serif;font-size:1rem;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:6px;">呪術廻戦</div>
  <div style="font-size:.75rem;color:var(--text-muted);">Jujutsu Kaisen © Gege Akutami / Shueisha</div>
</footer>
</body>
</html>