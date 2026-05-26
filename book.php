<?php
require 'includes/connectdb.php';
$pageTitle = 'Course Pal - Booking';
$activePage = 'courses';
$courseId = (int) ($_GET['id'] ?? 0);
$message = '';
$course = $pdo && $courseId > 0 ? get_course($pdo, $courseId) : null;

if (!is_logged_in()) {
    redirect_with_message('index.php', 'Please login or register to book this course.');
}

if (!$pdo || !$course) {
    $message = 'Booking unsuccessful. Course not found.';
} else {
    $userId = (int) $_SESSION['userid'];

    $existingStatement = $pdo->prepare('SELECT COUNT(*) FROM bookings WHERE user_id = ? AND course_id = ?');
    $existingStatement->execute([$userId, $courseId]);

    if ((int) $existingStatement->fetchColumn() > 0) {
        $message = 'Booking unsuccessful - you have already booked this course.';
    } elseif ((int) $course['total_bookings'] >= (int) $course['capacity']) {
        $message = "Booking unsuccessful - this course cannot be booked as it's full.";
    } else {
        $insertStatement = $pdo->prepare('INSERT INTO bookings (user_id, course_id, booking_date) VALUES (?, ?, CURRENT_TIMESTAMP)');
        $insertStatement->execute([$userId, $courseId]);
        $message = 'Booking created successfully.';
        $course = get_course($pdo, $courseId);
    }
}

require 'includes/header.php';
?>
<section class="narrow">
  <h1>Booking</h1>
  <article class="panel">
    <p class="notice"><?php echo e($message); ?></p>
    <?php if ($course): ?>
      <h2><?php echo e($course['name']); ?></h2>
      <p>Date: <?php echo e(format_course_date($course['date'])); ?></p>
      <p>Bookings: <?php echo e($course['total_bookings']); ?> / <?php echo e($course['capacity']); ?></p>
    <?php endif; ?>
    <a class="btn" href="account.php">My Account</a>
    <?php if ($course): ?><a class="btn btn_secondary" href="course.php?id=<?php echo e($courseId); ?>">Back to course</a><?php endif; ?>
  </article>
</section>
<?php require 'includes/footer.php'; ?>
