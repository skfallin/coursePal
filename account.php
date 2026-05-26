<?php
require 'includes/connectdb.php';
require_login();
$pageTitle = 'Course Pal - My Account';
$activePage = 'account';
$message = trim($_GET['msg'] ?? '');
$user = null;
$bookings = [];

if ($pdo) {
    $userStatement = $pdo->prepare('SELECT user_id, first_name, last_name, username, email FROM users WHERE user_id = ?');
    $userStatement->execute([$_SESSION['userid']]);
    $user = $userStatement->fetch();

    $bookingStatement = $pdo->prepare(
        'SELECT bookings.booking_id, bookings.booking_date, courses.course_id, courses.name, courses.date
         FROM bookings
         INNER JOIN courses ON courses.course_id = bookings.course_id
         WHERE bookings.user_id = ?
         ORDER BY courses.date ASC'
    );
    $bookingStatement->execute([$_SESSION['userid']]);
    $bookings = $bookingStatement->fetchAll();
}

require 'includes/header.php';
?>
<section>
  <h1>My Account</h1>
  <?php if ($message): ?><p class="notice"><?php echo e($message); ?></p><?php endif; ?>

  <div class="layout">
    <article class="panel block">
      <h2>Account details</h2>
      <?php if ($user): ?>
        <p><strong>User ID:</strong> <?php echo e($user['user_id']); ?></p>
        <p><strong>Name:</strong> <?php echo e($user['first_name'] . ' ' . $user['last_name']); ?></p>
        <p><strong>Username:</strong> <?php echo e($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo e($user['email']); ?></p>
      <?php endif; ?>
      <a class="btn" href="edit-account.php">Edit account details</a>
    </article>

    <article class="panel block">
      <h2>Booked courses</h2>
      <p>You are currently booked on <?php echo e(count($bookings)); ?> courses.</p>
      <table>
        <tr>
          <th>Name</th>
          <th>Course date</th>
          <th>Booking date</th>
          <th>Actions</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
          <tr>
            <td><?php echo e($booking['name']); ?></td>
            <td><?php echo e(format_course_date($booking['date'])); ?></td>
            <td><?php echo e(format_course_date($booking['booking_date'])); ?></td>
            <td>
              <a class="btn" href="course.php?id=<?php echo e($booking['course_id']); ?>">Learn more</a>
              <a class="btn btn_cancel" href="cancel-booking-action.php?id=<?php echo e($booking['course_id']); ?>">Cancel booking</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </article>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
