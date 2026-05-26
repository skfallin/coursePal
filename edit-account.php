<?php
require 'includes/connectdb.php';
require_login();
$pageTitle = 'Course Pal - Edit Account';
$activePage = 'account';
$message = trim($_GET['msg'] ?? '');
$user = ['first_name' => '', 'last_name' => '', 'username' => '', 'email' => ''];
$categories = $pdo ? get_all_categories($pdo) : [];
$selectedCategoryIds = [];

if ($pdo) {
    $statement = $pdo->prepare('SELECT first_name, last_name, username, email FROM users WHERE user_id = ?');
    $statement->execute([$_SESSION['userid']]);
    $user = $statement->fetch() ?: $user;

    $categoryStatement = $pdo->prepare('SELECT category_id FROM user_categories WHERE user_id = ?');
    $categoryStatement->execute([$_SESSION['userid']]);
    $selectedCategoryIds = array_map('intval', $categoryStatement->fetchAll(PDO::FETCH_COLUMN));
}

require 'includes/header.php';
?>
<section class="narrow">
  <h1>Edit Account</h1>
  <?php if ($message): ?><p class="notice"><?php echo e($message); ?></p><?php endif; ?>

  <form class="panel" method="post" action="edit-account-action.php">
    <label for="firstname">First Name</label>
    <input id="firstname" name="firstname" type="text" value="<?php echo e($user['first_name']); ?>" required maxlength="50">

    <label for="lastname">Last Name</label>
    <input id="lastname" name="lastname" type="text" value="<?php echo e($user['last_name']); ?>" required maxlength="50">

    <label for="username">UserName</label>
    <input id="username" name="username" type="text" value="<?php echo e($user['username']); ?>" required maxlength="50">

    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="<?php echo e($user['email']); ?>" required maxlength="255">

    <p>Leave password blank if you do not want to change it.</p>
    <label for="password">Password</label>
    <input id="password" name="password" type="password" minlength="8">

    <label for="password2">Repeat Password</label>
    <input id="password2" name="password2" type="password" minlength="8">

    <fieldset class="categories">
      <legend>Preferred course categories</legend>
      <?php foreach ($categories as $category): ?>
        <label>
          <input type="checkbox" name="categories[]" value="<?php echo e($category['category_id']); ?>" <?php echo in_array((int) $category['category_id'], $selectedCategoryIds, true) ? 'checked' : ''; ?>>
          <?php echo e($category['category_name']); ?>
        </label>
      <?php endforeach; ?>
    </fieldset>

    <button class="btn" type="submit">Update account</button>
    <a class="btn btn_secondary" href="account.php">Cancel</a>
  </form>
</section>
<?php require 'includes/footer.php'; ?>
