<?php
require_once '../includes/config.php'; 

// Tangkap parameter pencarian dari URL
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch data karakter dari DB menggunakan fungsi bawaanmu
$characters = getAllCharacters(null, $searchQuery);

// Setup Parameter View Header Dinamis
$pageTitle = "Characters - Gallery Pool";
$extra_css = ['characters'];

include '../includes/header.php';
?>

    <main class="characters" data-page="characters">
      
      <div class="bg-wrapper" aria-hidden="true">
        <img class="bg-image" src="../asset/( ֊' '֊) 💭 1.png" alt="Background" />
        <div class="overlay-light"></div>
        <div class="overlay-dark"></div>
      </div>

      <section class="search-filter-section">
          <form action="" method="GET" class="search-form">
              <input 
                  type="text" 
                  name="search" 
                  class="search-input" 
                  placeholder="Cari nama, grade, atau teknik kutukan..." 
                  value="<?= htmlspecialchars($searchQuery) ?>"
              >
              <button type="submit" class="btn-search">Cari</button>
              <?php if(!empty($searchQuery)): ?>
                  <a href="characters.php" class="btn-reset">Reset</a>
              <?php endif; ?>
          </form>
      </section>

      <section class="character-gallery" aria-label="Character gallery">
        <div class="gallery-wrapper">
          <?php if (!empty($characters)): ?>
              <?php foreach ($characters as $char): ?>
                  
                  <a href="<?= SITE_URL ?>/pages/character_detail.php?id=<?= $char['id'] ?>" class="card" data-character="true">
                      <div class="card-inner">
                          <img 
                              class="character-img" 
                              src="<?= htmlspecialchars($char['image_url']) ?>" 
                              alt="<?= htmlspecialchars($char['name']) ?>" 
                          />
                      </div>
                  </a>

              <?php endforeach; ?>
          <?php else: ?>
              <div class="empty-state">
                  <h2>Tidak ada karakter yang ditemukan.</h2>
                  <p>Coba gunakan kata kunci lain (misal: "Special Grade" atau "Gojo").</p>
              </div>
          <?php endif; ?>
        </div>
      </section>
    </main>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const gallery = document.querySelector(".character-gallery");
        const wrapper = document.querySelector(".gallery-wrapper");
        const cards = Array.from(document.querySelectorAll(".card[data-character='true']"));
        
        if (!gallery || !wrapper || cards.length === 0) return;

        // CEGAH INFINITE SCROLL JIKA HASIL PENCARIAN TERLALU SEDIKIT
        // Logika aslimu butuh setidaknya 6-7 kartu agar infinite scroll terlihat natural tanpa loncat
        if (cards.length < 6) {
            gallery.style.overflowX = 'auto'; // Ubah ke scroll normal
            wrapper.style.justifyContent = 'center'; // Tengahkan kartu jika sedikit
            return; // Hentikan eksekusi cloning
        }

        const cloneCount = Math.min(cards.length, 5); 

        for (let i = 0; i < cloneCount; i++) {
          const clone = cards[i].cloneNode(true);
          clone.removeAttribute("data-character"); 
          wrapper.appendChild(clone);
        }

        for (let i = cards.length - 1; i >= cards.length - cloneCount; i--) {
          const clone = cards[i].cloneNode(true);
          clone.removeAttribute("data-character");
          wrapper.insertBefore(clone, wrapper.firstChild);
        }

        const updateInitialScroll = () => {
          const cardWidth = cards[0].getBoundingClientRect().width;
          const gap = parseFloat(window.getComputedStyle(wrapper).gap) || 0;
          gallery.scrollLeft = (cardWidth + gap) * cloneCount;
        };

        setTimeout(updateInitialScroll, 50);

        gallery.addEventListener("scroll", () => {
          const cardWidth = cards[0].getBoundingClientRect().width;
          const gap = parseFloat(window.getComputedStyle(wrapper).gap) || 0;
          const singleCardSpace = cardWidth + gap;
          
          const startThreshold = singleCardSpace * cloneCount;

          if (gallery.scrollLeft <= 5) {
            gallery.scrollLeft = wrapper.scrollWidth - gallery.clientWidth - startThreshold - 10;
          } 
          else if (gallery.scrollLeft >= wrapper.scrollWidth - gallery.clientWidth - 5) {
            gallery.scrollLeft = startThreshold + 10;
          }
        });
      });
    </script>

<?php include '../includes/footer.php'; ?>