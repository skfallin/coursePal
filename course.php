<?php
$pageTitle = "Course Pal - Course Details";
$activePage = "courses";
require "includes/db.php";
require "includes/data.php";
require "includes/course_helpers.php";

$courseId = (int) ($_GET["id"] ?? 0);
$course = null;

foreach ($demoCourses as $demoCourse) {
    if ((int) $demoCourse["course_id"] === $courseId) {
        $course = $demoCourse;
    }
}

if ($pdo && $courseId) {
    $course = get_course($pdo, $courseId);
}

include "includes/header.php";
?>
<section>
  <?php if ($course): ?>
    <h1>Course Details</h1>
    <article class="course-card-main panel">
      <img src="uploads/<?php echo htmlspecialchars($course["course_image"]); ?>" alt="<?php echo htmlspecialchars($course["name"]); ?>">
      <div class="course-details">
        <p class="tag"><?php echo htmlspecialchars($course["category_name"]); ?></p>
        <h2><?php echo htmlspecialchars($course["name"]); ?></h2>
        <p><?php echo htmlspecialchars($course["description"]); ?></p>
        <p class="course_date">Date: <?php echo htmlspecialchars(format_course_date($course["date"])); ?></p>
        <p>Capacity: <?php echo htmlspecialchars($course["capacity"]); ?></p>
        <p>Current bookings: <?php echo htmlspecialchars($course["num_bookings"]); ?></p>
        <a class="btn" href="book.php?id=<?php echo urlencode($course["course_id"]); ?>">Book on course</a>
        <a class="btn btn_secondary" href="courses.php">All courses</a>
      </div>
    </article>
  <?php else: ?>
    <h1>Course not found</h1>
    <p>The selected course could not be found.</p>
    <a class="btn" href="courses.php">All courses</a>
  <?php endif; ?>
</section>
<?php include "includes/footer.php"; ?>
