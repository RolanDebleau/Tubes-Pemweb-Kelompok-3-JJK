<?php
require_once 'includes/config.php';

// Menyiapkan data untuk sistem header dinamis
$pageTitle = "Jujutsu Universe - Expand Your Domain";
$extra_css = ['index']; 

include 'includes/header.php'; 
?>

    <div class="home-container">
        
        <div class="bg-wrapper" aria-hidden="true">
            <img class="bg-image" src="asset/home_bg.jpg" alt="Background" />
            <div class="overlay-dark-vignette"></div>
        </div>
        
        <div class="hero-content">
            <h1 class="hero-title" id="hero-dynamic-title">Expand Your Domain</h1>
            <p class="hero-subtitle" id="hero-dynamic-subtitle">
                Masuki dunia di mana kutukan dan energi tersembunyi bertarung melawan para tukang sihir. Jelajahi karakter, pelajari teknik kutukan, dan buktikan kekuatanmu dalam arena.
            </p>
            
            <div class="carousel-navigation" aria-label="Carousel navigation">
                <button class="btn-arrow arrow-prev" type="button" aria-label="Previous slide">
                    <img src="<?= SITE_URL ?>/asset/Arrow 4.svg" alt="Previous" aria-hidden="true" />
                </button>
                <button class="btn-arrow arrow-next" type="button" aria-label="Next slide">
                    <img src="<?= SITE_URL ?>/asset/Arrow 3.svg" alt="Next" aria-hidden="true" />
                </button>
            </div>
        </div>

        <div class="hero-artwork-frame">
            <img class="artwork-bg" src="<?= SITE_URL ?>/asset/home_bg.jpg" style="display:none" alt="" aria-hidden="true" />
            <img class="artwork-character" id="hero-char-img" src="<?= SITE_URL ?>/asset/home_bg.jpg" alt="Jujutsu character artwork" />
        </div>

        <section class="lore-section" aria-labelledby="lore-title">
            <div class="lore-header-block">
                <span class="lore-tagline">Tentang Cerita</span>
                <h2 class="lore-main-title" id="lore-title">Dunia di Balik<br><span class="accent-text">Kutukan & Sihir</span></h2>
            </div>
            
            <div class="lore-body-block">
                <div class="lore-text-content">
                    <p>Di dunia Jujutsu Kaisen, makhluk gaib yang disebut Cursed Spirits terlahir dari emosi negatif manusia — rasa takut, kebencian, dan penderitaan yang terkumpul menjadi entitas berbahaya.</p>
                </div>
                
                <blockquote class="lore-quote">
                    <p>"Aku akan meninggalkan dunia ini dengan cara yang tepat — hidup penuh dan mati dikelilingi orang-orang."</p>
                </blockquote>
            </div>
        </section>

        <section class="carousel-section" aria-label="Featured Characters Showcase">
            <div class="carousel-header">
                <h2 class="section-title">Featured Characters</h2>
                </div>

            <div class="char-carousel-grid">
                <article class="char-card card-yuta">
                    <div class="card-overlay-blur">
                        <a href="<?=SITE_URL?>/pages/character_detail.php?id=10" class="btn-detail-view">
                            <span>Detail</span>
                        </a>
                    </div>
                </article>

                <article class="char-card card-takaba">
                    <div class="card-overlay-blur">
                        <a href="<?=SITE_URL?>/pages/character_detail.php?id=48" class="btn-detail-view">
                            <span>Detail</span>
                        </a>
                    </div>
                </article>

                <article class="char-card card-toji">
                    <div class="card-overlay-blur">
                        <a href="<?=SITE_URL?>/pages/character_detail.php?id=41" class="btn-detail-view">
                            <span>Detail</span>
                        </a>
                    </div>
                </article>
            </div>
        </section>

    </div>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // 1. Definisikan array data untuk 4 mode spotlight hero
        const heroData = [
            {
                title: "Expand Your Domain",
                subtitle: "Masuki dunia di mana kutukan dan energi tersembunyi bertarung melawan para tukang sihir. Jelajahi karakter, pelajari teknik kutukan, dan buktikan kekuatanmu dalam arena.",
                image: "<?=SITE_URL?>/asset/home_bg.jpg" // Gambar default bawaan awal Anda
            },
            {
                title: "Gojo vs Sukuna",
                subtitle: "Pertarungan puncak dua entitas terkuat sepanjang sejarah. Ketika Unlimited Void berbenturan langsung dengan Malevolent Shrine dalam perebutan dominasi mutlak.",
                image: "<?=SITE_URL?>/asset/gojovs.webp"
            },
            {
                title: "Suguru Geto",
                subtitle: "Mengumpulkan roh kutukan melalui teknik Cursed Spirit Manipulation. Bertekad menciptakan dunia baru murni bagi para penyihir jujutsu.",
                image: "<?=SITE_URL?>/asset/getball.png"
            },
            {
                title: "Ryomen Sukuna",
                subtitle: "Raja Kutukan tak tertandingi yang menguasai teknik pemotong instan 'Cleave and Dismantle'. Bangkit kembali mencari kebebasan mutlak atas dunia mutasi.",
                image: "<?=SITE_URL?>/asset/thukuna.png"
            }
        ];

        // 2. Seleksi element UI berdasarkan ID dan Class
        const titleEl = document.getElementById("hero-dynamic-title");
        const subtitleEl = document.getElementById("hero-dynamic-subtitle");
        const charImgEl = document.getElementById("hero-char-img");
        
        const prevBtn = document.querySelector(".arrow-prev");
        const nextBtn = document.querySelector(".arrow-next");

        let currentIndex = 0;

        // 3. Fungsi untuk mengupdate konten visual dengan efek transisi halus
        function updateHeroSpotlight(index) {
            // Beri efek transisi memudar tipis saat data berganti
            charImgEl.style.opacity = "0";
            titleEl.style.opacity = "0.3";
            subtitleEl.style.opacity = "0.3";

            setTimeout(() => {
                // Ganti data text dan link path source gambar
                titleEl.textContent = heroData[index].title;
                subtitleEl.textContent = heroData[index].subtitle;
                charImgEl.src = heroData[index].image;

                // Kembalikan kejelasan elemen
                charImgEl.style.opacity = "1";
                titleEl.style.opacity = "1";
                subtitleEl.style.opacity = "1";
            }, 200); // delay 200ms mengikuti efek fading
        }

        // 4. Trigger Event Listener saat tombol Next / Prev ditekan
        nextBtn.addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % heroData.length; // jika mentok balik ke 0
            updateHeroSpotlight(currentIndex);
        });

        prevBtn.addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + heroData.length) % heroData.length; // jika minus balik ke akhir
            updateHeroSpotlight(currentIndex);
        });
    });
    </script>

<?php include 'includes/footer.php'; ?>