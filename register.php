<?php
require 'includes/connectdb.php';
$pageTitle = 'Course Pal - Register';
$activePage = 'register';
$categories = $pdo ? get_all_categories($pdo) : [];
$message = trim($_GET['msg'] ?? '');
require 'includes/header.php';
?>
<section class="narrow">
  <h1>Register</h1>
  <p>Create a Course Pal account and choose preferred course categories.</p>

  <?php if ($message): ?>
    <p class="notice"><?php echo e($message); ?></p>
  <?php endif; ?>

  <form class="panel" method="post" action="register-action.php">
    <label for="firstname">First Name</label>
    <input id="firstname" name="firstname" type="text" required maxlength="50">

    <label for="lastname">Last Name</label>
    <input id="lastname" name="lastname" type="text" required maxlength="50">

    <label for="username">UserName</label>
    <input id="username" name="username" type="text" required maxlength="50">

    <label for="email">Email</label>
    <input id="email" name="email" type="email" required maxlength="255">

    <label for="password">Password</label>
    <input id="password" name="password" type="password" required minlength="8">

    <label for="password2">Repeat Password</label>
    <input id="password2" name="password2" type="password" required minlength="8">

    <fieldset class="categories">
      <legend>Course Categories</legend>
      <?php foreach ($categories as $category): ?>
        <label>
          <input type="checkbox" name="categories[]" value="<?php echo e($category['category_id']); ?>">
          <?php echo e($category['category_name']); ?>
        </label>
      <?php endforeach; ?>
    </fieldset>

    <button class="btn" type="submit">Register</button>
  </form>
</section>
<?php require 'includes/footer.php'; ?>
