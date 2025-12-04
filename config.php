<?php
$host = getenv("DB_HOST") ?: 'localhost';
$port = getenv("DB_PORT") ?: '3306';
$dbname = getenv("DB_NAME") ?: 'shop_db';
$user = getenv("DB_USER") ?: 'root';
$password = getenv("DB_PASSWORD") ?: '';

// Create a MySQLi connection (for existing mysqli_query calls)
$mysqli = mysqli_connect($host, $user, $password, $dbname, $port);
if (!$mysqli) {
    die("MySQLi connection failed: " . mysqli_connect_error());
}

// Create a PDO connection (optional, if you want PDO)
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO connection failed: " . $e->getMessage());
}
?>
