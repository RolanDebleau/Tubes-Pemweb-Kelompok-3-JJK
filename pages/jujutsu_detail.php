<?php require_once '../includes/config.php'; 

// Technique data — in a real app this would come from DB
$techniques = [
    'limitless' => [
        'id'       => 'limitless',
        'name'     => 'Limitless',
        'jp'       => '無下限呪術',
        'user'     => 'Satoru Gojo',
        'type'     => 'Innate Technique',
        'grade'    => 'Special Grade',
        'color'    => '#4dc8ff',
        'icon'     => '∞',
        'desc'     => 'Memanipulasi ruang di tingkat atom menggunakan matematika tak terbatas. Infinity secara otomatis melambatkan semua yang mendekati Gojo hingga tidak pernah benar-benar menyentuhnya.',
        'gif'      => null, // 'gifs/limitless.gif' when available
        'variants' => [
            ['name'=>'Infinity (Passive)', 'jp'=>'無限', 'color'=>'#4dc8ff', 'desc'=>'Pelindung pasif yang memanipulasi ruang — semua yang mendekati Gojo secara teoritis tidak pernah mencapai jarak nol.'],
            ['name'=>'Blue — Cursed Technique Lapse', 'jp'=>'蒼', 'color'=>'#2266ff', 'desc'=>'Menciptakan titik daya tarik negatif di ruang, menarik segala sesuatu menuju titik tersebut dengan kekuatan dahsyat.'],
            ['name'=>'Red — Cursed Technique Reversal', 'jp'=>'赫', 'color'=>'#ff3355', 'desc'=>'Kebalikan dari Blue — menciptakan titik tolak positif yang mendorong semua materi menjauh dengan kekuatan destruktif.'],
            ['name'=>'Hollow Purple', 'jp'=>'虚式·茈', 'color'=>'#9d4dff', 'desc'=>'Kombinasi Blue dan Red — dua daya berlawanan bertabrakan menghasilkan vortex penghapusan materi. Segala yang terkena teknik ini dihapus dari eksistensi.'],
        ],
        'domain'   => ['name'=>'Unlimited Void', 'jp'=>'無量空処', 'desc'=>'Membuka koneksi ke alam semesta yang tak terbatas — informasi, rangsangan, dan keberadaan tak terbatas membanjiri kesadaran lawan secara bersamaan. Dalam hitungan detik, lawan lumpuh total karena otak tidak mampu memproses semua informasi.'],
    ],
    'ten-shadows' => [
        'id'       => 'ten-shadows',
        'name'     => 'Ten Shadows Technique',
        'jp'       => '十種影法術',
        'user'     => 'Megumi Fushiguro',
        'type'     => 'Innate Technique',
        'grade'    => 'Grade 2',
        'color'    => '#888888',
        'icon'     => '🌑',
        'desc'     => 'Memanggil hingga 10 shikigami menggunakan bayangan sebagai medium. Shikigami yang dikalahkan kehilangan kemampuan dipanggil — kecuali kekuatannya diserap shikigami lain.',
        'gif'      => null,
        'variants' => [
            ['name'=>'Divine Dogs', 'jp'=>'神犬', 'color'=>'#aaaacc', 'desc'=>'Dua anjing — hitam (kuat) dan putih (lincah). Jika satu terbunuh, kekuatannya diserap yang selamat.'],
            ['name'=>'Nue', 'jp'=>'鵺', 'color'=>'#44aaff', 'desc'=>'Chimera burung raksasa dengan kemampuan terbang dan listrik. Dipakai untuk mobilitas dan serangan udara.'],
            ['name'=>'Great Serpent', 'jp'=>'大蛇', 'color'=>'#44cc44', 'desc'=>'Ular raksasa yang menelan target, digunakan untuk menjebak lawan.'],
            ['name'=>'Toad', 'jp'=>'蝦蟇', 'color'=>'#88bb44', 'desc'=>'Katak raksasa dengan lidah panjang untuk menangkap dan memindahkan target.'],
            ['name'=>'Max Elephant', 'jp'=>'満象', 'color'=>'#88aacc', 'desc'=>'Gajah raksasa yang menyemburkan air bervolume luar biasa besar, cukup untuk menghancurkan bangunan.'],
            ['name'=>'Mahoraga', 'jp'=>'魔虚羅', 'color'=>'#ff4444', 'desc'=>'Shikigami paling berbahaya — tidak pernah ditaklukkan siapa pun. Mampu beradaptasi dan kebal terhadap teknik apa pun setelah terpapar satu kali.'],
        ],
        'domain'   => ['name'=>'Chimera Shadow Garden', 'jp'=>'嵌合暗翳庭', 'desc'=>'Dimensi yang sepenuhnya terdiri dari bayangan cair. Megumi bisa memanggil semua shikigami dari segala arah. Domain masih dalam pengembangan saat pertama digunakan.'],
    ],
    'blood-manipulation' => [
        'id'       => 'blood-manipulation',
        'name'     => 'Blood Manipulation',
        'jp'       => '赤血操術',
        'user'     => 'Choso / Noritoshi Kamo',
        'type'     => 'Innate Technique (Klan Kamo)',
        'grade'    => 'Grade 1',
        'color'    => '#cc2233',
        'icon'     => '🩸',
        'desc'     => 'Mengontrol darah — milik sendiri maupun yang sudah keluar dari tubuh — untuk menciptakan proyektil, senjata solid, dan manipulasi aliran darah lawan.',
        'gif'      => null,
        'variants' => [
            ['name'=>'Flowing Red Scale', 'jp'=>'流赤鱗', 'color'=>'#cc2233', 'desc'=>'Meningkatkan sirkulasi darah dan suhu tubuh secara ekstrem untuk boost kecepatan dan kekuatan fisik.'],
            ['name'=>'Crimson Binding', 'jp'=>'赤縛', 'color'=>'#ee4444', 'desc'=>'Membuat darah mengeras seperti baja — digunakan untuk mengikat atau membekukan darah lawan dalam tubuhnya.'],
            ['name'=>'Supernova', 'jp'=>'赤星', 'color'=>'#ff6644', 'desc'=>'Melemparkan bola-bola darah bertekanan tinggi sebagai proyektil kecepatan peluru. Dapat dikontrol arahnya.'],
            ['name'=>'Piercing Blood', 'jp'=>'穿血', 'color'=>'#ff2244', 'desc'=>'Memompa darah menjadi aliran sempit dengan tekanan luar biasa — mampu menembus baja dan pertahanan kutukan.'],
        ],
        'domain'   => null,
    ],
    'dismantle' => [
        'id'       => 'dismantle',
        'name'     => 'Dismantle & Cleave',
        'jp'       => '解・捌',
        'user'     => 'Ryomen Sukuna',
        'type'     => 'Innate Technique',
        'grade'    => 'Special Grade',
        'color'    => '#f0c040',
        'icon'     => '💀',
        'desc'     => 'Teknik sayatan Sukuna yang tidak tertandingi. Dismantle untuk benda mati, Cleave untuk makhluk hidup — menyesuaikan kekuatan otomatis dengan ketangguhan target.',
        'gif'      => null,
        'variants' => [
            ['name'=>'Dismantle', 'jp'=>'解', 'color'=>'#f0c040', 'desc'=>'Serangan sayatan acak dengan energi tetap — paling efektif untuk benda mati atau target yang tidak bisa melawan.'],
            ['name'=>'Cleave', 'jp'=>'捌', 'color'=>'#ff9900', 'desc'=>'Menyesuaikan kekuatan secara otomatis dengan ketangguhan target — memastikan kehancuran total. Tidak ada pertahanan yang cukup.'],
            ['name'=>'Slash (Sukuna Arrow)', 'jp'=>'矢', 'color'=>'#ffcc00', 'desc'=>'Proyektil sayatan yang ditembakkan dari jarak jauh dengan akurasi sempurna — dipakai Sukuna untuk serangan dari kejauhan.'],
        ],
        'domain'   => ['name'=>'Malevolent Shrine', 'jp'=>'伏魔御廚子', 'desc'=>'Domain tanpa barrier — memperluas ke dunia nyata dalam radius 200 meter, menghancurkan segalanya dengan Dismantle dan Cleave terus menerus. Tidak bisa ditangkal dengan domain counter.'],
    ],
    'idle-transfiguration' => [
        'id'       => 'idle-transfiguration',
        'name'     => 'Idle Transfiguration',
        'jp'       => '無為転変',
        'user'     => 'Mahito',
        'type'     => 'Innate Technique (Cursed Spirit)',
        'grade'    => 'Special Grade',
        'color'    => '#10b981',
        'icon'     => '🪆',
        'desc'     => 'Menyentuh dan mengubah bentuk jiwa (soul) secara langsung. Karena jiwa mendefinisikan tubuh, setiap perubahan jiwa mengubah bentuk fisik target seketika.',
        'gif'      => null,
        'variants' => [
            ['name'=>'Soul Distortion', 'jp'=>'魂の歪み', 'color'=>'#10b981', 'desc'=>'Mengubah bentuk jiwa lawan dengan sentuhan — dapat mengubah manusia menjadi boneka kutukan atau membunuh instan.'],
            ['name'=>'Self-Embodiment', 'jp'=>'自己具現', 'color'=>'#34d399', 'desc'=>'Mengubah bentuk tubuh Mahito sendiri — mengembangkan anggota badan baru, meregenerasi luka, atau menciptakan senjata dari tubuhnya.'],
            ['name'=>'Polymorphic Soul Isomer', 'jp'=>'多形自己·同素体', 'color'=>'#6ee7b7', 'desc'=>'Menyerang jiwa lawan secara langsung — serangan yang ditujukan ke tingkat jiwa ini tidak bisa dihalangi pertahanan fisik biasa.'],
        ],
        'domain'   => ['name'=>'Self-Embodiment of Perfection', 'jp'=>'癈人〇', 'desc'=>'Memungkinkan Mahito menyentuh jiwa lawan secara langsung dan mengaktifkan Idle Transfiguration instan. Siapa pun yang tersentuh langsung mengalami deformasi jiwa.'],
    ],
];

