<?php
require 'includes/connectdb.php';
$pageTitle = 'Course Pal - Home';
$activePage = 'home';
$message = trim($_GET['msg'] ?? '');
require 'includes/header.php';
?>
<section class="homelayout">
  <article class="text panel">
    <h1>Welcome to Course Pal</h1>
    <p>Course Pal is a technology training company that runs courses on programming, software and web development, networking, cloud computing, cybersecurity, data science, design, IT support, artificial intelligence, and mobile app development.</p>
    <p>Use this website to browse available courses, create an account, book a place, and manage your current and past bookings.</p>

    <?php if ($message): ?>
      <h3 class="error"><?php echo e($message); ?></h3>
    <?php endif; ?>

    <?php if (!is_logged_in()): ?>
      <form class="login-form" method="post" action="login.php">
        <h2>Login</h2>
        <label for="username">Username</label>
        <input id="username" name="username" type="text" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <button class="btn" type="submit">Login</button>
        <a class="btn btn_secondary" href="register.php">Register</a>
      </form>
    <?php else: ?>
      <p class="notice">Welcome <?php echo e($_SESSION['username']); ?>. Choose a recommended course or visit your account.</p>
      <a class="btn" href="account.php">My Account</a>
    <?php endif; ?>
  </article>

  <aside class="image" aria-label="Recommended courses">
    <?php require 'includes/featured-courses.php'; ?>
  </aside>
</section>
<?php require 'includes/footer.php'; ?>
