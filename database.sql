--  JUJUTSU KAISEN WEB — DATABASE SCHEMA & SEED DATA

CREATE DATABASE IF NOT EXISTS jjk_web
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE jjk_web;

--  TABEL 1 : users
CREATE TABLE IF NOT EXISTS users (
    id          INT           AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)   NOT NULL UNIQUE,
    email       VARCHAR(100)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,
    role        ENUM('admin','user') DEFAULT 'user',
    avatar      VARCHAR(255)  DEFAULT NULL,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

--  TABEL 2 : characters
CREATE TABLE IF NOT EXISTS characters (
    id                INT           AUTO_INCREMENT PRIMARY KEY,
    name              VARCHAR(100)  NOT NULL,
    grade             ENUM('Special Grade','Semi-Grade 1','Grade 1',
                           'Grade 2','Grade 3','Grade 4','Unranked') NOT NULL,
    affiliation       VARCHAR(150),
    cursed_technique  VARCHAR(255),
    description       TEXT,
    lore              TEXT,
    image_url         VARCHAR(255),
    attack_power      INT           DEFAULT 50,
    defense_power     INT           DEFAULT 50,
    speed_power       INT           DEFAULT 50,
    is_playable       BOOLEAN       DEFAULT FALSE,
    created_at        TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
                                    ON UPDATE CURRENT_TIMESTAMP
);

--  TABEL 3 : game_scores
CREATE TABLE IF NOT EXISTS game_scores (
    id               INT   AUTO_INCREMENT PRIMARY KEY,
    user_id          INT   NOT NULL,
    character_used   VARCHAR(100),
    score            INT   DEFAULT 0,
    enemies_defeated INT   DEFAULT 0,
    played_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

--  TABEL 4 : comments
CREATE TABLE IF NOT EXISTS comments (
    id           INT   AUTO_INCREMENT PRIMARY KEY,
    user_id      INT   NOT NULL,
    character_id INT   NOT NULL,
    content      TEXT  NOT NULL,
    rating       INT   DEFAULT 5 CHECK (rating BETWEEN 1 AND 5),
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)      REFERENCES users(id)      ON DELETE CASCADE,
    FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE
);


