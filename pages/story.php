<?php require_once '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Story Arc — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root{--black:#03020a;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--red:#cc2233;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--border-gold:rgba(240,192,64,.2);--card-bg:rgba(10,8,20,.85);--nav-h:72px;}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;}
::-webkit-scrollbar{width:6px;} ::-webkit-scrollbar-track{background:#08060f;} ::-webkit-scrollbar-thumb{background:#3a0d7a;}
.navbar{position:fixed;top:0;left:0;right:0;height:var(--nav-h);z-index:100;display:flex;align-items:center;padding:0 40px;background:rgba(3,2,10,.9);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);}
.nav-logo{display:flex;align-items:center;gap:12px;text-decoration:none;flex:1;}
.logo-symbol{font-size:1.8rem;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.logo-text{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--text);}
.logo-sub{font-family:'Orbitron',sans-serif;font-size:.5rem;color:var(--text-muted);letter-spacing:3px;display:block;}
.nav-links{display:flex;align-items:center;gap:8px;list-style:none;}
.nav-links a{font-family:'Orbitron',sans-serif;font-size:.65rem;letter-spacing:2px;color:var(--text-muted);text-decoration:none;padding:8px 16px;border-radius:2px;transition:all .3s;text-transform:uppercase;}
.nav-links a:hover,.nav-links a.active{color:var(--text);background:rgba(107,33,232,.15);}
.nav-actions{display:flex;align-items:center;gap:12px;margin-left:20px;}
.btn-nav{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;padding:8px 20px;border-radius:2px;cursor:pointer;transition:all .3s;text-decoration:none;}
.btn-nav-outline{border:1px solid var(--border);color:var(--text-muted);background:transparent;}
.btn-nav-primary{background:var(--purple);border:1px solid var(--purple);color:white;}
.user-badge{display:flex;align-items:center;gap:8px;padding:6px 14px;border:1px solid var(--border-gold);border-radius:2px;background:rgba(240,192,64,.05);}
.user-badge-name{font-family:'Orbitron',sans-serif;font-size:.6rem;color:var(--gold);}

