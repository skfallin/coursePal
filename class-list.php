<?php
$pageTitle = "Course Pal - Class List";
$activePage = "admin";
require "includes/db.php";
require "includes/auth.php";
require "includes/course_helpers.php";

require_admin();

$courseId = (int) ($_GET["id"] ?? 0);
$course = $pdo && $courseId ? get_course($pdo, $courseId) : null;
$attendees = [];

if ($pdo && $course) {
    $statement = $pdo->prepare("
        SELECT users.user_id, users.first_name, users.last_name, users.email, bookings.booking_date
        FROM bookings
        INNER JOIN users ON users.user_id = bookings.user_id
        WHERE bookings.course_id = ?
        ORDER BY bookings.booking_date DESC
    ");
    $statement->execute([$courseId]);
    $attendees = $statement->fetchAll(PDO::FETCH_ASSOC);
}

include "includes/header.php";
?>
<section>
  <?php if ($course): ?>
    <h1>Class list</h1>
    <h2>Class list for <?php echo htmlspecialchars($course["name"]); ?> on <?php echo htmlspecialchars(format_course_date($course["date"])); ?></h2>
    <table>
      <tr>
        <th>User ID</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Email address</th>
        <th>Booking date</th>
      </tr>
      <?php foreach ($attendees as $attendee): ?>
        <tr>
          <td><?php echo htmlspecialchars($attendee["user_id"]); ?></td>
          <td><?php echo htmlspecialchars($attendee["first_name"]); ?></td>
          <td><?php echo htmlspecialchars($attendee["last_name"]); ?></td>
          <td><?php echo htmlspecialchars($attendee["email"]); ?></td>
          <td><?php echo htmlspecialchars(format_course_date($attendee["booking_date"])); ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
    <a class="btn" href="admin.php">Back to admin</a>
  <?php else: ?>
    <h1>Course not found</h1>
    <a class="btn" href="admin.php">Back to admin</a>
  <?php endif; ?>
</section>
<?php include "includes/footer.php"; ?>
