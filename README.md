# JJK Universe — Web Ensiklopedia & Mini Game Jujutsu Kaisen

Website ini adalah **portal informasi bertema Jujutsu Kaisen** (ensiklopedia karakter, teknik kutukan, dan dunia/lokasi) yang dilengkapi dengan **mini game side-scroller "Cursed Spirit Slayer"**, sistem akun pengguna, leaderboard skor, dan dashboard admin untuk mengelola konten.

Dibangun dengan **PHP (native, tanpa framework) + MySQL** untuk backend, serta **HTML/CSS/JavaScript (Canvas API)** untuk halaman dan mini game.

---

## 1. Tujuan & Fungsi Web

Secara garis besar, web ini punya 3 fungsi utama:

1. **Ensiklopedia/Wiki Jujutsu Kaisen** — menampilkan data karakter, teknik kutukan (cursed technique), dan lokasi/dunia dalam cerita, lengkap dengan deskripsi, lore, statistik, dan gambar.
2. **Mini Game Browser-based** — game aksi 2D side-scroller bertema cursed spirit hunting, di mana pemain memilih karakter dan bertarung melawan gelombang musuh demi skor tertinggi.
3. **Sistem Komunitas & Akun** — pengguna bisa mendaftar, login, memberi komentar/rating pada karakter, menyimpan skor game ke leaderboard, dan (untuk admin) mengelola seluruh konten lewat dashboard.

---

## 2. Alur Penggunaan (User Flow)

### a. Pengunjung (Guest)
1. Membuka **Home** (`index.php`) → melihat hero section "Expand Your Domain", sinopsis cerita, dan featured characters.
2. Menjelajahi menu **Characters**, **Jujutsu**, **World** → melihat daftar dan detail masing-masing entitas (karakter, teknik, lokasi) lengkap dengan pencarian & filter.
3. Membuka **Mini Game** → bisa memilih karakter dan bermain, tapi skor **tidak tersimpan** ke leaderboard.
4. Membuka **Leaderboard** → tetap bisa melihat peringkat skor pemain lain meski belum login.
5. Jika ingin menyimpan skor / komentar → diarahkan untuk **Register/Login**.

### b. Pengguna Terdaftar (User)
1. **Login** (`pages/login.php`) menggunakan email & password (hash bcrypt).
2. Setelah login, navbar menampilkan nama user + tombol Logout.
3. Bisa memberi **komentar & rating (1–5)** pada halaman detail karakter (`character_detail.php`).
4. Bermain mini game → setiap kali permainan berakhir (`endGame()`), skor dikirim via AJAX (`POST action=save_score`) ke `game/index.php`, lalu disimpan ke tabel `game_scores` dan otomatis tampil di leaderboard.

### c. Admin
1. Login dengan akun bertipe `role = admin`.
2. Mengakses **Admin Dashboard** (`admin/dashboard.php`) untuk:
   - CRUD data **Characters** (nama, grade, afiliasi, cursed technique, deskripsi, lore, gambar, statistik ATK/DEF/SPD, status playable di game).
   - CRUD data **Jujutsu Techniques** (teknik kutukan, jenis, deskripsi).
   - CRUD data **World/Locations** (lokasi dalam cerita beserta gambar & deskripsi).
3. Perubahan data ini langsung tercermin di halaman Characters/Jujutsu/World dan juga di daftar karakter yang bisa dipilih di mini game (kolom `is_playable`).

---

## 3. Struktur Halaman & Fungsi Tiap Bagian

| Halaman | File | Fungsi |
|---|---|---|
| Home | `index.php` | Landing page: hero banner, sinopsis cerita, featured characters |
| Characters | `pages/characters.php` | Daftar seluruh karakter + search & filter |
| Character Detail | `pages/character_detail.php` | Detail 1 karakter: lore, statistik, comment & rating |
| Jujutsu | `pages/jujutsu.php` & `jujutsu_detail.php` | Daftar & detail teknik kutukan |
| World | `pages/world.php` & `world_detail.php` | Daftar & detail lokasi dalam cerita |
| Mini Game | `game/index.php` | Game "Cursed Spirit Slayer" (lihat bagian 4) |
| Leaderboard | `pages/leaderboard.php` | Top 20 skor tertinggi semua pemain |
| Login / Register | `pages/login.php`, `pages/register.php` | Autentikasi pengguna |
| Logout | `pages/logout.php` | Mengakhiri sesi |
| Admin Dashboard | `admin/dashboard.php` | CRUD karakter, teknik, lokasi (khusus admin) |
| Story | `pages/story.php` | Konten naratif/lore tambahan |

Bagian umum yang dipakai di semua halaman (`includes/`):
- `config.php` — koneksi DB (mysqli) + fungsi helper (auth, CRUD, leaderboard, CSRF, dll).
- `header.php`, `footer.php`, `navbar.php`, `navbar2.php`, `navbar-game.php` — komponen navbar/footer yang menyesuaikan tampilan per halaman dan status login user.

