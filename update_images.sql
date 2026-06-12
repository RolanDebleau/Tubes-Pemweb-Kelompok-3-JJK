-- ============================================================
-- JJK Web — Update image_url untuk cursed_techniques & world_locations
-- Jalankan file ini di phpMyAdmin atau MySQL client
-- setelah database.sql sudah diimport
-- ============================================================

USE jjk_web;

-- ---- CURSED TECHNIQUES: Domain Expansions ----
UPDATE cursed_techniques SET image_url='Unlimited_Void.mp4'                WHERE name='Unlimited Void';
UPDATE cursed_techniques SET image_url='Chimera_Shadow_Garden.mp4'         WHERE name='Chimera Shadow Garden';
UPDATE cursed_techniques SET image_url='Self-Embodiment_Of_Perfection.mp4' WHERE name='Self-Embodiment of Perfection';
UPDATE cursed_techniques SET image_url='Malevolent_Shrine.mp4'             WHERE name='Malevolent Shrine';
UPDATE cursed_techniques SET image_url='Coffin_Of_The_Iron_Mountain.mp4'   WHERE name='Coffin of the Iron Mountain';

-- ---- CURSED TECHNIQUES: Innate Techniques ----
UPDATE cursed_techniques SET image_url='Limitless.mp4'                     WHERE name='Limitless';
UPDATE cursed_techniques SET image_url='Ten_Shadows_Technique.mp4'         WHERE name='Ten Shadows Technique';
UPDATE cursed_techniques SET image_url='Straw_Doll_Technique.mp4'          WHERE name='Straw Doll Technique';
UPDATE cursed_techniques SET image_url='Idle_Transfiguration.mp4'          WHERE name='Idle Transfiguration';
UPDATE cursed_techniques SET image_url='Ratio_Technique.jpeg'              WHERE name='Ratio Technique';
UPDATE cursed_techniques SET image_url='Blood_Manipulation.mp4'            WHERE name='Blood Manipulation';
UPDATE cursed_techniques SET image_url='Cursed_Spirit_Manipulation.mp4'    WHERE name='Cursed Spirit Manipulation';
UPDATE cursed_techniques SET image_url='Cursed_Speech.mp4'                 WHERE name='Cursed Speech';
UPDATE cursed_techniques SET image_url='Boogie_Woogie.mp4'                 WHERE name='Boogie Woogie';
UPDATE cursed_techniques SET image_url='Disaster_Flames.mp4'               WHERE name='Disaster Flames';
UPDATE cursed_techniques SET image_url='Black_Bird_Manipulation.mp4'       WHERE name='Bird Strike';

-- ---- CURSED TECHNIQUES: Special/Non-Innate ----
UPDATE cursed_techniques SET image_url='Domain_Amplification.jpeg'         WHERE name='Heavenly Restriction';

-- ---- WORLD LOCATIONS ----
UPDATE world_locations SET image_url='Tokyo_Metropolitan_Jujutsu_Technical_School.webp' WHERE name='Tokyo Jujutsu High';
UPDATE world_locations SET image_url='Kyoto_Jujutsu_High.webp'             WHERE name='Kyoto Jujutsu High';
UPDATE world_locations SET image_url='Shibuya.webp'                        WHERE name='Shibuya';
UPDATE world_locations SET image_url='The_Culling_Game.webp'               WHERE name='Culling Game Colonies';

-- ---- TAMBAH WORLD LOCATIONS EKSTRA ----
-- (hanya insert jika belum ada)
INSERT IGNORE INTO world_locations (name, name_jp, type, region, description, lore, image_url, significance_level) VALUES

('Saitama Urami East Junior High','埼玉県立裏見東中学校','Landmark','Saitama, Jepang',
'Sekolah menengah tempat Yuji Itadori bersekolah sebelum hidupnya berubah setelah bertemu kutukan.',
'Di sekolah biasa inilah perjalanan luar biasa Yuji Itadori dimulai.',
'Saitama_Urami_East_Junior_High.webp', 60),

('Sugisawa High School','杉沢第三高校','Landmark','Miyagi, Jepang',
'Sekolah menengah asal Yuji sebelum pindah ke Saitama. Tempat kenangan masa kecilnya bersama kakeknya.',
'Kakeknya berpesan padanya untuk selalu menyelamatkan banyak orang dan memastikan mereka mati dengan bermartabat.',
'Sugisawa_High.webp', 45),

('Yasohachi Bridge','八十八橋','Landmark','Jepang',
'Jembatan berhantu yang menjadi lokasi salah satu misi awal Yuji dan kawan-kawan.',
'Jembatan tua yang dikenal sebagai tempat terkutuk. Banyak orang hilang di sana.',
'Yasohachi_Bridge.webp', 55),

('Gachinko Fight Club','ガチンコファイトクラブ','Landmark','Tokyo, Jepang',
'Tempat perkelahian bawah tanah yang dikelola Hakari Kinji setelah diskors.',
'Di sinilah Hakari ditemukan oleh Yuji dan Megumi yang mencari bantuannya untuk Culling Game.',
'Gachinko_Fight_Club.webp', 50),

('Kawasaki City','川崎市','City','Kanagawa, Jepang',
'Kota industri di pinggiran Tokyo yang menjadi lokasi beberapa misi pembersihan kutukan.',
'Kawasaki, kota berbatasan langsung dengan Tokyo, menjadi salah satu area operasi para sorcerer.',
'Kawasaki_City.webp', 50),

('Tokyo Metro Underground','東京地下鉄','Battlefield','Tokyo, Jepang',
'Jaringan kereta bawah tanah Tokyo yang menjadi medan pertempuran saat Shibuya Incident.',
'Terowongan gelap dan platform sempit menjadi saksi bisu pertarungan sengit selama Shibuya Incident.',
'Tokyo_Metro_train.webp', 80),

('Renchoku Girls Junior High','連獄女子中学校','Landmark','Tokyo, Jepang',
'Sekolah putri yang menjadi salah satu lokasi misi pembersihan kutukan para sorcerer muda.',
'Sekolah ini menjadi lokasi misi di mana para sorcerer Tokyo Jujutsu High diutus untuk membersihkan aktivitas kutukan.',
'Renchoku_Girls_Junior_High.webp', 40);
