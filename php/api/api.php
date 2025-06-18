<?php
header("Content-Type: application/json; charset=UTF-8");
require_once("../config/database.php");

$headers = getallheaders();

if (!isset($headers['User-Request'], $headers['User-Pass'])) {
    echo json_encode(['status' => 401, 'message' => 'Error Header']); exit;
}

$user = $headers['User-Request'];
$pass = $headers['User-Pass'];
$service = $headers['Service-Name'] ?? '';
$param = $headers['Service-Parameter'] ?? '';

$stmt = $pdo->prepare("SELECT user_id FROM user WHERE user_login = ? AND user_passwd = ? AND user_status = 1");
$stmt->execute([$user, $pass]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user_data) {
    echo json_encode(['status' => 401, 'message' => 'No Authentication']); exit;
}

// ✅ ดึงข้อมูลตาม service
$data = [];
switch ($service) {
    case 'getall':
        $sql = "SELECT * FROM covid_cases";
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;

    case 'byhospital':
        if (ctype_digit($param)) {
            $stmt = $pdo->prepare("SELECT * FROM covid_cases WHERE hos_id = ?");
            $stmt->execute([$param]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        break;

    case 'totalgender':
        $sql = "SELECT sex, COUNT(*) AS total FROM covid_cases GROUP BY sex";
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;

    default:
        $stmt = $pdo->query("SELECT DATE_FORMAT(NOW(), '%a %Y-%m-%d %H:%i:%s') AS this_time");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode([
    'status' => 200,
    'message' => 'เรียกใช้ API สำเร็จ',
    'total' => count($data),
    'data' => $data
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);