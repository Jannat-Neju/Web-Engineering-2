<?php
$host = getenv("DB_HOST") ?: 'localhost';
$port = getenv("DB_PORT") ?: '3306';
$dbname = getenv("DB_NAME") ?: 'shop_db';
$user = getenv("DB_USER") ?: 'root';
$password = getenv("DB_PASSWORD") ?: '';

$conn = mysqli_connect($host, $user, $password, $dbname, $port);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
