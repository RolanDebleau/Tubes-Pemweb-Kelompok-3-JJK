<?php require_once '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>World — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root{--black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--red:#cc2233;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--border-gold:rgba(240,192,64,.2);--card-bg:rgba(10,8,20,.85);--nav-h:80px;}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;}::-webkit-scrollbar-track{background:#08060f;}::-webkit-scrollbar-thumb{background:#3a0d7a;border-radius:3px;}
















/* HERO */
.page-hero{padding-top:calc(var(--nav-h) + 80px);padding-bottom:70px;text-align:center;position:relative;overflow:hidden;padding-left:40px;padding-right:40px;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(107,33,232,.14) 0%,transparent 60%);pointer-events:none;}
.hero-eyebrow{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:5px;color:var(--purple-glow);text-transform:uppercase;margin-bottom:16px;display:block;}
.hero-title{font-family:'Cinzel Decorative',serif;font-size:clamp(2.2rem,4.5vw,3.5rem);background:linear-gradient(135deg,#fff,var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:16px;line-height:1.15;}
.hero-desc{color:var(--text-muted);font-size:1.05rem;max-width:620px;margin:0 auto 32px;line-height:1.7;}
.hero-divider{width:80px;height:2px;background:linear-gradient(to right,var(--purple),var(--gold));margin:0 auto;}

/* CONTENT */
.content-wrap{max-width:1160px;margin:0 auto;padding:0 40px 80px;}

/* SECTION */
.world-section{margin-bottom:72px;}
.section-eyebrow{font-family:'Orbitron',sans-serif;font-size:.58rem;letter-spacing:4px;color:var(--gold);text-transform:uppercase;margin-bottom:10px;}
.section-title{font-family:'Cinzel Decorative',serif;font-size:1.6rem;color:var(--text);margin-bottom:8px;}
.section-divider{width:40px;height:2px;background:linear-gradient(to right,var(--purple),var(--gold));margin-bottom:28px;}
.section-intro{color:var(--text-muted);font-size:1rem;line-height:1.8;max-width:800px;margin-bottom:32px;}

/* OVERVIEW GRID */
.overview-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:40px;}
.overview-card{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:28px;transition:border-color .3s;}
.overview-card:hover{border-color:var(--purple-glow);}
.ov-icon{font-size:2rem;margin-bottom:12px;display:block;}
.ov-title{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--text);margin-bottom:10px;}
.ov-text{font-size:.95rem;color:var(--text-muted);line-height:1.8;}