// Get requested technique, default to first
$techId = $_GET['id'] ?? 'limitless';
$tech = $techniques[$techId] ?? $techniques['limitless'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($tech['name']) ?> — Jujutsu System</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root{
    --black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;
    --gold:#f0c040;--red:#cc2233;--text:#ede8f5;--text-muted:#7a7490;
    --border:rgba(107,33,232,.2);--card-bg:rgba(10,8,20,.85);--nav-h:80px;
    --accent: <?= htmlspecialchars($tech['color']) ?>;
}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;}::-webkit-scrollbar-track{background:#08060f;}::-webkit-scrollbar-thumb{background:#3a0d7a;border-radius:3px;}

/* BACK BUTTON */
.back-btn{display:inline-flex;align-items:center;gap:8px;padding-top:calc(var(--nav-h) + 24px);padding-left:40px;padding-bottom:0;font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);text-decoration:none;transition:color .3s;}
.back-btn:hover{color:var(--accent);}

/* HERO */
.tech-hero{
    position:relative;
    padding:40px 40px 60px;
    max-width:1100px;margin:0 auto;
    overflow:hidden;
}
.tech-hero::before{
    content:'';position:absolute;inset:0;
    background:radial-gradient(ellipse 60% 80% at 80% 50%, rgba(107,33,232,.08) 0%, transparent 60%);
    pointer-events:none;
}
.tech-hero-grid{display:grid;grid-template-columns:1fr auto;gap:40px;align-items:start;}
.tech-hero-left{}
.tech-eyebrow{font-family:'Orbitron',sans-serif;font-size:.58rem;letter-spacing:4px;color:var(--accent);margin-bottom:12px;display:block;}
.tech-title{font-family:'Cinzel Decorative',serif;font-size:clamp(1.6rem,3vw,2.8rem);color:var(--text);margin-bottom:6px;line-height:1.2;}
.tech-jp{font-family:'Cinzel Decorative',serif;font-size:.9em;background:linear-gradient(90deg,var(--accent),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:block;margin-bottom:20px;}
.tech-meta{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;}
.meta-badge{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:2px;padding:5px 12px;border-radius:2px;border:1px solid var(--border);color:var(--text-muted);}
.meta-badge.accent{border-color:var(--accent);color:var(--accent);background:rgba(107,33,232,.08);}
.tech-desc{color:var(--text-muted);font-size:1rem;line-height:1.8;max-width:680px;margin-bottom:24px;}

/* GIF / PREVIEW BOX */
.tech-preview-box{
    width:260px;min-height:200px;
    border:1px solid rgba(107,33,232,.3);
    border-radius:8px;overflow:hidden;
    background:var(--card-bg);
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    position:relative;
    flex-shrink:0;
    box-shadow:0 0 40px rgba(107,33,232,.15);
}
.tech-preview-box img{width:100%;height:100%;object-fit:cover;}
.tech-preview-placeholder{
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    gap:12px;padding:28px;text-align:center;
}
.tech-preview-icon{font-size:4rem;filter:drop-shadow(0 0 20px var(--accent));}
.tech-preview-label{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:3px;color:var(--text-muted);}
.tech-preview-hint{font-size:.8rem;color:rgba(125,115,160,.6);}

/* Divider */
.divider-line{width:100%;height:1px;background:linear-gradient(to right,transparent,var(--border),transparent);margin:0;}

/* CONTENT */
.tech-content{max-width:1100px;margin:0 auto;padding:0 40px 60px;}

/* SECTION */
.content-section{margin-bottom:52px;}
.cs-eyebrow{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:4px;color:var(--gold);text-transform:uppercase;margin-bottom:8px;}
.cs-title{font-family:'Cinzel Decorative',serif;font-size:1.3rem;color:var(--text);margin-bottom:8px;}
.cs-divider{width:36px;height:2px;background:linear-gradient(to right,var(--purple),var(--accent));margin-bottom:24px;}
.cs-desc{color:var(--text-muted);font-size:1rem;line-height:1.8;}

/* VARIANT CARDS */
.variants-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;}
.variant-card{
    background:var(--card-bg);
    border:1px solid var(--border);
    border-radius:6px;
    padding:20px 22px;
    transition:all .35s;
    position:relative;
    overflow:hidden;
}
.variant-card::before{
    content:'';position:absolute;left:0;top:0;bottom:0;width:3px;
    background:var(--vc-color, var(--accent));
    border-radius:3px 0 0 3px;
}
.variant-card:hover{border-color:var(--vc-color, var(--accent));transform:translateX(4px);box-shadow:0 8px 32px rgba(0,0,0,.3);}
.vc-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;}
.vc-name{font-family:'Cinzel Decorative',serif;font-size:.88rem;color:var(--text);line-height:1.3;}
.vc-jp{font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:2px;color:var(--vc-color, var(--accent));flex-shrink:0;}
.vc-desc{font-size:.9rem;color:var(--text-muted);line-height:1.75;}

