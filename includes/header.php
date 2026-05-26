<?php
$pageTitle = $pageTitle ?? 'Course Pal';
$activePage = $activePage ?? '';
$isLoggedIn = is_logged_in();
$isAdmin = is_admin_user();
$welcomeName = $isLoggedIn ? ($_SESSION['username'] ?? 'user') : 'username';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?></title>
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
    <header>
      <div class="header-left">
        <a href="index.php" class="logolink" aria-label="Course Pal home">
          <img src="images/logo.svg" width="100" alt="Course Pal logo">
          <span class="sitename">Course Pal</span>
        </a>
      </div>
      <div class="header-right">
        <p class="welcome">Welcome <?php echo e($welcomeName); ?></p>
        <nav aria-label="Main navigation">
          <ul>
            <?php if ($isLoggedIn): ?>
              <li><a class="<?php echo $activePage === 'courses' ? 'active' : ''; ?>" href="courses.php">All Courses</a></li>
              <li><a class="<?php echo $activePage === 'home' ? 'active' : ''; ?>" href="index.php">Home</a></li>
              <li><a class="<?php echo $activePage === 'account' ? 'active' : ''; ?>" href="account.php">My Account</a></li>
              <li><a href="logout.php">Logout</a></li>
              <?php if ($isAdmin): ?>
                <li><a class="<?php echo $activePage === 'admin' ? 'active' : ''; ?>" href="admin.php">Admin</a></li>
                <li><a class="<?php echo $activePage === 'reports' ? 'active' : ''; ?>" href="reports.php">Reports</a></li>
              <?php endif; ?>
            <?php else: ?>
              <li><a class="<?php echo $activePage === 'courses' ? 'active' : ''; ?>" href="courses.php">All Courses</a></li>
              <li><a class="<?php echo $activePage === 'home' ? 'active' : ''; ?>" href="index.php">Login</a></li>
              <li><a class="<?php echo $activePage === 'register' ? 'active' : ''; ?>" href="register.php">Register</a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </header>
    <main class="container">
      <?php if (!empty($dbError)): ?>
        <section class="notice" role="alert"><?php echo e($dbError); ?></section>
      <?php endif; ?>