/* FACTIONS */
.factions-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
.faction-card{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:24px;position:relative;overflow:hidden;transition:all .3s;}
.faction-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
.faction-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(107,33,232,.15);}
.faction-card.tokyo::before{background:linear-gradient(to right,var(--purple),var(--purple-glow));}
.faction-card.kyoto::before{background:linear-gradient(to right,var(--gold),#ff9500);}
.faction-card.elders::before{background:linear-gradient(to right,var(--red),#ff6677);}
.faction-card.curses::before{background:linear-gradient(to right,#1a1a2e,#6a0dad);}
.faction-card.kenjaku::before{background:linear-gradient(to right,#2d6a4f,#40916c);}
.faction-card.clan::before{background:linear-gradient(to right,#8b5e3c,#c89b7b);}
.faction-tag{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:3px;text-transform:uppercase;margin-bottom:8px;color:var(--text-muted);}
.faction-name{font-family:'Cinzel Decorative',serif;font-size:.95rem;color:var(--text);margin-bottom:12px;}
.faction-text{font-size:.88rem;color:var(--text-muted);line-height:1.75;}
.faction-members{display:flex;flex-wrap:wrap;gap:6px;margin-top:14px;}
.member-badge{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:1px;padding:4px 10px;border:1px solid var(--border);border-radius:2px;color:var(--text-muted);}

/* LOCATIONS — Horizontal scroll slider */
.locations-scroll-wrap{position:relative;overflow:hidden;}
.locations-scroll-track{display:flex;gap:20px;overflow-x:auto;scroll-behavior:smooth;padding-bottom:16px;scrollbar-width:none;-ms-overflow-style:none;cursor:grab;}
.locations-scroll-track::-webkit-scrollbar{display:none;}
.locations-scroll-track.grabbing{cursor:grabbing;}
.location-card{background:var(--card-bg);border:1px solid var(--border);border-radius:6px;padding:22px;display:flex;gap:18px;align-items:flex-start;transition:all .3s;flex:0 0 340px;text-decoration:none;color:inherit;}
.location-card:hover{border-color:rgba(107,33,232,.6);transform:translateY(-4px);box-shadow:0 12px 40px rgba(107,33,232,.2);cursor:pointer;}
.scroll-btn{position:absolute;top:50%;transform:translateY(-50%);width:44px;height:44px;border-radius:50%;background:rgba(10,8,20,.9);border:1px solid var(--border);color:var(--text);font-size:1.2rem;cursor:pointer;z-index:5;display:flex;align-items:center;justify-content:center;transition:all .3s;}
.scroll-btn:hover{border-color:var(--purple-glow);color:var(--purple-glow);}
.scroll-btn-left{left:0;}
.scroll-btn-right{right:0;}
.scroll-indicator{display:flex;justify-content:center;gap:6px;margin-top:16px;}
.scroll-dot{width:24px;height:3px;border-radius:2px;background:rgba(107,33,232,.25);transition:background .3s;}
.scroll-dot.active{background:var(--purple-glow);}
.loc-icon{font-size:2.2rem;flex-shrink:0;margin-top:2px;}
.loc-name{font-family:'Cinzel Decorative',serif;font-size:.9rem;color:var(--text);margin-bottom:6px;}
.loc-text{font-size:.88rem;color:var(--text-muted);line-height:1.7;}
.loc-tag{display:inline-block;margin-top:8px;font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:2px;padding:3px 9px;border:1px solid var(--border-gold);color:var(--gold);border-radius:2px;}

/* TIMELINE */
.timeline{position:relative;padding-left:28px;}
.timeline::before{content:'';position:absolute;left:0;top:0;bottom:0;width:1px;background:linear-gradient(to bottom,transparent,var(--purple) 10%,var(--purple) 90%,transparent);}
.tl-item{position:relative;margin-bottom:36px;}
.tl-dot{position:absolute;left:-35px;top:4px;width:14px;height:14px;border-radius:50%;background:var(--purple);border:2px solid var(--black);}
.tl-era{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:3px;color:var(--gold);text-transform:uppercase;margin-bottom:4px;}
.tl-event{font-family:'Cinzel Decorative',serif;font-size:.9rem;color:var(--text);margin-bottom:6px;}
.tl-desc{font-size:.9rem;color:var(--text-muted);line-height:1.75;}

/* HIERARCHY */
.hierarchy{display:flex;flex-direction:column;gap:0;max-width:700px;margin:0 auto;}
.hr-tier{display:flex;align-items:center;gap:20px;padding:18px 24px;background:var(--card-bg);border:1px solid var(--border);position:relative;}
.hr-tier:not(:last-child)::after{content:'▼';position:absolute;bottom:-16px;left:50%;transform:translateX(-50%);color:var(--text-muted);font-size:.75rem;z-index:1;}
.hr-tier:first-child{border-radius:6px 6px 0 0;}
.hr-tier:last-child{border-radius:0 0 6px 6px;}
.hr-tier.t1{border-color:var(--gold);}
.hr-tier.t2{border-color:var(--purple);}
.hr-tier.t3{border-color:rgba(107,33,232,.4);}
.hr-tier.t4{border-color:var(--border);}
.hr-rank{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;width:80px;flex-shrink:0;color:var(--text-muted);}
.hr-title{font-family:'Cinzel Decorative',serif;font-size:.85rem;color:var(--text);flex:1;}
.hr-desc{font-size:.82rem;color:var(--text-muted);flex:2;line-height:1.6;}

/* FOOTER */
footer{background:rgba(3,2,10,.9);border-top:1px solid var(--border);padding:32px 40px;text-align:center;}
.foot-logo{font-family:'Cinzel Decorative',serif;font-size:1.1rem;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:8px;}
.foot-text{font-size:.78rem;color:var(--text-muted);}

@media(max-width:900px){
  .overview-grid,.factions-grid,.locations-grid{grid-template-columns:1fr;}
  .content-wrap,.page-hero{padding-left:20px;padding-right:20px;}
  
  .hierarchy{padding:0 20px;}
}
</style>
</head>
<body>

<?php
$currentPage = 'world';
$basePath    = '../';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="page-hero">
  <span class="hero-eyebrow">Dunia Jujutsu Kaisen</span>
  <h1 class="hero-title">World of Cursed Energy</h1>
  <p class="hero-desc">Sebuah dunia modern tersembunyi di balik yang kasat mata — di mana roh kutukan lahir dari emosi negatif manusia, dan para penyihir jujutsu berdiri sebagai satu-satunya garis pertahanan antara manusia dan kegelapan.</p>
  <div class="hero-divider"></div>
</div>

<div class="content-wrap">

  <!-- OVERVIEW -->
  <div class="world-section">
    <div class="section-eyebrow">Overview</div>
    <h2 class="section-title">Gambaran Umum Dunia</h2>
    <div class="section-divider"></div>
    <p class="section-intro">Dunia Jujutsu Kaisen secara kasat mata tampak seperti dunia modern biasa — namun di balik itu tersembunyi realita gelap: roh kutukan (cursed spirits) yang lahir dari emosi negatif manusia seperti rasa takut, kebencian, dan kesedihan. Hanya individu yang mampu merasakan dan menggunakan cursed energy yang bisa melihat dan melawan makhluk ini.</p>
    <div class="overview-grid">
      <div class="overview-card">
        <span class="ov-icon">🌏</span>
        <div class="ov-title">Dunia Tersembunyi</div>
        <p class="ov-text">Keberadaan roh kutukan dan penyihir jujutsu tersembunyi dari masyarakat umum. Pemerintah jujutsu (Jujutsu Elders) secara aktif menyembunyikan realita ini untuk mencegah kepanikan massal. Kebanyakan manusia hidup tanpa menyadari ancaman yang mengintai mereka setiap saat.</p>
      </div>
      <div class="overview-card">
        <span class="ov-icon">💀</span>
        <div class="ov-title">Kutukan yang Lahir dari Manusia</div>
        <p class="ov-text">Setiap manusia secara tidak sadar memancarkan cursed energy dari emosi negatif mereka. Energi ini menumpuk, mengkristal, dan akhirnya membentuk roh kutukan. Semakin negatif emosi di suatu tempat atau waktu, semakin kuat kutukan yang terlahir. Kuil, penjara, dan medan perang adalah tempat kelahiran kutukan terkuat.</p>
      </div>
      <div class="overview-card">
        <span class="ov-icon">⚔️</span>
        <div class="ov-title">Para Penyihir Jujutsu</div>
        <p class="ov-text">Penyihir jujutsu (jujutsu sorcerers) adalah individu yang mampu mengendalikan cursed energy mereka secara aktif untuk melawan roh kutukan. Mereka dilatih di sekolah-sekolah khusus yang tersebar di seluruh Jepang, dengan Tokyo Jujutsu High dan Kyoto Jujutsu High sebagai yang terkemuka.</p>
      </div>
      <div class="overview-card">
        <span class="ov-icon">📜</span>
        <div class="ov-title">Hukum dan Aturan</div>
        <p class="ov-text">Dunia jujutsu memiliki hukumnya sendiri. Informasi tentang kutukan dan sorcerer diklasifikasikan. Eksekusi diputuskan oleh dewan senior. Penyihir memiliki kewajiban untuk mengusir kutukan dan melindungi warga sipil — sebagian besar tanpa diketahui atau diapresiasi oleh masyarakat umum.</p>
      </div>
    </div>
  </div>

  <!-- FACTIONS -->
  <div class="world-section">
    <div class="section-eyebrow">Organisations</div>
    <h2 class="section-title">Faksi & Organisasi</h2>
    <div class="section-divider"></div>
    <p class="section-intro">Dunia jujutsu terbagi menjadi berbagai faksi dengan kepentingan dan ideologi yang berbeda, mulai dari institusi resmi hingga kelompok pemberontak dan kutukan yang terorganisir.</p>
    <div class="factions-grid">
      <div class="faction-card tokyo">
        <div class="faction-tag">Institusi Resmi</div>
        <div class="faction-name">🏫 Tokyo Jujutsu High</div>
        <p class="faction-text">Sekolah teknik jujutsu metropolitan Tokyo — institusi paling terkemuka dalam dunia penyihir modern. Melatih generasi penyihir baru sekaligus menangani misi pembersihan kutukan di seluruh wilayah Kanto. Dipimpin oleh Principal Masamichi Yaga.</p>
        <div class="faction-members">
          <span class="member-badge">Gojo Satoru</span>
          <span class="member-badge">Yuji Itadori</span>
          <span class="member-badge">Megumi Fushiguro</span>
          <span class="member-badge">Nobara Kugisaki</span>
          <span class="member-badge">Nanami Kento</span>
          <span class="member-badge">Yuta Okkotsu</span>
        </div>
      </div>
      <div class="faction-card kyoto">
        <div class="faction-tag">Institusi Resmi</div>
        <div class="faction-name">⛩️ Kyoto Jujutsu High</div>
        <p class="faction-text">Sekolah teknik jujutsu metropolitan Kyoto — rival historis Tokyo. Lebih konservatif dan menjunjung tinggi tradisi keluarga klan. Memiliki hubungan erat dengan keluarga-keluarga sorcerer berpengaruh seperti Kamo dan Zenin. Dipimpin oleh Principal Yoshinobu Gakuganji.</p>
        <div class="faction-members">
          <span class="member-badge">Aoi Todo</span>
          <span class="member-badge">Kasumi Miwa</span>
          <span class="member-badge">Noritoshi Kamo</span>
          <span class="member-badge">Mai Zenin</span>
          <span class="member-badge">Mechamaru</span>
        </div>
      </div>
      <div class="faction-card elders">
        <div class="faction-tag">Pemerintahan</div>
        <div class="faction-name">👴 Jujutsu Elders (Zenin)</div>
        <p class="faction-text">Para tetua jujutsu yang memegang kendali atas seluruh kebijakan dunia penyihir. Sangat konservatif, korup, dan mendukung status quo. Memiliki otoritas untuk menjatuhkan hukuman mati kepada penyihir mana pun — termasuk yang dianggap terlalu kuat atau tidak dapat dikendalikan seperti Gojo Satoru.</p>
        <div class="faction-members">
          <span class="member-badge">Naobito Zenin</span>
          <span class="member-badge">Ogi Zenin</span>
          <span class="member-badge">Gakuganji</span>
        </div>
      </div>
      <div class="faction-card curses">
        <div class="faction-tag">Antagonis — Kutukan</div>
        <div class="faction-name">👹 Cursed Spirit Alliance</div>
        <p class="faction-text">Aliansi roh kutukan yang dipimpin oleh Pseudo-Geto (Kenjaku). Terdiri dari kutukan kelas khusus seperti Mahito, Jogo, Hanami, dan Dagon. Tujuan mereka adalah menghapus manusia dari dunia dan menciptakan era baru di mana kutukan berkuasa. Menggunakan artefak kutukan Ryomen Sukuna sebagai bagian dari rencana besar mereka.</p>
        <div class="faction-members">
          <span class="member-badge">Mahito</span>
          <span class="member-badge">Jogo</span>
          <span class="member-badge">Hanami</span>
          <span class="member-badge">Dagon</span>
          <span class="member-badge">Kenjaku</span>
        </div>
      </div>
      <div class="faction-card kenjaku">
        <div class="faction-tag">Antagonis — Manusia</div>
        <div class="faction-name">🧠 Kenjaku & Culling Game</div>
        <p class="faction-text">Kenjaku adalah penyihir kuno yang hidup ribuan tahun dengan berpindah dari satu tubuh ke tubuh lain. Rencana utamanya adalah Culling Game — sebuah permainan pembantaian massal yang melibatkan sorcerer dari berbagai era. Tujuan akhirnya adalah mengaktifkan kembali seluruh cursed energy Jepang untuk ritual tertentu.</p>
        <div class="faction-members">
          <span class="member-badge">Kenjaku (Pseudo-Geto)</span>
          <span class="member-badge">Uraume</span>
          <span class="member-badge">Sukuna</span>
        </div>
      </div>
      <div class="faction-card clan">
        <div class="faction-tag">Klan Bersejarah</div>
        <div class="faction-name">⚜️ The Big Three Clans</div>
        <p class="faction-text">Tiga klan sorcerer paling berpengaruh dalam sejarah jujutsu: Zenin, Gojo, dan Kamo. Masing-masing mewarisi teknik turun-temurun yang sangat kuat. Klan Zenin paling dominan secara politik, sementara Klan Gojo dikenal karena Limitless-nya. Klan Kamo dikenal karena Blood Manipulation.</p>
        <div class="faction-members">
          <span class="member-badge">Klan Zenin</span>
          <span class="member-badge">Klan Gojo</span>
          <span class="member-badge">Klan Kamo</span>
        </div>
      </div>
    </div>
  </div>

  <!-- LOCATIONS -->
  <div class="world-section">
    <div class="section-eyebrow">Locations</div>
    <h2 class="section-title">Lokasi Penting</h2>
    <div class="section-divider"></div>
    <div class="locations-scroll-wrap">
    <button class="scroll-btn scroll-btn-left" onclick="scrollLoc(-1)">‹</button>
    <div class="locations-scroll-track" id="locTrack">
      <div class="location-card">
        <span class="loc-icon">🏫</span>
        <div>
          <div class="loc-name">Tokyo Jujutsu High</div>
          <p class="loc-text">Berlokasi di Tokyo, sekolah ini berdiri di atas tanah yang kaya akan cursed energy. Fasilitas mencakup ruang pelatihan, rumah sakit khusus penyihir, dan penjara bawah tanah untuk menyimpan artefak kutukan berbahaya. Dipagari oleh barrierBarrier (tembok kutukan) yang melindungi dari serangan luar.</p>
          <span class="loc-tag">Tokyo, Jepang</span>
        </div>
      </div>
      <div class="location-card">
        <span class="loc-icon">🚇</span>
        <div>
          <div class="loc-name">Shibuya — Medan Perang Bersejarah</div>
          <p class="loc-text">Distrik Shibuya di Tokyo menjadi lokasi insiden paling berdarah dalam sejarah modern jujutsu. Kenjaku dan para kutukan berhasil menjebak Gojo Satoru menggunakan Prison Realm di sini. Ribuan korban sipil, dan Sukuna akhirnya dilepaskan sepenuhnya untuk pertama kalinya dalam ribuan tahun.</p>
          <span class="loc-tag">Shibuya, Tokyo</span>
        </div>
      </div>
      <div class="location-card">
        <span class="loc-icon">⛩️</span>
        <div>
          <div class="loc-name">Kyoto Jujutsu High</div>
          <p class="loc-text">Sekolah rival Tokyo yang berlokasi di Kyoto, pusat tradisi dan budaya Jepang. Lebih konservatif dalam metode pengajaran dan sangat menjunjung hierarki klan. Menjadi lokasi Goodwill Event — turnamen tahunan antara murid Tokyo dan Kyoto.</p>
          <span class="loc-tag">Kyoto, Jepang</span>
        </div>
      </div>
      <div class="location-card">
        <span class="loc-icon">🏝️</span>
        <div>
          <div class="loc-name">Koloni Culling Game</div>
          <p class="loc-text">Berbagai "koloni" tersebar di seluruh Jepang — area terisolasi yang dibungkus oleh Barrier Kenjaku sebagai arena Culling Game. Di dalamnya, penyihir dari berbagai era yang telah dibangkitkan bertarung mengikuti aturan permainan yang kejam. Keluar dari koloni hampir mustahil tanpa poin yang cukup.</p>
          <span class="loc-tag">Seluruh Jepang</span>
        </div>
      </div>
      <div class="location-card">
        <span class="loc-icon">🗼</span>
        <div>
          <div class="loc-name">Jujutsu Headquarters (Sendai)</div>
          <p class="loc-text">Markas administratif para tetua jujutsu. Di sinilah keputusan-keputusan besar dibuat — termasuk hukuman mati untuk para penyihir yang dianggap berbahaya. Birokrasi jujutsu yang korup berpusat di sini, jauh dari medan pertempuran nyata.</p>
          <span class="loc-tag">Sendai, Jepang</span>
        </div>
      </div>
      <div class="location-card">
        <span class="loc-icon">🏯</span>
        <div>
          <div class="loc-name">Klan Zenin Compound</div>
          <p class="loc-text">Kediaman resmi Klan Zenin — salah satu klan sorcerer paling berpengaruh. Dikenal sebagai lingkungan yang sangat hierarkis dan keras. Maki dan Mai Zenin tumbuh di sini dalam tekanan ekstrem akibat pandangan klan terhadap kemampuan mereka. Menjadi lokasi pertempuran berdarah ketika Maki kembali.</p>
          <span class="loc-tag">Jepang</span>
        </div>
      </div>
    </div>
  </div>

  <!-- HISTORY TIMELINE -->
  <div class="world-section">
    <div class="section-eyebrow">History</div>
    <h2 class="section-title">Garis Waktu Sejarah</h2>
    <div class="section-divider"></div>
    <div class="timeline">
      <div class="tl-item">
        <div class="tl-dot"></div>
        <div class="tl-era">Era Heian (~794–1185)</div>
        <div class="tl-event">Ryomen Sukuna — Era Penyihir Terkuat</div>
        <p class="tl-desc">Ryomen Sukuna muncul sebagai kutukan manusia (curse user) yang melampaui semua batas. Dengan empat tangan dan dua wajah, ia membantai seluruh penyihir yang mencoba melawannya di era Heian. Ia bukan dilahirkan sebagai kutukan, melainkan manusia yang terdeformasi oleh kekuatan ekstrem. Para penyihir era itu tidak bisa membunuhnya, hanya memotong-motong tubuhnya menjadi 20 jari yang menjadi artefak kutukan abadi.</p>
      </div>
      <div class="tl-item">
        <div class="tl-dot"></div>
        <div class="tl-era">Periode Modern Awal</div>
        <div class="tl-event">Berdirinya Sistem Jujutsu Modern</div>
        <p class="tl-desc">Sistem sekolah jujutsu formal dibentuk untuk melatih penyihir secara terstruktur. Tokyo dan Kyoto Jujutsu High didirikan. Tiga klan besar (Zenin, Gojo, Kamo) menjadi fondasi politik dunia jujutsu. Jari-jari Sukuna tersebar dan sebagian berhasil diamankan sebagai artefak terklasifikasi.</p>
      </div>
      <div class="tl-item">
        <div class="tl-dot"></div>
        <div class="tl-era">~12 Tahun Sebelum Cerita Utama</div>
        <div class="tl-event">Gojo & Geto — Dua Sorcerer Terkuat Generasinya</div>
        <p class="tl-desc">Satoru Gojo dan Suguru Geto menjadi sorcerer terkuat satu generasi. Keduanya bersahabat namun memiliki pandangan berbeda tentang dunia. Geto akhirnya membelot setelah trauma misi Star Plasma Vessel, bergabung dengan pihak kutukan dan membantai lebih dari 100 sorcerer biasa dalam semalam. Gojo terpaksa membiarkannya pergi.</p>
      </div>
      <div class="tl-item">
        <div class="tl-dot"></div>
        <div class="tl-era">~1 Tahun Sebelum Cerita Utama</div>
        <div class="tl-event">Yuta Okkotsu & Rika Orimoto</div>
        <p class="tl-desc">Yuta Okkotsu hampir dihukum mati oleh para tetua karena dihantui oleh kutukan cinta Rika yang sangat kuat. Gojo menyelamatkannya dan membawanya ke Tokyo Jujutsu High. Yuta akhirnya berhasil membebaskan Rika dan menjadi salah satu sorcerer kelas khusus termuda dalam sejarah.</p>
      </div>
      <div class="tl-item">
        <div class="tl-dot"></div>
        <div class="tl-era">Cerita Utama</div>
        <div class="tl-event">Yuji Itadori Menelan Jari Sukuna</div>
        <p class="tl-desc">Yuji Itadori menelan jari Sukuna untuk menyelamatkan temannya. Alih-alih mati, ia menjadi vessel yang mampu menampung Raja Kutukan. Para tetua memutuskan untuk menggunakannya sebagai "wadah" — mengumpulkan seluruh 20 jari Sukuna di dalam tubuh Yuji, lalu mengeksekusinya. Rencana ini melibatkan seluruh generasi muda Tokyo Jujutsu High.</p>
      </div>
      <div class="tl-item">
        <div class="tl-dot"></div>
        <div class="tl-era">Shibuya Incident</div>
        <div class="tl-event">Malam Paling Gelap — Gojo Terjebak</div>
        <p class="tl-desc">Kenjaku dan aliansi kutukan mengeksekusi rencana mereka: menjebak Gojo Satoru menggunakan Prison Realm. Shibuya menjadi medan perang kacau. Nanami tewas. Nobara hampir tewas. Sukuna dilepaskan sepenuhnya dan menghancurkan sebagian Shibuya. Dunia jujutsu berubah untuk selamanya malam itu.</p>
      </div>
      <div class="tl-item">
        <div class="tl-dot"></div>
        <div class="tl-era">Culling Game</div>
        <div class="tl-event">Permainan Pembantaian Kenjaku Dimulai</div>
        <p class="tl-desc">Kenjaku mengaktifkan Culling Game — permainan dengan aturan kejam yang melibatkan penyihir dari berbagai era yang telah dibangkitkan kembali. Koloni-koloni terbentuk di seluruh Jepang. Tujuan Kenjaku akhirnya terungkap: mengumpulkan energi kutukan seluruh Jepang untuk ritual paling dahsyat dalam sejarah manusia.</p>
      </div>
    </div>
  </div>

  <!-- SOCIAL HIERARCHY -->
  <div class="world-section">
    <div class="section-eyebrow">Social Structure</div>
    <h2 class="section-title">Hierarki Sosial Penyihir</h2>
    <div class="section-divider"></div>
    <p class="section-intro">Dunia jujutsu memiliki hierarki yang kaku berdasarkan kekuatan dan keturunan. Posisi seseorang dalam hierarki ini menentukan misi yang diterima, perlakuan dari institusi, dan bahkan hak untuk hidup.</p>
    <div class="hierarchy">
      <div class="hr-tier t1">
        <div class="hr-rank">SPECIAL GRADE</div>
        <div class="hr-title">⭐ Kelas Khusus</div>
        <p class="hr-desc">Hanya 4 penyihir aktif di seluruh dunia: Gojo Satoru, Yuta Okkotsu, Yuki Tsukumo, Suguru Geto. Kekuatan mereka dianggap setara dengan bencana alam. Satu penyihir kelas khusus mampu mengubah keseimbangan kekuatan dunia.</p>
      </div>
      <div class="hr-tier t2">
        <div class="hr-rank">GRADE 1</div>
        <div class="hr-title">🔷 Kelas Satu</div>
        <p class="hr-desc">Penyihir berpengalaman dengan kekuatan dan kemampuan yang terbukti di lapangan. Nanami Kento, Mei Mei, dan Naobito Zenin adalah contohnya. Mampu menangani kutukan kelas khusus dengan strategi yang tepat.</p>
      </div>
      <div class="hr-tier t3">
        <div class="hr-rank">GRADE 2</div>
        <div class="hr-title">🔹 Kelas Dua</div>
        <p class="hr-desc">Penyihir yang kompeten dan sudah terbukti di lapangan. Kebanyakan lulusan sekolah jujutsu memulai di level ini. Mampu menangani kutukan kelas 1 dan 2 secara mandiri.</p>
      </div>
      <div class="hr-tier t4">
        <div class="hr-rank">GRADE 3 & 4</div>
        <div class="hr-title">⬜ Kelas Tiga & Empat / Unranked</div>
        <p class="hr-desc">Penyihir junior dan mahasiswa. Kebanyakan beroperasi dalam tim atau dengan pengawasan senior. Murid Tokyo Jujutsu High seperti Yuji, Megumi, dan Nobara memulai di level ini meskipun potensi mereka jauh melampaui peringkat awal.</p>
      </div>
    </div>
  </div>

</div>

<footer>
  <div class="foot-logo">呪術廻戦 — JJK Universe</div>
  <p class="foot-text">Jujutsu Kaisen © Gege Akutami / Shueisha / MAPPA</p>
  <p class="foot-text" style="margin-top:4px;">Dibuat untuk keperluan Tugas Praktikum Pemrograman Web 2026</p>
</footer>

<script>
// Horizontal scroll
const track = document.getElementById('locTrack');
const cards = track ? track.querySelectorAll('.location-card') : [];
const dotsContainer = document.getElementById('locDots');

// Create dots
if (dotsContainer && cards.length) {
    cards.forEach((_, i) => {
        const d = document.createElement('div');
        d.className = 'scroll-dot' + (i===0?' active':'');
        d.onclick = () => track.scrollTo({ left: cards[i].offsetLeft - 20, behavior:'smooth' });
        dotsContainer.appendChild(d);
    });
}

function scrollLoc(dir) {
    if (!track) return;
    track.scrollBy({ left: dir * 360, behavior: 'smooth' });
}

// Update dots on scroll
if (track) {
    track.addEventListener('scroll', () => {
        const dots = document.querySelectorAll('.scroll-dot');
        cards.forEach((card, i) => {
            const inView = card.offsetLeft - track.scrollLeft < track.offsetWidth/2;
            if (dots[i]) dots[i].classList.toggle('active', inView);
        });
    });

    // Drag to scroll
    let isDown=false, startX, scrollLeft;
    track.addEventListener('mousedown', e => { isDown=true; track.classList.add('grabbing'); startX=e.pageX-track.offsetLeft; scrollLeft=track.scrollLeft; });
    track.addEventListener('mouseleave', () => { isDown=false; track.classList.remove('grabbing'); });
    track.addEventListener('mouseup', () => { isDown=false; track.classList.remove('grabbing'); });
    track.addEventListener('mousemove', e => { if(!isDown) return; e.preventDefault(); const x=e.pageX-track.offsetLeft; track.scrollLeft=scrollLeft-(x-startX)*1.5; });
}

// Make location cards navigate to this page with hash (or just themselves)
document.querySelectorAll('.location-card[data-target]').forEach(card => {
    card.style.cursor = 'pointer';
    card.addEventListener('click', () => {
        window.location.href = card.dataset.target;
    });
});
</script>
</body>
</html>