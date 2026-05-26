<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Course Pal - My Account";
$activePage = "account";
require "includes/db.php";
require "includes/auth.php";
require "includes/course_helpers.php";

require_login();

$message = $_GET["message"] ?? "";
$user = ["username" => "", "first_name" => "", "last_name" => "", "email" => ""];
$bookings = [];

if ($pdo) {
    $userStatement = $pdo->prepare("SELECT username, first_name, last_name, email FROM users WHERE user_id = ?");
    $userStatement->execute([$_SESSION["user_id"]]);
    $user = $userStatement->fetch(PDO::FETCH_ASSOC) ?: $user;

    $statement = $pdo->prepare("
        SELECT bookings.booking_id, courses.course_id, courses.name, courses.date, bookings.booking_date
        FROM bookings
        INNER JOIN courses ON courses.course_id = bookings.course_id
        WHERE bookings.user_id = ?
        ORDER BY courses.date DESC
    ");
    $statement->execute([$_SESSION["user_id"]]);
    $bookings = $statement->fetchAll(PDO::FETCH_ASSOC);
}

include "includes/header.php";
?>
<section>
  <h1>My Account</h1>
  <p>View your details and the courses you have booked.</p>

  <?php if ($message === "booking_cancelled"): ?>
    <p class="notice">Your booking has been cancelled.</p>
  <?php endif; ?>

  <div class="layout">
    <article class="panel block">
      <h2>Profile</h2>
      <p><strong>Username:</strong> <?php echo htmlspecialchars($user["username"]); ?></p>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user["first_name"] . " " . $user["last_name"]); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user["email"]); ?></p>
      <a class="btn" href="update_account.php">Edit account details</a>
    </article>

    <article class="panel block">
      <h2>My Courses</h2>
      <table>
        <tr>
          <th>Course</th>
          <th>Course date</th>
          <th>Booking date</th>
          <th>Action</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
          <tr>
            <td><a href="course.php?id=<?php echo urlencode($booking["course_id"]); ?>"><?php echo htmlspecialchars($booking["name"]); ?></a></td>
            <td><?php echo htmlspecialchars(format_course_date($booking["date"])); ?></td>
            <td><?php echo htmlspecialchars(format_course_date($booking["booking_date"])); ?></td>
            <td><a class="btn btn_cancel" href="cancel_booking.php?id=<?php echo urlencode($booking["booking_id"]); ?>">Cancel</a></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </article>
  </div>
</section>
<?php include "includes/footer.php"; ?>
