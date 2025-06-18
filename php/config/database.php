<?php
$host = "localhost";
$db_name = "covid";
$username = "root";
$password = "";
$port = 3306;

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", 
        $username, 
        $password,
        [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

function convertToUTF8($data) {
  if (is_array($data)) {
    return array_map('convertToUTF8', $data);
  } elseif (is_string($data)) {
    return mb_convert_encoding($data, 'UTF-8', 'auto');
  }
  return $data;
}

?>