--  SEED : USERS
--  Password default semua akun : "password"
INSERT INTO users (username, email, password, role) VALUES
('admin',          'admin@jjk.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('yuji_itadori',   'yuji@jjk.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('megumi_fan',     'megumi@jjk.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('gojo_strongest', 'gojo@jjk.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');


--  SEED : CHARACTERS

--  BLOK A — TOKYO JUJUTSU HIGH
INSERT INTO characters
    (name, grade, affiliation, cursed_technique,
     description, lore,
     image_url, attack_power, defense_power, speed_power, is_playable)
VALUES

('Yuji Itadori',
 'Special Grade', 'Tokyo Jujutsu High',
 'Divergent Fist / Black Flash / Shrine (Sukuna)',
 'Protagonis utama dengan kekuatan fisik luar biasa dan inang Ryomen Sukuna. Mampu menggunakan Black Flash secara konsisten.',
 'Yuji Itadori adalah siswa SMA biasa yang hidupnya berubah drastis saat ia menelan jari kutukan Ryomen Sukuna untuk melindungi temannya. Dengan tubuh yang luar biasa kuat bahkan untuk standar sorcerer, Yuji mampu menampung Sukuna tanpa hancur. Ia berjalan di jalur sorcerer dengan tujuan tunggal: memastikan orang-orang dapat mati dengan damai dan bermartabat.',
 'Yuji_Itadori.webp', 90, 78, 88, TRUE),

('Megumi Fushiguro',
 'Grade 1', 'Tokyo Jujutsu High',
 'Ten Shadows Technique',
 'Sorcerer berbakat yang mewarisi Ten Shadows Technique dari klan Zenin. Dapat memanggil shikigami dari bayangan.',
 'Megumi Fushiguro adalah anak dari Toji Fushiguro yang kemudian diakui oleh klan Zenin. Ia direkrut oleh Gojo Satoru sebelum klan Zenin bisa mengklaimnya. Ten Shadows Technique-nya memungkinkan ia memanggil hingga 10 shikigami berbeda menggunakan bayangan sebagai media. Potensinya dianggap setara bahkan melebihi Gojo oleh Sukuna sendiri.',
 'Megumi_Fushiguro.webp', 82, 80, 78, TRUE),

('Nobara Kugisaki',
 'Grade 1', 'Tokyo Jujutsu High',
 'Straw Doll Technique / Resonance',
 'Sorcerer perempuan tangguh dari pedesaan yang menggunakan palu, paku, dan boneka jerami untuk mengutuk musuh.',
 'Nobara Kugisaki tumbuh di desa kecil dan pindah ke Tokyo untuk mengejar impiannya. Straw Doll Technique-nya memungkinkan ia menancapkan paku ke boneka jerami untuk mentransfer kutukan ke target nyata melalui resonansi darah. Teknik Resonance-nya sangat efektif melawan Cursed Spirits bertipe khusus.',
 'Nobara_Kugisaki.jpg', 78, 72, 80, TRUE),

('Satoru Gojo',
 'Special Grade', 'Tokyo Jujutsu High',
 'Infinity / Limitless / Six Eyes / Hollow Purple',
 'Sorcerer terkuat di dunia dengan Six Eyes dan Limitless. Tidak terkalahkan dalam kondisi normal.',
 'Gojo Satoru lahir pada 7 Desember 1989, pertama kalinya dalam sejarah seseorang memiliki Six Eyes dan Limitless secara bersamaan. Six Eyes memberinya kemampuan persepsi tak terbatas sementara Limitless memungkinkan ia memanipulasi ruang di tingkat atom. Infinity-nya membuat hampir semua serangan tidak bisa menyentuhnya. Ia dianggap sebagai penjaga keseimbangan dunia sorcerer.',
 'Satoru_Gojo.webp', 100, 100, 100, TRUE),

('Kento Nanami',
 'Grade 1', 'Tokyo Jujutsu High',
 'Ratio Technique / Overtime',
 'Mantan salaryman yang kembali menjadi sorcerer. Metodis, profesional, dan sangat efisien dalam pertarungan.',
 'Nanami Kento pernah meninggalkan dunia sorcerer untuk bekerja sebagai karyawan biasa, namun kembali karena merasa lebih jujur sebagai sorcerer. Ratio Technique-nya membagi target menjadi 7 bagian dan menyerang titik lemah 7:3 dengan tenaga yang diperkuat. Saat overtime (lewat jam kerja), kekuatannya meningkat drastis.',
 'Kento_Nanami.jpg', 84, 80, 74, FALSE),

('Aoi Todo',
 'Grade 1', 'Kyoto Jujutsu High',
 'Boogie Woogie',
 'Sorcerer Grade 1 Kyoto dengan kekuatan fisik yang mengagumkan dan teknik pertukaran posisi via tepukan tangan.',
 'Todo Aoi adalah sorcerer eksentrik yang terobsesi dengan "tipe wanitanya" dan persahabatan sejati. Boogie Woogie-nya memungkinkan ia menukar posisi dua objek atau orang dengan cara bertepuk tangan, menciptakan kombinasi serangan yang membingungkan musuh. Ia mengajarkan Yuji konsep Black Flash dan menganggap Yuji sebagai "sahabat karib".',
 'Aoi_Todo.webp', 92, 82, 86, FALSE),

('Maki Zenin',
 'Semi-Grade 1', 'Tokyo Jujutsu High',
 'Tidak ada (Zero Cursed Energy) / Heavenly Restriction',
 'Anggota klan Zenin yang lahir tanpa cursed energy namun memiliki fisik yang melampaui manusia biasa berkat Heavenly Restriction.',
 'Maki Zenin adalah putri dari keluarga Zenin yang dipandang rendah karena tidak memiliki cursed energy. Namun Heavenly Restriction-nya justru mengkompensasi dengan memberikan kekuatan fisik, kecepatan, dan ketahanan yang jauh melampaui sorcerer biasa. Ia mahir menggunakan berbagai cursed tools sebagai pengganti cursed technique.',
 'Maki_Zenin.webp', 86, 75, 90, FALSE),

('Toge Inumaki',
 'Semi-Grade 1', 'Tokyo Jujutsu High',
 'Cursed Speech',
 'Sorcerer keturunan klan Inumaki yang berbicara hanya dengan bahan onigiri untuk mencegah kutukan tidak sengaja.',
 'Inumaki Toge mewarisi Cursed Speech dari klan Inumaki — teknik yang menyalurkan cursed energy ke ucapannya sehingga kata-katanya menjadi perintah yang harus dipatuhi, bahkan oleh Cursed Spirits. Efek sampingnya adalah kerusakan pada tenggorokannya sendiri. Ia berkomunikasi sehari-hari menggunakan nama bahan onigiri untuk menghindari kecelakaan.',
 'Toge_Inumaki.webp', 80, 65, 75, FALSE),

('Panda',
 'Semi-Grade 1', 'Tokyo Jujutsu High',
 'Divergent Fist (Gorilla Core) / Panda Core / Elder Brother Core',
 'Cursed Corpse yang diciptakan oleh Masamichi Yaga berbentuk panda dengan tiga core berbeda yang bisa diaktifkan.',
 'Panda adalah Cursed Corpse mutant yang diciptakan oleh Kepala Sekolah Yaga. Ia bukan panda sungguhan, melainkan entitas yang terbentuk dari tiga core (inti): Panda, Gorilla, dan sosok misterius ketiga. Ia memiliki kesadaran penuh, kepribadian ramah, dan bisa berbicara. Gorilla Core-nya meningkatkan kekuatan serangan secara drastis.',
 'Panda.webp', 78, 82, 72, FALSE),

('Yuta Okkotsu',
 'Special Grade', 'Tokyo Jujutsu High',
 'Copy / Rika (Queen of Curses)',
 'Sorcerer Special Grade yang memiliki ikatan dengan roh kutukan Rika, mantan kekasihnya yang telah meninggal.',
 'Okkotsu Yuta awalnya dianggap sebagai korban yang dihantui oleh Rika Orimoto, mantan kekasihnya yang meninggal dalam kecelakaan. Namun sebenarnya Rika adalah Special Grade Cursed Spirit maha kuat yang terikat padanya. Setelah belajar di Tokyo Jujutsu High, Yuta berkembang menjadi salah satu sorcerer Special Grade termuda. Teknik Copy-nya memungkinkan ia meniru teknik kutukan orang lain.',
 'Yuta_Okkotsu.jpg', 96, 88, 85, FALSE),

('Masamichi Yaga',
 'Grade 1', 'Tokyo Jujutsu High',
 'Cursed Corpse Manipulation',
 'Kepala Sekolah Tokyo Jujutsu High yang menciptakan Cursed Corpse termasuk Panda.',
 'Yaga Masamichi adalah Kepala Sekolah Tokyo Jujutsu High dan pencipta Cursed Corpse paling canggih yang pernah ada. Kemampuannya membuat Cursed Corpse yang memiliki kesadaran mandiri seperti Panda dianggap sebagai pencapaian luar biasa. Ia sosok yang keras di luar namun peduli terhadap murid-muridnya.',
 'Masamichi_Yaga.jpg', 76, 74, 68, FALSE),

('Kiyotaka Ijichi',
 'Grade 3', 'Tokyo Jujutsu High',
 'Territory-type barrier',
 'Asisten manajer Tokyo Jujutsu High yang sering bertugas mendampingi dan memandu para sorcerer muda.',
 'Ijichi adalah staf administrasi Tokyo Jujutsu High yang bertugas sebagai pendukung operasional para sorcerer. Meski kemampuan bertarungnya terbatas, ia sangat berdedikasi dalam tugasnya dan menjadi figur yang dapat diandalkan oleh Yuji dan kawan-kawan.',
 'Kiyotaka_Ijichi.jpg', 38, 42, 50, FALSE);


--  BLOK B — KYOTO JUJUTSU HIGH
INSERT INTO characters
    (name, grade, affiliation, cursed_technique,
     description, lore,
     image_url, attack_power, defense_power, speed_power, is_playable)
VALUES

('Arata Nitta',
 'Grade 2', 'Kyoto Jujutsu High',
 'Healing Technique (terbatas)',
 'Sorcerer Kyoto yang memiliki kemampuan penyembuhan terbatas, bisa menghentikan pendarahan dan meringankan luka.',
 'Nitta Arata adalah sorcerer dari Kyoto Jujutsu High dengan kemampuan penyembuhan yang tidak bisa menyembuhkan sepenuhnya namun sangat berguna dalam situasi darurat. Ia berperan sebagai dukungan medis bagi rekan-rekannya.',
 'Arata_Nitta.jpg', 42, 55, 58, FALSE),

('Kasumi Miwa',
 'Grade 3', 'Kyoto Jujutsu High',
 'New Shadow Style: Simple Domain',
 'Sorcerer perempuan Kyoto yang mengagumi Gojo dan menggunakan pedang katana dengan gaya Simple Domain.',
 'Miwa Kasumi adalah sorcerer biasa yang tidak memiliki teknik kutukan bawaan, namun ia mengembangkan kemampuannya dengan katana dan Simple Domain. Ia jujur tentang keterbatasannya dan bekerja keras untuk mengkompensasinya. Mengagumi Gojo Satoru secara berlebihan.',
 'Kasumi_Miwa.webp', 55, 52, 65, FALSE),

('Noritoshi Kamo',
 'Grade 1', 'Kyoto Jujutsu High',
 'Blood Manipulation',
 'Pewaris resmi klan Kamo dengan teknik manipulasi darah yang sangat versatil.',
 'Kamo Noritoshi adalah pewaris klan Kamo, salah satu dari tiga keluarga besar sorcerer. Blood Manipulation-nya memungkinkan ia mengontrol darahnya sendiri dan darah orang lain, menciptakan proyektil, perisai, bahkan mempercepat pembekuan darah. Ia awalnya antagonis namun kemudian bergabung bersama tim Yuji.',
 'Noritoshi_Kamo.jpg', 78, 72, 74, FALSE),

('Mai Zenin',
 'Grade 3', 'Kyoto Jujutsu High',
 'Construction / Zenin Clan Technique',
 'Saudara kembar Maki yang memiliki cursed energy namun kemampuan terbatas, menggunakan revolver dalam pertarungan.',
 'Mai Zenin adalah saudara kembar Maki yang lahir dengan cursed energy. Ironisnya, memiliki cursed energy di keluarga Zenin justru membuatnya memikul beban lebih besar karena ekspektasi yang tinggi. Construction-nya memungkinkan ia menciptakan satu peluru dari cursed energy yang sangat kuat, namun hanya bisa sekali.',
 'Mai_Zenin.webp', 58, 55, 62, FALSE),

('Kokichi Muta (Mechamaru)',
 'Grade 2', 'Kyoto Jujutsu High',
 'Ultimate Mechamaru / Puppet Manipulation',
 'Sorcerer yang tubuhnya lemah dan dikurung di satu tempat, namun mengendalikan robot Mechamaru dari jauh.',
 'Muta Kokichi lahir dengan Heavenly Restriction yang membuat tubuhnya sangat lemah dan sensitif — bahkan sinar bulan terasa menyakitkan. Sebagai kompensasi, ia memiliki reservoir cursed energy yang sangat besar dan mengendalikan robot Mechamaru di seluruh Jepang. Ia akhirnya berkhianat demi keinginan memiliki tubuh yang normal.',
 'Kokichi_Muta.jpg', 72, 60, 70, FALSE);


--  BLOK C — SPECIAL GRADE CURSED SPIRITS
INSERT INTO characters
    (name, grade, affiliation, cursed_technique,
     description, lore,
     image_url, attack_power, defense_power, speed_power, is_playable)
VALUES

('Ryomen Sukuna',
 'Special Grade', 'Tidak ada (Raja Kutukan)',
 'Malevolent Shrine / Dismantle / Cleave / Fire Arrow',
 'Raja Kutukan tertinggi yang pernah hidup. Kekuatannya begitu besar sehingga sisa jarinya masih menjadi kutukan setelah kematiannya 1000 tahun lalu.',
 'Sukuna Ryomen adalah kutukan terkuat sepanjang sejarah, hidup lebih dari 1000 tahun lalu saat ia adalah manusia sorcerer. Setelah kematiannya, kutukan dalam dirinya terkumpul menjadi 20 jari yang tidak bisa dihancurkan. Malevolent Shrine-nya adalah Domain Expansion maha kuat yang mencakup radius besar dan secara otomatis memotong semua yang ada di dalamnya. Ia menganggap semua manusia sebagai yang lebih rendah.',
 'Ryomen_Sukuna.webp', 100, 98, 98, FALSE),

('Mahito',
 'Special Grade', 'Cursed Spirit Alliance',
 'Idle Transfiguration',
 'Kutukan yang lahir dari rasa takut manusia terhadap manusia lain. Dapat mengubah bentuk jiwa dan tubuh siapapun.',
 'Mahito adalah Cursed Spirit yang lahir dari kebencian dan rasa takut antar sesama manusia. Idle Transfiguration-nya memungkinkan ia menyentuh jiwa seseorang dan mengubah bentuk tubuh mereka sesuka hati — menjadi monster, meledakkan mereka, atau bahkan membunuh instan. Ia adalah musuh bebuyutan Yuji karena membunuh Junpei Yoshino di depannya.',
 'Mahito.jpg', 88, 85, 82, FALSE),

('Jogo',
 'Special Grade', 'Cursed Spirit Alliance',
 'Disaster Flames / Maximum: Meteor',
 'Kutukan api yang percaya dirinya lebih "manusiawi" dari manusia. Memiliki kekuatan api dan gunung berapi.',
 'Jogo adalah Cursed Spirit yang lahir dari rasa takut manusia terhadap bencana alam berupa api dan gunung berapi. Ia sangat percaya diri dan yakin bahwa Cursed Spirits adalah bentuk manusia yang lebih murni dari manusia itu sendiri. Kekuatan apinya sangat destruktif, dan teknik Maximum: Meteor-nya memanggil meteor api dari langit.',
 'Jogo.webp', 90, 80, 78, FALSE),

('Hanami',
 'Special Grade', 'Cursed Spirit Alliance',
 'Disaster Plants / Flower Fields',
 'Kutukan tanaman yang tidak memiliki mata namun dapat merasakan segalanya melalui getaran. Bijaksana dan tenang.',
 'Hanami adalah Cursed Spirit yang lahir dari rasa takut manusia terhadap alam dan bencana tumbuhan. Tidak seperti rekan-rekannya, Hanami tenang dan filosofis, percaya bahwa alam harus dilindungi dari manusia. Tanaman-tanamannya bisa tumbuh dalam tubuh lawan, mencuri cursed energy, dan menciptakan medan bunga yang mematikan.',
 'Hanami.jpg', 85, 88, 76, FALSE),

('Dagon',
 'Special Grade', 'Cursed Spirit Alliance',
 'Disaster Tides / Death Swarm',
 'Kutukan air laut yang berevolusi dari benih kutukan menjadi Special Grade dalam waktu singkat.',
 'Dagon awalnya adalah benih kutukan yang dipelihara oleh Jogo dan rekan-rekannya. Selama Shibuya Incident, ia berevolusi menjadi Special Grade Cursed Spirit sepenuhnya. Teknik-nya berkaitan dengan air laut, ikan, dan bencana kelautan. Domain Expansion-nya Horizon of the Captivating Skandha menjebak target di pantai yang dipenuhi ikan mematikan.',
 'Dagon.jpg', 84, 82, 80, FALSE);


--  BLOK D — CURSED SPIRIT USERS / ANTAGONIS
INSERT INTO characters
    (name, grade, affiliation, cursed_technique,
     description, lore,
     image_url, attack_power, defense_power, speed_power, is_playable)
VALUES

('Suguru Geto',
 'Special Grade', 'Cursed Spirit Users',
 'Cursed Spirit Manipulation',
 'Mantan sahabat Gojo yang berubah menjadi villain setelah memutuskan bahwa non-sorcerer adalah hambatan dunia.',
 'Geto Suguru dulunya adalah sahabat terbaik Gojo dan salah satu sorcerer paling berbakat di generasinya. Setelah menyaksikan kematian tragis seorang gadis yang ia lindungi dan bertanya-tanya tentang makna eksistensi sorcerer, ia memutuskan untuk memusnahkan semua non-sorcerer. Cursed Spirit Manipulation-nya memungkinkan ia menyerap dan mengendalikan kutukan yang sudah dikalahkannya.',
 'Suguru_Geto.webp', 88, 84, 82, FALSE),

('Pseudo-Geto (Kenjaku)',
 'Special Grade', 'Cursed Spirit Users',
 'Brain Transplantation / Cursed Spirit Manipulation (warisan)',
 'Entitas kuno yang menguasai tubuh Geto setelah kematian Geto asli. Dalang utama di balik Shibuya Incident.',
 'Kenjaku adalah sorcerer kuno berusia lebih dari 1000 tahun yang bisa memindahkan otaknya ke tubuh orang lain. Ia telah hidup dalam banyak tubuh sepanjang sejarah dan menjadi dalang dari berbagai insiden besar. Setelah membunuh Geto asli, ia mengambil tubuhnya dan mendapatkan Cursed Spirit Manipulation. Tujuannya mengevolusi manusia ke tahap berikutnya.',
 'Kenjaku.webp', 92, 88, 84, FALSE),

('Haruta Shigemo',
 'Grade 2', 'Cursed Spirit Users',
 'Miracles',
 'Sorcerer antagonis yang memiliki kemampuan keberuntungan luar biasa yang melindunginya dari serangan fatal.',
 'Shigemo Haruta adalah sorcerer jahat yang bekerja untuk Kenjaku. Teknik Miracles-nya mengumpulkan "keberuntungan" dari orang-orang yang ia sakiti, memberinya perlindungan supernatural dari serangan yang seharusnya mematikan. Ia kejam dan sembrono dalam pertarungan.',
 'Haruta_Shigemo.webp', 65, 60, 70, FALSE),

('Choso',
 'Special Grade', 'Cursed Spirit Users (awalnya)',
 'Blood Manipulation',
 'Setengah manusia setengah kutukan, saudara tertua dari 9 saudara kandung Death Paintings. Akhirnya berpihak pada Yuji.',
 'Choso adalah salah satu dari Cursed Womb: Death Paintings — entitas yang lahir dari hubungan antara manusia dan Cursed Spirit. Sebagai yang tertua dari 9 bersaudara, ia memiliki Blood Manipulation yang jauh lebih kuat dari Kamo karena darahnya sendiri adalah kutukan. Setelah menyadari bahwa Yuji adalah "adik"-nya (karena Kenjaku adalah ibu biologis keduanya), ia berbalik melawan Kenjaku.',
 'Choso.jpg', 86, 78, 80, FALSE),

('Eso',
 'Special Grade', 'Death Paintings',
 'Rot Technique / Wings',
 'Death Painting kedua yang memiliki kemampuan racun busuk dan sayap untuk terbang.',
 'Eso adalah salah satu dari Cursed Womb: Death Paintings dan saudara kandung Choso. Rot Technique-nya menciptakan racun busuk yang menyebar cepat melalui kontak, sementara sayapnya memberinya mobilitas tinggi di udara. Ia sangat setia kepada Choso dan saudara-saudaranya.',
 'Eso.jpg', 78, 72, 82, FALSE),

('Kechizu',
 'Special Grade', 'Death Paintings',
 'Rot Technique',
 'Death Painting ketiga yang berbagi teknik Rot dengan Eso namun lebih besar dan kuat secara fisik.',
 'Kechizu adalah saudara termuda dari tiga Death Painting yang muncul pertama kali dalam cerita. Ia lebih mengandalkan kekuatan fisik dan Rot Technique-nya yang melumpuhkan lawan melalui racun busuk. Bersama Eso, ia hampir mengalahkan Yuji dan Nobara sebelum akhirnya dikalahkan.',
 'Kechizu.jpg', 75, 70, 68, FALSE);


--  BLOK E — KLAN ZENIN
INSERT INTO characters
    (name, grade, affiliation, cursed_technique,
     description, lore,
     image_url, attack_power, defense_power, speed_power, is_playable)
VALUES

('Naobito Zenin',
 'Special Grade', 'Klan Zenin',
 'Projection Sorcery',
 'Kepala klan Zenin, sorcerer tercepat ke-2 di dunia setelah Gojo. Menggunakan teknik proyeksi frame-per-frame.',
 'Zenin Naobito adalah pemimpin klan Zenin dan sorcerer dengan kecepatan tertinggi kedua setelah Gojo. Projection Sorcery-nya memungkinkan ia membagi gerakan menjadi 24 frame per detik dan bergerak dengan presisi absolut. Siapa pun yang menyentuh "Projected" dalam satu frame akan membeku selama satu detik penuh.',
 'Naobito_Zenin.webp', 88, 82, 95, FALSE),

('Naoya Zenin',
 'Semi-Grade 1', 'Klan Zenin',
 'Projection Sorcery',
 'Putra Naobito yang mewarisi Projection Sorcery. Sexist, arogan, dan sangat berbahaya.',
 'Zenin Naoya adalah pewaris Projection Sorcery dari ayahnya Naobito. Ia adalah seorang sexist sejati yang menganggap perempuan tidak layak menjadi sorcerer. Meskipun kepribadiannya menjijikkan, kemampuan tempurnya sangat tinggi berkat kecepatan Projection Sorcery yang ia warisi. Bahkan setelah kematiannya, ia bangkit kembali sebagai Cursed Spirit.',
 'Naoya_Zenin.webp', 85, 78, 96, FALSE),

('Ogi Zenin',
 'Grade 1', 'Klan Zenin',
 'Bamboo Sword Technique',
 'Ayah kandung Maki dan Mai Zenin yang memandang rendah putrinya karena tidak sesuai ekspektasi klan.',
 'Zenin Ogi adalah ayah Maki dan Mai yang tidak pernah menerima mereka karena merasa mereka membawa malu pada klan Zenin. Ia adalah sorcerer Grade 1 yang kuat namun karakter moralnya sangat buruk. Ia akhirnya dikalahkan oleh Maki yang telah mencapai potensi penuhnya setelah kehilangan Mai.',
 'Ogi_Zenin.jpg', 78, 72, 76, FALSE),

('Jinichi Zenin',
 'Grade 1', 'Klan Zenin',
 'Zenin Clan Techniques',
 'Anggota senior klan Zenin yang setia pada tradisi klan.',
 'Anggota klan Zenin yang terlibat dalam urusan internal klan saat konflik antara Maki dan klan Zenin memuncak.',
 'Jinichi_Zenin.jpg', 76, 74, 70, FALSE),

('Ranta Zenin',
 'Grade 2', 'Klan Zenin',
 'Zenin Clan Techniques',
 'Anggota muda klan Zenin yang terlibat dalam konfrontasi dengan Maki.',
 'Anggota klan Zenin yang menjadi bagian dari pasukan yang menghadapi Maki setelah kematian Mai.',
 'Ranta_Zenin.webp', 65, 62, 68, FALSE);


--  BLOK F — SORCERER SENIOR / INDEPENDEN
INSERT INTO characters
    (name, grade, affiliation, cursed_technique,
     description, lore,
     image_url, attack_power, defense_power, speed_power, is_playable)
VALUES

('Mei Mei',
 'Grade 1', 'Independen',
 'Bird Strike',
 'Sorcerer independen yang bermotivasi uang. Menggunakan burung gagak yang dikendalikan sebagai senjata.',
 'Mei Mei adalah sorcerer Grade 1 yang bekerja independen dengan bayaran tinggi. Bird Strike-nya mengorbankan burung yang dikendalikannya untuk meledakkan target dengan cursed energy terkonsentrasi. Ia sangat dingin dan kalkulatif, selalu memprioritaskan keuntungan finansial. Adiknya Ui Ui sangat terobsesi dengannya.',
 'Mei_Mei.webp', 82, 76, 80, FALSE),

('Ui Ui',
 'Grade 4', 'Independen',
 'Technique Relay',
 'Adik Mei Mei yang terobsesi dengan kakaknya. Memiliki teknik Technique Relay untuk memindahkan teknik kutukan ke orang lain.',
 'Ui Ui adalah adik Mei Mei yang mengikuti kakaknya ke mana saja. Meski kemampuan tempurnya terbatas, teknik Technique Relay-nya memungkinkan pemindahan teknik kutukan dari satu orang ke orang lain dalam kondisi tertentu. Obsesinya terhadap Mei Mei sering dikomentari oleh karakter lain.',
 'Ui_Ui.webp', 42, 45, 68, FALSE),

('Utahime Iori',
 'Semi-Grade 1', 'Kyoto Jujutsu High',
 'Solo Solo Kinship',
 'Supervisor Kyoto Jujutsu High yang memiliki hubungan cinta-benci dengan Gojo. Sering dikerjain Gojo.',
 'Iori Utahime adalah supervisor Kyoto Jujutsu High yang bertanggung jawab mengawasi para siswa. Solo Solo Kinship-nya adalah teknik yang memperkuat kemampuan semua rekan satu tim di sekitarnya secara signifikan. Ia sering kali frustrasi dengan tingkah Gojo yang selalu mengganggunya.',
 'Utahime_Iori.webp', 68, 65, 70, FALSE),

('Shoko Ieiri',
 'Grade 1', 'Tokyo Jujutsu High (Dokter)',
 'Reverse Cursed Technique (Healing)',
 'Satu-satunya sorcerer yang bisa menggunakan Reverse Cursed Technique untuk penyembuhan total. Dokter utama Jujutsu Society.',
 'Ieiri Shoko adalah teman seangkatan Gojo dan Geto. Ia satu-satunya sorcerer aktif yang mampu menggunakan Reverse Cursed Technique untuk menyembuhkan orang lain — kemampuan yang sangat langka. Sebagai dokter utama Jujutsu Society, ia berperan vital dalam merawat sorcerer yang terluka.',
 'Shoko_Ieiri.webp', 55, 65, 60, FALSE),

('Atsuya Kusakabe',
 'Grade 1', 'Tokyo Jujutsu High',
 'Classical Swordsmanship',
 'Guru Tokyo Jujutsu High yang ahli pedang dan sangat pragmatis dalam menghadapi bahaya.',
 'Kusakabe Atsuya adalah sorcerer Grade 1 yang menjadi guru di Tokyo Jujutsu High. Ia sangat pragmatis dan cenderung menghindari risiko yang tidak perlu. Meskipun tidak memiliki teknik kutukan yang diketahui publik, keahlian pedangnya sangat tinggi.',
 'Atsuya_Kusakabe.webp', 78, 75, 76, FALSE),

('Yuki Tsukumo',
 'Special Grade', 'Independen',
 'Star Rage / Virtual Mass',
 'Satu dari empat Special Grade sorcerer resmi. Tidak terikat pada Jujutsu Society dan meneliti cara menghilangkan kutukan dari manusia.',
 'Tsukumo Yuki adalah salah satu dari empat Special Grade sorcerer yang diakui secara resmi. Ia menolak bekerja untuk Jujutsu Society dan hidup sebagai sorcerer independen sambil meneliti cara menghapus kutukan dari umat manusia sepenuhnya. Star Rage-nya menambahkan Virtual Mass ke dirinya atau orang lain, menciptakan kekuatan destruktif setara bintang.',
 'Yuki_Tsukumo.webp', 95, 85, 88, FALSE),

('Master Tengen',
 'Special Grade', 'Jujutsu Society',
 'Immortality / Barrier Technique',
 'Sorcerer abadi yang menjaga barrier seluruh Jepang. Keberadaannya adalah fondasi dunia sorcerer.',
 'Tengen adalah sorcerer yang telah hidup selama ratusan tahun berkat teknik Immortality-nya. Ia bertanggung jawab mempertahankan barrier-barrier yang melindungi berbagai fasilitas Jujutsu Society di seluruh Jepang. Tanpa intervensi Star Plasma Vessel setiap 500 tahun, Tengen akan berevolusi melampaui bentuk manusia dan menjadi ancaman.',
 'Master_Tengen.jpg', 60, 100, 40, FALSE);


--  BLOK G — KARAKTER LEGENDARIS / MASA LALU
INSERT INTO characters
    (name, grade, affiliation, cursed_technique,
     description, lore,
     image_url, attack_power, defense_power, speed_power, is_playable)
VALUES

('Toji Fushiguro',
 'Unranked', 'Independen (mantan)',
 'Tidak ada (Heavenly Restriction)',
 'Mantan anggota klan Zenin, ayah Megumi. Meninggalkan sorcery namun menjadi pembunuh bayaran paling ditakuti.',
 'Fushiguro Toji, dijuluki "Sorcerer Killer", adalah mantan anggota klan Zenin yang lahir dengan Heavenly Restriction — tidak memiliki cursed energy sama sekali. Namun sebagai kompensasi, tubuhnya menjadi senjata sempurna: kekuatan, kecepatan, dan ketahanan yang melampaui batas manusia. Ia meninggalkan anaknya Megumi dengan klan Zenin dan menjadi pembunuh bayaran profesional. Bahkan Gojo muda hampir dikalahkannya.',
 'Toji_Fushiguro.webp', 95, 85, 98, FALSE),

('Riko Amanai',
 'Unranked', 'Star Plasma Vessel',
 'Tidak ada',
 'Star Plasma Vessel yang ditakdirkan untuk bersatu dengan Tengen. Dilindungi oleh Gojo dan Geto muda.',
 'Amanai Riko adalah Star Plasma Vessel — manusia yang dipilih untuk bersatu dengan Sorcerer Tengen agar Tengen bisa mempertahankan bentuk manusianya. Ia dilindungi oleh Gojo dan Geto muda dalam misi yang akhirnya berakhir tragis saat Toji Fushiguro berhasil membunuhnya.',
 'Riko_Amanai.webp', 20, 20, 35, FALSE),

('Haibara Yu',
 'Grade 2', 'Tokyo Jujutsu High',
 'Tidak diketahui sepenuhnya',
 'Senpai Nanami yang gugur saat misi, kematiannya menjadi salah satu motivasi Nanami kembali menjadi sorcerer.',
 'Haibara Yu adalah senior Nanami di Tokyo Jujutsu High yang tewas dalam misi. Kematiannya yang tragis meninggalkan kesan mendalam pada Nanami dan menjadi salah satu alasan Nanami akhirnya kembali ke dunia sorcerer meski pernah meninggalkannya.',
 'Haibara_Yu.jpg', 62, 58, 65, FALSE),

('Rika Orimoto',
 'Special Grade', 'Yuta Okkotsu (terikat)',
 'Unlimited Cursed Energy Stockpile',
 'Roh kutukan Special Grade yang terikat pada Yuta Okkotsu. Disebut Ratu Kutukan.',
 'Rika Orimoto adalah mantan kekasih Yuta yang meninggal dalam kecelakaan saat masih kecil. Cintanya yang kuat pada Yuta mengubahnya menjadi Cursed Spirit maha kuat yang terikat padanya. Kekuatannya hampir tak terbatas, dan ia melindungi Yuta secara fanatik dari siapapun yang mengancamnya. Setelah Yuta membebaskannya, Rika tetap ada dalam bentuk yang lebih lemah namun masih bisa dipanggil.',
 'Rika_Orimoto.jpg', 98, 92, 88, FALSE);


--  BLOK H — CULLING GAME
INSERT INTO characters
    (name, grade, affiliation, cursed_technique,
     description, lore,
     image_url, attack_power, defense_power, speed_power, is_playable)
VALUES

('Hiromi Higuruma',
 'Unranked', 'Culling Game',
 'Juryman / Deadly Sentencing',
 'Mantan pengacara yang mendapatkan teknik kutukan setelah menerima poin dalam Culling Game. Menggunakan sistem pengadilan.',
 'Higuruma Hiromi adalah mantan pengacara yang kecewa dengan sistem hukum. Setelah bergabung dalam Culling Game, ia mendapatkan teknik Juryman yang menciptakan domain pengadilan di mana ia bertindak sebagai hakim. Jika terdakwa dinyatakan bersalah, mereka kehilangan teknik kutukan mereka. Ia akhirnya bersekutu dengan Yuji.',
 'Hiromi_Higuruma.webp', 80, 75, 70, FALSE),

('Hajime Kashimo',
 'Unranked', 'Culling Game',
 'Genju Kasshin / Mythical Beast Amber',
 'Sorcerer dari 400 tahun lalu yang mengorbankan semua kemampuannya untuk satu teknik terakhir maha kuat demi melawan Sukuna.',
 'Kashimo Hajime adalah sorcerer dari era 400 tahun lalu yang lahir di zaman yang sama dengan Sukuna. Ia menghabiskan seluruh hidupnya menginginkan pertarungan melawan Sukuna. Teknik Mythical Beast Amber-nya mengubah tubuhnya menjadi senjata listrik hidup yang maha destruktif, namun hanya bisa digunakan sekali.',
 'Hajime_Kashimo.webp', 95, 80, 90, FALSE),

('Kinji Hakari',
 'Grade 3', 'Tokyo Jujutsu High',
 'Idle Death Gamble / Pachinko Domain',
 'Sorcerer yang diskors karena melanggar aturan. Tekniknya berdasarkan perjudian pachinko dengan hadiah jackpot regenerasi tak terbatas.',
 'Hakari Kinji adalah sorcerer yang sangat kuat namun diskors dari Tokyo Jujutsu High karena bertengkar dengan petinggi Jujutsu Society. Domain Expansion-nya didasarkan pada mesin pachinko, dan jika jackpot tercapai, ia mendapatkan Reverse Cursed Technique tak terbatas selama durasi tertentu, membuatnya praktis abadi. Gojo menganggapnya setara dengannya saat jackpot aktif.',
 'Kinji_Hakari.webp', 90, 85, 85, FALSE),

('Fumihiko Takaba',
 'Unranked', 'Culling Game',
 'Comedian',
 'Komedian gagal yang mendapatkan teknik kutukan yang kekuatannya bergantung pada seberapa lucu ia.',
 'Takaba Fumihiko adalah komedian yang berjuang keras tapi tidak pernah berhasil. Teknik Comedian-nya membuat apapun yang ia anggap lucu menjadi kenyataan — kekuatan yang secara teoritis tidak terbatas jika ia benar-benar meyakini leluconnya. Ia adalah karakter paling tak terduga dalam cerita.',
 'Fumihiko_Takaba.webp', 70, 65, 72, FALSE),

('Angel (Hana Kurusu)',
 'Unranked', 'Culling Game',
 'Jacob''s Ladder',
 'Sorcerer dari masa lalu yang jiwanya mendiami tubuh Hana Kurusu. Memiliki teknik untuk menghancurkan teknik kutukan apapun.',
 'Angel adalah jiwa sorcerer kuno yang mendiami tubuh Hana Kurusu dalam Culling Game. Jacob''s Ladder-nya adalah satu-satunya teknik yang mampu menghancurkan Prison Realm yang memenjarakan Gojo, menjadikannya target utama Yuji dan Megumi. Ia membenci reinkarnasi dan Kenjaku.',
 'Hana_Kurusu.jpg', 75, 72, 70, FALSE),

('Dhruv Lakdawalla',
 'Unranked', 'Culling Game',
 'Star Rage (diadopsi)',
 'Peserta Culling Game dari India yang memiliki kekuatan fisik luar biasa.',
 'Dhruv adalah peserta Culling Game yang berasal dari India dengan kekuatan fisik yang sangat tinggi. Ia adalah salah satu peserta non-Jepang dalam game mematikan tersebut.',
 'Dhruv_Lakdawalla.jpg', 78, 75, 72, FALSE),

('Kurourushi',
 'Special Grade', 'Culling Game',
 'Cockroach Manipulation',
 'Kutukan berbentuk kecoa raksasa dengan kemampuan memanipulasi dan memanggil kecoa dalam jumlah tak terbatas.',
 'Kurourushi adalah Cursed Spirit berbentuk kecoa raksasa yang berpartisipasi dalam Culling Game. Kemampuannya memanipulasi kecoa dalam jumlah masif membuatnya sangat berbahaya karena kecoa-kecoanya bisa masuk ke tubuh lawan dan menghancurkan dari dalam.',
 'Kurourushi.jpg', 82, 78, 75, FALSE);


--  BLOK I — KARAKTER PENDUKUNG
INSERT INTO characters
    (name, grade, affiliation, cursed_technique,
     description, lore,
     image_url, attack_power, defense_power, speed_power, is_playable)
VALUES

('Junpei Yoshino',
 'Unranked', 'Tokyo Jujutsu High (sebentar)',
 'Moon Dregs (via Mahito)',
 'Siswa SMA biasa yang di-bully dan akhirnya bertemu Yuji. Korban Mahito yang paling membekas.',
 'Yoshino Junpei adalah siswa SMA biasa yang sering di-bully dan tidak punya tempat berpijak hingga bertemu Yuji Itadori. Pertemuan dengan Mahito mengubahnya — ia diberikan teknik Moon Dregs yang menggunakan ubur-ubur kutukan. Namun Mahito akhirnya mengkhianatinya dan mengubah Junpei menjadi monster di depan mata Yuji, menciptakan luka yang tidak pernah sembuh bagi Yuji.',
 'Junpei_Yoshino.jpg', 45, 40, 55, FALSE),

('Takuma Ino',
 'Grade 2', 'Tokyo Jujutsu High',
 'Auspicious Beasts Summon',
 'Sorcerer yang sangat menghormati Nanami dan menganggapnya sebagai mentor.',
 'Ino Takuma adalah sorcerer Grade 2 yang bekerja di bawah Nanami dan sangat mengidolakan seniornya itu. Auspicious Beasts Summon-nya memungkinkan ia memanggil berbagai makhluk jimat sesuai level kekuatan yang dibutuhkan. Ia terluka parah selama Shibuya Incident saat mencoba melindungi rekan-rekannya.',
 'Takuma_Ino.webp', 68, 62, 66, FALSE),

('Akari Nitta',
 'Grade 3', 'Tokyo Jujutsu High',
 'Healing (terbatas)',
 'Asisten manajer yang memiliki kemampuan penyembuhan terbatas, bertugas mendampingi para sorcerer dalam misi.',
 'Nitta Akari adalah asisten manajer yang sering mendampingi sorcerer dalam misi. Kemampuan penyembuhannya terbatas namun berguna untuk pertolongan pertama di lapangan.',
 'Akari_Nitta.webp', 38, 48, 52, FALSE),

('Nana Pele',
 'Grade 1', 'Independen',
 'Tidak diketahui',
 'Sorcerer Grade 1 yang gugur selama Shibuya Incident.',
 'Sorcerer Grade 1 yang menjadi salah satu korban dalam kekacauan Shibuya Incident. Kematiannya menjadi bagian dari korban besar yang berjatuhan dalam malam bersejarah tersebut.',
 'Nana_Pele.webp', 75, 70, 72, FALSE);


--  SEED : COMMENTS
INSERT INTO comments (user_id, character_id, content, rating) VALUES
(2,  1, 'Yuji Itadori adalah MC terbaik! Kuat secara fisik tapi hatinya selalu di tempat yang benar.', 5),
(2,  4, 'Gojo Satoru simply the strongest. Tidak ada yang bisa menandingi Six Eyes dan Infinity!',     5),
(2,  6, 'Todo Aoi karakter yang paling absurd tapi loveable. Boogie Woogie plus Black Flash = combo gila!', 4),
(2,  3, 'Nobara Kugisaki best girl! Straw Doll Technique-nya sangat unik dan Resonance sangat OP.',   5),
(3,  2, 'Megumi underrated banget. Ten Shadows Technique-nya sangat versatile dan Sukuna sendiri tertarik padanya!', 5),
(3, 19, 'Mahito adalah villain terbaik JJK. Idle Transfiguration-nya benar-benar mengerikan.',        4),
(3,  9, 'Yuta Okkotsu di JJK 0 sangat emosional. Hubungannya dengan Rika bikin nangis.',             5),
(3, 24, 'Choso adalah plot twist terbaik JJK. Dia nganggep Yuji sebagai adik karena Kenjaku!',       5),
(4,  5, 'Nanami Kento adalah sorcerer paling profesional. Overtime mode-nya sangat keren!',           5),
(4, 17, 'Sukuna terlalu OP. Malevolent Shrine di Shibuya benar-benar destruktif sekali.',             5);


--  SEED : GAME SCORES
INSERT INTO game_scores (user_id, character_used, score, enemies_defeated) VALUES
(2, 'Yuji Itadori',     15400, 28),
(3, 'Megumi Fushiguro', 12800, 22),
(4, 'Satoru Gojo',      21500, 35),
(2, 'Nobara Kugisaki',   9600, 18),
(3, 'Satoru Gojo',      18900, 31),
(4, 'Yuji Itadori',     13200, 24);


-- TABLE: cursed_techniques
CREATE TABLE IF NOT EXISTS cursed_techniques (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150) NOT NULL,
    name_jp         VARCHAR(150),
    type            ENUM('Innate Technique','Non-Innate','Domain Expansion','Special Ability','Shikigami') NOT NULL DEFAULT 'Innate Technique',
    user_name       VARCHAR(150),
    affiliation     VARCHAR(150),
    description     TEXT,
    lore            TEXT,
    image_url       VARCHAR(255),
    power_level     INT DEFAULT 50,
    difficulty      INT DEFAULT 50,
    is_domain       BOOLEAN DEFAULT FALSE,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLE: world_locations
CREATE TABLE IF NOT EXISTS world_locations (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(150) NOT NULL,
    name_jp             VARCHAR(150),
    type                ENUM('School','City','Battlefield','Landmark','Clan Compound','Hidden','Colony','Dimension') NOT NULL DEFAULT 'Landmark',
    region              VARCHAR(100),
    description         TEXT,
    lore                TEXT,
    image_url           VARCHAR(255),
    significance_level  INT DEFAULT 50,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- SEED: cursed_techniques
INSERT INTO cursed_techniques (name, name_jp, type, user_name, affiliation, description, lore, power_level, difficulty, is_domain) VALUES

('Limitless','無下限呪術','Innate Technique','Satoru Gojo','Tokyo Jujutsu High',
'Teknik bawaan Klan Gojo yang memanipulasi ruang di tingkat atom. Infinity melindungi penggunanya dengan membuat semua yang mendekati mereka melambat hingga tidak pernah benar-benar menyentuh.',
'Limitless adalah teknik yang diwariskan turun-temurun dalam Klan Gojo. Dikombinasikan dengan Six Eyes, teknik ini mencapai potensi absolutnya. Varian ofensif meliputi Blue (引力), Red (斥力), dan Purple (虚式). Hanya Satoru Gojo yang mampu menggunakan ketiganya secara simultan.',
100, 95, FALSE),

('Unlimited Void','無量空処','Domain Expansion','Satoru Gojo','Tokyo Jujutsu High',
'Domain Expansion terkuat yang diketahui. Membuka koneksi lawan ke alam semesta yang tak terbatas — membombardir mereka dengan informasi dan stimulasi tak terbatas hingga lumpuh total.',
'Di dalam Unlimited Void, setiap neuron lawan bekerja sepenuhnya namun tidak dapat memproses informasi yang terus mengalir tanpa henti. Lawan tidak bisa bergerak, berpikir, atau bahkan merasakan — semuanya terjadi secara bersamaan. Gojo mampu mengaktifkan domain dalam skala microsecond untuk efisiensi energi.',
100, 98, TRUE),

('Ten Shadows Technique','十種影法術','Innate Technique','Megumi Fushiguro','Tokyo Jujutsu High',
'Memanggil hingga 10 shikigami berbeda menggunakan bayangan sebagai medium. Setiap shikigami memiliki kemampuan unik yang saling melengkapi dalam pertarungan.',
'Teknik warisan Klan Zenin ini memungkinkan pengguna memanggil shikigami dari bayangan. Shikigami yang pernah dikalahkan menjadi milik permanen penggunanya. Puncaknya adalah Mahoraga — shikigami yang belum pernah ditaklukkan sepanjang sejarah, memiliki kemampuan adaptasi sempurna terhadap teknik apa pun.',
85, 80, FALSE),

('Chimera Shadow Garden','嵌合暗翳庭','Domain Expansion','Megumi Fushiguro','Tokyo Jujutsu High',
'Domain yang menciptakan dimensi bayangan cair yang luas. Seluruh shikigami dapat dipanggil dari segala penjuru bayangan dengan kekuatan penuh.',
'Chimera Shadow Garden masih dalam tahap pengembangan saat pertama digunakan Megumi. Domain ini tidak memiliki sure-hit effect sepenuhnya, namun kompleksitas dimensi bayangan membuatnya sangat sulit dinavigasi oleh lawan. Setiap sudut bisa menjadi pintu masuk serangan shikigami.',
82, 85, TRUE),

('Straw Doll Technique','藁人形呪法','Innate Technique','Nobara Kugisaki','Tokyo Jujutsu High',
'Menggunakan boneka jerami dan paku sebagai medium kutukan. Resonance mengirimkan kerusakan langsung ke target yang terhubung secara kutukan.',
'Teknik unik yang sangat efektif melawan kutukan yang mengoperasikan satu jiwa dalam dua tubuh terpisah. Saat paku ditancapkan ke boneka, kerusakan dikirim langsung ke jiwa target. Teknik ini nyaris tak bisa dielak karena bersifat remote dan menghantam di tingkat jiwa.',
72, 65, FALSE),

('Idle Transfiguration','無為転変','Innate Technique','Mahito','Cursed Spirit Alliance',
'Menyentuh dan mengubah jiwa secara langsung. Karena jiwa mendefinisikan tubuh, setiap perubahan pada jiwa secara otomatis mengubah bentuk fisik.',
'Teknik Mahito yang paling mengerikan — ia tidak menyerang tubuh, melainkan jiwa itu sendiri. Korban yang tersentuh dapat diubah bentuknya menjadi apa saja sesuka Mahito. Teknik ini juga memungkinkan Mahito menciptakan pasukan transfigured humans dari manusia biasa.',
88, 70, FALSE),

('Self-Embodiment of Perfection','癈人〇蔵〇皮膚と癈人','Domain Expansion','Mahito','Cursed Spirit Alliance',
'Domain Mahito yang memungkinkannya menyentuh jiwa lawan secara langsung dalam area domain. Sure-hit effect berupa Idle Transfiguration yang tidak bisa dielak.',
'Di dalam domain ini, setiap kontak dengan Mahito langsung mengaktifkan Idle Transfiguration. Tidak ada pertahanan fisik yang berarti karena serangan ditargetkan pada jiwa, bukan tubuh. Domain ini menjadi mimpi buruk bagi siapa pun yang tidak memiliki perlindungan jiwa khusus.',
85, 75, TRUE),

('Dismantle & Cleave','解・捌','Innate Technique','Ryomen Sukuna','Independent',
'Dismantle: serangan sayatan acak dengan energi tetap, efektif untuk benda mati. Cleave: menyesuaikan kekuatan secara presisi dengan ketangguhan target untuk memastikan kehancuran.',
'Dua teknik sayatan utama Sukuna yang saling melengkapi. Cleave secara otomatis mengkalkulasi kekuatan yang dibutuhkan untuk menghancurkan target — tidak ada pertahanan yang bisa menahannya. Dikombinasikan dengan Malevolent Shrine, keduanya menjadi jurus pemusnahan massal.',
100, 90, FALSE),

('Malevolent Shrine','伏魔御廚子','Domain Expansion','Ryomen Sukuna','Independent',
'Domain tanpa barrier — alih-alih menjebak lawan dalam ruang tertutup, domain ini memperluas diri ke dunia nyata dalam radius 200 meter, menghancurkan segalanya.',
'Malevolent Shrine adalah anomali dalam sistem Domain Expansion karena tidak memiliki barrier konvensional. Ini membuatnya tidak bisa ditangkal dengan domain counter biasa. Seluruh area radius 200 meter dihujani Dismantle dan Cleave secara bersamaan. Tanda-tanda kuil kuno bermunculan saat domain aktif.',
100, 99, TRUE),

('Ratio Technique','十劃呪法','Innate Technique','Kento Nanami','Tokyo Jujutsu High',
'Membagi setiap objek menjadi 10 bagian dan menyerang titik 7:3 — titik terlemah yang secara inheren dimiliki setiap makhluk dan benda.',
'Teknik profesional Nanami yang mencerminkan pendekatannya yang efisien. Tidak ada makhluk yang kebal selama memiliki titik lemah struktural. Nanami memanfaatkan ini dengan senjata tumpul yang dibungkus kutukan, memberikan kerusakan maksimal di titik paling rentan.',
82, 60, FALSE),

('Blood Manipulation','赤血操術','Innate Technique','Choso / Noritoshi Kamo','Independent / Kyoto Jujutsu High',
'Mengontrol darah — baik milik sendiri maupun darah yang sudah keluar dari tubuh. Memungkinkan proyektil darah berkecepatan tinggi, solidifikasi darah, dan manipulasi aliran darah lawan.',
'Teknik warisan Klan Kamo yang telah berkembang pesat di tangan Choso. Sebagai setengah kutukan, Choso memiliki volume darah tak terbatas dan mampu menggunakannya tanpa batas. Teknik Piercing Blood miliknya mampu menembus hampir semua pertahanan dengan kecepatan supersonik.',
80, 70, FALSE),

('Disaster Flames','炎','Innate Technique','Jogo','Cursed Spirit Alliance',
'Menghasilkan api dan magma dengan skala dahsyat. Satu serangan mampu menghanguskan seluruh stasiun kereta bawah tanah.',
'Jogo adalah roh kutukan bertema gunung berapi dengan kekuatan destruktif luar biasa. Api yang dihasilkannya tidak bisa dipadamkan dengan cara biasa karena bersifat kutukan. Namun bahkan dengan kekuatan ini, Jogo tetap tidak bisa menandingi Gojo Satoru.',
88, 55, FALSE),

('Coffin of the Iron Mountain','蓋棺鉄囲山','Domain Expansion','Jogo','Cursed Spirit Alliance',
'Domain bertema gunung berapi yang menciptakan lingkungan ekstrem dengan lava dan api di seluruh penjuru. Suhu di dalam domain mencapai titik ekstrem.',
'Domain Jogo yang memanfaatkan kekuatan temanya secara maksimal. Di dalam Coffin of the Iron Mountain, api Jogo mendapat amplifikasi drastis. Hanya sorcerer dengan pertahanan luar biasa atau kemampuan seperti Infinity Gojo yang bisa bertahan di dalamnya.',
85, 65, TRUE),

('Heavenly Restriction','天与呪縛','Special Ability','Toji Fushiguro / Maki Zenin','Independent',
'Bukan teknik yang dipelajari — kondisi bawaan lahir yang menghapus cursed energy sepenuhnya sebagai imbalan untuk tubuh yang melampaui batas manusia normal.',
'Heavenly Restriction adalah "kontrak dengan surga" yang terjadi saat kelahiran. Toji Fushiguro tidak memiliki cursed energy sama sekali, namun kecepatan, kekuatan, dan instingnya melampaui penyihir kelas khusus mana pun. Ia menggunakan Inventory Curse — roh kecil yang bisa menelan senjata apa pun.',
95, 0, FALSE),

('Reverse Cursed Technique','反転術式','Special Ability','Satoru Gojo / Yuta Okkotsu','Multiple',
'Mengalikan dua aliran cursed energy negatif menghasilkan energi positif. Digunakan untuk penyembuhan atau serangan dengan prinsip "negatif × negatif = positif".',
'Kemampuan langka yang hanya dikuasai segelintir sorcerer terpilih. Shoko Ieiri menggunakannya murni untuk penyembuhan medis. Gojo dan Yuta menggunakannya untuk regenerasi dan meningkatkan kekuatan teknik mereka. Sukuna juga menguasainya pada level yang memungkinkan regenerasi instan.',
90, 92, FALSE);

-- SEED: world_locations
INSERT INTO world_locations (name, name_jp, type, region, description, lore, image_url, significance_level) VALUES

('Tokyo Jujutsu High','東京都立呪術高等専門学校','School','Tokyo, Jepang',
'Sekolah teknik jujutsu metropolitan Tokyo — institusi paling terkemuka dalam dunia penyihir modern. Melatih penyihir baru sambil menangani misi pembersihan kutukan.',
'Berdiri di atas tanah yang kaya cursed energy, Tokyo Jujutsu High dilindungi oleh barrier permanen yang menyembunyikannya dari pandangan sipil. Fasilitas mencakup ruang latihan, rumah sakit khusus sorcerer, dan penjara bawah tanah untuk artefak kutukan berbahaya. Gojo Satoru, Nanami Kento, dan para sorcerer terkuat generasi ini tumbuh di sini.',
'Tokyo_Metropolitan_Jujutsu_Technical_School.webp',
95),

('Kyoto Jujutsu High','京都府立呪術高等専門学校','School','Kyoto, Jepang',
'Sekolah teknik jujutsu metropolitan Kyoto — rival historis Tokyo. Lebih konservatif dan menjunjung tinggi tradisi klan sorcerer.',
'Berlokasi di kota bersejarah Kyoto, sekolah ini memiliki hubungan erat dengan keluarga-keluarga sorcerer berpengaruh seperti Kamo dan Zenin. Principal Yoshinobu Gakuganji menjalankan institusi ini dengan pendekatan tradisional. Goodwill Event antara Tokyo dan Kyoto diadakan setahun sekali di sini.',
'Kyoto_Jujutsu_High.webp',
80),

('Shibuya','渋谷','Battlefield','Tokyo, Jepang',
'Distrik perbelanjaan tersibuk Tokyo yang menjadi lokasi insiden paling berdarah dalam sejarah modern jujutsu — Shibuya Incident.',
'Pada malam Shibuya Incident, Kenjaku dan aliansi kutukan mengaktifkan barrier raksasa yang menjebak ribuan sipil. Gojo Satoru berhasil dijebak dalam Prison Realm di persimpangan Shibuya. Sukuna dilepaskan sepenuhnya dan menghancurkan sebagian distrik. Malam itu mengubah keseimbangan kekuatan dunia jujutsu selamanya.',
'Shibuya.webp',
100),

('Zenin Clan Compound','禅院家の屋敷','Clan Compound','Jepang',
'Kediaman resmi Klan Zenin — salah satu dari Tiga Klan Besar yang paling berpengaruh dan paling kontroversial dalam dunia jujutsu.',
'Klan Zenin dikenal sebagai lingkungan yang sangat hierarkis dan kejam. Anggota yang tidak memiliki cursed energy yang cukup diperlakukan sebagai warga kelas dua. Maki dan Mai Zenin tumbuh di bawah tekanan ekstrem di sini. Tempat ini kemudian menjadi lokasi pertempuran berdarah ketika Maki kembali dengan kekuatan Heavenly Restriction penuh.',
NULL,
75),

('Jujutsu Headquarters','呪術総本山','Hidden','Jepang',
'Markas administratif para tetua jujutsu tempat kebijakan dan hukum dunia sorcerer diputuskan — termasuk hukuman mati.',
'Birokrasi jujutsu yang korup berpusat di sini, jauh dari medan pertempuran nyata. Para tetua yang tidak pernah turun ke lapangan membuat keputusan hidup mati para penyihir muda. Eksekusi Yuji Itadori diputuskan di sini. Satoru Gojo adalah duri terbesar bagi institusi ini.',
NULL,
70),

('Prison Realm','獄界封印・裏','Dimension','Dimensi Terpisah',
'Artefak kutukan berbentuk kotak yang bisa memenjarakan siapa pun yang berdiri di hadapannya — termasuk sorcerer terkuat sekalipun.',
'Prison Realm adalah kutukan khusus tingkat tinggi yang diciptakan ribuan tahun lalu. Di dalamnya, waktu berhenti sepenuhnya — penghuni tidak lapar, tidak haus, tidak mati, namun tidak bisa bergerak atau menggunakan teknik. Kenjaku menggunakannya untuk menjebak Gojo Satoru selama Shibuya Incident.',
NULL,
90),

('Cursed Womb: Death Paintings Site','呪胎九相図','Landmark','Tokyo',
'Lokasi tersimpannya artefak kutukan Death Painting Womb — embrio kutukan setengah manusia yang berisi kesadaran anak-anak Noritoshi Kamo yang terdeformasi.',
'Death Painting Womb adalah salah satu kutukan paling unik dalam sejarah — mereka bukan kutukan murni maupun manusia murni. Tiga dari sembilan embrio berhasil dibebaskan: Choso, Eso, dan Kechizu. Mereka memiliki ikatan darah langsung dengan Yuji Itadori, menciptakan komplikasi yang tidak terduga.',
NULL,
65),

('Gojo Family Estate','五条家の屋敷','Clan Compound','Jepang',
'Kediaman Klan Gojo — salah satu dari Tiga Klan Besar dengan warisan teknik Limitless dan Six Eyes.',
'Klan Gojo adalah satu-satunya klan yang menghasilkan penyihir dengan Limitless dan Six Eyes — kombinasi yang hanya muncul bersamaan sekali per abad. Satoru Gojo lahir di sini dan merupakan yang pertama dalam 400 tahun yang memiliki keduanya secara bersamaan, mengubahnya menjadi penyihir terkuat dalam sejarah.',
NULL,
70),

('Culling Game Colonies','コロニー','Colony','Seluruh Jepang',
'Area-area terisolasi yang dibungkus Barrier Kenjaku sebagai arena Culling Game — permainan pembantaian dengan aturan kejam.',
'Kenjaku mengaktifkan Culling Game setelah Shibuya Incident. Koloni tersebar di seluruh Jepang, masing-masing berisi sorcerer dari berbagai era yang dibangkitkan kembali. Aturan permainan: bunuh lawan untuk mendapat poin, kumpulkan poin untuk mengubah aturan. Meninggalkan koloni tanpa poin yang cukup hampir mustahil.',
'The_Culling_Game.webp',
85),

('Tombs of the Star Corridor','星の回廊の墓所','Hidden','Dimensi Tersembunyi',
'Kediaman Master Tengen — penyihir abadi yang menjaga barrier pelindung seluruh Jepang dari serangan kutukan skala besar.',
'Master Tengen telah hidup selama ribuan tahun, memperbarui tubuhnya melalui ritual Star Plasma Vessel setiap 500 tahun. Namun kali ini ritual gagal, dan Tengen mulai berevolusi menjadi sesuatu yang melampaui kemanusiaan. Ia mengelola ribuan barrier yang melindungi sekolah jujutsu dan fasilitas penting dari serangan kutukan.',
NULL,
80);

-- ============================================================
-- UPDATE IMAGE URLs: Cursed Techniques
-- (Jalankan setelah INSERT seed di atas)
-- ============================================================

-- Domain Expansions
UPDATE cursed_techniques SET image_url='Unlimited_Void.mp4'               WHERE name='Unlimited Void';
UPDATE cursed_techniques SET image_url='Chimera_Shadow_Garden.mp4'        WHERE name='Chimera Shadow Garden';
UPDATE cursed_techniques SET image_url='Self-Embodiment_Of_Perfection.mp4' WHERE name='Self-Embodiment of Perfection';
UPDATE cursed_techniques SET image_url='Malevolent_Shrine.mp4'            WHERE name='Malevolent Shrine';
UPDATE cursed_techniques SET image_url='Coffin_Of_The_Iron_Mountain.mp4'  WHERE name='Coffin of the Iron Mountain';

-- Innate Techniques
UPDATE cursed_techniques SET image_url='Limitless.mp4'                    WHERE name='Limitless';
UPDATE cursed_techniques SET image_url='Ten_Shadows_Technique.mp4'        WHERE name='Ten Shadows Technique';
UPDATE cursed_techniques SET image_url='Straw_Doll_Technique.mp4'         WHERE name='Straw Doll Technique';
UPDATE cursed_techniques SET image_url='Idle_Transfiguration.mp4'         WHERE name='Idle Transfiguration';
UPDATE cursed_techniques SET image_url='Ratio_Technique.jpeg'             WHERE name='Ratio Technique';
UPDATE cursed_techniques SET image_url='Blood_Manipulation.mp4'           WHERE name='Blood Manipulation';
UPDATE cursed_techniques SET image_url='Cursed_Spirit_Manipulation.mp4'   WHERE name='Cursed Spirit Manipulation';
UPDATE cursed_techniques SET image_url='Cursed_Speech.mp4'                WHERE name='Cursed Speech';
UPDATE cursed_techniques SET image_url='Boogie_Woogie.mp4'                WHERE name='Boogie Woogie';
UPDATE cursed_techniques SET image_url='Disaster_Flames.mp4'              WHERE name='Disaster Flames';
UPDATE cursed_techniques SET image_url='Black_Bird_Manipulation.mp4'      WHERE name='Bird Strike';

-- Special / Non-Innate
UPDATE cursed_techniques SET image_url='Domain_Amplification.jpeg'        WHERE name='Heavenly Restriction';

-- World Locations
UPDATE world_locations SET image_url='Tokyo_Metropolitan_Jujutsu_Technical_School.webp' WHERE name='Tokyo Jujutsu High';
UPDATE world_locations SET image_url='Kyoto_Jujutsu_High.webp'            WHERE name='Kyoto Jujutsu High';
UPDATE world_locations SET image_url='Shibuya.webp'                       WHERE name='Shibuya';
UPDATE world_locations SET image_url='The_Culling_Game.webp'              WHERE name='Culling Game Colonies';


-- ============================================================
-- TAMBAHAN WORLD LOCATIONS (agar lebih banyak tampil)
-- ============================================================
INSERT INTO world_locations (name, name_jp, type, region, description, lore, image_url, significance_level) VALUES

('Saitama Urami East Junior High','埼玉県立裏見東中学校','Landmark','Saitama, Jepang',
'Sekolah menengah tempat Yuji Itadori bersekolah sebelum hidupnya berubah setelah bertemu kutukan.',
'Di sekolah biasa inilah perjalanan luar biasa Yuji Itadori dimulai. Di sini ia pertama kali menemukan jari kutukan Sukuna dan mengambil keputusan yang mengubah hidupnya selamanya.',
'Saitama_Urami_East_Junior_High.webp', 60),

('Sugisawa High School','杉沢第三高校','Landmark','Miyagi, Jepang',
'Sekolah menengah asal Yuji sebelum pindah ke Saitama. Tempat kenangan masa kecilnya bersama kakeknya.',
'Sugisawa adalah sekolah pertama Yuji sebelum pindah. Kakeknya yang berpengalaman dalam dunia kutukan berpesan padanya untuk selalu menyelamatkan banyak orang dan memastikan mereka mati dengan bermartabat.',
'Sugisawa_High.webp', 45),

('Yasohachi Bridge','八十八橋','Landmark','Jepang',
'Jembatan berhantu yang menjadi lokasi salah satu misi awal Yuji dan kawan-kawan. Kutukan kuat bersarang di sini.',
'Yasohachi Bridge adalah jembatan tua yang dikenal sebagai tempat terkutuk. Banyak orang hilang di sana sebelum tim Yuji diutus untuk membersihkan kutukan yang bersarang.',
'Yasohachi_Bridge.webp', 55),

('Gachinko Fight Club','ガチンコファイトクラブ','Landmark','Tokyo, Jepang',
'Tempat perkelahian bawah tanah yang dikelola Hakari Kinji, menjadi markas tidak resminya setelah diskors.',
'Setelah diskors dari Tokyo Jujutsu High, Hakari Kinji mendirikan arena pertarungan bawah tanah ini. Di sinilah ia ditemukan oleh Yuji dan Megumi yang mencari bantuannya untuk Culling Game.',
'Gachinko_Fight_Club.webp', 50),

('Kawasaki City','川崎市','City','Kanagawa, Jepang',
'Kota industri di pinggiran Tokyo yang menjadi lokasi beberapa misi pembersihan kutukan.',
'Kawasaki, kota yang berbatasan langsung dengan Tokyo, menjadi salah satu area operasi para sorcerer. Kepadatan penduduk dan industri berat di kota ini menciptakan lingkungan yang subur untuk tumbuhnya kutukan.',
'Kawasaki_City.webp', 50),

('Tokyo Metro Underground','東京地下鉄','Battlefield','Tokyo, Jepang',
'Jaringan kereta bawah tanah Tokyo yang menjadi medan pertempuran saat Shibuya Incident.',
'Terowongan gelap dan platform sempit stasiun metro Tokyo menjadi saksi bisu pertarungan sengit selama Shibuya Incident. Ribuan warga sipil terjebak di dalam barrier saat kutukan menyerang.',
'Tokyo_Metro_train.webp', 80),

('Renchoku Girls Junior High','連獄女子中学校','Landmark','Tokyo, Jepang',
'Sekolah putri yang menjadi salah satu lokasi misi pembersihan kutukan para sorcerer muda.',
'Sekolah ini menjadi lokasi misi di mana para sorcerer Tokyo Jujutsu High diutus untuk membersihkan aktivitas kutukan yang mengancam para siswa.',
'Renchoku_Girls_Junior_High.webp', 40);

