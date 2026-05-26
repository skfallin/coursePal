<?php
require 'includes/connectdb.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$firstName = trim($_POST['firstname'] ?? '');
$lastName = trim($_POST['lastname'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$selectedCategories = $_POST['categories'] ?? [];

if (!$pdo) {
    redirect_with_message('register.php', 'Database connection is required to register.');
}
if ($firstName === '' || $lastName === '' || $username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_message('register.php', 'All registration fields must be completed with a valid email address.');
}
if (strlen($password) < 8) {
    redirect_with_message('register.php', 'Password must be at least 8 characters.');
}
if ($password !== $password2) {
    redirect_with_message('register.php', 'Password and repeat password must match.');
}
if (!$selectedCategories) {
    redirect_with_message('register.php', 'Choose at least one preferred course category.');
}

try {
    $pdo->beginTransaction();

    $duplicateStatement = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR email = ?');
    $duplicateStatement->execute([$username, $email]);
    if ((int) $duplicateStatement->fetchColumn() > 0) {
        $pdo->rollBack();
        redirect_with_message('register.php', 'That username or email address is already registered.');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $statement = $pdo->prepare('INSERT INTO users (first_name, last_name, username, email, password) VALUES (?, ?, ?, ?, ?)');
    $statement->execute([$firstName, $lastName, $username, $email, $hash]);
    $newUserId = (int) $pdo->lastInsertId();

    $categoryStatement = $pdo->prepare('INSERT INTO user_categories (user_id, category_id) VALUES (?, ?)');
    foreach ($selectedCategories as $categoryId) {
        $categoryStatement->execute([$newUserId, (int) $categoryId]);
    }

    $pdo->commit();
    redirect_with_message('index.php', 'Account created. Please login.');
} catch (PDOException $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    redirect_with_message('register.php', 'Registration could not be completed. Check your details and try again.');
}
?>