.page-hero{padding-top:calc(var(--nav-h)+60px);padding-bottom:60px;text-align:center;position:relative;overflow:hidden;padding-left:40px;padding-right:40px;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 0%,rgba(204,34,51,.1) 0%,transparent 60%);pointer-events:none;}
.page-tag{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:4px;color:var(--red);text-transform:uppercase;margin-bottom:16px;display:block;}
.page-title{font-family:'Cinzel Decorative',serif;font-size:clamp(2rem,4vw,3rem);background:linear-gradient(135deg,var(--text),#ff5566);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:12px;}
.page-sub{color:var(--text-muted);font-size:1rem;max-width:500px;margin:0 auto;}

/* TIMELINE */
.timeline-wrap{max-width:900px;margin:0 auto;padding:0 40px 80px;}
.arc-tabs{display:flex;gap:8px;flex-wrap:wrap;justify-content:center;margin-bottom:48px;}
.arc-tab{font-family:'Orbitron',sans-serif;font-size:.6rem;letter-spacing:2px;padding:10px 20px;border-radius:2px;border:1px solid var(--border);color:var(--text-muted);cursor:pointer;transition:all .3s;background:transparent;}
.arc-tab:hover{border-color:var(--purple-glow);color:var(--purple-glow);}
.arc-tab.active{border-color:var(--gold);color:var(--gold);background:rgba(240,192,64,.08);}

.arc-content{display:none;}
.arc-content.active{display:block;}

.timeline{position:relative;padding-left:40px;}
.timeline::before{content:'';position:absolute;left:16px;top:0;bottom:0;width:2px;background:linear-gradient(180deg,var(--purple-glow),var(--gold),var(--red));border-radius:1px;}

.tl-item{position:relative;margin-bottom:40px;opacity:0;transform:translateX(20px);transition:all .6s ease;}
.tl-item.visible{opacity:1;transform:translateX(0);}

.tl-dot{position:absolute;left:-32px;top:8px;width:16px;height:16px;border-radius:50%;border:2px solid var(--purple-glow);background:var(--black);box-shadow:0 0 12px rgba(107,33,232,.6);transition:all .3s;}
.tl-item:hover .tl-dot{background:var(--purple-glow);box-shadow:0 0 20px rgba(107,33,232,.8);}

.tl-card{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;padding:24px;transition:all .3s;}
.tl-card:hover{border-color:var(--purple-glow);transform:translateX(6px);}

.tl-episode{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:3px;color:var(--purple-glow);margin-bottom:8px;display:block;}
.tl-title{font-family:'Cinzel Decorative',serif;font-size:1.1rem;color:var(--text);margin-bottom:10px;}
.tl-desc{color:var(--text-muted);font-size:.95rem;line-height:1.8;}
.tl-tag{display:inline-block;font-family:'Orbitron',sans-serif;font-size:.45rem;letter-spacing:1px;padding:2px 8px;border-radius:1px;margin-right:6px;margin-top:10px;}
.tag-fight{background:rgba(204,34,51,.15);border:1px solid rgba(204,34,51,.4);color:#ff6677;}
.tag-reveal{background:rgba(240,192,64,.12);border:1px solid rgba(240,192,64,.3);color:var(--gold);}
.tag-death{background:rgba(100,100,120,.15);border:1px solid rgba(100,100,120,.4);color:#aaa8c0;}
.tag-power{background:rgba(107,33,232,.15);border:1px solid rgba(107,33,232,.4);color:var(--purple-glow);}

/* WORLD LORE CARDS */
.lore-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:1100px;margin:60px auto 80px;padding:0 40px;}
.lore-card{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;padding:24px;transition:all .3s;}
.lore-card:hover{border-color:var(--purple-glow);transform:translateY(-4px);}
.lore-card-icon{font-size:2.5rem;margin-bottom:14px;display:block;}
.lore-card-title{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--text);margin-bottom:10px;}
.lore-card-text{color:var(--text-muted);font-size:.9rem;line-height:1.7;}

footer{border-top:1px solid var(--border);padding:30px 40px;text-align:center;}
.footer-logo{font-family:'Cinzel Decorative',serif;font-size:1rem;color:var(--gold);}
.footer-sub{font-size:.75rem;color:var(--text-muted);margin-top:6px;}

@media(max-width:768px){.lore-cards{grid-template-columns:1fr;}.timeline-wrap,.lore-cards{padding-left:20px;padding-right:20px;}.navbar{padding:0 20px;}.nav-links{display:none;}}
</style>
</head>
<body>

<nav class="navbar">
    <a href="../index.php" class="nav-logo">
        <span class="logo-symbol">呪</span>
        <div><span class="logo-text">JJK Universe</span><span class="logo-sub">Cursed Energy Portal</span></div>
    </a>
    <ul class="nav-links">
        <li><a href="../index.php">Home</a></li>
        <li><a href="characters.php">Characters</a></li>
        <li><a href="story.php" class="active">Story Arc</a></li>
        <li><a href="../game/index.php">Mini Game</a></li>
    </ul>
    <div class="nav-actions">
        <?php if(isLoggedIn()):?>
        <div class="user-badge"><span class="user-badge-name">⚡ <?=htmlspecialchars($_SESSION['username'] ?? '')?></span></div>
        <a href="logout.php" class="btn-nav btn-nav-outline">Logout</a>
        <?php else:?>
        <a href="login.php" class="btn-nav btn-nav-outline">Login</a>
        <a href="register.php" class="btn-nav btn-nav-primary">Register</a>
        <?php endif;?>
    </div>
</nav>

<div class="page-hero">
    <span class="page-tag">Story Arc</span>
    <h1 class="page-title">Alur Cerita Jujutsu Kaisen</h1>
    <p class="page-sub">Ikuti perjalanan Yuji Itadori dari murid biasa menjadi sorcerer terkuat melalui ujian, kehilangan, dan pengorbanan.</p>
</div>

<!-- WORLD LORE -->
<div class="lore-cards">
    <div class="lore-card">
        <span class="lore-card-icon">👁</span>
        <div class="lore-card-title">Cursed Energy</div>
        <div class="lore-card-text">Energi kutukan lahir dari emosi negatif manusia — rasa takut, kebencian, dan kesedihan. Hampir semua manusia memilikinya, namun hanya sedikit yang bisa mengontrolnya sebagai senjata.</div>
    </div>
    <div class="lore-card">
        <span class="lore-card-icon">🌑</span>
        <div class="lore-card-title">Cursed Spirits</div>
        <div class="lore-card-text">Roh Kutukan terbentuk dari akumulasi energi kutukan yang cukup kuat. Mereka tidak bisa dilihat oleh orang biasa dan menyerang manusia yang tak berdaya. Grade mereka ditentukan dari kekuatan curse energy yang dimiliki.</div>
    </div>
    <div class="lore-card">
        <span class="lore-card-icon">⚔</span>
        <div class="lore-card-title">Jujutsu Sorcerers</div>
        <div class="lore-card-text">Para tukang sihir yang mampu menggunakan cursed energy untuk bertarung melawan Cursed Spirits. Mereka dilatih di sekolah khusus seperti Tokyo dan Kyoto Jujutsu High. Tiap sorcerer memiliki teknik kutukan unik yang diwariskan atau dikembangkan sendiri.</div>
    </div>
</div>

<!-- ARC TABS -->
<div class="timeline-wrap">
    <div class="arc-tabs">
        <button class="arc-tab active" onclick="switchArc('arc1')">Arc 1: Cursed Womb</button>
        <button class="arc-tab" onclick="switchArc('arc2')">Arc 2: Kyoto Goodwill</button>
        <button class="arc-tab" onclick="switchArc('arc3')">Arc 3: Shibuya Incident</button>
    </div>

    <!-- ARC 1 -->
    <div class="arc-content active" id="arc1">
        <div class="timeline">
            <div class="tl-item">
                <div class="tl-dot"></div>
                <div class="tl-card">
                    <span class="tl-episode">Episode 1–3</span>
                    <div class="tl-title">Lahirnya Seorang Sorcerer</div>
                    <div class="tl-desc">Yuji Itadori, seorang siswa SMA biasa dengan kemampuan fisik luar biasa, terseret ke dunia tukang sihir saat klub okultisme di sekolahnya tanpa sengaja memecahkan segel jari Ryomen Sukuna. Untuk melindungi temannya, Yuji menelan jari tersebut dan menjadi inang Sukuna.</div>
                    <span class="tl-tag tag-reveal">REVEAL</span>
                    <span class="tl-tag tag-power">POWER UP</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot"></div>
                <div class="tl-card">
                    <span class="tl-episode">Episode 4–6</span>
                    <div class="tl-title">Bertemu Satoru Gojo</div>
                    <div class="tl-desc">Satoru Gojo, tukang sihir paling kuat di dunia, muncul dan menyelamatkan Yuji. Ia membuat kesepakatan: Yuji akan hidup selama ia mampu mengonsumsi semua 20 jari Sukuna. Yuji mulai berlatih di Tokyo Jujutsu High bersama Megumi dan Nobara.</div>
                    <span class="tl-tag tag-reveal">PERTEMUAN</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot"></div>
                <div class="tl-card">
                    <span class="tl-episode">Episode 7–9</span>
                    <div class="tl-title">Misi Penjara Kutukan</div>
                    <div class="tl-desc">Tim pertama Yuji — bersama Megumi dan Nobara — dikirim ke gedung apartemen tua yang dipenuhi Cursed Spirits. Di sinilah Yuji pertama kali berhadapan dengan kematian rekannya dan merasakan beban menjadi tukang sihir yang sesungguhnya.</div>
                    <span class="tl-tag tag-fight">PERTARUNGAN</span>
                    <span class="tl-tag tag-death">KEHILANGAN</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot"></div>
                <div class="tl-card">
                    <span class="tl-episode">Episode 10–13</span>
                    <div class="tl-title">Kebangkitan Sukuna & Kematian Semu</div>
                    <div class="tl-desc">Sukuna mengambil alih kendali tubuh Yuji dan menghancurkan segalanya. Setelah insiden ini, Yuji secara resmi "dieksekusi" oleh organisasi Jujutsu dan harus hidup dalam bayangan kematian — menyelesaikan tugasnya sebelum dieksekusi sungguhan.</div>
                    <span class="tl-tag tag-fight">SUKUNA BANGKIT</span>
                    <span class="tl-tag tag-reveal">PLOT TWIST</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ARC 2 -->
    <div class="arc-content" id="arc2">
        <div class="timeline">
            <div class="tl-item">
                <div class="tl-dot" style="border-color:var(--gold);box-shadow:0 0 12px rgba(240,192,64,.5)"></div>
                <div class="tl-card">
                    <span class="tl-episode">Episode 14–17</span>
                    <div class="tl-title">Kyoto Goodwill Event Dimulai</div>
                    <div class="tl-desc">Pertukaran persahabatan tahunan antara Tokyo dan Kyoto Jujutsu High dimulai. Namun di balik kompetisi ini, kelompok teroris cursed spirit users berencana menggunakan momen ini untuk membunuh Yuji Itadori.</div>
                    <span class="tl-tag tag-reveal">KOMPETISI</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot" style="border-color:var(--gold);box-shadow:0 0 12px rgba(240,192,64,.5)"></div>
                <div class="tl-card">
                    <span class="tl-episode">Episode 18–20</span>
                    <div class="tl-title">Aoi Todo & Persahabatan Sejati</div>
                    <div class="tl-desc">Yuji berhadapan dengan Aoi Todo, sorcerer Grade 1 Kyoto yang eksentrik. Setelah bertarung sengit, Todo malah menjadi sahabat karib Yuji dan mengajarkannya teknik "Divergent Fist" yang ditingkatkan — cikal bakal Black Flash.</div>
                    <span class="tl-tag tag-fight">PERTARUNGAN</span>
                    <span class="tl-tag tag-power">POWER UP</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot" style="border-color:var(--gold);box-shadow:0 0 12px rgba(240,192,64,.5)"></div>
                <div class="tl-card">
                    <span class="tl-episode">Episode 21–24</span>
                    <div class="tl-title">Black Flash — Puncak Konsentrasi</div>
                    <div class="tl-desc">Yuji untuk pertama kalinya berhasil melepaskan Black Flash — serangan yang memadatkan cursed energy tepat sebelum impact, menghasilkan kekuatan yang secara teori meningkat lebih dari 2,5 juta kali. Momen ikonik yang mendefinisikan kemampuan Yuji.</div>
                    <span class="tl-tag tag-power">BLACK FLASH</span>
                    <span class="tl-tag tag-reveal">IKONIK</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ARC 3 -->
    <div class="arc-content" id="arc3">
        <div class="timeline">
            <div class="tl-item">
                <div class="tl-dot" style="border-color:#ff5566;box-shadow:0 0 12px rgba(204,34,51,.5)"></div>
                <div class="tl-card">
                    <span class="tl-episode">Episode 31–36</span>
                    <div class="tl-title">Shibuya — Malam Kutukan Terbesar</div>
                    <div class="tl-desc">31 Oktober, Shibuya dikepung kabut kutukan yang memerangkap jutaan warga sipil. Para teroris mengeksekusi rencana ambisius: memenjara Satoru Gojo menggunakan Prison Realm — artefak kutukan khusus yang bahkan Gojo tidak bisa lawan.</div>
                    <span class="tl-tag tag-fight">SKALA BESAR</span>
                    <span class="tl-tag tag-death">TRAGEDI</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot" style="border-color:#ff5566;box-shadow:0 0 12px rgba(204,34,51,.5)"></div>
                <div class="tl-card">
                    <span class="tl-episode">Episode 37–39</span>
                    <div class="tl-title">Gojo Dipenjara — Dunia Berubah</div>
                    <div class="tl-desc">Satoru Gojo yang tak terkalahkan berhasil dipenjara dalam Prison Realm oleh Pseudo-Geto. Kejadian ini mengguncang keseimbangan dunia sorcerer — tanpa Gojo, tidak ada yang sanggup membendung Special Grade Cursed Spirits dan penjahat-penjahat kelas atas.</div>
                    <span class="tl-tag tag-reveal">PLOT TWIST TERBESAR</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot" style="border-color:#ff5566;box-shadow:0 0 12px rgba(204,34,51,.5)"></div>
                <div class="tl-card">
                    <span class="tl-episode">Episode 40–47</span>
                    <div class="tl-title">Sukuna Dibebaskan — Kehancuran Shibuya</div>
                    <div class="tl-desc">Yuji terpaksa menelan 10 jari Sukuna sekaligus. Sukuna mengambil alih dan melepaskan Domain Expansion "Malevolent Shrine" yang menghancurkan seluruh kawasan Shibuya. Ribuan warga sipil tewas. Ini adalah momen terdarkest dalam sejarah JJK.</div>
                    <span class="tl-tag tag-fight">MALEVOLENT SHRINE</span>
                    <span class="tl-tag tag-death">MASS DESTRUCTION</span>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-dot" style="border-color:#ff5566;box-shadow:0 0 12px rgba(204,34,51,.5)"></div>
                <div class="tl-card">
                    <span class="tl-episode">Pasca Shibuya</span>
                    <div class="tl-title">Kehilangan & Rekonstruksi</div>
                    <div class="tl-desc">Akibat Shibuya Incident, Yuji dicap sebagai penjahat. Nanami, Haibara, dan banyak karakter yang dicintai gugur. Megumi terpuruk mengetahui keluarganya terlibat. Dunia jujutsu bersiap menghadapi era baru yang lebih gelap dan berbahaya.</div>
                    <span class="tl-tag tag-death">AFTERMATH</span>
                    <span class="tl-tag tag-reveal">TURNING POINT</span>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="footer-logo">呪 JJK Universe</div>
    <div class="footer-sub">Praktikum Pemrograman Web 2026</div>
</footer>

<script>
function switchArc(id) {
    document.querySelectorAll('.arc-content').forEach(a => a.classList.remove('active'));
    document.querySelectorAll('.arc-tab').forEach(t => t.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    event.target.classList.add('active');
    // Re-trigger animations
    document.querySelectorAll('#'+id+' .tl-item').forEach((el,i) => {
        el.classList.remove('visible');
        setTimeout(() => el.classList.add('visible'), i*120);
    });
}

const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('visible'); });
}, {threshold:.15});

document.querySelectorAll('.tl-item').forEach(el => observer.observe(el));
</script>
</body>
</html>