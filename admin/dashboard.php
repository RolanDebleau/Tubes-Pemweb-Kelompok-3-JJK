<?php
require_once '../includes/config.php';
requireAdmin();

$msg = '';
$msgType = '';

// Handle CRUD actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        $msg = 'Invalid request.'; $msgType = 'error';
    } else {
        $action = $_POST['action'] ?? '';
        
        // Handle image upload if file was provided
        $uploadedImageUrl = trim($_POST['image_url'] ?? '');
        if (!empty($_FILES['image_file']['tmp_name'])) {
            $allowedExts = ['jpg','jpeg','png','webp','gif'];
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowedExts)) {
                $targetDir = __DIR__ . '/../asset/';
                $safeName = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', basename($_FILES['image_file']['name']));
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetDir . $safeName)) {
                    $uploadedImageUrl = $safeName;
                }
            }
        }

        if ($action === 'create') {
            $data = [
                'name' => trim($_POST['name']),
                'grade' => $_POST['grade'],
                'affiliation' => trim($_POST['affiliation']),
                'cursed_technique' => trim($_POST['cursed_technique']),
                'description' => trim($_POST['description']),
                'lore' => trim($_POST['lore']),
                'image_url' => $uploadedImageUrl,
                'attack_power' => (int)$_POST['attack_power'],
                'defense_power' => (int)$_POST['defense_power'],
                'speed_power' => (int)$_POST['speed_power'],
                'is_playable' => isset($_POST['is_playable']) ? 1 : 0,
            ];
            if (createCharacter($data)) { $msg = 'Karakter berhasil ditambahkan!'; $msgType = 'success'; }
            else { $msg = 'Gagal menambahkan karakter.'; $msgType = 'error'; }
        }
        
        if ($action === 'update') {
            $id = (int)$_POST['char_id'];
            $data = [
                'name' => trim($_POST['name']),
                'grade' => $_POST['grade'],
                'affiliation' => trim($_POST['affiliation']),
                'cursed_technique' => trim($_POST['cursed_technique']),
                'description' => trim($_POST['description']),
                'lore' => trim($_POST['lore']),
                'image_url' => $uploadedImageUrl,
                'attack_power' => (int)$_POST['attack_power'],
                'defense_power' => (int)$_POST['defense_power'],
                'speed_power' => (int)$_POST['speed_power'],
                'is_playable' => isset($_POST['is_playable']) ? 1 : 0,
            ];
            if (updateCharacter($id, $data)) { $msg = 'Karakter berhasil diperbarui!'; $msgType = 'success'; }
            else { $msg = 'Gagal memperbarui karakter.'; $msgType = 'error'; }
        }
        
        if ($action === 'delete') {
            $id = (int)$_POST['char_id'];
            if (deleteCharacter($id)) { $msg = 'Karakter berhasil dihapus!'; $msgType = 'success'; }
            else { $msg = 'Gagal menghapus karakter.'; $msgType = 'error'; }
        }

        // === JUJUTSU CRUD ===
        if ($action === 'create_technique') {
            $data = [
                'name'        => trim($_POST['name']),
                'name_jp'     => trim($_POST['name_jp'] ?? ''),
                'type'        => $_POST['tech_type'],
                'user_name'   => trim($_POST['user_name']),
                'affiliation' => trim($_POST['affiliation']),
                'description' => trim($_POST['description']),
                'lore'        => trim($_POST['lore']),
                'image_url'   => $uploadedImageUrl,
                'power_level' => (int)$_POST['power_level'],
                'difficulty'  => (int)$_POST['difficulty'],
                'is_domain'   => isset($_POST['is_domain']) ? 1 : 0,
            ];
            if (createTechnique($data)) { $msg = 'Teknik berhasil ditambahkan!'; $msgType = 'success'; }
            else { $msg = 'Gagal menambahkan teknik.'; $msgType = 'error'; }
        }
        if ($action === 'update_technique') {
            $id = (int)$_POST['tech_id'];
            $data = [
                'name'        => trim($_POST['name']),
                'name_jp'     => trim($_POST['name_jp'] ?? ''),
                'type'        => $_POST['tech_type'],
                'user_name'   => trim($_POST['user_name']),
                'affiliation' => trim($_POST['affiliation']),
                'description' => trim($_POST['description']),
                'lore'        => trim($_POST['lore']),
                'image_url'   => $uploadedImageUrl,
                'power_level' => (int)$_POST['power_level'],
                'difficulty'  => (int)$_POST['difficulty'],
                'is_domain'   => isset($_POST['is_domain']) ? 1 : 0,
            ];
            if (updateTechnique($id, $data)) { $msg = 'Teknik berhasil diperbarui!'; $msgType = 'success'; }
            else { $msg = 'Gagal memperbarui teknik.'; $msgType = 'error'; }
        }
        if ($action === 'delete_technique') {
            $id = (int)$_POST['tech_id'];
            if (deleteTechnique($id)) { $msg = 'Teknik berhasil dihapus!'; $msgType = 'success'; }
            else { $msg = 'Gagal menghapus teknik.'; $msgType = 'error'; }
        }

        // === WORLD CRUD ===
        if ($action === 'create_location') {
            $data = [
                'name'               => trim($_POST['name']),
                'name_jp'            => trim($_POST['name_jp'] ?? ''),
                'type'               => $_POST['loc_type'],
                'region'             => trim($_POST['region']),
                'description'        => trim($_POST['description']),
                'lore'               => trim($_POST['lore']),
                'image_url'          => $uploadedImageUrl,
                'significance_level' => (int)$_POST['significance_level'],
            ];
            if (createLocation($data)) { $msg = 'Lokasi berhasil ditambahkan!'; $msgType = 'success'; }
            else { $msg = 'Gagal menambahkan lokasi.'; $msgType = 'error'; }
        }
        if ($action === 'update_location') {
            $id = (int)$_POST['loc_id'];
            $data = [
                'name'               => trim($_POST['name']),
                'name_jp'            => trim($_POST['name_jp'] ?? ''),
                'type'               => $_POST['loc_type'],
                'region'             => trim($_POST['region']),
                'description'        => trim($_POST['description']),
                'lore'               => trim($_POST['lore']),
                'image_url'          => $uploadedImageUrl,
                'significance_level' => (int)$_POST['significance_level'],
            ];
            if (updateLocation($id, $data)) { $msg = 'Lokasi berhasil diperbarui!'; $msgType = 'success'; }
            else { $msg = 'Gagal memperbarui lokasi.'; $msgType = 'error'; }
        }
        if ($action === 'delete_location') {
            $id = (int)$_POST['loc_id'];
            if (deleteLocation($id)) { $msg = 'Lokasi berhasil dihapus!'; $msgType = 'success'; }
            else { $msg = 'Gagal menghapus lokasi.'; $msgType = 'error'; }
        }
    }
}