---

## 4. Mini Game — "Cursed Spirit Slayer"

Game aksi 2D side-scroller berbasis **HTML5 Canvas**, seluruh logic ada di `game/index.php` (sprite pixel-art digambar manual via JavaScript, tanpa file gambar sprite terpisah).

### Konten dalam game
- **Pemilihan Karakter**: hanya karakter dengan `is_playable = 1` di database yang muncul (mis. Yuji Itadori, Megumi Fushiguro, Nobara Kugisaki), masing-masing punya statistik ATK/DEF/SPD dan 2 skill unik berbasis lore-nya.
- **HUD**: bar HP, bar CE (Cursed Energy), combo counter, jumlah wave, jumlah musuh dikalahkan, dan skor.
- **Kontrol**:
  - `A/D` atau `←/→` — gerak
  - `W/Space` — lompat (termasuk naik ke platform melayang)
  - `J/Z` — Attack (serangan dasar, membangun combo)
  - `K/X` — Skill 1 (proyektil, butuh CE)
  - `L/C` — Skill 2 / Ultimate (area attack + multi-proyektil, butuh CE lebih besar)
  - `P` / `Esc` — Pause / keluar dari permainan
- **Musuh**: 3 tipe Cursed Spirit (`cursed`, `fast`, `strong`) dengan stat berbeda, muncul bergelombang (wave) yang makin sulit.
- **Boss**: setelah satu wave musuh biasa habis, **Ryomen Sukuna** muncul sebagai boss dengan fase "rage" saat HP < 50%, serangan charge, dan tembakan proyektil.
- **Sistem Skor**: poin didapat dari setiap musuh/boss yang dikalahkan, dikalikan multiplier wave; combo serangan menambah damage.
- **End Screen**: menampilkan skor akhir, jumlah musuh dikalahkan, wave tercapai, dan combo maksimum, plus opsi "Main Lagi" atau kembali ke menu.
- **Leaderboard Integration**: jika user sudah login, skor otomatis dikirim & disimpan ke tabel `game_scores` saat game selesai; jika belum login, ada peringatan untuk login agar skor tersimpan.

---

## 5. Struktur Database (MySQL)

Skema lengkap ada di `database.sql`. Tabel utama:

- **`users`** — akun pengguna (`username`, `email`, `password` hash bcrypt, `role`: admin/user).
- **`characters`** — data karakter: `name`, `grade`, `affiliation`, `cursed_technique`, `description`, `lore`, `image_url`, statistik (`attack_power`, `defense_power`, `speed_power`), dan `is_playable` (menentukan apakah muncul di mini game).
- **`game_scores`** — riwayat skor mini game per user (`character_used`, `score`, `enemies_defeated`, `played_at`) → sumber data Leaderboard.
- **`comments`** — komentar & rating (1–5) user terhadap karakter tertentu.
- Tabel tambahan untuk **techniques** (Jujutsu) dan **locations** (World) menyimpan data ensiklopedia teknik & lokasi.

---

## 6. Teknologi & Catatan Implementasi

- **Backend**: PHP native + MySQLi, session-based authentication, CSRF token untuk form, password hashing dengan bcrypt.
- **Frontend**: HTML/CSS kustom (tema gelap ungu/emas khas "cursed energy"), font Google Fonts (Cinzel Decorative, Orbitron, Rajdhani, Inter).
- **Mini Game**: JavaScript murni + Canvas 2D API, seluruh sprite karakter/musuh/boss adalah pixel-art yang digambar lewat data array (tidak memakai file gambar terpisah), fisika sederhana (gravitasi, platform collision, knockback, particle effect, screen shake).
- **Aset gambar** (`asset/`): foto/ilustrasi karakter (full & half-body), serta gambar lokasi dunia (`asset/World/`), digunakan di halaman ensiklopedia dan kartu pemilihan karakter di mini game.
- **Responsif**: navbar & kontrol mini game menyesuaikan untuk tampilan mobile (mobile control buttons untuk game, menu navbar disembunyikan di layar kecil).

---

## 7. Ringkasan Alur Data

```
Admin (Dashboard) ──CRUD──▶ Database (characters/techniques/locations)
                                   │
                                   ▼
        Halaman Characters / Jujutsu / World  ◀── ditampilkan ke semua pengunjung
                                   │
                                   ▼
        Karakter is_playable=1 ──▶ Muncul di pemilihan Mini Game
                                   │
                         User bermain & menang/kalah
                                   │
                          (jika login) skor dikirim
                                   ▼
                          Tabel game_scores ──▶ Leaderboard
```

Singkatnya: web ini menggabungkan **konten ensiklopedia statis** (dikelola admin via dashboard) dengan **gameplay interaktif** yang hasilnya (skor) terhubung kembali ke sistem akun & leaderboard komunitas.