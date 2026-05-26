<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = $pageTitle ?? "Course Pal - find computing and tech courses easily";
$activePage = $activePage ?? "";
$userName = $_SESSION["username"] ?? "Guest";
$isLoggedIn = isset($_SESSION["user_id"]);
$isAdmin = ($_SESSION["is_admin"] ?? false) === true;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
    <header>
      <div class="header-left">
        <a href="index.php" class="logolink">
          <img src="images/logo.svg" width="100" alt="Course Pal Logo">
          <span class="sitename">Course Pal</span>
        </a>
      </div>
      <div class="header-right">
        <p class="welcome">Welcome <?php echo htmlspecialchars($userName); ?></p>
        <nav>
          <ul>
            <li><a class="<?php echo $activePage === "courses" ? "active" : ""; ?>" href="courses.php">All Courses</a></li>
            <?php if ($isLoggedIn): ?>
              <li><a class="<?php echo $activePage === "home" ? "active" : ""; ?>" href="index.php">Home</a></li>
              <li><a class="<?php echo $activePage === "account" ? "active" : ""; ?>" href="account.php">My Account</a></li>
              <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
              <li><a class="<?php echo $activePage === "home" ? "active" : ""; ?>" href="index.php">Login</a></li>
              <li><a class="<?php echo $activePage === "register" ? "active" : ""; ?>" href="register.php">Register</a></li>
            <?php endif; ?>
            <?php if ($isAdmin): ?>
              <li><a class="<?php echo $activePage === "admin" ? "active" : ""; ?>" href="admin.php">Admin</a></li>
              <li><a class="<?php echo $activePage === "reports" ? "active" : ""; ?>" href="reports.php">Reports</a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </header>
    <main class="container">
