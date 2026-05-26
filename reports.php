<?php
$pageTitle = "Course Pal - Reports";
$activePage = "reports";
require "includes/db.php";
require "includes/auth.php";

require_admin();

$popularCourses = [];
if ($pdo) {
    $popularCourses = $pdo->query("
        SELECT courses.name, categories.category_name, COUNT(bookings.booking_id) AS total_bookings
        FROM courses
        INNER JOIN categories ON courses.category_id = categories.category_id
        LEFT JOIN bookings ON courses.course_id = bookings.course_id
        GROUP BY courses.course_id
        ORDER BY total_bookings DESC, courses.date
    ")->fetchAll(PDO::FETCH_ASSOC);
}

$maxBookings = 1;
foreach ($popularCourses as $course) {
    $maxBookings = max($maxBookings, (int) $course["total_bookings"]);
}

include "includes/header.php";
?>
<section>
  <h1>Reports</h1>
  <p>Most popular courses, sorted by booking number. Categories are highlighted by colour.</p>

  <div class="chart">
    <?php foreach ($popularCourses as $course): ?>
      <div class="chart-row">
        <span><?php echo htmlspecialchars($course["name"]); ?></span>
        <div class="chart-cell category-<?php echo preg_replace('/[^a-z0-9]+/', '-', strtolower($course["category_name"])); ?>">
          <progress max="<?php echo htmlspecialchars((string) $maxBookings); ?>" value="<?php echo htmlspecialchars($course["total_bookings"]); ?>"></progress>
          <strong><?php echo htmlspecialchars($course["total_bookings"]); ?></strong>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <table>
    <tr>
      <th>Course</th>
      <th>Category</th>
      <th>Bookings</th>
    </tr>
    <?php foreach ($popularCourses as $course): ?>
      <tr>
        <td><?php echo htmlspecialchars($course["name"]); ?></td>
        <td><?php echo htmlspecialchars($course["category_name"]); ?></td>
        <td><?php echo htmlspecialchars($course["total_bookings"]); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>
<?php include "includes/footer.php"; ?>
