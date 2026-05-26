<?php
require 'includes/connectdb.php';
require_login();

$courseId = (int) ($_GET['id'] ?? 0);

if (!$pdo || $courseId <= 0) {
    redirect_with_message('account.php', 'Booking not found.');
}

$statement = $pdo->prepare('DELETE FROM bookings WHERE user_id = ? AND course_id = ?');
$statement->execute([(int) $_SESSION['userid'], $courseId]);

if ($statement->rowCount() > 0) {
    redirect_with_message('account.php', 'Your booking has been cancelled.');
}

redirect_with_message('account.php', 'Booking not found or it does not belong to your account.');
?>
