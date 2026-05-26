<?php
require 'includes/connectdb.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: course-add.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$categoryId = (int) ($_POST['category_id'] ?? 0);
$date = $_POST['date'] ?? '';
$capacity = (int) ($_POST['capacity'] ?? 0);

if (!$pdo || $name === '' || $description === '' || $categoryId <= 0 || $date === '' || $capacity < 1 || $capacity > 50) {
    redirect_with_message('course-add.php', 'Complete all course fields. Capacity must be between 1 and 50.');
}

[$uploadOk, $courseImage, $uploadMessage] = save_course_image_upload('courseimage', 'html_and_css_for_beginners.jpg');
if (!$uploadOk) {
    redirect_with_message('course-add.php', $uploadMessage);
}

$statement = $pdo->prepare('INSERT INTO courses (name, description, category_id, capacity, date, course_image) VALUES (?, ?, ?, ?, ?, ?)');
$statement->execute([$name, $description, $categoryId, $capacity, $date, $courseImage]);
redirect_with_message('admin.php', trim($uploadMessage . ' Course added.'));
?>
