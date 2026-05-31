-- =============================================
-- JUJUTSU KAISEN WEB - DATABASE SCHEMA
-- =============================================

CREATE DATABASE IF NOT EXISTS jjk_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jjk_web;

-- Table 1: Users (2 roles: admin & user)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    avatar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table 2: Characters (CRUD utama)
CREATE TABLE IF NOT EXISTS characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    grade ENUM('Special Grade', 'Grade 1', 'Grade 2', 'Grade 3', 'Semi-Grade 1') NOT NULL,
    affiliation VARCHAR(100),
    cursed_technique VARCHAR(200),
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

-- Table 3: Game Scores (leaderboard)
CREATE TABLE IF NOT EXISTS game_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    character_used VARCHAR(100),
    score INT DEFAULT 0,
    enemies_defeated INT DEFAULT 0,
    played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table 4: Comments/Reviews
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

-- =============================================
-- SEED DATA
-- =============================================

-- Default admin account
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@jjk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('yuji_itadori', 'yuji@jjk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');
-- Default password: password

-- Seed characters
INSERT INTO characters (name, grade, affiliation, cursed_technique, description, lore, image_url, attack_power, defense_power, speed_power, is_playable) VALUES
('Yuji Itadori', 'Special Grade', 'Tokyo Jujutsu High', 'Divergent Fist / Black Flash', 'The main protagonist with superhuman physical abilities and host to Ryomen Sukuna.', 'Yuji Itadori was an ordinary high school student until he swallowed a finger of the most powerful cursed spirit, Ryomen Sukuna. Now he walks the path of a Jujutsu sorcerer to find and consume all of Sukuna\'s fingers, then die a proper death.', 'https://static.wikia.nocookie.net/jujutsu-kaisen/images/6/6f/Yuji_Itadori_(Anime_2).png/revision/latest?cb=20200908071838', 90, 75, 85, TRUE),
('Megumi Fushiguro', 'Grade 1', 'Tokyo Jujutsu High', 'Ten Shadows Technique', 'A skilled sorcerer who can summon shikigami using shadows.', 'Megumi is a stoic and pragmatic sorcerer who believes in saving people worthy of being saved. His Ten Shadows Technique allows him to summon powerful shikigami from shadows.', 'megumi.png', 80, 80, 75, TRUE),
('Nobara Kugisaki', 'Grade 3', 'Tokyo Jujutsu High', 'Straw Doll Technique', 'A fierce sorcerer who uses a hammer, nails, and straw dolls to curse her enemies.', 'Nobara came from rural Japan to Tokyo with a dream of living life to the fullest. Her Straw Doll Technique allows her to curse enemies by driving nails into straw dolls.', 'nobara.png', 75, 70, 80, TRUE),
('Satoru Gojo', 'Special Grade', 'Tokyo Jujutsu High', 'Infinity / Six Eyes / Limitless', 'The strongest sorcerer alive, teacher at Tokyo Jujutsu High.', 'Gojo is widely considered the strongest sorcerer in the world. His Six Eyes allow him to use the Limitless cursed technique to its fullest, and his Infinity makes him virtually untouchable.', 'https://static.wikia.nocookie.net/jujutsu-kaisen/images/e/ef/Satoru_Gojo_(Anime_2).png/revision/latest?cb=20240622022211', 100, 100, 100, TRUE),
('Ryomen Sukuna', 'Special Grade', 'None (Cursed Spirit)', 'Malevolent Shrine / Dismantle / Cleave', 'The King of Curses, sealed within Yuji Itadori\'s body through his fingers.', 'Sukuna is the undisputed King of Curses who lived over 1000 years ago. His immense power was so great that even after death, his cursed energy remained in his 20 severed fingers.', 'sukuna.png', 100, 95, 95, FALSE),
('Suguru Geto', 'Special Grade', 'Cursed Spirit Users', 'Cursed Spirit Manipulation', 'A former Jujutsu sorcerer turned villain who can manipulate cursed spirits.', 'Once Gojo\'s best friend and a promising sorcerer, Geto turned to the dark side after witnessing the death of a girl he tried to protect, leading him to despise non-sorcerers.', 'geto.png', 85, 85, 80, FALSE),
('Aoi Todo', 'Grade 1', 'Kyoto Jujutsu High', 'Boogie Woogie', 'An incredibly strong sorcerer who can swap positions of objects and people.', 'Todo is an eccentric sorcerer from Kyoto Jujutsu High whose immense physical strength rivals Special Grade sorcerers. His Boogie Woogie technique allows him to swap positions by clapping.', 'todo.png', 92, 82, 85, FALSE),
('Kento Nanami', 'Grade 1', 'Tokyo Jujutsu High', 'Ratio Technique', 'A stoic and professional sorcerer who divides targets into 7:3 ratios to create weak points.', 'Nanami is a former salaryman who returned to jujutsu sorcery. His methodical and no-nonsense approach combined with the Ratio Technique makes him a formidable Grade 1 sorcerer.', 'nanami.png', 82, 78, 72, FALSE),
('Toji Fushiguro', 'Special Grade', 'Zenin Clan (former)', 'Heavenly Restriction / Inventory Curse', 'A legendary assassin known as the Sorcerer Killer, possessing zero cursed energy but superhuman physical abilities.', 'Toji Fushiguro, born Toji Zenin, abandoned the prestigious Zenin clan after being treated as inferior due to his lack of cursed energy. His Heavenly Restriction compensated by granting him a physique surpassing all sorcerers.', 'https://static.wikia.nocookie.net/jujutsu-kaisen/images/d/db/Toji_Fushiguro_(Anime).png/revision/latest?cb=20221217105010', 98, 70, 99, FALSE);

-- Seed comments
INSERT INTO comments (user_id, character_id, content, rating) VALUES
(2, 1, 'Yuji Itadori is the best protagonist! His determination and power are unmatched!', 5),
(2, 4, 'Gojo Satoru is simply the strongest. No debate needed.', 5),
(2, 5, 'Sukuna is terrifying but his design is so cool with 4 arms!', 4);