/* DOMAIN CARD */
.domain-showcase{
    background:rgba(107,33,232,.06);
    border:1px solid rgba(107,33,232,.3);
    border-radius:8px;
    padding:28px 32px;
    position:relative;
    overflow:hidden;
}
.domain-showcase::before{
    content:'領域展開';
    position:absolute;right:-20px;top:50%;transform:translateY(-50%);
    font-family:'Cinzel Decorative',serif;font-size:5rem;
    color:rgba(107,33,232,.06);white-space:nowrap;pointer-events:none;
}
.ds-label{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:4px;color:var(--purple-glow);margin-bottom:8px;}
.ds-name{font-family:'Cinzel Decorative',serif;font-size:1.4rem;color:var(--text);margin-bottom:4px;}
.ds-jp{font-size:.9rem;color:var(--text-muted);margin-bottom:16px;font-style:italic;}
.ds-desc{color:var(--text-muted);font-size:1rem;line-height:1.8;}

/* NO DOMAIN */
.no-domain{
    background:rgba(10,8,20,.4);
    border:1px dashed rgba(107,33,232,.2);
    border-radius:6px;padding:28px;text-align:center;
    color:var(--text-muted);font-size:.9rem;
}

/* TECHNIQUE NAVIGATION (sibling techniques) */
.tech-nav{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:40px;}
.tech-nav-btn{
    font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:2px;
    padding:9px 16px;border-radius:3px;
    border:1px solid var(--border);
    background:transparent;color:var(--text-muted);
    text-decoration:none;transition:all .3s;
}
.tech-nav-btn:hover{border-color:var(--purple-glow);color:var(--text);}
.tech-nav-btn.active{border-color:var(--accent);color:var(--accent);background:rgba(107,33,232,.08);}

