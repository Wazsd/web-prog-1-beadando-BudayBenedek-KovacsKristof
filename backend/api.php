<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Adatbázis kapcsolódási hiba: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) && $_GET['id'] !== '' ? (int)$_GET['id'] : null;

function read_body() {
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') return [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function cast_row($row) {
    if ($row === false) return null;
    $row['fkod'] = (int)$row['fkod'];
    $row['szul'] = $row['szul'] !== null ? (int)$row['szul'] : null;
    $row['meghal'] = $row['meghal'] !== null ? (int)$row['meghal'] : null;
    return $row;
}

try {
    if ($method === 'GET' && $id === null) {
        $stmt = $pdo->query('SELECT fkod, nev, szul, meghal FROM kutato ORDER BY nev');
        $rows = array_map('cast_row', $stmt->fetchAll());
        respond($rows);
    }

    if ($method === 'GET' && $id !== null) {
        $stmt = $pdo->prepare('SELECT fkod, nev, szul, meghal FROM kutato WHERE fkod = ?');
        $stmt->execute([$id]);
        $row = cast_row($stmt->fetch());
        if (!$row) respond(['error' => 'Feltaláló nem található'], 404);
        respond($row);
    }

    if ($method === 'POST') {
        $data = read_body();
        $nev = isset($data['nev']) ? trim($data['nev']) : '';
        if ($nev === '') respond(['error' => 'A név kötelező'], 400);
        $szul = isset($data['szul']) && $data['szul'] !== '' && $data['szul'] !== null ? (int)$data['szul'] : null;
        $meghal = isset($data['meghal']) && $data['meghal'] !== '' && $data['meghal'] !== null ? (int)$data['meghal'] : null;

        $stmt = $pdo->prepare('INSERT INTO kutato (nev, szul, meghal) VALUES (?, ?, ?)');
        $stmt->execute([$nev, $szul, $meghal]);
        $newId = (int)$pdo->lastInsertId();
        respond(['fkod' => $newId, 'nev' => $nev, 'szul' => $szul, 'meghal' => $meghal], 201);
    }

    if ($method === 'PUT' && $id !== null) {
        $data = read_body();
        $fields = [];
        $params = [];
        if (array_key_exists('nev', $data) && trim($data['nev']) !== '') {
            $fields[] = 'nev = ?';
            $params[] = trim($data['nev']);
        }
        if (array_key_exists('szul', $data)) {
            $fields[] = 'szul = ?';
            $params[] = $data['szul'] !== '' && $data['szul'] !== null ? (int)$data['szul'] : null;
        }
        if (array_key_exists('meghal', $data)) {
            $fields[] = 'meghal = ?';
            $params[] = $data['meghal'] !== '' && $data['meghal'] !== null ? (int)$data['meghal'] : null;
        }
        if (empty($fields)) respond(['error' => 'Nincs frissítendő mező'], 400);

        $params[] = $id;
        $stmt = $pdo->prepare('UPDATE kutato SET ' . implode(', ', $fields) . ' WHERE fkod = ?');
        $stmt->execute($params);

        $stmt = $pdo->prepare('SELECT fkod, nev, szul, meghal FROM kutato WHERE fkod = ?');
        $stmt->execute([$id]);
        $row = cast_row($stmt->fetch());
        if (!$row) respond(['error' => 'Feltaláló nem található'], 404);
        respond($row);
    }

    if ($method === 'DELETE' && $id !== null) {
        $stmt = $pdo->prepare('DELETE FROM kutato WHERE fkod = ?');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 0) respond(['error' => 'Feltaláló nem található'], 404);
        respond(['ok' => true, 'deleted' => $id]);
    }

    respond(['error' => 'Method not allowed'], 405);
} catch (PDOException $e) {
    respond(['error' => 'SQL hiba: ' . $e->getMessage()], 500);
}
