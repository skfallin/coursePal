<?php
require 'includes/connectdb.php';
require_admin();
$pageTitle = 'Course Pal - Add Course';
$activePage = 'admin';
$categories = $pdo ? get_all_categories($pdo) : [];
$message = trim($_GET['msg'] ?? '');
require 'includes/header.php';
?>
<section class="narrow">
  <h1>Add Course</h1>
  <?php if ($message): ?><p class="notice"><?php echo e($message); ?></p><?php endif; ?>

  <form class="panel" method="post" action="course-add-action.php" enctype="multipart/form-data">
    <label for="name">Course Name</label>
    <input id="name" name="name" type="text" required maxlength="150">

    <label for="category_id">Category</label>
    <select id="category_id" name="category_id" required>
      <option value="">Choose a category</option>
      <?php foreach ($categories as $category): ?>
        <option value="<?php echo e($category['category_id']); ?>"><?php echo e($category['category_name']); ?></option>
      <?php endforeach; ?>
    </select>

    <label for="description">Course Description</label>
    <textarea id="description" name="description" rows="6" required></textarea>

    <label for="date">Date</label>
    <input id="date" name="date" type="datetime-local" required>

    <label for="capacity">Capacity</label>
    <input id="capacity" name="capacity" type="number" min="1" max="50" required>

    <label for="courseimage">Image</label>
    <input id="courseimage" name="courseimage" type="file" accept=".jpg,.jpeg,.png,.gif">

    <button class="btn" type="submit">Save Course</button>
    <a class="btn btn_secondary" href="admin.php">Cancel</a>
  </form>
</section>
<?php require 'includes/footer.php'; ?>
