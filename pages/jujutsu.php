<?php require_once '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jujutsu System — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root{--black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--red:#cc2233;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--border-gold:rgba(240,192,64,.2);--card-bg:rgba(10,8,20,.85);--nav-h:80px;}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;}::-webkit-scrollbar-track{background:#08060f;}::-webkit-scrollbar-thumb{background:#3a0d7a;border-radius:3px;}
















/* HERO */
.page-hero{padding-top:calc(var(--nav-h) + 80px);padding-bottom:70px;text-align:center;position:relative;overflow:hidden;padding-left:40px;padding-right:40px;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(107,33,232,.16) 0%,transparent 60%);pointer-events:none;}
.hero-eyebrow{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:5px;color:var(--purple-glow);text-transform:uppercase;margin-bottom:16px;display:block;}
.hero-title{font-family:'Cinzel Decorative',serif;font-size:clamp(2rem,4vw,3.2rem);background:linear-gradient(135deg,#fff,var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:16px;line-height:1.15;}
.hero-desc{color:var(--text-muted);font-size:1.05rem;max-width:620px;margin:0 auto 32px;line-height:1.7;}
.hero-divider{width:80px;height:2px;background:linear-gradient(to right,var(--purple),var(--gold));margin:0 auto;}

/* LAYOUT */
.content-wrap{max-width:1160px;margin:0 auto;padding:0 40px 80px;}
.world-section{margin-bottom:72px;}
.section-eyebrow{font-family:'Orbitron',sans-serif;font-size:.58rem;letter-spacing:4px;color:var(--gold);text-transform:uppercase;margin-bottom:10px;}
.section-title{font-family:'Cinzel Decorative',serif;font-size:1.6rem;color:var(--text);margin-bottom:8px;}
.section-divider{width:40px;height:2px;background:linear-gradient(to right,var(--purple),var(--gold));margin-bottom:28px;}
.section-intro{color:var(--text-muted);font-size:1rem;line-height:1.8;max-width:860px;margin-bottom:32px;}

/* CURSED ENERGY EXPLAINER */
.ce-visual{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1px;background:var(--border);border:1px solid var(--border);border-radius:6px;overflow:hidden;margin-bottom:32px;}
.ce-step{background:var(--card-bg);padding:28px 24px;text-align:center;}
.ce-step-num{font-family:'Orbitron',sans-serif;font-size:2rem;font-weight:700;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:8px;}
.ce-step-title{font-family:'Cinzel Decorative',serif;font-size:.85rem;color:var(--text);margin-bottom:10px;}
.ce-step-text{font-size:.88rem;color:var(--text-muted);line-height:1.7;}

/* TECHNIQUE TYPES */
.tech-types{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:32px;}
.tt-card{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:24px;transition:border-color .3s;}
.tt-card:hover{border-color:var(--purple-glow);}
.tt-badge{display:inline-block;font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:2px;padding:4px 10px;border-radius:2px;margin-bottom:12px;text-transform:uppercase;}
.tt-innate{background:rgba(107,33,232,.15);border:1px solid var(--purple);color:var(--purple-glow);}
.tt-noinnate{background:rgba(240,192,64,.1);border:1px solid var(--gold);color:var(--gold);}
.tt-reverse{background:rgba(16,185,129,.1);border:1px solid #10b981;color:#34d399;}
.tt-binding{background:rgba(239,68,68,.1);border:1px solid #ef4444;color:#fca5a5;}
.tt-title{font-family:'Cinzel Decorative',serif;font-size:.95rem;color:var(--text);margin-bottom:10px;}
.tt-text{font-size:.9rem;color:var(--text-muted);line-height:1.75;}
.tt-examples{display:flex;flex-wrap:wrap;gap:6px;margin-top:14px;}
.ex-badge{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:1px;padding:4px 9px;border:1px solid var(--border);border-radius:2px;color:var(--text-muted);}

/* TECHNIQUE LIST */
.tech-list{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
.tech-card{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:20px;transition:all .3s;cursor:default;}
.tech-card:hover{border-color:var(--purple-glow);transform:translateY(-3px);}
.tc-icon{font-size:1.8rem;margin-bottom:10px;display:block;}
.tc-name{font-family:'Cinzel Decorative',serif;font-size:.82rem;color:var(--text);margin-bottom:4px;}
.tc-user{font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:2px;color:var(--gold);margin-bottom:10px;}
.tc-desc{font-size:.85rem;color:var(--text-muted);line-height:1.7;}
.tc-tag{display:inline-block;margin-top:10px;font-family:'Orbitron',sans-serif;font-size:.48rem;letter-spacing:1px;padding:3px 8px;border:1px solid var(--border);border-radius:2px;color:var(--text-muted);}

/* DOMAIN TABLE */
.domain-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
.domain-card{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;overflow:hidden;transition:border-color .3s;}
.domain-card:hover{border-color:var(--purple-glow);}
.domain-header{padding:16px 20px;display:flex;align-items:center;gap:14px;border-bottom:1px solid var(--border);}
.domain-icon{font-size:1.8rem;}
.domain-name{font-family:'Cinzel Decorative',serif;font-size:.88rem;color:var(--text);}
.domain-jp{font-size:.8rem;color:var(--text-muted);}
.domain-body{padding:16px 20px;}
.domain-user{font-family:'Orbitron',sans-serif;font-size:.52rem;letter-spacing:2px;color:var(--gold);margin-bottom:8px;}
.domain-desc{font-size:.88rem;color:var(--text-muted);line-height:1.75;}

/* GRADE TABLE */
.grade-table{width:100%;border-collapse:collapse;border-radius:6px;overflow:hidden;}
.grade-table th{background:rgba(107,33,232,.2);font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;color:var(--text-muted);padding:12px 16px;text-align:left;border-bottom:1px solid var(--border);}
.grade-table td{padding:14px 16px;border-bottom:1px solid rgba(107,33,232,.1);font-size:.9rem;color:var(--text-muted);vertical-align:top;line-height:1.6;}
.grade-table tr:hover td{background:rgba(107,33,232,.05);}
.grade-table tr:last-child td{border-bottom:none;}
.grade-rank{font-family:'Orbitron',sans-serif;font-size:.65rem;letter-spacing:2px;font-weight:700;}
.grade-sp{color:var(--gold);}
.grade-1{color:var(--red);}
.grade-2{color:var(--purple-glow);}
.grade-3{color:var(--text-muted);}

/* SPECIAL BOX */
.special-box{background:rgba(240,192,64,.05);border:1px solid var(--border-gold);border-radius:6px;padding:24px 28px;margin-bottom:20px;}
.sb-title{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--gold);margin-bottom:10px;display:flex;align-items:center;gap:10px;}
.sb-text{font-size:.92rem;color:var(--text-muted);line-height:1.8;}

/* ACCORDION */
.accord{margin-bottom:8px;border:1px solid var(--border);border-radius:4px;overflow:hidden;}
.accord-head{padding:16px 20px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;background:var(--card-bg);transition:background .3s;user-select:none;}
.accord-head:hover{background:rgba(107,33,232,.1);}
.accord-head-title{font-family:'Cinzel Decorative',serif;font-size:.9rem;color:var(--text);}
.accord-arrow{color:var(--text-muted);font-size:.8rem;transition:transform .3s;}
.accord-body{display:none;padding:16px 20px;background:rgba(5,3,15,.6);border-top:1px solid var(--border);}
.accord-body.open{display:block;}
.accord-body p{font-size:.9rem;color:var(--text-muted);line-height:1.8;margin-bottom:10px;}
.accord-body p:last-child{margin-bottom:0;}
.accord.active .accord-arrow{transform:rotate(180deg);}

/* FOOTER */
footer{background:rgba(3,2,10,.9);border-top:1px solid var(--border);padding:32px 40px;text-align:center;}
.foot-logo{font-family:'Cinzel Decorative',serif;font-size:1.1rem;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:8px;}
.foot-text{font-size:.78rem;color:var(--text-muted);}

@media(max-width:900px){
  .tech-types,.tech-list,.domain-grid,.ce-visual{grid-template-columns:1fr;}
  .content-wrap,.page-hero{padding-left:20px;padding-right:20px;}
  
}
</style>
</head>
<body>

<?php
$currentPage = 'jujutsu';
$basePath    = '../';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="page-hero">
  <span class="hero-eyebrow">Sistem Sihir Kutukan</span>
  <h1 class="hero-title">The Art of Jujutsu</h1>
  <p class="hero-desc">Dari Cursed Energy yang mengalir dalam setiap manusia, hingga Domain Expansion yang menciptakan dimensi tersendiri — pelajari seluruh sistem sihir dunia Jujutsu Kaisen secara mendalam.</p>
  <div class="hero-divider"></div>
</div>

<div class="content-wrap">

  <!-- CURSED ENERGY -->
  <div class="world-section">
    <div class="section-eyebrow">Fondasi Segalanya</div>
    <h2 class="section-title">Cursed Energy — 呪力</h2>
    <div class="section-divider"></div>
    <p class="section-intro">Cursed Energy (呪力, Juryoku) adalah bentuk energi spiritual yang mengalir keluar dari manusia sebagai hasil dari emosi negatif mereka. Ini adalah fondasi dari seluruh sistem jujutsu — tanpa energi kutukan, tidak ada teknik, tidak ada pertarungan, dan tidak ada roh kutukan.</p>
    <div class="ce-visual">
      <div class="ce-step">
        <div class="ce-step-num">01</div>
        <div class="ce-step-title">Emosi Negatif</div>
        <p class="ce-step-text">Setiap manusia memancarkan cursed energy dari emosi negatif seperti rasa takut, kesedihan, kecemburuan, dan kebencian. Ini terjadi tanpa disadari — bahkan seorang bayi pun memancarkannya.</p>
      </div>
      <div class="ce-step">
        <div class="ce-step-num">02</div>
        <div class="ce-step-title">Akumulasi & Manifestasi</div>
        <p class="ce-step-text">Energi yang terakumulasi dapat mengkristal menjadi roh kutukan (cursed spirits). Di tempat-tempat dengan emosi negatif kuat — penjara, medan perang, kuil tua — kutukan terkuat dilahirkan.</p>
      </div>
      <div class="ce-step">
        <div class="ce-step-num">03</div>
        <div class="ce-step-title">Dikendalikan Penyihir</div>
        <p class="ce-step-text">Sorcerer mampu merasakan dan mengalirkan cursed energy secara sadar. Mereka menggunakannya untuk memperkuat tubuh, mengaktifkan teknik kutukan, dan pada akhirnya melawan roh kutukan yang lahir darinya.</p>
      </div>
    </div>
    <div class="special-box">
      <div class="sb-title">⚡ Reverse Cursed Technique — 反転術式</div>
      <p class="sb-text">Teknik langka yang menggunakan prinsip "negatif × negatif = positif". Alih-alih merusak, cursed energy yang dibalik menghasilkan energi penyembuhan (positive energy). Hanya segelintir sorcerer yang mampu menguasainya — termasuk Gojo Satoru, Yuta Okkotsu, dan Shoko Ieiri (yang menggunakannya murni untuk penyembuhan). Sukuna juga menguasai teknik ini hingga level yang memungkinkan ia meregenerasi tubuh secara instan.</p>
    </div>
    <div class="special-box" style="border-color:rgba(56,189,248,.3);background:rgba(56,189,248,.04);">
      <div class="sb-title" style="color:#38bdf8;">⚡ Black Flash — 黒閃</div>
      <p class="sb-text">Kondisi istimewa di mana penyihir berhasil mengalirkan cursed energy dalam waktu 0.000001 detik saat kontak fisik. Hasilnya adalah distorsi ruang berwarna hitam yang melipatgandakan kekuatan serangan menjadi 2,5 kali lipat dari pukulan biasa berpangkat dua. Tidak bisa dilakukan secara sengaja — hanya terjadi ketika semua kondisi fisik dan mental terpenuhi secara sempurna. Yuji Itadori memegang rekor paling banyak Black Flash berturut-turut.</p>
    </div>
  </div>

  <!-- TECHNIQUES -->
  <div class="world-section">
    <div class="section-eyebrow">Jenis Teknik</div>
    <h2 class="section-title">Cursed Techniques — 術式</h2>
    <div class="section-divider"></div>
    <p class="section-intro">Teknik kutukan adalah cara sorcerer menyalurkan cursed energy untuk efek yang lebih spesifik dan kuat daripada sekadar memperkuat tubuh. Ada berbagai jenis teknik, masing-masing dengan asal-usul dan cara kerja yang berbeda.</p>
    <div class="tech-types">
      <div class="tt-card">
        <span class="tt-badge tt-innate">Innate Technique</span>
        <div class="tt-title">Teknik Bawaan — 生得術式</div>
        <p class="tt-text">Teknik yang diwariskan secara turun-temurun dalam keluarga atau lahir secara unik dalam individu tertentu. Setiap sorcerer hanya punya satu innate technique yang sifatnya permanen. Teknik ini bisa dikembangkan dengan Extension (perluasan) dan akhirnya mencapai puncaknya dalam Domain Expansion.</p>
        <div class="tt-examples">
          <span class="ex-badge">Limitless (Gojo)</span>
          <span class="ex-badge">Ten Shadows (Megumi)</span>
          <span class="ex-badge">Ratio (Nanami)</span>
          <span class="ex-badge">Straw Doll (Nobara)</span>
          <span class="ex-badge">Blood Manipulation (Choso)</span>
        </div>
      </div>
      <div class="tt-card">
        <span class="tt-badge tt-noinnate">Non-Innate Technique</span>
        <div class="tt-title">Teknik Non-Bawaan</div>
        <p class="tt-text">Teknik yang dipelajari atau diperoleh melalui cara lain — bukan warisan genetik. Contohnya adalah teknik yang disalin (seperti Copy Technique milik Yuta melalui Rika) atau teknik yang diperoleh melalui pengorbanan khusus (Binding Vow). Langka dan memerlukan kondisi khusus untuk digunakan.</p>
        <div class="tt-examples">
          <span class="ex-badge">Copy (Yuta via Rika)</span>
          <span class="ex-badge">Simple Domain</span>
          <span class="ex-badge">Anti-Domain Technique</span>
        </div>
      </div>
      <div class="tt-card">
        <span class="tt-badge tt-reverse">Reverse Cursed Tech</span>
        <div class="tt-title">Teknik Kutukan Terbalik — 反転術式</div>
        <p class="tt-text">Dengan mengalikan dua aliran cursed energy negatif, dihasilkan energi positif yang bisa digunakan untuk penyembuhan atau serangan. Sangat sulit dikuasai karena mengharuskan sorcerer mempertahankan dua aliran energi berlawanan secara bersamaan. Mampu menyembuhkan luka parah yang tidak bisa ditangani medis biasa.</p>
        <div class="tt-examples">
          <span class="ex-badge">Gojo Satoru</span>
          <span class="ex-badge">Yuta Okkotsu</span>
          <span class="ex-badge">Shoko Ieiri</span>
          <span class="ex-badge">Sukuna</span>
        </div>
      </div>
      <div class="tt-card">
        <span class="tt-badge tt-binding">Binding Vow</span>
        <div class="tt-title">Ikrar Kutukan — 縛り</div>
        <p class="tt-text">Perjanjian yang mengikat antara dua pihak atau dengan diri sendiri menggunakan cursed energy sebagai jaminan. Dengan membatasi atau mengungkapkan sesuatu (memberi keuntungan pada lawan), sorcerer mendapat peningkatan kekuatan atau kemampuan baru sebagai imbalan. Melanggar Binding Vow akan melemahkan sorcerer tersebut secara drastis.</p>
        <div class="tt-examples">
          <span class="ex-badge">Gojo: Mengungkap Infinity</span>
          <span class="ex-badge">Nanami: Overtime Buff</span>
          <span class="ex-badge">Hakari: Jackpot Domain</span>
        </div>
      </div>
    </div>

    <!-- TECHNIQUE EXAMPLES -->
    <h3 style="font-family:'Cinzel Decorative',serif;font-size:1.1rem;color:var(--text);margin-bottom:20px;">Teknik-Teknik Terkenal</h3>
    <div class="tech-list">
      <div class="tech-card">
        <span class="tc-icon">∞</span>
        <div class="tc-name">Limitless — 無下限呪術</div>
        <div class="tc-user">Satoru Gojo · Klan Gojo</div>
        <p class="tc-desc">Memanipulasi ruang di tingkat atom. Infinity secara otomatis melambatkan semua yang mendekati Gojo hingga tidak pernah benar-benar menyentuhnya. Varian ofensif: Blue (притяжение), Red (tolakan), dan Purple (penghapusan materi).</p>
        <span class="tc-tag">Innate · Klan Gojo</span>
      </div>
      <div class="tech-card">
        <span class="tc-icon">🌑</span>
        <div class="tc-name">Ten Shadows — 十種影法術</div>
        <div class="tc-user">Megumi Fushiguro · Klan Zenin</div>
        <p class="tc-desc">Memanggil hingga 10 shikigami menggunakan bayangan sebagai medium. Setiap shikigami memiliki kemampuan unik. Mahoraga — shikigami ke-8 yang belum pernah ditaklukkan — mampu beradaptasi terhadap teknik apa pun dalam satu kali paparan.</p>
        <span class="tc-tag">Innate · Warisan Klan</span>
      </div>
      <div class="tech-card">
        <span class="tc-icon">🩸</span>
        <div class="tc-name">Blood Manipulation — 赤血操術</div>
        <div class="tc-user">Choso · Klan Kamo</div>
        <p class="tc-desc">Mengontrol darah — baik milik sendiri maupun darah yang sudah keluar dari tubuh. Memungkinkan penciptaan proyektil darah berkecepatan tinggi, solidifikasi darah sebagai senjata, dan bahkan memperlambat aliran darah lawan.</p>
        <span class="tc-tag">Innate · Warisan Klan Kamo</span>
      </div>
      <div class="tech-card">
        <span class="tc-icon">💀</span>
        <div class="tc-name">Dismantle & Cleave — 解・捌</div>
        <div class="tc-user">Ryomen Sukuna</div>
        <p class="tc-desc">Dismantle: serangan sayatan acak dengan energi tetap — efektif untuk benda mati. Cleave: menyesuaikan kekuatan secara otomatis dengan ketangguhan target hingga memastikan kehancuran. Tidak ada pertahanan yang bisa menahan Cleave jika Sukuna menargetkan seseorang.</p>
        <span class="tc-tag">Innate · Raja Kutukan</span>
      </div>
      <div class="tech-card">
        <span class="tc-icon">🔥</span>
        <div class="tc-name">Disaster Flames — 炎</div>
        <div class="tc-user">Jogo · Cursed Spirit</div>
        <p class="tc-desc">Jogo, roh kutukan bertema gunung berapi, dapat menghasilkan api dan magma dengan skala dahsyat. Satu serangan mampu menghanguskan seluruh stasiun kereta bawah tanah. Domain Expansion-nya — Coffin of the Iron Mountain — menciptakan lingkungan gunung berapi yang membakar segalanya.</p>
        <span class="tc-tag">Innate · Cursed Spirit</span>
      </div>
      <div class="tech-card">
        <span class="tc-icon">🪆</span>
        <div class="tc-name">Idle Transfiguration — 無為転変</div>
        <div class="tc-user">Mahito · Cursed Spirit</div>
        <p class="tc-desc">Mahito mampu menyentuh dan mengubah bentuk jiwa (soul) secara langsung. Karena jiwa mendefinisikan tubuh, bukan sebaliknya, setiap perubahan pada jiwa secara otomatis mengubah bentuk fisik. Teknik ini membuat Mahito sangat sulit dilawan karena serangan fisik biasa tidak efektif.</p>
        <span class="tc-tag">Innate · Cursed Spirit</span>
      </div>
      <div class="tech-card">
        <span class="tc-icon">🔨</span>
        <div class="tc-name">Straw Doll — 藁人形呪法</div>
        <div class="tc-user">Nobara Kugisaki</div>
        <p class="tc-desc">Menggunakan boneka jerami dan paku sebagai medium kutukan. Resonance mengirimkan kerusakan dari paku ke target yang terhubung kutukan dengannya — memungkinkan serangan jarak jauh yang tidak bisa dielak dengan berpindah posisi. Sangat efektif melawan kutukan dengan dua jiwa dalam satu tubuh.</p>
        <span class="tc-tag">Innate · Unik</span>
      </div>
      <div class="tech-card">
        <span class="tc-icon">📐</span>
        <div class="tc-name">Ratio Technique — 十劃呪法</div>
        <div class="tc-user">Kento Nanami</div>
        <p class="tc-desc">Membagi setiap objek menjadi 10 bagian dan menyerang titik 7:3 yang merupakan titik terlemah secara inheren. Tidak ada satu pun makhluk hidup yang kebal terhadap teknik ini selama mereka memiliki "titik lemah". Nanami memanfaatkan ini dengan efisiensi luar biasa menggunakan senjata tumpul yang dibalut kutukan.</p>
        <span class="tc-tag">Innate · Profesional</span>
      </div>
      <div class="tech-card">
        <span class="tc-icon">⛓️</span>
        <div class="tc-name">Heavenly Restriction — 天与呪縛</div>
        <div class="tc-user">Toji Fushiguro · Maki Zenin</div>
        <p class="tc-desc">Bukan teknik yang dipelajari, melainkan kondisi bawaan lahir. Sorcerer yang lahir dengan Heavenly Restriction memiliki cursed energy yang sangat terbatas atau bahkan nol sama sekali — sebagai gantinya, tubuh mereka ditingkatkan secara ekstrem melampaui batas manusia normal. Toji Fushiguro adalah contoh ekstremnya.</p>
        <span class="tc-tag">Bawaan Lahir · Langka</span>
      </div>
    </div>
  </div>

  <!-- SHIKIGAMI -->
  <div class="world-section">
    <div class="section-eyebrow">Makhluk Pemanggilan</div>
    <h2 class="section-title">Shikigami — 式神</h2>
    <div class="section-divider"></div>
    <p class="section-intro">Shikigami adalah makhluk spiritual yang dapat dipanggil oleh sorcerer tertentu untuk bertarung. Pengguna Ten Shadows Technique milik Megumi adalah contoh terbaik — ia bisa memanggil hingga 10 shikigami berbeda, masing-masing dengan kemampuan unik.</p>
    <div class="accord" onclick="toggleAccord(this)">
      <div class="accord-head"><span class="accord-head-title">🐕 Divine Dogs (神犬 · Macan Dewa)</span><span class="accord-arrow">▼</span></div>
      <div class="accord-body"><p>Dua anjing hitam-putih yang dapat mencium dan melacak kutukan. Anjing hitam memiliki kekuatan lebih besar, sementara anjing putih lebih lincah. Setelah satu terbunuh, kekuatannya diserap oleh yang selamat — menjadikan yang tersisa lebih kuat.</p></div>
    </div>
    <div class="accord" onclick="toggleAccord(this)">
      <div class="accord-head"><span class="accord-head-title">🦅 Nue (鵺 · Chimera Petir)</span><span class="accord-arrow">▼</span></div>
      <div class="accord-body"><p>Shikigami bertema burung besar dengan kemampuan terbang dan listrik. Dapat membawa pengguna ke udara dan menyerang dari atas. Digunakan Megumi untuk mobilitas cepat dan serangan area.</p></div>
    </div>
    <div class="accord" onclick="toggleAccord(this)">
      <div class="accord-head"><span class="accord-head-title">🐍 Great Serpent (大蛇 · Ular Raksasa)</span><span class="accord-arrow">▼</span></div>
      <div class="accord-body"><p>Ular raksasa yang bisa menelan target sepenuhnya. Digunakan untuk menjebak lawan, memberikan Megumi waktu untuk menyiapkan serangan lanjutan atau Domain Expansion.</p></div>
    </div>
    <div class="accord" onclick="toggleAccord(this)">
      <div class="accord-head"><span class="accord-head-title">🌸 Max Elephant (満象 · Gajah Agung)</span><span class="accord-arrow">▼</span></div>
      <div class="accord-body"><p>Shikigami berbentuk gajah raksasa yang mampu menyemburkan air dalam volume luar biasa besar. Serangan semprotannya cukup kuat untuk menghancurkan bangunan dan memukul mundur kutukan kelas tinggi sekalipun.</p></div>
    </div>
    <div class="accord" onclick="toggleAccord(this)">
      <div class="accord-head"><span class="accord-head-title">🐢 Toad (蝦蟇 · Katak Raksasa)</span><span class="accord-arrow">▼</span></div>
      <div class="accord-body"><p>Katak raksasa dengan lidah panjang yang bisa menangkap dan menelan target. Dapat mengevakuasi orang yang perlu dilindungi dari zona bahaya dengan cepat.</p></div>
    </div>
    <div class="accord" onclick="toggleAccord(this)">
      <div class="accord-head"><span class="accord-head-title">⚠️ Eight-Handled Sword Divergent Sila Divine General Mahoraga (八握剣異戒神将魔虚羅)</span><span class="accord-arrow">▼</span></div>
      <div class="accord-body"><p>Shikigami terkuat dan paling berbahaya dalam Ten Shadows Technique. Tidak pernah berhasil ditaklukkan oleh siapa pun sepanjang sejarah keluarga yang menggunakan teknik ini — termasuk Megumi sendiri. Mahoraga memiliki kemampuan adaptasi yang sempurna: setelah terkena teknik apa pun satu kali, ia tidak akan pernah terpengaruh oleh teknik yang sama lagi. Sukuna-lah yang akhirnya menundukkan Mahoraga dalam pertarungannya.</p></div>
    </div>
  </div>

  <!-- DOMAIN EXPANSION -->
  <div class="world-section">
    <div class="section-eyebrow">Jurus Pamungkas</div>
    <h2 class="section-title">Domain Expansion — 領域展開</h2>
    <div class="section-divider"></div>
    <p class="section-intro">Domain Expansion adalah bentuk tertinggi teknik kutukan — menciptakan dimensi tersendiri yang menjebak lawan dan memastikan seluruh serangan "sure-hit" (pasti mengenai). Di dalam domain, pengguna memiliki keunggulan absolut karena teknik mereka terpatri ke dalam lingkungan itu sendiri.</p>
    <div class="special-box" style="margin-bottom:28px;">
      <div class="sb-title">📖 Cara Kerja Domain Expansion</div>
      <p class="sb-text">Sorcerer menciptakan "barrier" yang memisahkan dimensi domain dari dunia nyata. Di dalam barrier, innate technique mereka terpatri ke seluruh ruang — setiap serangan otomatis mengenai target (sure-hit). Kekurangan: menguras cursed energy sangat besar dan mengekspos kelemahan teknik kepada lawan. Jika barrier domain yang lebih kuat bertabrakan dengan domain yang lebih lemah, domain yang lebih lemah akan hancur.</p>
    </div>
    <div class="domain-grid">
      <div class="domain-card">
        <div class="domain-header">
          <span class="domain-icon">∞</span>
          <div><div class="domain-name">Unlimited Void</div><div class="domain-jp">無量空処 — Gojo Satoru</div></div>
        </div>
        <div class="domain-body">
          <p class="domain-desc">Domain paling kuat yang diketahui. Membuka koneksi lawan ke alam semesta yang tak terbatas — informasi, rangsangan, dan keberadaan tak terbatas membanjiri kesadaran mereka secara bersamaan. Dalam hitungan detik, lawan lumpuh total karena otak tidak mampu memproses semua informasi tersebut. Gojo mampu mengaktifkan domain dalam skala kecil (microsecond) untuk menghindari kehabisan energi.</p>
        </div>
      </div>
      <div class="domain-card">
        <div class="domain-header">
          <span class="domain-icon">👹</span>
          <div><div class="domain-name">Malevolent Shrine</div><div class="domain-jp">伏魔御廚子 — Ryomen Sukuna</div></div>
        </div>
        <div class="domain-body">
          <p class="domain-desc">Domain paling unik dalam seri — tidak memiliki barrier. Alih-alih menjebak lawan dalam ruang tertutup, Malevolent Shrine memperluas diri ke dunia nyata dalam radius 200 meter, menghancurkan segalanya dengan Dismantle dan Cleave tanpa henti. Ini membuatnya lebih berbahaya dari domain berbarrier karena tidak bisa "ditangkal" dengan domain counter. Tanda-tanda kuil kuno bermunculan saat domain ini aktif.</p>
        </div>
      </div>
      <div class="domain-card">
        <div class="domain-header">
          <span class="domain-icon">🌑</span>
          <div><div class="domain-name">Chimera Shadow Garden</div><div class="domain-jp">嵌合暗翳庭 — Megumi Fushiguro</div></div>
        </div>
        <div class="domain-body">
          <p class="domain-desc">Dimensi yang sepenuhnya terdiri dari bayangan cair. Di dalamnya, Megumi bisa memanggil seluruh shikigami-nya dengan kekuatan penuh dari segala arah. Lawan tidak bisa memastikan dari mana serangan berikutnya akan datang. Domain ini masih dalam tahap pengembangan saat pertama digunakan Megumi, tanpa sure-hit sepenuhnya namun tetap sangat berbahaya karena kesulitan melacak bayangan.</p>
        </div>
      </div>
      <div class="domain-card">
        <div class="domain-header">
          <span class="domain-icon">🔮</span>
          <div><div class="domain-name">Self-Embodiment of Perfection</div><div class="domain-jp">癈人〇 — Mahito</div></div>
        </div>
        <div class="domain-body">
          <p class="domain-desc">Domain Mahito yang memungkinkannya menyentuh jiwa lawan secara langsung dan langsung mengaktifkan Idle Transfiguration. Di dalam domain ini, siapa pun yang tersentuh Mahito akan langsung mengalami deformasi jiwa yang mengubah bentuk fisik mereka seketika. Tidak ada pertahanan fisik yang berarti karena serangan ditujukan langsung ke tingkat jiwa.</p>
        </div>
      </div>
      <div class="domain-card">
        <div class="domain-header">
          <span class="domain-icon">🔥</span>
          <div><div class="domain-name">Coffin of the Iron Mountain</div><div class="domain-jp">蓋棺鉄囲山 — Jogo</div></div>
        </div>
        <div class="domain-body">
          <p class="domain-desc">Domain Jogo yang menciptakan lingkungan gunung berapi di dalam barrier. Suhu mencapai ekstrem, lava mengalir dari seluruh penjuru, dan api Jogo mendapat peningkatan kekuatan dramatis. Di dalam domain ini, hampir tidak ada sorcerer yang bisa bertahan lama — kecuali mereka memiliki Infinity yang bisa memblok panas sekalipun.</p>
        </div>
      </div>
      <div class="domain-card">
        <div class="domain-header">
          <span class="domain-icon">🎰</span>
          <div><div class="domain-name">Idle Death Gamble</div><div class="domain-jp">死籠り賭博 — Kinji Hakari</div></div>
        </div>
        <div class="domain-body">
          <p class="domain-desc">Domain paling unik dari sisi mekanik — berdasarkan jackpot pachinko. Di dalamnya, Hakari memainkan skenario pachinko. Jika jackpot terpenuhi, ia mendapatkan energi kutukan tak terbatas selama 4 menit, termasuk kemampuan reverse cursed technique yang membuat dirinya hampir immortal. Probabilitas jackpot yang dimanipulasi membuat domain ini sangat berbahaya.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- SORCERER GRADES -->
  <div class="world-section">
    <div class="section-eyebrow">Klasifikasi Kekuatan</div>
    <h2 class="section-title">Sorcerer & Curse Grades</h2>
    <div class="section-divider"></div>
    <p class="section-intro">Baik penyihir maupun roh kutukan diklasifikasikan berdasarkan kekuatan mereka dalam sistem grade yang sama. Ini memudahkan penugasan misi dan penilaian ancaman.</p>
    <table class="grade-table">
      <thead>
        <tr><th>Grade</th><th>Deskripsi</th><th>Contoh Sorcerer</th><th>Contoh Cursed Spirit</th></tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="grade-rank grade-sp">⭐ Special Grade</span></td>
          <td>Kekuatan setara bencana alam. Hanya 4 penyihir aktif. Kutukan kelas ini mampu menghancurkan kota sendirian. Penanganan memerlukan penyihir kelas khusus atau tim besar kelas 1.</td>
          <td>Gojo Satoru, Yuta Okkotsu, Yuki Tsukumo</td>
          <td>Ryomen Sukuna, Mahito, Jogo, Hanami, Dagon</td>
        </tr>
        <tr>
          <td><span class="grade-rank grade-1">Grade 1</span></td>
          <td>Penyihir berpengalaman dengan rekam jejak luar biasa. Mampu menangani kutukan kelas khusus dalam kondisi tertentu. Penanganan kutukan grade 1 memerlukan minimal satu penyihir grade 1.</td>
          <td>Nanami Kento, Mei Mei, Naobito Zenin, Utahime Iori</td>
          <td>Kutukan berbentuk manusia tingkat tinggi</td>
        </tr>
        <tr>
          <td><span class="grade-rank grade-2">Grade 2</span></td>
          <td>Sorcerer kompeten yang telah terbukti di lapangan. Umumnya menangani misi solo untuk kutukan grade 2 dan bawah, atau bergabung dalam tim untuk grade 1.</td>
          <td>Megumi Fushiguro (awal), Maki Zenin (awal)</td>
          <td>Kutukan kelas menengah</td>
        </tr>
        <tr>
          <td><span class="grade-rank grade-3">Grade 3 & 4</span></td>
          <td>Penyihir junior atau mahasiswa. Penanganan misi dilakukan dalam kelompok dengan pengawasan. Seringkali memiliki potensi yang belum berkembang penuh.</td>
          <td>Yuji Itadori (awal), Nobara Kugisaki</td>
          <td>Kutukan umum sehari-hari</td>
        </tr>
      </tbody>
    </table>
  </div>

</div>

<footer>
  <div class="foot-logo">呪術廻戦 — JJK Universe</div>
  <p class="foot-text">Jujutsu Kaisen © Gege Akutami / Shueisha / MAPPA</p>
  <p class="foot-text" style="margin-top:4px;">Dibuat untuk keperluan Tugas Praktikum Pemrograman Web 2026</p>
</footer>

<script>
function toggleAccord(el){
  el.classList.toggle('active');
  el.querySelector('.accord-body').classList.toggle('open');
}
</script>
</body>
</html>