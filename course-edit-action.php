<?php
require 'includes/connectdb.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

$courseId = (int) ($_POST['course_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$categoryId = (int) ($_POST['category_id'] ?? 0);
$date = $_POST['date'] ?? '';
$capacity = (int) ($_POST['capacity'] ?? 0);
$currentImage = trim($_POST['current_image'] ?? '');

if (!$pdo || $courseId <= 0 || $name === '' || $description === '' || $categoryId <= 0 || $date === '' || $capacity < 1 || $capacity > 50) {
    redirect_with_message('course-edit.php?id=' . $courseId, 'Complete all course fields. Capacity must be between 1 and 50.');
}

[$uploadOk, $courseImage, $uploadMessage] = save_course_image_upload('courseimage', $currentImage);
if (!$uploadOk) {
    redirect_with_message('course-edit.php?id=' . $courseId, $uploadMessage);
}

$statement = $pdo->prepare('UPDATE courses SET name = ?, description = ?, category_id = ?, capacity = ?, date = ?, course_image = ? WHERE course_id = ?');
$statement->execute([$name, $description, $categoryId, $capacity, $date, $courseImage, $courseId]);
redirect_with_message('admin.php', trim($uploadMessage . ' Course updated.'));
?>
