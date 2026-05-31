<?php
// =============================================
// KONFIGURASI DATABASE & SESSION
// =============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'jjk_web');
define('SITE_NAME', 'Jujutsu Kaisen Universe');
define('SITE_URL', 'http://localhost/jjk-web');

// Start session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi database
function getDB() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
        }
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}

// =============================================
// AUTH FUNCTIONS (Function 1)
// =============================================

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/pages/login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ' . SITE_URL . '/index.php?error=unauthorized');
        exit;
    }
}

function loginUser($email, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, username, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            return ['success' => true, 'role' => $user['role']];
        }
    }
    return ['success' => false, 'message' => 'Email atau password salah.'];
}

function registerUser($username, $email, $password) {
    $db = getDB();
    
    // Cek email sudah ada
    $check = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        return ['success' => false, 'message' => 'Username atau email sudah digunakan.'];
    }
    
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $username, $email, $hashed);
    
    if ($stmt->execute()) {
        return ['success' => true];
    }
    return ['success' => false, 'message' => 'Registrasi gagal. Coba lagi.'];
}

function logoutUser() {
    session_destroy();
    header('Location: ' . SITE_URL . '/pages/login.php');
    exit;
}

// =============================================
// CRUD CHARACTERS (Function 2)
// =============================================

function getAllCharacters($limit = null, $search = '') {
    $db = getDB();
    $sql = "SELECT * FROM characters";
    $params = [];
    $types = '';
    
    if (!empty($search)) {
        $sql .= " WHERE name LIKE ? OR grade LIKE ? OR cursed_technique LIKE ?";
        $s = "%$search%";
        $params = [$s, $s, $s];
        $types = 'sss';
    }
    
    $sql .= " ORDER BY attack_power DESC";
    if ($limit) $sql .= " LIMIT $limit";
    
    $stmt = $db->prepare($sql);
    if (!empty($params)) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getCharacterById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM characters WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function createCharacter($data) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO characters (name, grade, affiliation, cursed_technique, description, lore, image_url, attack_power, defense_power, speed_power, is_playable) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssiiis",
        $data['name'], $data['grade'], $data['affiliation'],
        $data['cursed_technique'], $data['description'], $data['lore'],
        $data['image_url'], $data['attack_power'], $data['defense_power'],
        $data['speed_power'], $data['is_playable']
    );
    return $stmt->execute();
}

function updateCharacter($id, $data) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE characters SET name=?, grade=?, affiliation=?, cursed_technique=?, description=?, lore=?, image_url=?, attack_power=?, defense_power=?, speed_power=?, is_playable=? WHERE id=?");
    $stmt->bind_param("sssssssiiii",
        $data['name'], $data['grade'], $data['affiliation'],
        $data['cursed_technique'], $data['description'], $data['lore'],
        $data['image_url'], $data['attack_power'], $data['defense_power'],
        $data['speed_power'], $data['is_playable'], $id
    );
    return $stmt->execute();
}

function deleteCharacter($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM characters WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function saveGameScore($userId, $character, $score, $enemies) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO game_scores (user_id, character_used, score, enemies_defeated) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $userId, $character, $score, $enemies);
    return $stmt->execute();
}

function getLeaderboard($limit = 10) {
    $db = getDB();
    $stmt = $db->prepare("SELECT gs.*, u.username FROM game_scores gs JOIN users u ON gs.user_id = u.id ORDER BY gs.score DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function addComment($userId, $charId, $content, $rating) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO comments (user_id, character_id, content, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $userId, $charId, $content, $rating);
    return $stmt->execute();
}

function getCommentsByCharacter($charId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.character_id = ? ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $charId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