footer{border-top:1px solid var(--border);padding:28px 40px;text-align:center;}
.footer-logo{font-family:'Cinzel Decorative',serif;font-size:.95rem;color:var(--gold);}
.footer-sub{font-size:.75rem;color:var(--text-muted);margin-top:4px;}

@media(max-width:900px){
    .tech-hero-grid{grid-template-columns:1fr;}
    .tech-preview-box{width:100%;min-height:160px;}
    .variants-grid{grid-template-columns:1fr;}
    .tech-hero,.tech-content,.back-btn{padding-left:20px;padding-right:20px;}
}
</style>
</head>
<body>

<?php
$currentPage = 'jujutsu';
$basePath    = '../';
require_once __DIR__ . '/../includes/navbar.php';
?>

<a href="jujutsu.php" class="back-btn">← Kembali ke Sistem Jujutsu</a>

<!-- HERO -->
<div class="tech-hero">
    <div class="tech-hero-grid">
        <div class="tech-hero-left">
            <span class="tech-eyebrow"><?= htmlspecialchars($tech['type']) ?></span>
            <h1 class="tech-title"><?= htmlspecialchars($tech['name']) ?></h1>
            <span class="tech-jp"><?= htmlspecialchars($tech['jp']) ?></span>
            <div class="tech-meta">
                <span class="meta-badge accent">👤 <?= htmlspecialchars($tech['user']) ?></span>
                <span class="meta-badge">⭐ <?= htmlspecialchars($tech['grade']) ?></span>
                <span class="meta-badge"><?= htmlspecialchars($tech['type']) ?></span>
            </div>
            <p class="tech-desc"><?= htmlspecialchars($tech['desc']) ?></p>
        </div>

        <!-- GIF / Preview -->
        <div class="tech-preview-box">
            <?php if (!empty($tech['gif']) && file_exists(__DIR__.'/../asset/'.$tech['gif'])): ?>
            <img src="../asset/<?= htmlspecialchars($tech['gif']) ?>" alt="<?= htmlspecialchars($tech['name']) ?> animation">
            <?php else: ?>
            <div class="tech-preview-placeholder">
                <div class="tech-preview-icon"><?= htmlspecialchars($tech['icon']) ?></div>
                <div class="tech-preview-label">TECHNIQUE PREVIEW</div>
                <div class="tech-preview-hint">GIF animasi akan<br>ditampilkan di sini</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="divider-line"></div>

