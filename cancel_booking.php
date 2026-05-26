<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Course Pal - Cancel Booking";
$activePage = "account";
require "includes/db.php";
require "includes/auth.php";
require "includes/course_helpers.php";

require_login();

$bookingId = (int) ($_GET["id"] ?? $_POST["id"] ?? 0);
$booking = null;

if ($pdo && $bookingId) {
    $statement = $pdo->prepare("
        SELECT bookings.booking_id, courses.name, courses.date
        FROM bookings
        INNER JOIN courses ON courses.course_id = bookings.course_id
        WHERE bookings.booking_id = ? AND bookings.user_id = ?
    ");
    $statement->execute([$bookingId, $_SESSION["user_id"]]);
    $booking = $statement->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $pdo && $booking) {
    $statement = $pdo->prepare("DELETE FROM bookings WHERE booking_id = ? AND user_id = ?");
    $statement->execute([$bookingId, $_SESSION["user_id"]]);
    header("Location: account.php?message=booking_cancelled");
    exit;
}

include "includes/header.php";
?>
<section class="narrow">
  <h1>Cancel booking</h1>
  <?php if ($booking): ?>
    <article class="panel">
      <p>Cancel your booking for <strong><?php echo htmlspecialchars($booking["name"]); ?></strong> on <?php echo htmlspecialchars(format_course_date($booking["date"])); ?>?</p>
      <form method="post" action="cancel_booking.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($bookingId); ?>">
        <button class="btn btn_cancel" type="submit">Cancel booking</button>
        <a class="btn btn_secondary" href="account.php">Keep booking</a>
      </form>
    </article>
  <?php else: ?>
    <p class="notice">Booking not found.</p>
    <a class="btn" href="account.php">Back to account</a>
  <?php endif; ?>
</section>
<?php include "includes/footer.php"; ?>
