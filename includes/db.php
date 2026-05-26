<?php
$dbHost = "localhost";
$dbName = "course_pal";
$dbUser = "root";
$dbPass = "";
$pdo = null;

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $exception) {
    $pdo = null;
}
?>
