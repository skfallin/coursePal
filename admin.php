<?php
$pageTitle = "Course Pal - Admin";
$activePage = "admin";
require "includes/db.php";
require "includes/auth.php";
require "includes/course_helpers.php";

require_admin();

$courses = [];
if ($pdo) {
    $statement = $pdo->query("
        SELECT courses.*, categories.category_name, COUNT(bookings.booking_id) AS num_bookings
        FROM courses
        INNER JOIN categories ON courses.category_id = categories.category_id
        LEFT JOIN bookings ON courses.course_id = bookings.course_id
        GROUP BY courses.course_id
        ORDER BY courses.date
    ");
    $courses = $statement->fetchAll(PDO::FETCH_ASSOC);
}

include "includes/header.php";
?>
<section>
  <div class="section-heading">
    <div>
      <h1>Course Admin</h1>
      <p>Browse the admin view of courses and manage course records.</p>
    </div>
    <div class="buttons">
      <a class="btn btn_admin" href="edit_course.php">Add course</a>
    </div>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>Course</th>
      <th>Category</th>
      <th>Date</th>
      <th>Capacity</th>
      <th>Bookings</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($courses as $course): ?>
      <tr>
        <td><?php echo htmlspecialchars($course["course_id"]); ?></td>
        <td><?php echo htmlspecialchars($course["name"]); ?></td>
        <td><?php echo htmlspecialchars($course["category_name"]); ?></td>
        <td><?php echo htmlspecialchars(format_course_date($course["date"])); ?></td>
        <td><?php echo htmlspecialchars($course["capacity"]); ?></td>
        <td><?php echo htmlspecialchars($course["num_bookings"]); ?></td>
        <td>
          <a class="btn" href="edit_course.php?id=<?php echo urlencode($course["course_id"]); ?>">Edit</a>
          <a class="btn btn_secondary" href="class-list.php?id=<?php echo urlencode($course["course_id"]); ?>">Class list</a>
          <a class="btn btn_cancel" href="delete_course.php?id=<?php echo urlencode($course["course_id"]); ?>">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>
<?php include "includes/footer.php"; ?>
