-- JUJUTSU KAISEN WEB - DATABASE SCHEMA 

CREATE DATABASE IF NOT EXISTS jjk_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jjk_web;

-- Table 1: Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    avatar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table 2: Characters
CREATE TABLE IF NOT EXISTS characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    grade ENUM('Special Grade','Semi-Grade 1','Grade 1','Grade 2','Grade 3','Grade 4','Unranked') NOT NULL,
    affiliation VARCHAR(150),
    cursed_technique VARCHAR(255),
    description TEXT,
    lore TEXT,
    image_url VARCHAR(255),
    attack_power INT DEFAULT 50,
    defense_power INT DEFAULT 50,
    speed_power INT DEFAULT 50,
    is_playable BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table 3: Game Scores
CREATE TABLE IF NOT EXISTS game_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    character_used VARCHAR(100),
    score INT DEFAULT 0,
    enemies_defeated INT DEFAULT 0,
    played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table 4: Comments
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    character_id INT NOT NULL,
    content TEXT NOT NULL,
    rating INT DEFAULT 5 CHECK (rating BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE
);


-- SEED: USERS
-- password default: "password"
INSERT INTO users (username, email, password, role) VALUES
('admin',          'admin@jjk.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('yuji_itadori',   'yuji@jjk.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('megumi_fan',     'megumi@jjk.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('gojo_strongest', 'gojo@jjk.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');


-- SEED: CHARACTERS 50+ karakter JJK 
INSERT INTO characters
(name, grade, affiliation, cursed_technique, description, lore, image_url, attack_power, defense_power, speed_power, is_playable)
VALUES

-- ===================== TOKYO JUJUTSU HIGH ====================
('Yuji Itadori',
 'Special Grade', 'Tokyo Jujutsu High',
 'Divergent Fist / Black Flash / Shrine (Sukuna)',
 'Protagonis utama dengan kekuatan fisik luar biasa dan inang Ryomen Sukuna. Mampu menggunakan Black Flash secara konsisten.',
 'Yuji Itadori adalah siswa SMA biasa yang hidupnya berubah drastis saat ia menelan jari kutukan Ryomen Sukuna untuk melindungi temannya. Dengan tubuh yang luar biasa kuat bahkan untuk standar sorcerer, Yuji mampu menampung Sukuna tanpa hancur. Ia berjalan di jalur sorcerer dengan tujuan tunggal: memastikan orang-orang dapat mati dengan damai dan bermartabat.',
 'yuji.png', 90, 78, 88, TRUE),

('Megumi Fushiguro',
 'Grade 1', 'Tokyo Jujutsu High',
 'Ten Shadows Technique',
 'Sorcerer berbakat yang mewarisi Ten Shadows Technique dari klan Zenin. Dapat memanggil shikigami dari bayangan.',
 'Megumi Fushiguro adalah anak dari Toji Fushiguro yang kemudian diakui oleh klan Zenin. Ia direkrut oleh Gojo Satoru sebelum klan Zenin bisa mengklaimnya. Ten Shadows Technique-nya memungkinkan ia memanggil hingga 10 shikigami berbeda menggunakan bayangan sebagai media. Potensinya dianggap setara bahkan melebihi Gojo oleh Sukuna sendiri.',
 'megumi.png', 82, 80, 78, TRUE),

('Nobara Kugisaki',
 'Grade 1', 'Tokyo Jujutsu High',
 'Straw Doll Technique / Resonance',
 'Sorcerer perempuan tangguh dari pedesaan yang menggunakan palu, paku, dan boneka jerami untuk mengutuk musuh.',
 'Nobara Kugisaki tumbuh di desa kecil dan pindah ke Tokyo untuk mengejar impiannya. Straw Doll Technique-nya memungkinkan ia menancapkan paku ke boneka jerami untuk mentransfer kutukan ke target nyata melalui resonansi darah. Teknik Resonance-nya sangat efektif melawan Cursed Spirits bertipe khusus.',
 'nobara.png', 78, 72, 80, TRUE),

('Satoru Gojo',
 'Special Grade', 'Tokyo Jujutsu High',
 'Infinity / Limitless / Six Eyes / Hollow Purple',
 'Sorcerer terkuat di dunia dengan Six Eyes dan Limitless. Tidak terkalahkan dalam kondisi normal.',
 'Gojo Satoru lahir pada 7 Desember 1989, pertama kalinya dalam sejarah seseorang memiliki Six Eyes dan Limitless secara bersamaan. Six Eyes memberinya kemampuan persepsi tak terbatas sementara Limitless memungkinkan ia memanipulasi ruang di tingkat atom. Infinity-nya membuat hampir semua serangan tidak bisa menyentuhnya. Ia dianggap sebagai penjaga keseimbangan dunia sorcerer.',
 'gojo.png', 100, 100, 100, TRUE),

('Kento Nanami',
 'Grade 1', 'Tokyo Jujutsu High',
 'Ratio Technique / Overtime',
 'Mantan salaryman yang kembali menjadi sorcerer. Metodis, profesional, dan sangat efisien dalam pertarungan.',
 'Nanami Kento pernah meninggalkan dunia sorcerer untuk bekerja sebagai karyawan biasa, namun kembali karena merasa lebih jujur sebagai sorcerer. Ratio Technique-nya membagi target menjadi 7 bagian dan menyerang titik lemah 7:3 dengan tenaga yang diperkuat. Saat overtime (lewat jam kerja), kekuatannya meningkat drastis.',
 'nanami.png', 84, 80, 74, FALSE),

('Aoi Todo',
 'Grade 1', 'Kyoto Jujutsu High',
 'Boogie Woogie',
 'Sorcerer Grade 1 Kyoto dengan kekuatan fisik yang mengagumkan dan teknik pertukaran posisi via tepukan tangan.',
 'Todo Aoi adalah sorcerer eksentrik yang terobsesi dengan "tipe wanitanya" dan persahabatan sejati. Boogie Woogie-nya memungkinkan ia menukar posisi dua objek atau orang dengan cara bertepuk tangan, menciptakan kombinasi serangan yang membingungkan musuh. Ia mengajarkan Yuji konsep Black Flash dan menganggap Yuji sebagai "sahabat karib".',
 'todo.png', 92, 82, 86, FALSE),

('Maki Zenin',
 'Semi-Grade 1', 'Tokyo Jujutsu High',
 'Tidak ada (Zero Cursed Energy) / Heavenly Restriction',
 'Anggota klan Zenin yang lahir tanpa cursed energy namun memiliki fisik yang melampaui manusia biasa berkat Heavenly Restriction.',
 'Maki Zenin adalah putri dari keluarga Zenin yang dipandang rendah karena tidak memiliki cursed energy. Namun Heavenly Restriction-nya justru mengkompensasi dengan memberikan kekuatan fisik, kecepatan, dan ketahanan yang jauh melampaui sorcerer biasa. Ia mahir menggunakan berbagai cursed tools sebagai pengganti cursed technique.',
 'maki.png', 86, 75, 90, FALSE),

('Toge Inumaki',
 'Semi-Grade 1', 'Tokyo Jujutsu High',
 'Cursed Speech',
 'Sorcerer keturunan klan Inumaki yang berbicara hanya dengan bahan onigiri untuk mencegah kutukan tidak sengaja.',
 'Inumaki Toge mewarisi Cursed Speech dari klan Inumaki — teknik yang menyalurkan cursed energy ke ucapannya sehingga kata-katanya menjadi perintah yang harus dipatuhi, bahkan oleh Cursed Spirits. Efek sampingnya adalah kerusakan pada tenggorokannya sendiri. Ia berkomunikasi sehari-hari menggunakan nama bahan onigiri untuk menghindari kecelakaan.',
 'inumaki.png', 80, 65, 75, FALSE),

('Panda',
 'Semi-Grade 1', 'Tokyo Jujutsu High',
 'Divergent Fist (Gorilla Core) / Panda Core / Elder Brother Core',
 'Cursed Corpse yang diciptakan oleh Masamichi Yaga berbentuk panda dengan tiga core berbeda yang bisa diaktifkan.',
 'Panda adalah Cursed Corpse mutant yang diciptakan oleh Kepala Sekolah Yaga. Ia bukan panda sungguhan, melainkan entitas yang terbentuk dari tiga core (inti): Panda, Gorilla, dan sosok misterius ketiga. Ia memiliki kesadaran penuh, kepribadian ramah, dan bisa berbicara. Gorilla Core-nya meningkatkan kekuatan serangan secara drastis.',
 'panda.png', 78, 82, 72, FALSE),

('Yuta Okkotsu',
 'Special Grade', 'Tokyo Jujutsu High',
 'Copy / Rika (Queen of Curses)',
 'Sorcerer Special Grade yang memiliki ikatan dengan roh kutukan Rika, mantan kekasihnya yang telah meninggal.',
 'Okkotsu Yuta awalnya dianggap sebagai korban yang dihantui oleh Rika Orimoto, mantan kekasihnya yang meninggal dalam kecelakaan. Namun sebenarnya Rika adalah Special Grade Cursed Spirit maha kuat yang terikat padanya. Setelah belajar di Tokyo Jujutsu High, Yuta berkembang menjadi salah satu sorcerer Special Grade termuda. Teknik Copy-nya memungkinkan ia meniru teknik kutukan orang lain.',
 'yuta.png', 96, 88, 85, FALSE),

('Masamichi Yaga',
 'Grade 1', 'Tokyo Jujutsu High',
 'Cursed Corpse Manipulation',
 'Kepala Sekolah Tokyo Jujutsu High yang menciptakan Cursed Corpse termasuk Panda.',
 'Yaga Masamichi adalah Kepala Sekolah Tokyo Jujutsu High dan pencipta Cursed Corpse paling canggih yang pernah ada. Kemampuannya membuat Cursed Corpse yang memiliki kesadaran mandiri seperti Panda dianggap sebagai pencapaian luar biasa. Ia sosok yang keras di luar namun peduli terhadap murid-muridnya.',
 'yaga.png', 76, 74, 68, FALSE),

('Ijichi Kiyotaka',
 'Grade 3', 'Tokyo Jujutsu High',
 'Tidak diketahui',
 'Asisten manajer Tokyo Jujutsu High yang sering bertugas mendampingi dan memandu para sorcerer muda.',
 'Ijichi adalah staf administrasi Tokyo Jujutsu High yang bertugas sebagai pendukung operasional para sorcerer. Meski kemampuan bertarungnya terbatas, ia sangat berdedikasi dalam tugasnya dan menjadi figur yang dapat diandalkan oleh Yuji dan kawan-kawan.',
 'ijichi.png', 35, 40, 45, FALSE),

-- ==================== KYOTO JUJUTSU HIGH ====================

('Arata Nitta',
 'Grade 2', 'Kyoto Jujutsu High',
 'Healing Technique (terbatas)',
 'Sorcerer Kyoto yang memiliki kemampuan penyembuhan terbatas, bisa menghentikan pendarahan dan meringankan luka.',
 'Nitta Arata adalah sorcerer dari Kyoto Jujutsu High dengan kemampuan penyembuhan yang tidak bisa menyembuhkan sepenuhnya namun sangat berguna dalam situasi darurat. Ia berperan sebagai dukungan medis bagi rekan-rekannya.',
 'nitta.png', 42, 55, 58, FALSE),

('Kasumi Miwa',
 'Grade 3', 'Kyoto Jujutsu High',
 'New Shadow Style: Simple Domain',
 'Sorcerer perempuan Kyoto yang mengagumi Gojo dan menggunakan pedang katana dengan gaya Simple Domain.',
 'Miwa Kasumi adalah sorcerer biasa yang tidak memiliki teknik kutukan bawaan, namun ia mengembangkan kemampuannya dengan katana dan Simple Domain. Ia jujur tentang keterbatasannya dan bekerja keras untuk mengkompensasinya. Mengagumi Gojo Satoru secara berlebihan.',
 'miwa.png', 55, 52, 65, FALSE),

('Noritoshi Kamo',
 'Grade 1', 'Kyoto Jujutsu High',
 'Blood Manipulation',
 'Pewaris resmi klan Kamo dengan teknik manipulasi darah yang sangat versatil.',
 'Kamo Noritoshi adalah pewaris klan Kamo, salah satu dari tiga keluarga besar sorcerer. Blood Manipulation-nya memungkinkan ia mengontrol darahnya sendiri dan darah orang lain, menciptakan proyektil, perisai, bahkan mempercepat pembekuan darah. Ia awalnya antagonis namun kemudian bergabung bersama tim Yuji.',
 'kamo.png', 78, 72, 74, FALSE),

('Mai Zenin',
 'Grade 3', 'Kyoto Jujutsu High',
 'Construction / Zenin Clan Technique',
 'Saudara kembar Maki yang memiliki cursed energy namun kemampuan terbatas, menggunakan revolver dalam pertarungan.',
 'Mai Zenin adalah saudara kembar Maki yang lahir dengan cursed energy. Ironisnya, memiliki cursed energy di keluarga Zenin justru membuatnya memikul beban lebih besar karena ekspektasi yang tinggi. Construction-nya memungkinkan ia menciptakan satu peluru dari cursed energy yang sangat kuat, namun hanya bisa sekali.',
 'mai.png', 58, 55, 62, FALSE),

('Kokichi Muta (Mechamaru)',
 'Grade 2', 'Kyoto Jujutsu High',
 'Ultimate Mechamaru / Puppet Manipulation',
 'Sorcerer yang tubuhnya lemah dan dikurung di satu tempat, namun mengendalikan robot Mechamaru dari jauh.',
 'Muta Kokichi lahir dengan Heavenly Restriction yang membuat tubuhnya sangat lemah dan sensitif — bahkan sinar bulan terasa menyakitkan. Sebagai kompensasi, ia memiliki reservoir cursed energy yang sangat besar dan mengendalikan robot Mechamaru di seluruh Jepang. Ia akhirnya berkhianat demi keinginan memiliki tubuh yang normal.',
 'mechamaru.png', 72, 60, 70, FALSE),

-- ==================== SPECIAL GRADE CURSED SPIRITS ====================

('Ryomen Sukuna',
 'Special Grade', 'Tidak ada (Raja Kutukan)',
 'Malevolent Shrine / Dismantle / Cleave / Fire Arrow',
 'Raja Kutukan tertinggi yang pernah hidup. Kekuatannya begitu besar sehingga sisa jarinya masih menjadi kutukan setelah kematiannya 1000 tahun lalu.',
 'Sukuna Ryomen adalah kutukan terkuat sepanjang sejarah, hidup lebih dari 1000 tahun lalu saat ia adalah manusia sorcerer. Setelah kematiannya, kutukan dalam dirinya terkumpul menjadi 20 jari yang tidak bisa dihancurkan. Malevolent Shrine-nya adalah Domain Expansion maha kuat yang mencakup radius besar dan secara otomatis memotong semua yang ada di dalamnya. Ia menganggap semua manusia sebagai yang lebih rendah.',
 'sukuna.png', 100, 98, 98, FALSE),

('Mahito',
 'Special Grade', 'Cursed Spirit Alliance',
 'Idle Transfiguration',
 'Kutukan yang lahir dari rasa takut manusia terhadap manusia lain. Dapat mengubah bentuk jiwa dan tubuh siapapun.',
 'Mahito adalah Cursed Spirit yang lahir dari kebencian dan rasa takut antar sesama manusia. Idle Transfiguration-nya memungkinkan ia menyentuh jiwa seseorang dan mengubah bentuk tubuh mereka sesuka hati — menjadi monster, meledakkan mereka, atau bahkan membunuh instan. Ia adalah musuh bebuyutan Yuji karena membunuh Junpei Yoshino di depannya.',
 'mahito.png', 88, 85, 82, FALSE),

('Jogo',
 'Special Grade', 'Cursed Spirit Alliance',
 'Disaster Flames / Maximum: Meteor',
 'Kutukan api yang percaya dirinya lebih "manusiawi" dari manusia. Memiliki kekuatan api dan gunung berapi.',
 'Jogo adalah Cursed Spirit yang lahir dari rasa takut manusia terhadap bencana alam berupa api dan gunung berapi. Ia sangat percaya diri dan yakin bahwa Cursed Spirits adalah bentuk manusia yang lebih murni dari manusia itu sendiri. Kekuatan apinya sangat destruktif, dan teknik Maximum: Meteor-nya memanggil meteor api dari langit.',
 'jogo.png', 90, 80, 78, FALSE),

('Hanami',
 'Special Grade', 'Cursed Spirit Alliance',
 'Disaster Plants / Flower Fields',
 'Kutukan tanaman yang tidak memiliki mata namun dapat merasakan segalanya melalui getaran. Bijaksana dan tenang.',
 'Hanami adalah Cursed Spirit yang lahir dari rasa takut manusia terhadap alam dan bencana tumbuhan. Tidak seperti rekan-rekannya, Hanami tenang dan filosofis, percaya bahwa alam harus dilindungi dari manusia. Tanaman-tanamannya bisa tumbuh dalam tubuh lawan, mencuri cursed energy, dan menciptakan medan bunga yang mematikan.',
 'hanami.png', 85, 88, 76, FALSE),

('Dagon',
 'Special Grade', 'Cursed Spirit Alliance',
 'Disaster Tides / Death Swarm',
 'Kutukan air laut yang berevolusi dari benih kutukan menjadi Special Grade dalam waktu singkat.',
 'Dagon awalnya adalah benih kutukan yang dipelihara oleh Jogo dan rekan-rekannya. Selama Shibuya Incident, ia berevolusi menjadi Special Grade Cursed Spirit sepenuhnya. Teknik-nya berkaitan dengan air laut, ikan, dan bencana kelautan. Domain Expansion-nya Horizon of the Captivating Skandha menjebak target di pantai yang dipenuhi ikan mematikan.',
 'dagon.png', 84, 82, 80, FALSE),

-- ==================== CURSED SPIRIT USERS / ANTAGONIS ====================

('Suguru Geto',
 'Special Grade', 'Cursed Spirit Users (mantan Tokyo)',
 'Cursed Spirit Manipulation',
 'Mantan sahabat Gojo yang berubah menjadi villain setelah memutuskan bahwa non-sorcerer adalah hambatan dunia.',
 'Geto Suguru dulunya adalah sahabat terbaik Gojo dan salah satu sorcerer paling berbakat di generasinya. Setelah menyaksikan kematian tragis seorang gadis yang ia lindungi dan bertanya-tanya tentang makna eksistensi sorcerer, ia memutuskan untuk memusnahkan semua non-sorcerer. Cursed Spirit Manipulation-nya memungkinkan ia menyerap dan mengendalikan kutukan yang sudah dikalahkannya.',
 'geto.png', 88, 84, 82, FALSE),

('Pseudo-Geto (Kenjaku)',
 'Special Grade', 'Cursed Spirit Users',
 'Brain Transplantation / Cursed Spirit Manipulation (warisan)',
 'Entitas kuno yang menguasai tubuh Geto setelah kematian Geto asli. Dalang utama di balik Shibuya Incident.',
 'Kenjaku adalah sorcerer kuno berusia lebih dari 1000 tahun yang bisa memindahkan otaknya ke tubuh orang lain. Ia telah hidup dalam banyak tubuh sepanjang sejarah dan menjadi dalang dari berbagai insiden besar. Setelah membunuh Geto asli, ia mengambil tubuhnya dan mendapatkan Cursed Spirit Manipulation. Tujuannya mengevolusi manusia ke tahap berikutnya.',
 'kenjaku.png', 92, 88, 84, FALSE),

('Haruta Shigemo',
 'Grade 2', 'Cursed Spirit Users',
 'Miracles',
 'Sorcerer antagonis yang memiliki kemampuan keberuntungan luar biasa yang melindunginya dari serangan fatal.',
 'Shigemo Haruta adalah sorcerer jahat yang bekerja untuk Kenjaku. Teknik Miracles-nya mengumpulkan "keberuntungan" dari orang-orang yang ia sakiti, memberinya perlindungan supernatural dari serangan yang seharusnya mematikan. Ia kejam dan sembrono dalam pertarungan.',
 'shigemo.png', 65, 60, 70, FALSE),

('Choso',
 'Special Grade', 'Cursed Spirit Users (awalnya)',
 'Blood Manipulation',
 'Setengah manusia setengah kutukan, saudara tertua dari 9 saudara kandung Death Paintings. Akhirnya berpihak pada Yuji.',
 'Choso adalah salah satu dari Cursed Womb: Death Paintings — entitas yang lahir dari hubungan antara manusia dan Cursed Spirit. Sebagai yang tertua dari 9 bersaudara, ia memiliki Blood Manipulation yang jauh lebih kuat dari Kamo karena darahnya sendiri adalah kutukan. Setelah menyadari bahwa Yuji adalah "adik"-nya (karena Kenjaku adalah ibu biologis keduanya), ia berbalik melawan Kenjaku.',
 'choso.png', 86, 78, 80, FALSE),

('Eso',
 'Special Grade', 'Death Paintings',
 'Rot Technique / Wings',
 'Death Painting kedua yang memiliki kemampuan racun busuk dan sayap untuk terbang.',
 'Eso adalah salah satu dari Cursed Womb: Death Paintings dan saudara kandung Choso. Rot Technique-nya menciptakan racun busuk yang menyebar cepat melalui kontak, sementara sayapnya memberinya mobilitas tinggi di udara. Ia sangat setia kepada Choso dan saudara-saudaranya.',
 'eso.png', 78, 72, 82, FALSE),

('Kechizu',
 'Special Grade', 'Death Paintings',
 'Rot Technique',
 'Death Painting ketiga yang berbagi teknik Rot dengan Eso namun lebih besar dan kuat secara fisik.',
 'Kechizu adalah saudara termuda dari tiga Death Painting yang muncul pertama kali dalam cerita. Ia lebih mengandalkan kekuatan fisik dan Rot Technique-nya yang melumpuhkan lawan melalui racun busuk. Bersama Eso, ia hampir mengalahkan Yuji dan Nobara sebelum akhirnya dikalahkan.',
 'kechizu.png', 75, 70, 68, FALSE),

-- ==================== KLAN ZENIN ====================

('Naobito Zenin',
 'Special Grade', 'Klan Zenin',
 'Projection Sorcery',
 'Kepala klan Zenin, sorcerer tercepat ke-2 di dunia setelah Gojo. Menggunakan teknik proyeksi frame-per-frame.',
 'Zenin Naobito adalah pemimpin klan Zenin dan sorcerer dengan kecepatan tertinggi kedua setelah Gojo. Projection Sorcery-nya memungkinkan ia membagi gerakan menjadi 24 frame per detik dan bergerak dengan presisi absolut. Siapa pun yang menyentuh "Projected" dalam satu frame akan membeku selama satu detik penuh.',
 'naobito.png', 88, 82, 95, FALSE),

('Naoya Zenin',
 'Semi-Grade 1', 'Klan Zenin',
 'Projection Sorcery',
 'Putra Naobito yang mewarisi Projection Sorcery. Sexist, arogan, dan sangat berbahaya.',
 'Zenin Naoya adalah pewaris Projection Sorcery dari ayahnya Naobito. Ia adalah seorang sexist sejati yang menganggap perempuan tidak layak menjadi sorcerer. Meskipun kepribadiannya menjijikkan, kemampuan tempurnya sangat tinggi berkat kecepatan Projection Sorcery yang ia warisi. Bahkan setelah kematiannya, ia bangkit kembali sebagai Cursed Spirit.',
 'naoya.png', 85, 78, 96, FALSE),

('Ogi Zenin',
 'Grade 1', 'Klan Zenin',
 'Bamboo Sword Technique',
 'Ayah kandung Maki dan Mai Zenin yang memandang rendah putrinya karena tidak sesuai ekspektasi klan.',
 'Zenin Ogi adalah ayah Maki dan Mai yang tidak pernah menerima mereka karena merasa mereka membawa malu pada klan Zenin. Ia adalah sorcerer Grade 1 yang kuat namun karakter moralnya sangat buruk. Ia akhirnya dikalahkan oleh Maki yang telah mencapai potensi penuhnya setelah kehilangan Mai.',
 'ogi.png', 78, 72, 76, FALSE),

-- ==================== SORCERER SENIOR / JUJUTSU SOCIETY ====================

('Mei Mei',
 'Grade 1', 'Independen',
 'Bird Strike',
 'Sorcerer independen yang bermotivasi uang. Menggunakan burung gagak yang dikendalikan sebagai senjata.',
 'Mei Mei adalah sorcerer Grade 1 yang bekerja independen dengan bayaran tinggi. Bird Strike-nya mengorbankan burung yang dikendalikannya untuk meledakkan target dengan cursed energy terkonsentrasi. Ia sangat dingin dan kalkulatif, selalu memprioritaskan keuntungan finansial. Adiknya Ui Ui sangat terobsesi dengannya.',
 'meimei.png', 82, 76, 80, FALSE),

('Ui Ui',
 'Grade 4', 'Independen',
 'Teleportation (via kakak)',
 'Adik Mei Mei yang terobsesi dengan kakaknya. Mampu melakukan teleportasi dalam kondisi tertentu.',
 'Ui Ui adalah adik Mei Mei yang mengikuti kakaknya ke mana saja. Meski kemampuan tempurnya terbatas, ia memiliki teknik yang memungkinkan teleportasi dirinya dan orang lain dalam kondisi tertentu. Obsesinya terhadap Mei Mei sering dikomentari oleh karakter lain.',
 'uiui.png', 40, 45, 65, FALSE),

('Utahime Iori',
 'Semi-Grade 1', 'Kyoto Jujutsu High',
 'Solo Solo Kinship',
 'Supervisor Kyoto Jujutsu High yang memiliki hubungan cinta-benci dengan Gojo. Sering dikerjain Gojo.',
 'Iori Utahime adalah supervisor Kyoto Jujutsu High yang bertanggung jawab mengawasi para siswa. Solo Solo Kinship-nya adalah teknik yang memperkuat kemampuan semua rekan satu tim di sekitarnya secara signifikan. Ia sering kali frustrasi dengan tingkah Gojo yang selalu mengganggunya.',
 'utahime.png', 68, 65, 70, FALSE),

('Shoko Ieiri',
 'Grade 1', 'Tokyo Jujutsu High (Dokter)',
 'Reverse Cursed Technique (Healing)',
 'Satu-satunya sorcerer yang bisa menggunakan Reverse Cursed Technique untuk penyembuhan total. Dokter utama Jujutsu Society.',
 'Ieiri Shoko adalah teman seangkatan Gojo dan Geto. Ia satu-satunya sorcerer aktif yang mampu menggunakan Reverse Cursed Technique untuk menyembuhkan orang lain — kemampuan yang sangat langka. Sebagai dokter utama Jujutsu Society, ia berperan vital dalam merawat sorcerer yang terluka.',
 'shoko.png', 55, 65, 60, FALSE),

('Haibara Yu',
 'Grade 2', 'Tokyo Jujutsu High',
 'Tidak diketahui sepenuhnya',
 'Senpai Nanami yang gugur saat misi, kematiannya menjadi salah satu motivasi Nanami kembali menjadi sorcerer.',
 'Haibara Yu adalah senior Nanami di Tokyo Jujutsu High yang tewas dalam misi. Kematiannya yang tragis meninggalkan kesan mendalam pada Nanami dan menjadi salah satu alasan Nanami akhirnya kembali ke dunia sorcerer meski pernah meninggalkannya.',
 'haibara.png', 62, 58, 65, FALSE),

-- ==================== KARAKTER PENDUKUNG ====================

('Junpei Yoshino',
 'Unranked', 'Tokyo Jujutsu High (sebentar)',
 'Moon Dregs (via Mahito)',
 'Siswa SMA biasa yang di-bully dan akhirnya bertemu Yuji. Korban Mahito yang paling membekas.',
 'Yoshino Junpei adalah siswa SMA biasa yang sering di-bully dan tidak punya tempat berpijak hingga bertemu Yuji Itadori. Pertemuan dengan Mahito mengubahnya — ia diberikan teknik Moon Dregs yang menggunakan ubur-ubur kutukan. Namun Mahito akhirnya mengkhianatinya dan mengubah Junpei menjadi monster di depan mata Yuji, menciptakan luka yang tidak pernah sembuh bagi Yuji.',
 'junpei.png', 45, 40, 55, FALSE),

('Takuma Ino',
 'Grade 2', 'Tokyo Jujutsu High',
 'Auspicious Beasts Summon',
 'Sorcerer yang sangat menghormati Nanami dan menganggapnya sebagai mentor.',
 'Ino Takuma adalah sorcerer Grade 2 yang bekerja di bawah Nanami dan sangat mengidolakan seniornya itu. Auspicious Beasts Summon-nya memungkinkan ia memanggil berbagai makhluk jimat sesuai level kekuatan yang dibutuhkan. Ia terluka parah selama Shibuya Incident saat mencoba melindungi rekan-rekannya.',
 'ino.png', 68, 62, 66, FALSE),

('Nobuo Takuma',
 'Grade 3', 'Tokyo Jujutsu High',
 'Tidak diketahui',
 'Sorcerer pendukung yang bertugas dalam berbagai misi.',
 'Sorcerer Grade 3 yang menjadi bagian dari pasukan Tokyo Jujutsu High dalam berbagai misi pertempuran besar.',
 'nobuo.png', 52, 50, 55, FALSE),

('Akari Nitta',
 'Grade 3', 'Tokyo Jujutsu High',
 'Healing (terbatas)',
 'Asisten manajer yang memiliki kemampuan penyembuhan terbatas, bertugas mendampingi para sorcerer dalam misi.',
 'Nitta Akari adalah asisten manajer yang sering mendampingi sorcerer dalam misi. Kemampuan penyembuhannya terbatas namun berguna untuk pertolongan pertama di lapangan.',
 'akari.png', 38, 48, 52, FALSE),

-- ==================== KARAKTER SENPAI LEGENDARIS ====================

('Toji Fushiguro',
 'Unranked', 'Independen (mantan)',
 'Tidak ada (Heavenly Restriction)',
 'Mantan anggota klan Zenin, ayah Megumi. Meninggalkan sorcery namun menjadi pembunuh bayaran paling ditakuti.',
 'Fushiguro Toji, dijuluki "Sorcerer Killer", adalah mantan anggota klan Zenin yang lahir dengan Heavenly Restriction — tidak memiliki cursed energy sama sekali. Namun sebagai kompensasi, tubuhnya menjadi senjata sempurna: kekuatan, kecepatan, dan ketahanan yang melampaui batas manusia. Ia meninggalkan anaknya Megumi dengan klan Zenin dan menjadi pembunuh bayaran profesional. Bahkan Gojo muda hampir dikalahkannya.',
 'toji.png', 95, 85, 98, FALSE),

('Riko Amanai',
 'Unranked', 'Star Plasma Vessel',
 'Tidak ada',
 'Star Plasma Vessel yang ditakdirkan untuk bersatu dengan Tengen. Dilindungi oleh Gojo dan Geto muda.',
 'Amanai Riko adalah Star Plasma Vessel — manusia yang dipilih untuk bersatu dengan Sorcerer Tengen agar Tengen bisa mempertahankan bentuk manusianya. Ia dilindungi oleh Gojo dan Geto muda dalam misi yang akhirnya berakhir tragis saat Toji Fushiguro berhasil membunuhnya.',
 'riko.png', 20, 20, 35, FALSE),

('Master Tengen',
 'Special Grade', 'Jujutsu Society',
 'Immortality / Barrier Technique',
 'Sorcerer abadi yang menjaga barrier seluruh Jepang. Keberadaannya adalah fondasi dunia sorcerer.',
 'Tengen adalah sorcerer yang telah hidup selama ratusan tahun berkat teknik Immortality-nya. Ia bertanggung jawab mempertahankan barrier-barrier yang melindungi berbagai fasilitas Jujutsu Society di seluruh Jepang. Tanpa intervensi Star Plasma Vessel setiap 500 tahun, Tengen akan berevolusi melampaui bentuk manusia dan menjadi ancaman.',
 'tengen.png', 60, 100, 40, FALSE),

-- ==================== KARAKTER CULLING GAME ====================

('Hiromi Higuruma',
 'Unranked (mantan pengacara)', 'Culling Game',
 'Juryman / Deadly Sentencing',
 'Mantan pengacara yang mendapatkan teknik kutukan setelah menerima poin dalam Culling Game. Menggunakan sistem pengadilan.',
 'Higuruma Hiromi adalah mantan pengacara yang kecewa dengan sistem hukum. Setelah bergabung dalam Culling Game, ia mendapatkan teknik Juryman yang menciptakan domain pengadilan di mana ia bertindak sebagai hakim. Jika terdakwa dinyatakan bersalah, mereka kehilangan teknik kutukan mereka. Ia akhirnya bersekutu dengan Yuji.',
 'higuruma.png', 80, 75, 70, FALSE),

('Hajime Kashimo',
 'Unranked (sorcerer kuno)', 'Culling Game',
 'Genju Kasshin / Mythical Beast Amber',
 'Sorcerer dari 400 tahun lalu yang mengorbankan semua kemampuannya untuk satu teknik terakhir maha kuat demi melawan Sukuna.',
 'Kashimo Hajime adalah sorcerer dari era 400 tahun lalu yang lahir di zaman yang sama dengan Sukuna. Ia menghabiskan seluruh hidupnya menginginkan pertarungan melawan Sukuna. Teknik Mythical Beast Amber-nya mengubah tubuhnya menjadi senjata listrik hidup yang maha destruktif, namun hanya bisa digunakan sekali.',
 'kashimo.png', 95, 80, 90, FALSE),

('Yuki Tsukumo',
 'Special Grade', 'Independen',
 'Star Rage / Virtual Mass',
 'Satu dari empat Special Grade sorcerer resmi. Tidak terikat pada Jujutsu Society dan meneliti cara menghilangkan kutukan dari manusia.',
 'Tsukumo Yuki adalah salah satu dari empat Special Grade sorcerer yang diakui secara resmi. Ia menolak bekerja untuk Jujutsu Society dan hidup sebagai sorcerer independen sambil meneliti cara menghapus kutukan dari umat manusia sepenuhnya. Star Rage-nya menambahkan Virtual Mass ke dirinya atau orang lain, menciptakan kekuatan destruktif setara bintang.',
 'yuki.png', 95, 85, 88, FALSE),

('Kinji Hakari',
 'Grade 3 (potensi Special Grade)', 'Tokyo Jujutsu High',
 'Idle Death Gamble / Pachinko Domain',
 'Sorcerer yang diskors karena melanggar aturan. Tekniknya berdasarkan perjudian pachinko dengan hadiah jackpot regenerasi tak terbatas.',
 'Hakari Kinji adalah sorcerer yang sangat kuat namun diskors dari Tokyo Jujutsu High karena bertengkar dengan petinggi Jujutsu Society. Domain Expansion-nya didasarkan pada mesin pachinko, dan jika jackpot tercapai, ia mendapatkan Reverse Cursed Technique tak terbatas selama durasi tertentu, membuatnya praktis abadi. Gojo menganggapnya setara dengannya saat jackpot aktif.',
 'hakari.png', 90, 85, 85, FALSE),

('Fumihiko Takaba',
 'Unranked', 'Culling Game',
 'Comedian',
 'Komedian gagal yang mendapatkan teknik kutukan yang kekuatannya bergantung pada seberapa lucu ia.',
 'Takaba Fumihiko adalah komedian yang berjuang keras tapi tidak pernah berhasil. Teknik Comedian-nya membuat apapun yang ia anggap lucu menjadi kenyataan — kekuatan yang secara teoritis tidak terbatas jika ia benar-benar meyakini leluconnya. Ia adalah karakter paling tak terduga dalam cerita.',
 'takaba.png', 70, 65, 72, FALSE),

('Kurourushi',
 'Special Grade', 'Culling Game',
 'Cockroach Manipulation',
 'Kutukan berbentuk kecoa raksasa dengan kemampuan memanipulasi dan memanggil kecoa dalam jumlah tak terbatas.',
 'Kurourushi adalah Cursed Spirit berbentuk kecoa raksasa yang berpartisipasi dalam Culling Game. Kemampuannya memanipulasi kecoa dalam jumlah masif membuatnya sangat berbahaya karena kecoa-kecoanya bisa masuk ke tubuh lawan dan menghancurkan dari dalam.',
 'kurourushi.png', 82, 78, 75, FALSE),

-- ==================== KARAKTER TAMBAHAN ====================

('Nana Pele',
 'Grade 1', 'Independen',
 'Tidak diketahui',
 'Sorcerer Grade 1 yang gugur selama Shibuya Incident.',
 'Sorcerer Grade 1 yang menjadi salah satu korban dalam kekacauan Shibuya Incident. Kematiannya menjadi bagian dari korban besar yang berjatuhan dalam malam bersejarah tersebut.',
 'nanapele.png', 75, 70, 72, FALSE),

('Atsuya Kusakabe',
 'Grade 1', 'Tokyo Jujutsu High',
 'Unkonwn / Classical Swordsmanship',
 'Guru Tokyo Jujutsu High yang ahli pedang dan sangat pragmatis dalam menghadapi bahaya.',
 'Kusakabe Atsuya adalah sorcerer Grade 1 yang menjadi guru di Tokyo Jujutsu High. Ia sangat pragmatis dan cenderung menghindari risiko yang tidak perlu. Meskipun tidak memiliki teknik kutukan yang diketahui publik, keahlian pedangnya sangat tinggi.',
 'kusakabe.png', 78, 75, 76, FALSE),

('Angel (Hana Kurusu)',
 'Unranked', 'Culling Game',
 'Jacob''s Ladder',
 'Sorcerer dari masa lalu yang jiwa-nya mendiami tubuh Hana Kurusu. Memiliki teknik untuk menghancurkan teknik kutukan apapun.',
 'Angel adalah jiwa sorcerer kuno yang mendiami tubuh Hana Kurusu dalam Culling Game. Jacob''s Ladder-nya adalah satu-satunya teknik yang mampu menghancurkan Prison Realm yang memenjarakan Gojo, menjadikannya target utama Yuji dan Megumi. Ia membenci reinkarnasi dan Kenjaku.',
 'angel.png', 75, 72, 70, FALSE),

('Ui Ui (versi lengkap)',
 'Grade 4', 'Independen',
 'Technique Relay',
 'Adik Mei Mei yang memiliki teknik Technique Relay untuk memindahkan teknik kutukan ke orang lain.',
 'Ui Ui mampu memindahkan teknik kutukan dari satu orang ke orang lain dalam kondisi tertentu. Meskipun kekuatan tempurnya sendiri terbatas, teknik ini membuatnya sangat berharga dalam operasi tim.',
 'uiui2.png', 42, 45, 68, FALSE),

('Dhruv Lakdawalla',
 'Unranked', 'Culling Game',
 'Star Rage (diadopsi)',
 'Peserta Culling Game dari India yang memiliki kekuatan fisik luar biasa.',
 'Dhruv adalah peserta Culling Game yang berasal dari India dengan kekuatan fisik yang sangat tinggi. Ia adalah salah satu peserta non-Jepang dalam game mematikan tersebut.',
 'dhruv.png', 78, 75, 72, FALSE),

('Kiyotaka Ijichi (revisi)',
 'Grade 3', 'Tokyo Jujutsu High',
 'Territory-type barrier',
 'Asisten manajer yang juga bertugas sebagai pengemudi dan koordinator lapangan untuk para sorcerer.',
 'Ijichi Kiyotaka berperan vital sebagai penghubung antara sorcerer dan Jujutsu Society dalam misi-misi lapangan. Meski kemampuan tempurnya tidak menonjol, dedikasinya sangat tinggi.',
 'ijichi2.png', 38, 42, 50, FALSE),

-- ==================== KARAKTER KLAN KAMO / PENDUKUNG LAIN ====================

('Jinichi Zenin',
 'Grade 1', 'Klan Zenin',
 'Zenin Clan Techniques',
 'Anggota senior klan Zenin yang setia pada tradisi klan.',
 'Anggota klan Zenin yang terlibat dalam urusan internal klan saat konflik antara Maki dan klan Zenin memuncak.',
 'jinichi.png', 76, 74, 70, FALSE),

('Ranta Zenin',
 'Grade 2', 'Klan Zenin',
 'Zenin Clan Techniques',
 'Anggota muda klan Zenin yang terlibat dalam konfrontasi dengan Maki.',
 'Anggota klan Zenin yang menjadi bagian dari pasukan yang menghadapi Maki setelah kematian Mai.',
 'ranta.png', 65, 62, 68, FALSE),

('Rika Orimoto',
 'Special Grade', 'Yuta Okkotsu (terikat)',
 'Unlimited Cursed Energy Stockpile',
 'Roh kutukan Special Grade yang terikat pada Yuta Okkotsu. Disebut Ratu Kutukan.',
 'Rika Orimoto adalah mantan kekasih Yuta yang meninggal dalam kecelakaan saat masih kecil. Cintanya yang kuat pada Yuta mengubahnya menjadi Cursed Spirit maha kuat yang terikat padanya. Kekuatannya hampir tak terbatas, dan ia melindungi Yuta secara fanatik dari siapapun yang mengancamnya. Setelah Yuta membebaskannya, Rika tetap ada dalam bentuk yang lebih lemah namun masih bisa dipanggil.',
 'rika.png', 98, 92, 88, FALSE);


-- SEED: COMMENTS
INSERT INTO comments (user_id, character_id, content, rating) VALUES
(2, 1,  'Yuji Itadori adalah MC terbaik! Kuat secara fisik tapi hatinya selalu di tempat yang benar.', 5),
(2, 4,  'Gojo Satoru simply the strongest. Tidak ada yang bisa menandingi Six Eyes dan Infinity!', 5),
(3, 2,  'Megumi underrated banget. Ten Shadows Technique-nya sangat versatile dan Sukuna sendiri tertarik padanya!', 5),
(3, 19, 'Mahito adalah villain terbaik JJK. Idle Transfiguration-nya benar-benar mengerikan.', 4),
(4, 5,  'Nanami Kento adalah sorcerer paling profesional. Overtime mode-nya sangat keren!', 5),
(4, 17, 'Sukuna terlalu OP. Malevolent Shrine di Shibuya benar-benar destruktif sekali.', 5),
(2, 6,  'Todo Aoi character yang paling absurd tapi loveable. Boogie Woogie plus Black Flash = combo gila!', 4),
(3, 9,  'Yuta Okkotsu di JJK 0 sangat emosional. Hubungannya dengan Rika bikin nangis.', 5),
(4, 24, 'Choso adalah plot twist terbaik JJK. Dia nganggep Yuji sebagai adik karena Kenjaku!', 5),
(2, 3,  'Nobara Kugisaki best girl! Straw Doll Technique-nya sangat unik dan Resonance sangat OP.', 5);


-- SEED: GAME SCORES (sample)
INSERT INTO game_scores (user_id, character_used, score, enemies_defeated) VALUES
(2, 'Yuji Itadori',    15400, 28),
(3, 'Megumi Fushiguro', 12800, 22),
(4, 'Satoru Gojo',     21500, 35),
(2, 'Nobara Kugisaki', 9600,  18),
(3, 'Satoru Gojo',     18900, 31),
(4, 'Yuji Itadori',    13200, 24);