$characters = getAllCharacters();
$techniques  = getAllTechniques();
$locations   = getAllLocations();
$db = getDB();
$userCount    = $db->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$commentCount = $db->query("SELECT COUNT(*) FROM comments")->fetch_row()[0];
$scoreCount   = $db->query("SELECT COUNT(*) FROM game_scores")->fetch_row()[0];
$topScore     = $db->query("SELECT MAX(score) FROM game_scores")->fetch_row()[0] ?? 0;
$techCount    = count($techniques);
$locCount     = count($locations);

$editChar = null;
if (isset($_GET['edit'])) {
    $editChar = getCharacterById((int)$_GET['edit']);
}
$editTech = null;
if (isset($_GET['edit_tech'])) {
    $editTech = getTechniqueById((int)$_GET['edit_tech']);
}
$editLoc = null;
if (isset($_GET['edit_loc'])) {
    $editLoc = getLocationById((int)$_GET['edit_loc']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard — JJK Universe</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700&family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root{--black:#03020a;--deep:#08060f;--purple:#6b21e8;--purple-glow:#9d4dff;--gold:#f0c040;--red:#cc2233;--green:#00cc66;--text:#ede8f5;--text-muted:#7a7490;--border:rgba(107,33,232,.2);--border-gold:rgba(240,192,64,.2);--card-bg:rgba(10,8,20,.9);--sidebar-w:260px;--nav-h:64px;}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--black);color:var(--text);font-family:'Rajdhani',sans-serif;min-height:100vh;display:flex;flex-direction:column;}
::-webkit-scrollbar{width:5px;} ::-webkit-scrollbar-track{background:#08060f;} ::-webkit-scrollbar-thumb{background:#3a0d7a;}

/* TOP BAR */
.topbar{position:fixed;top:0;left:0;right:0;height:var(--nav-h);z-index:100;display:flex;align-items:center;padding:0 24px;background:rgba(3,2,10,.95);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);gap:16px;}
.topbar-logo{display:flex;align-items:center;gap:10px;text-decoration:none;}
.tl-sym{font-size:1.5rem;background:linear-gradient(135deg,var(--purple-glow),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.tl-text{font-family:'Cinzel Decorative',serif;font-size:.9rem;color:var(--text);}
.topbar-badge{background:rgba(204,34,51,.15);border:1px solid rgba(204,34,51,.4);border-radius:2px;padding:3px 10px;font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:#ff6677;margin-left:4px;}
.topbar-spacer{flex:1;}
.topbar-user{font-family:'Orbitron',sans-serif;font-size:.6rem;color:var(--gold);letter-spacing:1px;margin-right:8px;}
.btn-topbar{font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;padding:6px 14px;border-radius:2px;border:1px solid var(--border);color:var(--text-muted);background:transparent;text-decoration:none;cursor:pointer;transition:all .3s;}
.btn-topbar:hover{border-color:var(--purple-glow);color:var(--purple-glow);}

/* LAYOUT */
.layout{display:flex;margin-top:var(--nav-h);min-height:calc(100vh - var(--nav-h));}

/* SIDEBAR */
.sidebar{width:var(--sidebar-w);background:rgba(5,3,12,.95);border-right:1px solid var(--border);padding:24px 0;position:fixed;top:var(--nav-h);bottom:0;overflow-y:auto;z-index:50;}
.sidebar-section{padding:0 16px;margin-bottom:24px;}
.sidebar-label{font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:3px;color:var(--text-muted);text-transform:uppercase;padding:0 8px;margin-bottom:8px;display:block;}
.sidebar-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:2px;color:var(--text-muted);text-decoration:none;font-size:.9rem;transition:all .3s;cursor:pointer;border:none;background:none;width:100%;text-align:left;font-family:'Rajdhani',sans-serif;}
.sidebar-item:hover,.sidebar-item.active{background:rgba(107,33,232,.12);color:var(--text);border-left:2px solid var(--purple-glow);}
.sidebar-item.active{color:var(--purple-glow);}
.sidebar-icon{font-size:1.1rem;width:24px;text-align:center;flex-shrink:0;}
.sidebar-count{margin-left:auto;font-family:'Orbitron',sans-serif;font-size:.55rem;background:rgba(107,33,232,.2);border:1px solid rgba(107,33,232,.3);border-radius:10px;padding:1px 8px;color:var(--purple-glow);}

/* MAIN */
.main-area{margin-left:var(--sidebar-w);flex:1;padding:32px;overflow-y:auto;}

/* PANELS */
.panel{display:none;}
.panel.active{display:block;}

/* STATS CARDS */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:32px;}
.stat-card{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;padding:20px;position:relative;overflow:hidden;transition:all .3s;}
.stat-card:hover{border-color:var(--purple-glow);transform:translateY(-2px);}
.stat-card::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at top left,rgba(107,33,232,.08),transparent 60%);pointer-events:none;}
.stat-num{font-family:'Orbitron',sans-serif;font-size:2rem;font-weight:900;background:linear-gradient(135deg,var(--gold),var(--purple-glow));-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:block;margin-bottom:4px;}
.stat-label{font-size:.8rem;color:var(--text-muted);letter-spacing:1px;}
.stat-icon{position:absolute;right:16px;top:16px;font-size:2rem;opacity:.15;}

/* PAGE HEADER */
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
.page-title{font-family:'Cinzel Decorative',serif;font-size:1.4rem;color:var(--text);}
.page-sub{color:var(--text-muted);font-size:.85rem;margin-top:4px;}

/* MESSAGES */
.msg{padding:12px 16px;border-radius:2px;margin-bottom:20px;font-size:.9rem;}
.msg-success{background:rgba(0,204,102,.1);border:1px solid rgba(0,204,102,.3);color:var(--green);}
.msg-error{background:rgba(204,34,51,.1);border:1px solid rgba(204,34,51,.3);color:#ff6677;}

/* TABLE */
.data-table{width:100%;border-collapse:collapse;background:var(--card-bg);border:1px solid var(--border);border-radius:4px;overflow:hidden;}
.data-table th{background:rgba(107,33,232,.1);padding:12px 16px;text-align:left;font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--purple-glow);text-transform:uppercase;border-bottom:1px solid var(--border);}
.data-table td{padding:12px 16px;border-bottom:1px solid rgba(107,33,232,.08);vertical-align:middle;font-size:.9rem;}
.data-table tr:hover td{background:rgba(107,33,232,.04);}
.data-table tr:last-child td{border-bottom:none;}

.grade-badge{display:inline-block;font-family:'Orbitron',sans-serif;font-size:.45rem;letter-spacing:1px;padding:2px 7px;border-radius:1px;text-transform:uppercase;}
.grade-special{background:rgba(240,192,64,.15);border:1px solid rgba(240,192,64,.4);color:var(--gold);}
.grade-1{background:rgba(107,33,232,.15);border:1px solid rgba(107,33,232,.4);color:var(--purple-glow);}
.grade-2{background:rgba(0,150,255,.1);border:1px solid rgba(0,150,255,.3);color:#4dc8ff;}
.grade-3{background:rgba(100,100,120,.15);border:1px solid rgba(100,100,120,.4);color:#aaa8c0;}
.grade-4    { background:rgba(80,80,80,.15); border:1px solid rgba(80,80,80,.4); color:#888888; }
.grade-unranked { background:rgba(60,60,60,.12); border:1px solid rgba(60,60,60,.3); color:#666666; }

.playable-badge{display:inline-block;font-family:'Orbitron',sans-serif;font-size:.45rem;padding:2px 7px;border-radius:1px;}
.playable-yes{background:rgba(0,204,102,.12);border:1px solid rgba(0,204,102,.3);color:var(--green);}
.playable-no{background:rgba(100,100,120,.12);border:1px solid rgba(100,100,120,.3);color:var(--text-muted);}

.action-btns{display:flex;gap:6px;}
.btn-edit{padding:5px 12px;background:rgba(107,33,232,.15);border:1px solid rgba(107,33,232,.35);border-radius:2px;color:var(--purple-glow);font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:1px;cursor:pointer;text-decoration:none;transition:all .3s;}
.btn-edit:hover{background:rgba(107,33,232,.3);}
.btn-delete{padding:5px 12px;background:rgba(204,34,51,.1);border:1px solid rgba(204,34,51,.3);border-radius:2px;color:#ff6677;font-family:'Orbitron',sans-serif;font-size:.5rem;letter-spacing:1px;cursor:pointer;transition:all .3s;}
.btn-delete:hover{background:rgba(204,34,51,.25);}
.btn-add{padding:10px 24px;background:linear-gradient(135deg,var(--purple),#8b30ff);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.65rem;font-weight:700;letter-spacing:2px;cursor:pointer;transition:all .3s;}
.btn-add:hover{box-shadow:0 0 20px rgba(107,33,232,.4);}

/* FORM */
.form-card{background:var(--card-bg);border:1px solid var(--border);border-radius:4px;padding:28px;margin-bottom:24px;}
.form-card-title{font-family:'Cinzel Decorative',serif;font-size:1.1rem;color:var(--text);margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid var(--border);}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.form-group{margin-bottom:16px;}
/* DRAG-DROP UPLOAD */
.drop-zone{border:2px dashed var(--border);border-radius:4px;padding:24px;text-align:center;cursor:pointer;transition:all .3s;position:relative;background:rgba(107,33,232,.03);}
.drop-zone:hover,.drop-zone.drag-over{border-color:var(--purple-glow);background:rgba(107,33,232,.08);}
.drop-zone-icon{font-size:2rem;margin-bottom:8px;display:block;}
.drop-zone-text{font-size:.85rem;color:var(--text-muted);}
.drop-zone-text strong{color:var(--purple-glow);}
.drop-zone input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
.drop-preview{margin-top:10px;display:none;align-items:center;gap:10px;}
.drop-preview img,.drop-preview video{width:60px;height:60px;object-fit:cover;border-radius:3px;border:1px solid var(--border);}
.drop-preview-name{font-size:.82rem;color:var(--text-muted);}
.or-divider{display:flex;align-items:center;gap:8px;margin:10px 0;color:var(--text-muted);font-size:.78rem;}
.or-divider::before,.or-divider::after{content:'';flex:1;height:1px;background:var(--border);}
.form-group.full{grid-column:1/-1;}
.form-label{display:block;font-family:'Orbitron',sans-serif;font-size:.55rem;letter-spacing:2px;color:var(--purple-glow);text-transform:uppercase;margin-bottom:7px;}
.form-input,.form-select,.form-textarea{width:100%;background:rgba(107,33,232,.05);border:1px solid var(--border);border-radius:2px;padding:10px 14px;color:var(--text);font-family:'Rajdhani',sans-serif;font-size:.95rem;outline:none;transition:all .3s;}
.form-input:focus,.form-select:focus,.form-textarea:focus{border-color:var(--purple-glow);background:rgba(107,33,232,.08);}
.form-input::placeholder,.form-textarea::placeholder{color:var(--text-muted);}
.form-select option{background:#0a0814;color:var(--text);}
.form-textarea{resize:vertical;min-height:90px;}
.form-row{display:flex;align-items:center;gap:8px;}
.range-input{flex:1;accent-color:var(--purple-glow);}
.range-val{font-family:'Orbitron',sans-serif;font-size:.7rem;color:var(--gold);width:30px;text-align:right;}
.checkbox-group{display:flex;align-items:center;gap:10px;padding:10px 14px;background:rgba(107,33,232,.05);border:1px solid var(--border);border-radius:2px;}
.checkbox-group input[type=checkbox]{accent-color:var(--purple-glow);width:16px;height:16px;cursor:pointer;}
.checkbox-group label{cursor:pointer;font-size:.9rem;}

.form-actions{display:flex;gap:10px;margin-top:8px;}
.btn-save{padding:11px 28px;background:linear-gradient(135deg,var(--purple),#8b30ff);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.65rem;font-weight:700;letter-spacing:2px;cursor:pointer;transition:all .3s;}
.btn-save:hover{box-shadow:0 0 20px rgba(107,33,232,.4);}
.btn-cancel{padding:11px 24px;background:transparent;border:1px solid var(--border);border-radius:2px;color:var(--text-muted);font-family:'Orbitron',sans-serif;font-size:.65rem;letter-spacing:2px;cursor:pointer;transition:all .3s;text-decoration:none;}
.btn-cancel:hover{border-color:var(--purple-glow);color:var(--purple-glow);}

/* USERS TABLE */
.avatar-circle{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--purple),#8b30ff);display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;flex-shrink:0;}
.role-badge{display:inline-block;font-family:'Orbitron',sans-serif;font-size:.45rem;padding:2px 7px;border-radius:1px;}
.role-admin{background:rgba(240,192,64,.15);border:1px solid rgba(240,192,64,.4);color:var(--gold);}
.role-user{background:rgba(107,33,232,.15);border:1px solid rgba(107,33,232,.4);color:var(--purple-glow);}

/* SCORE TABLE */
.score-rank{font-family:'Orbitron',sans-serif;font-size:.8rem;font-weight:900;}
.rank-1{color:var(--gold);}
.rank-2{color:#c0c0c0;}
.rank-3{color:#cd7f32;}

/* CONFIRM MODAL */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:200;display:none;align-items:center;justify-content:center;backdrop-filter:blur(8px);}
.modal-overlay.show{display:flex;}
.modal-box{background:var(--deep);border:1px solid var(--border);border-radius:4px;padding:32px;max-width:400px;width:90%;text-align:center;}
.modal-icon{font-size:3rem;margin-bottom:16px;}
.modal-title{font-family:'Cinzel Decorative',serif;font-size:1.2rem;color:var(--text);margin-bottom:8px;}
.modal-sub{color:var(--text-muted);font-size:.9rem;margin-bottom:24px;}
.modal-btns{display:flex;gap:10px;justify-content:center;}
.btn-modal-confirm{padding:10px 28px;background:linear-gradient(135deg,var(--red),#ff3355);border:none;border-radius:2px;color:white;font-family:'Orbitron',sans-serif;font-size:.65rem;letter-spacing:2px;cursor:pointer;transition:all .3s;}
.btn-modal-confirm:hover{box-shadow:0 0 20px rgba(204,34,51,.5);}
.btn-modal-cancel{padding:10px 24px;background:transparent;border:1px solid var(--border);border-radius:2px;color:var(--text-muted);font-family:'Orbitron',sans-serif;font-size:.65rem;letter-spacing:2px;cursor:pointer;transition:all .3s;}
.btn-modal-cancel:hover{border-color:var(--purple-glow);color:var(--purple-glow);}

/* SEARCH BAR */
.search-bar{display:flex;gap:10px;margin-bottom:18px;}
.search-input{flex:1;background:rgba(107,33,232,.05);border:1px solid var(--border);border-radius:2px;padding:9px 14px;color:var(--text);font-family:'Rajdhani',sans-serif;font-size:.9rem;outline:none;}
.search-input:focus{border-color:var(--purple-glow);}
.search-input::placeholder{color:var(--text-muted);}

@media(max-width:900px){.stats-grid{grid-template-columns:1fr 1fr;}.form-grid{grid-template-columns:1fr;}.sidebar{width:200px;}.main-area{margin-left:200px;padding:20px;}}
@media(max-width:600px){.sidebar{display:none;}.main-area{margin-left:0;}}
</style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <a href="../index.php" class="topbar-logo">
        <span class="tl-sym">呪</span>
        <span class="tl-text">JJK Universe</span>
    </a>
    <div class="topbar-badge">ADMIN PANEL</div>
    <div class="topbar-spacer"></div>
    <span class="topbar-user"> <?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
    <a href="../index.php" class="btn-topbar">← Site</a>
    <a href="../pages/logout.php" class="btn-topbar">Logout</a>
</div>

<!-- CONFIRM MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon"></div>
        <div class="modal-title">Hapus Karakter?</div>
        <div class="modal-sub">Tindakan ini tidak dapat dibatalkan. Data karakter akan dihapus permanen.</div>
        <form method="POST" id="deleteForm">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="char_id" id="deleteCharId">
            <div class="modal-btns">
                <button type="submit" class="btn-modal-confirm">YA, HAPUS</button>
                <button type="button" class="btn-modal-cancel" onclick="closeModal()">BATAL</button>
            </div>
        </form>
    </div>
</div>

<div class="layout">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-section">
            <span class="sidebar-label">Navigation</span>
            <button class="sidebar-item active" id="nav-dashboard" onclick="showPanel('dashboard')">
                <span class="sidebar-icon"></span> Dashboard
            </button>
            <button class="sidebar-item" id="nav-characters" onclick="showPanel('characters')">
                <span class="sidebar-icon"></span> Characters
                <span class="sidebar-count"><?= count($characters) ?></span>
            </button>
            <button class="sidebar-item" id="nav-add-char" onclick="showPanel('add-char')">
                <span class="sidebar-icon"></span> Tambah Karakter
            </button>
        </div>
        <div class="sidebar-section">
            <span class="sidebar-label">Jujutsu</span>
            <button class="sidebar-item" id="nav-techniques" onclick="showPanel('techniques')">
                <span class="sidebar-icon"></span> Cursed Techniques
                <span class="sidebar-count"><?= $techCount ?></span>
            </button>
            <button class="sidebar-item" id="nav-add-tech" onclick="showPanel('add-tech')">
                <span class="sidebar-icon"></span> Tambah Teknik
            </button>
        </div>
        <div class="sidebar-section">
            <span class="sidebar-label">World</span>
            <button class="sidebar-item" id="nav-locations" onclick="showPanel('locations')">
                <span class="sidebar-icon"></span> Locations
                <span class="sidebar-count"><?= $locCount ?></span>
            </button>
            <button class="sidebar-item" id="nav-add-loc" onclick="showPanel('add-loc')">
                <span class="sidebar-icon"></span> Tambah Lokasi
            </button>
        </div>
        <div class="sidebar-section">
            <span class="sidebar-label">Data</span>
            <button class="sidebar-item" id="nav-users" onclick="showPanel('users')">
                <span class="sidebar-icon"></span> Users
                <span class="sidebar-count"><?= $userCount ?></span>
            </button>
            <button class="sidebar-item" id="nav-scores" onclick="showPanel('scores')">
                <span class="sidebar-icon"></span> Leaderboard
                <span class="sidebar-count"><?= $scoreCount ?></span>
            </button>
            <button class="sidebar-item" id="nav-comments" onclick="showPanel('comments')">
                <span class="sidebar-icon"></span> Komentar
                <span class="sidebar-count"><?= $commentCount ?></span>
            </button>
        </div>
    </div>

    <!-- MAIN AREA -->
    <div class="main-area">
        
        <?php if ($msg): ?>
        <div class="msg msg-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✓' : '⚠' ?> <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <!-- DASHBOARD PANEL -->
        <div class="panel active" id="panel-dashboard">
            <div class="page-header">
                <div>
                    <div class="page-title">Dashboard</div>
                    <div class="page-sub">Selamat datang di admin panel JJK Universe</div>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-icon">⚔</span>
                    <span class="stat-num"><?= count($characters) ?></span>
                    <span class="stat-label">Total Karakter</span>
                </div>
                <div class="stat-card">
                    <span class="stat-icon"></span>
                    <span class="stat-num"><?= $userCount ?></span>
                    <span class="stat-label">Total Users</span>
                </div>
                <div class="stat-card">
                    <span class="stat-icon"></span>
                    <span class="stat-num"><?= $scoreCount ?></span>
                    <span class="stat-label">Game Sessions</span>
                </div>
                <div class="stat-card">
                    <span class="stat-icon"></span>
                    <span class="stat-num"><?= number_format($topScore) ?></span>
                    <span class="stat-label">Top Score</span>
                </div>
            </div>
            
            <!-- Recent scores -->
            <div class="form-card">
                <div class="form-card-title"> 5 Game Score Terbaru</div>
                <?php
                $recent = $db->query("SELECT gs.*, u.username FROM game_scores gs JOIN users u ON gs.user_id = u.id ORDER BY gs.played_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
                ?>
                <table class="data-table">
                    <thead><tr><th>#</th><th>User</th><th>Character</th><th>Score</th><th>Enemies</th><th>Tanggal</th></tr></thead>
                    <tbody>
                    <?php foreach ($recent as $i => $r): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($r['username']) ?></td>
                        <td><?= htmlspecialchars($r['character_used']??'-') ?></td>
                        <td><strong style="color:var(--gold)"><?= number_format($r['score']) ?></strong></td>
                        <td><?= $r['enemies_defeated'] ?></td>
                        <td style="color:var(--text-muted)"><?= date('d/m/Y H:i', strtotime($r['played_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CHARACTERS PANEL -->
        <div class="panel" id="panel-characters">
            <div class="page-header">
                <div>
                    <div class="page-title">Kelola Karakter</div>
                    <div class="page-sub">CRUD data karakter Jujutsu Kaisen</div>
                </div>
                <button class="btn-add" onclick="showPanel('add-char')">+ Tambah Karakter</button>
            </div>
            
            <div class="search-bar">
                <input type="text" class="search-input" id="charSearch" placeholder="🔍 Cari karakter..." oninput="filterTable()">
            </div>
            
            <table class="data-table" id="charTable">
                <thead>
                    <tr>
                        <th>ID</th><th>Nama</th><th>Grade</th><th>Teknik</th>
                        <th>ATK</th><th>DEF</th><th>SPD</th>
                        <th>Playable</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($characters as $c): 
                    $gradeClass = str_contains($c['grade'],'Special') ? 'grade-special' : (str_contains($c['grade'],'1') ? 'grade-1' : (str_contains($c['grade'],'2') ? 'grade-2' : 'grade-3'));
                ?>
                <tr>
                    <td style="color:var(--text-muted)">#<?= $c['id'] ?></td>
                    <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                    <td><span class="grade-badge <?= $gradeClass ?>"><?= htmlspecialchars($c['grade']) ?></span></td>
                    <td style="color:var(--text-muted);font-size:.85rem;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($c['cursed_technique']) ?></td>
                    <td style="color:#ff5566"><?= $c['attack_power'] ?></td>
                    <td style="color:var(--purple-glow)"><?= $c['defense_power'] ?></td>
                    <td style="color:#44ccff"><?= $c['speed_power'] ?></td>
                    <td><span class="playable-badge <?= $c['is_playable'] ? 'playable-yes' : 'playable-no' ?>"><?= $c['is_playable'] ? 'YES' : 'NO' ?></span></td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-edit" onclick="editChar(<?= $c['id'] ?>)">✏ Edit</button>
                            <button class="btn-delete" onclick="confirmDelete(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['name'])) ?>')">🗑 Hapus</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- ADD/EDIT CHAR PANEL -->
        <div class="panel" id="panel-add-char">
            <div class="page-header">
                <div>
                    <div class="page-title" id="charFormTitle">Tambah Karakter Baru</div>
                    <div class="page-sub">Isi data karakter Jujutsu Kaisen</div>
                </div>
            </div>
            
            <div class="form-card">
                <form method="POST" id="charForm" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <input type="hidden" name="action" value="create" id="formAction">
                    <input type="hidden" name="char_id" id="formCharId" value="">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nama Karakter *</label>
                            <input type="text" name="name" class="form-input" placeholder="Satoru Gojo" required id="fn_name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Grade *</label>
                            <select name="grade" class="form-select" required id="fn_grade">
                                <option value="Special Grade">Special Grade</option>
                                <option value="Semi-Grade 1">Semi-Grade 1</option>
                                <option value="Grade 1">Grade 1</option>
                                <option value="Grade 2">Grade 2</option>
                                <option value="Grade 3">Grade 3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Afiliasi</label>
                            <input type="text" name="affiliation" class="form-input" placeholder="Tokyo Jujutsu High" id="fn_affiliation">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cursed Technique *</label>
                            <input type="text" name="cursed_technique" class="form-input" placeholder="Limitless / Six Eyes" required id="fn_technique">
                        </div>
                        <div class="form-group full">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="description" class="form-textarea" placeholder="Deskripsi singkat karakter..." id="fn_desc"></textarea>
                        </div>
                        <div class="form-group full">
                            <label class="form-label">Lore / Backstory</label>
                            <textarea name="lore" class="form-textarea" style="min-height:130px" placeholder="Cerita latar belakang karakter..." id="fn_lore"></textarea>
                        </div>
                        <div class="form-group full">
                            <label class="form-label">Gambar Karakter (opsional)</label>
                            <!-- Drag & Drop Upload Zone -->
                            <div class="drop-zone" id="dropZone">
                                <span class="drop-zone-icon"></span>
                                <p class="drop-zone-text"><strong>Drag & drop gambar ke sini</strong><br>atau klik untuk pilih file</p>
                                <input type="file" name="image_file" id="imageFileInput" accept="image/*">
                            </div>
                            <div class="drop-preview" id="dropPreview">
                                <img id="previewImg" src="" alt="preview" style="display:none">
                                <span class="drop-preview-name" id="previewName"></span>
                                <button type="button" onclick="clearImageUpload()" style="margin-left:auto;background:rgba(204,34,51,.1);border:1px solid rgba(204,34,51,.3);color:#ff6677;border-radius:2px;padding:4px 10px;font-size:.75rem;cursor:pointer;">✕ Hapus</button>
                            </div>
                            <div class="or-divider">ATAU gunakan nama file yang sudah ada</div>
                            <input type="text" name="image_url" class="form-input" placeholder="contoh: Satoru_Gojo.webp" id="fn_image">
                            <div style="font-size:.75rem;color:var(--text-muted);margin-top:6px;"> File yang diupload akan tersimpan di folder <code style="color:var(--purple-glow)">/asset/</code> secara otomatis.</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Attack Power: <span id="atk_val">50</span></label>
                            <div class="form-row">
                                <input type="range" name="attack_power" class="range-input" min="1" max="100" value="50" id="fn_atk" oninput="document.getElementById('atk_val').textContent=this.value">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Defense Power: <span id="def_val">50</span></label>
                            <div class="form-row">
                                <input type="range" name="defense_power" class="range-input" min="1" max="100" value="50" id="fn_def" oninput="document.getElementById('def_val').textContent=this.value">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Speed Power: <span id="spd_val">50</span></label>
                            <div class="form-row">
                                <input type="range" name="speed_power" class="range-input" min="1" max="100" value="50" id="fn_spd" oninput="document.getElementById('spd_val').textContent=this.value">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Opsi</label>
                            <div class="checkbox-group">
                                <input type="checkbox" name="is_playable" id="fn_playable" value="1">
                                <label for="fn_playable">Karakter dapat dimainkan di Mini Game</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-save"> SIMPAN KARAKTER</button>
                        <button type="button" class="btn-cancel" onclick="resetForm()">✕ RESET</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- USERS PANEL -->
        <div class="panel" id="panel-users">
            <div class="page-header">
                <div>
                    <div class="page-title">Data Users</div>
                    <div class="page-sub">Daftar semua user terdaftar</div>
                </div>
            </div>
            <?php
            $users = $db->query("SELECT id, username, email, role, created_at FROM users ORDER BY id")->fetch_all(MYSQLI_ASSOC);
            ?>
            <table class="data-table">
                <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Bergabung</th></tr></thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td style="color:var(--text-muted)">#<?= $u['id'] ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="avatar-circle"><?= strtoupper(substr($u['username'],0,1)) ?></div>
                            <strong><?= htmlspecialchars($u['username']) ?></strong>
                        </div>
                    </td>
                    <td style="color:var(--text-muted)"><?= htmlspecialchars($u['email']) ?></td>
                    <td><span class="role-badge <?= $u['role']==='admin'?'role-admin':'role-user' ?>"><?= strtoupper($u['role']) ?></span></td>
                    <td style="color:var(--text-muted)"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- SCORES PANEL -->
        <div class="panel" id="panel-scores">
            <div class="page-header">
                <div>
                    <div class="page-title">Game Leaderboard</div>
                    <div class="page-sub">Semua skor permainan</div>
                </div>
            </div>
            <?php
            $allScores = $db->query("SELECT gs.*, u.username FROM game_scores gs JOIN users u ON gs.user_id=u.id ORDER BY gs.score DESC LIMIT 50")->fetch_all(MYSQLI_ASSOC);
            ?>
            <table class="data-table">
                <thead><tr><th>Rank</th><th>User</th><th>Karakter</th><th>Score</th><th>Enemies</th><th>Tanggal</th></tr></thead>
                <tbody>
                <?php foreach ($allScores as $i => $s): ?>
                <tr>
                    <td><span class="score-rank <?= $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'')) ?>"><?= $i===0?'👑':($i+1) ?></span></td>
                    <td><?= htmlspecialchars($s['username']) ?></td>
                    <td><?= htmlspecialchars($s['character_used']??'-') ?></td>
                    <td><strong style="color:var(--gold)"><?= number_format($s['score']) ?></strong></td>
                    <td><?= $s['enemies_defeated'] ?></td>
                    <td style="color:var(--text-muted)"><?= date('d/m/Y H:i',strtotime($s['played_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- COMMENTS PANEL -->
        <div class="panel" id="panel-comments">
            <div class="page-header">
                <div>
                    <div class="page-title">Komentar User</div>
                    <div class="page-sub">Semua komentar dan review karakter</div>
                </div>
            </div>
            <?php
            $allComments = $db->query("SELECT c.*, u.username, ch.name as char_name FROM comments c JOIN users u ON c.user_id=u.id JOIN characters ch ON c.character_id=ch.id ORDER BY c.created_at DESC")->fetch_all(MYSQLI_ASSOC);
            ?>
            <table class="data-table">
                <thead><tr><th>User</th><th>Karakter</th><th>Rating</th><th>Komentar</th><th>Tanggal</th></tr></thead>
                <tbody>
                <?php foreach ($allComments as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['username']) ?></td>
                    <td style="color:var(--purple-glow)"><?= htmlspecialchars($c['char_name']) ?></td>
                    <td><?= str_repeat('⭐',$c['rating']) ?></td>
                    <td style="color:var(--text-muted);max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($c['content']) ?></td>
                    <td style="color:var(--text-muted)"><?= date('d/m/Y',strtotime($c['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <!-- ==================== PANEL: TECHNIQUES ==================== -->
    <div class="panel" id="panel-techniques">
        <div class="page-header">
            <div>
                <div class="page-title"> Cursed Techniques</div>
                <div class="page-sub">Kelola semua teknik kutukan</div>
            </div>
            <button class="btn-add" onclick="showPanel('add-tech')">+ Tambah Teknik</button>
        </div>
        <table class="data-table">
            <thead><tr><th>Nama</th><th>Tipe</th><th>Pengguna</th><th>Power</th><th>Domain</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($techniques as $t): ?>
            <tr>
                <td><strong><?= htmlspecialchars($t['name']) ?></strong><br><span style="color:var(--text-muted);font-size:.8rem"><?= htmlspecialchars($t['name_jp'] ?? '') ?></span></td>
                <td><span class="grade-badge grade-1"><?= htmlspecialchars($t['type']) ?></span></td>
                <td style="color:var(--gold)"><?= htmlspecialchars($t['user_name'] ?? '-') ?></td>
                <td><?= $t['power_level'] ?>/100</td>
                <td><?= $t['is_domain'] ? '<span class="playable-badge playable-yes">YA</span>' : '<span class="playable-badge playable-no">Tidak</span>' ?></td>
                <td>
                    <div class="action-btns">
                        <button class="btn-edit" onclick="editTech(<?= $t['id'] ?>)">Edit</button>
                        <button class="btn-delete" onclick="confirmDeleteTech(<?= $t['id'] ?>, '<?= htmlspecialchars(addslashes($t['name'])) ?>')">Hapus</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ==================== PANEL: ADD/EDIT TECHNIQUE ==================== -->
    <div class="panel" id="panel-add-tech">
        <div class="form-card">
            <div class="form-card-title" id="techFormTitle"> Tambah Teknik Baru</div>
            <?php if($msg && (isset($_POST['action']) && str_contains($_POST['action'],'technique'))): ?>
            <div class="msg msg-<?= $msgType ?>"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="action" value="create_technique" id="techFormAction">
                <input type="hidden" name="tech_id" id="techFormId" value="">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Teknik *</label>
                        <input class="form-input" type="text" name="name" id="tf_name" required placeholder="cth: Limitless" value="<?= htmlspecialchars($editTech['name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Jepang</label>
                        <input class="form-input" type="text" name="name_jp" id="tf_name_jp" placeholder="cth: 無下限呪術" value="<?= htmlspecialchars($editTech['name_jp'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe *</label>
                        <select class="form-input" name="tech_type" id="tf_type">
                            <?php foreach(['Innate Technique','Non-Innate','Domain Expansion','Special Ability','Shikigami'] as $tp): ?>
                            <option value="<?= $tp ?>" <?= ($editTech['type'] ?? '') === $tp ? 'selected' : '' ?>><?= $tp ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pengguna</label>
                        <input class="form-input" type="text" name="user_name" id="tf_user" placeholder="cth: Satoru Gojo" value="<?= htmlspecialchars($editTech['user_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Afiliasi</label>
                        <input class="form-input" type="text" name="affiliation" id="tf_affiliation" placeholder="cth: Tokyo Jujutsu High" value="<?= htmlspecialchars($editTech['affiliation'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Image URL / Nama File</label>
                        <input class="form-input" type="text" name="image_url" id="tf_image" placeholder="cth: Limitless.mp4" value="<?= htmlspecialchars($editTech['image_url'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Power Level (0-100)</label>
                        <input class="form-input" type="number" name="power_level" id="tf_power" min="0" max="100" value="<?= $editTech['power_level'] ?? 50 ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Difficulty (0-100)</label>
                        <input class="form-input" type="number" name="difficulty" id="tf_difficulty" min="0" max="100" value="<?= $editTech['difficulty'] ?? 50 ?>">
                    </div>
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Deskripsi *</label>
                    <textarea class="form-input" name="description" id="tf_description" rows="3" required placeholder="Deskripsi singkat teknik..."><?= htmlspecialchars($editTech['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Lore / Detail</label>
                    <textarea class="form-input" name="lore" id="tf_lore" rows="4" placeholder="Lore mendalam..."><?= htmlspecialchars($editTech['lore'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-family:'Orbitron',sans-serif;font-size:.65rem;color:var(--gold)">
                        <input type="checkbox" name="is_domain" id="tf_domain" <?= !empty($editTech['is_domain']) ? 'checked' : '' ?>>
                        Ini adalah Domain Expansion
                    </label>
                </div>
                <div style="display:flex;gap:10px;margin-top:8px">
                    <button type="submit" class="btn-add">💾 Simpan Teknik</button>
                    <button type="button" class="btn-modal-cancel" onclick="resetTechForm()">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== PANEL: LOCATIONS ==================== -->
    <div class="panel" id="panel-locations">
        <div class="page-header">
            <div>
                <div class="page-title"> World Locations</div>
                <div class="page-sub">Kelola semua lokasi dunia JJK</div>
            </div>
            <button class="btn-add" onclick="showPanel('add-loc')">+ Tambah Lokasi</button>
        </div>
        <table class="data-table">
            <thead><tr><th>Nama</th><th>Tipe</th><th>Region</th><th>Significance</th><th>Gambar</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($locations as $loc): ?>
            <tr>
                <td><strong><?= htmlspecialchars($loc['name']) ?></strong><br><span style="color:var(--text-muted);font-size:.8rem"><?= htmlspecialchars($loc['name_jp'] ?? '') ?></span></td>
                <td><span class="grade-badge grade-2"><?= htmlspecialchars($loc['type']) ?></span></td>
                <td style="color:var(--gold)"><?= htmlspecialchars($loc['region'] ?? '-') ?></td>
                <td><?= $loc['significance_level'] ?>/100</td>
                <td style="color:var(--text-muted);font-size:.8rem"><?= $loc['image_url'] ? ' '.htmlspecialchars($loc['image_url']) : ' Tidak ada' ?></td>
                <td>
                    <div class="action-btns">
                        <button class="btn-edit" onclick="editLoc(<?= $loc['id'] ?>)">Edit</button>
                        <button class="btn-delete" onclick="confirmDeleteLoc(<?= $loc['id'] ?>, '<?= htmlspecialchars(addslashes($loc['name'])) ?>')">Hapus</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ==================== PANEL: ADD/EDIT LOCATION ==================== -->
    <div class="panel" id="panel-add-loc">
        <div class="form-card">
            <div class="form-card-title" id="locFormTitle"> Tambah Lokasi Baru</div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="action" value="create_location" id="locFormAction">
                <input type="hidden" name="loc_id" id="locFormId" value="">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Lokasi *</label>
                        <input class="form-input" type="text" name="name" id="lf_name" required placeholder="cth: Shibuya" value="<?= htmlspecialchars($editLoc['name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Jepang</label>
                        <input class="form-input" type="text" name="name_jp" id="lf_name_jp" placeholder="cth: 渋谷" value="<?= htmlspecialchars($editLoc['name_jp'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe *</label>
                        <select class="form-input" name="loc_type" id="lf_type">
                            <?php foreach(['School','City','Battlefield','Landmark','Clan Compound','Hidden','Colony','Dimension'] as $tp): ?>
                            <option value="<?= $tp ?>" <?= ($editLoc['type'] ?? '') === $tp ? 'selected' : '' ?>><?= $tp ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Region</label>
                        <input class="form-input" type="text" name="region" id="lf_region" placeholder="cth: Tokyo, Jepang" value="<?= htmlspecialchars($editLoc['region'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Image URL / Nama File</label>
                        <input class="form-input" type="text" name="image_url" id="lf_image" placeholder="cth: Shibuya.webp" value="<?= htmlspecialchars($editLoc['image_url'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Significance Level (0-100)</label>
                        <input class="form-input" type="number" name="significance_level" id="lf_sig" min="0" max="100" value="<?= $editLoc['significance_level'] ?? 50 ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi *</label>
                    <textarea class="form-input" name="description" id="lf_description" rows="3" required placeholder="Deskripsi singkat lokasi..."><?= htmlspecialchars($editLoc['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Lore / Detail</label>
                    <textarea class="form-input" name="lore" id="lf_lore" rows="4" placeholder="Lore mendalam..."><?= htmlspecialchars($editLoc['lore'] ?? '') ?></textarea>
                </div>
                <div style="display:flex;gap:10px;margin-top:8px">
                    <button type="submit" class="btn-add"> Simpan Lokasi</button>
                    <button type="button" class="btn-modal-cancel" onclick="resetLocForm()">Reset</button>
                </div>
            </form>
        </div>
    </div>

    </div>
</div>

<!-- Delete Tech Modal -->
<div class="modal-overlay" id="deleteTechModal">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <div class="modal-title">Hapus Teknik?</div>
        <div class="modal-sub" id="deleteTechSub"></div>
        <form method="POST" id="deleteTechForm">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="delete_technique">
            <input type="hidden" name="tech_id" id="deleteTechId">
            <div class="modal-btns">
                <button type="submit" class="btn-modal-confirm">YA, HAPUS</button>
                <button type="button" class="btn-modal-cancel" onclick="document.getElementById('deleteTechModal').classList.remove('show')">BATAL</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Loc Modal -->
<div class="modal-overlay" id="deleteLocModal">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <div class="modal-title">Hapus Lokasi?</div>
        <div class="modal-sub" id="deleteLocSub"></div>
        <form method="POST" id="deleteLocForm">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="delete_location">
            <input type="hidden" name="loc_id" id="deleteLocId">
            <div class="modal-btns">
                <button type="submit" class="btn-modal-confirm">YA, HAPUS</button>
                <button type="button" class="btn-modal-cancel" onclick="document.getElementById('deleteLocModal').classList.remove('show')">BATAL</button>
            </div>
        </form>
    </div>
</div>

<script>
// Panel switching
function showPanel(name) {
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.sidebar-item').forEach(s => s.classList.remove('active'));
    document.getElementById('panel-' + name)?.classList.add('active');
    document.getElementById('nav-' + name)?.classList.add('active');
}

// Delete modal
function confirmDelete(id, name) {
    document.getElementById('deleteCharId').value = id;
    document.querySelector('#deleteModal .modal-sub').textContent = `Hapus karakter "${name}"? Tindakan ini tidak dapat dibatalkan.`;
    document.getElementById('deleteModal').classList.add('show');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

// Edit character - auto fill form
const charData = <?= json_encode(array_values($characters)) ?>;

function editChar(id) {
    const c = charData.find(x => x.id == id);
    if (!c) return;
    
    showPanel('add-char');
    document.getElementById('charFormTitle').textContent = 'Edit Karakter: ' + c.name;
    document.getElementById('formAction').value = 'update';
    document.getElementById('formCharId').value = c.id;
    
    document.getElementById('fn_name').value = c.name;
    document.getElementById('fn_grade').value = c.grade;
    document.getElementById('fn_affiliation').value = c.affiliation || '';
    document.getElementById('fn_technique').value = c.cursed_technique;
    document.getElementById('fn_desc').value = c.description || '';
    document.getElementById('fn_lore').value = c.lore || '';
    document.getElementById('fn_image').value = c.image_url || '';
    // Clear uploaded file when editing
    clearImageUpload();
    document.getElementById('fn_atk').value = c.attack_power;
    document.getElementById('fn_def').value = c.defense_power;
    document.getElementById('fn_spd').value = c.speed_power;
    document.getElementById('fn_playable').checked = c.is_playable == 1;
    
    document.getElementById('atk_val').textContent = c.attack_power;
    document.getElementById('def_val').textContent = c.defense_power;
    document.getElementById('spd_val').textContent = c.speed_power;
    
    document.querySelector('#charForm .btn-save').textContent = '💾 UPDATE KARAKTER';
}

function resetForm() {
    document.getElementById('charFormTitle').textContent = 'Tambah Karakter Baru';
    document.getElementById('formAction').value = 'create';
    document.getElementById('formCharId').value = '';
    document.getElementById('charForm').reset();
    document.getElementById('atk_val').textContent = '50';
    document.getElementById('def_val').textContent = '50';
    document.getElementById('spd_val').textContent = '50';
    document.querySelector('#charForm .btn-save').textContent = '💾 SIMPAN KARAKTER';
}

// Filter table
function filterTable() {
    const q = document.getElementById('charSearch').value.toLowerCase();
    document.querySelectorAll('#charTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

// Auto open panel based on URL
<?php if ($editChar): ?>
editChar(<?= $editChar['id'] ?>);
<?php endif; ?>

// Close modal on outside click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// ===== DRAG & DROP IMAGE UPLOAD =====
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('imageFileInput');
const dropPreview = document.getElementById('dropPreview');
const previewImg = document.getElementById('previewImg');
const previewName = document.getElementById('previewName');

['dragenter','dragover'].forEach(ev => {
  dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
});
['dragleave','drop'].forEach(ev => {
  dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.remove('drag-over'); });
});
dropZone.addEventListener('drop', e => {
  const files = e.dataTransfer.files;
  if (files.length) handleImageFile(files[0]);
});
fileInput.addEventListener('change', e => {
  if (e.target.files.length) handleImageFile(e.target.files[0]);
});

function handleImageFile(file) {
  if (!file.type.startsWith('image/')) { alert('File harus berupa gambar!'); return; }
  const reader = new FileReader();
  reader.onload = e => {
    previewImg.src = e.target.result;
    previewImg.style.display = 'block';
    previewName.textContent = file.name;
    dropPreview.style.display = 'flex';
    dropZone.style.borderColor = 'var(--purple-glow)';
    // Suggest filename in text input
    document.getElementById('fn_image').value = file.name;
    document.getElementById('fn_image').placeholder = '(akan diupload: ' + file.name + ')';
  };
  reader.readAsDataURL(file);
}

function clearImageUpload() {
  fileInput.value = '';
  previewImg.src = '';
  previewImg.style.display = 'none';
  previewName.textContent = '';
  dropPreview.style.display = 'none';
  dropZone.style.borderColor = '';
  document.getElementById('fn_image').placeholder = 'contoh: Satoru_Gojo.webp';
}

// === TECHNIQUE CRUD JS ===
const techData = <?= json_encode(array_values($techniques)) ?>;
const locData  = <?= json_encode(array_values($locations)) ?>;

function editTech(id) {
    const t = techData.find(x => x.id == id);
    if (!t) return;
    showPanel('add-tech');
    document.getElementById('techFormTitle').textContent = 'Edit Teknik: ' + t.name;
    document.getElementById('techFormAction').value = 'update_technique';
    document.getElementById('techFormId').value = t.id;
    document.getElementById('tf_name').value = t.name || '';
    document.getElementById('tf_name_jp').value = t.name_jp || '';
    document.getElementById('tf_type').value = t.type || 'Innate Technique';
    document.getElementById('tf_user').value = t.user_name || '';
    document.getElementById('tf_affiliation').value = t.affiliation || '';
    document.getElementById('tf_image').value = t.image_url || '';
    document.getElementById('tf_power').value = t.power_level || 50;
    document.getElementById('tf_difficulty').value = t.difficulty || 50;
    document.getElementById('tf_description').value = t.description || '';
    document.getElementById('tf_lore').value = t.lore || '';
    document.getElementById('tf_domain').checked = t.is_domain == 1;
}

function resetTechForm() {
    document.getElementById('techFormTitle').textContent = '➕ Tambah Teknik Baru';
    document.getElementById('techFormAction').value = 'create_technique';
    document.getElementById('techFormId').value = '';
    ['tf_name','tf_name_jp','tf_user','tf_affiliation','tf_image','tf_description','tf_lore'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.getElementById('tf_power').value = 50;
    document.getElementById('tf_difficulty').value = 50;
    document.getElementById('tf_domain').checked = false;
}

function confirmDeleteTech(id, name) {
    document.getElementById('deleteTechId').value = id;
    document.getElementById('deleteTechSub').textContent = `Hapus teknik "${name}"? Tidak bisa dibatalkan.`;
    document.getElementById('deleteTechModal').classList.add('show');
}

// === LOCATION CRUD JS ===
function editLoc(id) {
    const l = locData.find(x => x.id == id);
    if (!l) return;
    showPanel('add-loc');
    document.getElementById('locFormTitle').textContent = 'Edit Lokasi: ' + l.name;
    document.getElementById('locFormAction').value = 'update_location';
    document.getElementById('locFormId').value = l.id;
    document.getElementById('lf_name').value = l.name || '';
    document.getElementById('lf_name_jp').value = l.name_jp || '';
    document.getElementById('lf_type').value = l.type || 'Landmark';
    document.getElementById('lf_region').value = l.region || '';
    document.getElementById('lf_image').value = l.image_url || '';
    document.getElementById('lf_sig').value = l.significance_level || 50;
    document.getElementById('lf_description').value = l.description || '';
    document.getElementById('lf_lore').value = l.lore || '';
}

function resetLocForm() {
    document.getElementById('locFormTitle').textContent = ' Tambah Lokasi Baru';
    document.getElementById('locFormAction').value = 'create_location';
    document.getElementById('locFormId').value = '';
    ['lf_name','lf_name_jp','lf_region','lf_image','lf_description','lf_lore'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.getElementById('lf_sig').value = 50;
}

function confirmDeleteLoc(id, name) {
    document.getElementById('deleteLocId').value = id;
    document.getElementById('deleteLocSub').textContent = `Hapus lokasi "${name}"? Tidak bisa dibatalkan.`;
    document.getElementById('deleteLocModal').classList.add('show');
}

// Auto-open edit panel if GET param set
<?php if ($editTech): ?>
editTech(<?= $editTech['id'] ?>);
<?php endif; ?>
<?php if ($editLoc): ?>
editLoc(<?= $editLoc['id'] ?>);
<?php endif; ?>
</script>
</body>
</html>