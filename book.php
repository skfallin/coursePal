<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Course Pal - Book Course";
$activePage = "courses";
require "includes/db.php";
require "includes/auth.php";
require "includes/course_helpers.php";

$courseId = (int) ($_GET["id"] ?? 0);
$course = $pdo && $courseId ? get_course($pdo, $courseId) : null;
$message = "";

if (!is_logged_in()) {
    $message = "You must log in or register before you can book a course.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!is_logged_in()) {
        $message = "You must log in or register before you can book a course.";
    } elseif (!$course) {
        $message = "Course not found.";
    } elseif ((int) $course["num_bookings"] >= (int) $course["capacity"]) {
        $message = "Booking unsuccessful. This course is fully booked.";
    } elseif (is_user_booked_on_course($pdo, (int) $_SESSION["user_id"], $courseId)) {
        $message = "Booking unsuccessful. You are already booked on this course.";
    } else {
        $statement = $pdo->prepare("INSERT INTO bookings (user_id, course_id) VALUES (?, ?)");
        $statement->execute([$_SESSION["user_id"], $courseId]);
        $message = "Booking successful. Your booking has been added.";
        $course = get_course($pdo, $courseId);
    }
}

include "includes/header.php";
?>
<section class="narrow">
  <?php if ($course): ?>
    <h1>Book on course</h1>
    <article class="panel">
      <h2><?php echo htmlspecialchars($course["name"]); ?></h2>
      <p><?php echo htmlspecialchars($course["description"]); ?></p>
      <p>Date: <?php echo htmlspecialchars(format_course_date($course["date"])); ?></p>
      <p>Capacity: <?php echo htmlspecialchars($course["capacity"]); ?> | Current bookings: <?php echo htmlspecialchars($course["num_bookings"]); ?></p>
      <?php if ($message): ?>
        <p class="notice"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>
      <?php if (is_logged_in()): ?>
        <form method="post" action="book.php?id=<?php echo urlencode($courseId); ?>">
          <button class="btn" type="submit">Confirm booking</button>
          <a class="btn btn_secondary" href="course.php?id=<?php echo urlencode($courseId); ?>">Back to course</a>
        </form>
      <?php else: ?>
        <a class="btn" href="index.php">Login</a>
        <a class="btn btn_secondary" href="register.php">Register</a>
      <?php endif; ?>
    </article>
  <?php else: ?>
    <h1>Course not found</h1>
    <p>The selected course could not be found.</p>
  <?php endif; ?>
</section>
<?php include "includes/footer.php"; ?>
