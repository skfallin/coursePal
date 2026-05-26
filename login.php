<?php
require 'includes/connectdb.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$pdo || $username === '' || $password === '') {
    redirect_with_message('index.php', 'Invalid name or password');
}

$statement = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$statement->execute([$username]);
$user = $statement->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    redirect_with_message('index.php', 'Invalid name or password');
}

$_SESSION['isLoggedIn'] = true;
$_SESSION['userid'] = (int) $user['user_id'];
$_SESSION['username'] = $user['first_name'];
$_SESSION['login_username'] = $user['username'];
$_SESSION['isAdmin'] = (int) $user['is_admin'];

header('Location: index.php');
exit;
?>
