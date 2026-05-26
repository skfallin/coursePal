<?php
$pageTitle = "Course Pal - Delete Course";
$activePage = "admin";
require "includes/db.php";
require "includes/auth.php";
require "includes/course_helpers.php";

require_admin();

$courseId = (int) ($_GET["id"] ?? $_POST["id"] ?? 0);
$course = $pdo && $courseId ? get_course($pdo, $courseId) : null;
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $pdo && $course) {
    if ((int) $course["num_bookings"] > 0) {
        $message = "This course has live bookings and cannot be deleted.";
    } else {
        $statement = $pdo->prepare("DELETE FROM courses WHERE course_id = ?");
        $statement->execute([$courseId]);
        header("Location: admin.php");
        exit;
    }
}

include "includes/header.php";
?>
<section class="narrow">
  <h1>Delete course</h1>
  <?php if ($message): ?>
    <p class="notice"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>
  <?php if ($course): ?>
    <article class="panel">
      <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($course["name"]); ?></strong>?</p>
      <form method="post" action="delete_course.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($courseId); ?>">
        <button class="btn btn_cancel" type="submit">Delete course</button>
        <a class="btn btn_secondary" href="admin.php">Cancel</a>
      </form>
    </article>
  <?php else: ?>
    <p class="notice">Course not found.</p>
    <a class="btn" href="admin.php">Back to admin</a>
  <?php endif; ?>
</section>
<?php include "includes/footer.php"; ?>
