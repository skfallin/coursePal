<?php
require 'includes/connectdb.php';
require_admin();
$pageTitle = 'Course Pal - Class List';
$activePage = 'admin';
$courseId = (int) ($_GET['id'] ?? 0);
$course = $pdo && $courseId > 0 ? get_course($pdo, $courseId) : null;
$attendees = [];

if ($pdo && $course) {
    $statement = $pdo->prepare(
        'SELECT users.user_id, users.first_name, users.last_name, users.email, bookings.booking_date
         FROM bookings
         INNER JOIN users ON users.user_id = bookings.user_id
         WHERE bookings.course_id = ?
         ORDER BY bookings.booking_date DESC'
    );
    $statement->execute([$courseId]);
    $attendees = $statement->fetchAll();
}

require 'includes/header.php';
?>
<section class="printable">
  <?php if ($course): ?>
    <div class="section-heading no-print">
      <div>
        <h1>Class List</h1>
        <p>Printable register for the selected course.</p>
      </div>
      <button class="btn" type="button" onclick="window.print()">Print</button>
    </div>

    <h2>Class List for <?php echo e($course['name']); ?> on <?php echo e(format_course_date($course['date'])); ?></h2>
    <p>Number of students booked: <?php echo e(count($attendees)); ?></p>

    <?php if (!$attendees): ?>
      <p class="notice">No users are booked on this course yet.</p>
    <?php else: ?>
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
            <td><?php echo e($attendee['user_id']); ?></td>
            <td><?php echo e($attendee['first_name']); ?></td>
            <td><?php echo e($attendee['last_name']); ?></td>
            <td><?php echo e($attendee['email']); ?></td>
            <td><?php echo e(format_course_date($attendee['booking_date'])); ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
    <a class="btn no-print" href="admin.php">Back to admin</a>
  <?php else: ?>
    <h1>Course not found</h1>
    <a class="btn" href="admin.php">Back to admin</a>
  <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