<!-- CONTENT -->
<div class="tech-content">

    <!-- Technique Navigation -->
    <div style="margin-top:36px;">
        <div style="font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:3px;color:var(--text-muted);margin-bottom:12px;">TEKNIK LAINNYA</div>
        <div class="tech-nav">
            <?php foreach($techniques as $t): ?>
            <a href="jujutsu_detail.php?id=<?= $t['id'] ?>"
               class="tech-nav-btn <?= $t['id']===$tech['id']?'active':'' ?>">
                <?= $t['icon'] ?> <?= htmlspecialchars($t['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Variants / Sub-techniques -->
    <?php if (!empty($tech['variants'])): ?>
    <div class="content-section">
        <div class="cs-eyebrow">Varian Teknik</div>
        <h2 class="cs-title">Jurus & Variasi</h2>
        <div class="cs-divider"></div>
        <div class="variants-grid">
            <?php foreach($tech['variants'] as $v): ?>
            <div class="variant-card" style="--vc-color:<?= htmlspecialchars($v['color']) ?>;">
                <div class="vc-header">
                    <div class="vc-name"><?= htmlspecialchars($v['name']) ?></div>
                    <div class="vc-jp"><?= htmlspecialchars($v['jp']) ?></div>
                </div>
                <p class="vc-desc"><?= htmlspecialchars($v['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Domain Expansion -->
    <div class="content-section">
        <div class="cs-eyebrow">Jurus Pamungkas</div>
        <h2 class="cs-title">Domain Expansion — 領域展開</h2>
        <div class="cs-divider"></div>
        <?php if (!empty($tech['domain'])): ?>
        <div class="domain-showcase">
            <div class="ds-label">DOMAIN EXPANSION</div>
            <div class="ds-name"><?= htmlspecialchars($tech['domain']['name']) ?></div>
            <div class="ds-jp"><?= htmlspecialchars($tech['domain']['jp']) ?></div>
            <p class="ds-desc"><?= htmlspecialchars($tech['domain']['desc']) ?></p>
        </div>
        <?php else: ?>
        <div class="no-domain">
            <div style="font-size:2rem;margin-bottom:8px;">🔮</div>
            Teknik ini tidak memiliki Domain Expansion yang diketahui, atau penggunanya belum mencapai level tersebut.
        </div>
        <?php endif; ?>
    </div>

</div>

<footer>
    <div class="footer-logo">呪術廻戦 — JJK Universe</div>
    <div class="footer-sub">Praktikum Pemrograman Web 2026</div>
</footer>
</body>
</html>
