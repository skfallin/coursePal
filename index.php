<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Course Pal - Home";
$activePage = "home";
require "includes/db.php";
require "includes/data.php";
require "includes/course_helpers.php";

$message = $_GET["message"] ?? "";
if ($message === "login_required") {
    $message = "Please login before using that page.";
}
if ($message === "admin_required") {
    $message = "Administrators only. Please login with an administrator account.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($pdo) {
        $statement = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $statement->execute([$username]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["first_name"] = $user["first_name"];
            $_SESSION["is_admin"] = (bool) $user["is_admin"];
            header("Location: account.php");
            exit;
        }
        $message = "Invalid name or password.";
    } else {
        $message = "Database not connected yet.";
    }
}

function recommended_courses(?PDO $pdo = null, array $demoCourses = []): array
{
    if (!$pdo) {
        return array_slice($demoCourses, 0, 8);
    }

    $userId = $_SESSION["user_id"] ?? null;
    $preferredIds = [];
    $bookedIds = [];

    if ($userId) {
        $preferredStatement = $pdo->prepare("SELECT category_id FROM user_categories WHERE user_id = ?");
        $preferredStatement->execute([$userId]);
        $preferredIds = array_map("intval", $preferredStatement->fetchAll(PDO::FETCH_COLUMN));

        $bookedStatement = $pdo->prepare("SELECT course_id FROM bookings WHERE user_id = ?");
        $bookedStatement->execute([$userId]);
        $bookedIds = array_map("intval", $bookedStatement->fetchAll(PDO::FETCH_COLUMN));
    }

    $statement = $pdo->query("
        SELECT courses.*, categories.category_name, COUNT(bookings.booking_id) AS num_bookings
        FROM courses
        INNER JOIN categories ON courses.category_id = categories.category_id
        LEFT JOIN bookings ON courses.course_id = bookings.course_id
        WHERE courses.date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
        GROUP BY courses.course_id
        HAVING num_bookings < courses.capacity
        ORDER BY courses.date
    ");
    $allCourses = $statement->fetchAll(PDO::FETCH_ASSOC);
    $preferred = [];
    $other = [];

    foreach ($allCourses as $course) {
        if (in_array((int) $course["course_id"], $bookedIds, true)) {
            continue;
        }
        if (in_array((int) $course["category_id"], $preferredIds, true)) {
            $course["subtitle"] = "Recommended";
            $preferred[] = $course;
        } else {
            $course["subtitle"] = "Starting soon!";
            $other[] = $course;
        }
    }

    usort($preferred, fn($a, $b) => strtotime($a["date"]) - strtotime($b["date"]));
    usort($other, fn($a, $b) => strtotime($a["date"]) - strtotime($b["date"]));
    return array_slice(array_merge($preferred, $other), 0, 8);
}

$featuredCourses = recommended_courses($pdo, $demoCourses);
include "includes/header.php";
?>
<section class="homelayout">
  <div class="text">
    <h1>Welcome to Course Pal</h1>
    <p>Unlock your future with hands-on tech training. Course Pal runs courses in programming, software and web development, networking, cybersecurity, cloud computing, data science, and design.</p>
    <p>Browse the catalogue, register for an account, book a place, and manage your courses from your account page.</p>

    <?php if (!isset($_SESSION["user_id"])): ?>
      <div class="panel">
        <h2>Login</h2>
        <?php if ($message): ?>
          <p class="error"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post" action="index.php">
          <label for="username">Username</label>
          <input id="username" name="username" type="text" required>

          <label for="password">Password</label>
          <input id="password" name="password" type="password" required>

          <button class="btn" type="submit">Login</button>
          <a class="btn btn_secondary" href="register.php">Register</a>
        </form>
      </div>
    <?php else: ?>
      <p class="notice">Welcome <?php echo htmlspecialchars($_SESSION["first_name"] ?? $_SESSION["username"]); ?>.</p>
    <?php endif; ?>
  </div>

  <div class="image">
    <h2>Featured Courses</h2>
    <div class="stack">
      <?php foreach ($featuredCourses as $course): ?>
        <article class="mini-course">
          <img src="uploads/<?php echo htmlspecialchars($course["course_image"]); ?>" alt="<?php echo htmlspecialchars($course["name"]); ?>">
          <div>
            <h3><?php echo htmlspecialchars($course["name"]); ?></h3>
            <p><strong><?php echo htmlspecialchars($course["subtitle"] ?? "Starting soon!"); ?></strong></p>
            <p><?php echo htmlspecialchars($course["category_name"]); ?> - <?php echo htmlspecialchars(format_course_date($course["date"])); ?></p>
            <p><?php echo htmlspecialchars($course["description"]); ?></p>
            <a href="course.php?id=<?php echo urlencode($course["course_id"]); ?>">Read more</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include "includes/footer.php"; ?>
