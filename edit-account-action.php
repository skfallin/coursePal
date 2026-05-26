<?php
require 'includes/connectdb.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: edit-account.php');
    exit;
}

$firstName = trim($_POST['firstname'] ?? '');
$lastName = trim($_POST['lastname'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$selectedCategories = $_POST['categories'] ?? [];
$userId = (int) $_SESSION['userid'];

if (!$pdo) {
    redirect_with_message('edit-account.php', 'Database connection is required to update account details.');
}
if ($firstName === '' || $lastName === '' || $username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_message('edit-account.php', 'All account fields must be completed with a valid email address.');
}
if ($password !== '' && strlen($password) < 8) {
    redirect_with_message('edit-account.php', 'New password must be at least 8 characters.');
}
if ($password !== '' && $password !== $password2) {
    redirect_with_message('edit-account.php', 'Password and repeat password must match.');
}
if (!$selectedCategories) {
    redirect_with_message('edit-account.php', 'Choose at least one preferred course category.');
}

try {
    $pdo->beginTransaction();

    $duplicateStatement = $pdo->prepare('SELECT COUNT(*) FROM users WHERE (username = ? OR email = ?) AND user_id <> ?');
    $duplicateStatement->execute([$username, $email, $userId]);
    if ((int) $duplicateStatement->fetchColumn() > 0) {
        $pdo->rollBack();
        redirect_with_message('edit-account.php', 'That username or email address is already used by another account.');
    }

    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $statement = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, username = ?, email = ?, password = ? WHERE user_id = ?');
        $statement->execute([$firstName, $lastName, $username, $email, $hash, $userId]);
    } else {
        $statement = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, username = ?, email = ? WHERE user_id = ?');
        $statement->execute([$firstName, $lastName, $username, $email, $userId]);
    }

    $deleteStatement = $pdo->prepare('DELETE FROM user_categories WHERE user_id = ?');
    $deleteStatement->execute([$userId]);
    $insertStatement = $pdo->prepare('INSERT INTO user_categories (user_id, category_id) VALUES (?, ?)');
    foreach ($selectedCategories as $categoryId) {
        $insertStatement->execute([$userId, (int) $categoryId]);
    }

    $_SESSION['username'] = $firstName;
    $_SESSION['login_username'] = $username;
    $pdo->commit();
    redirect_with_message('account.php', 'Account details updated.');
} catch (PDOException $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    redirect_with_message('edit-account.php', 'Account update failed. Please check your details.');
}
?>
