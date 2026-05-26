<?php
require 'includes/connectdb.php';
require_admin();
$pageTitle = 'Course Pal - Edit Course';
$activePage = 'admin';
$courseId = (int) ($_GET['id'] ?? 0);
$course = $pdo && $courseId > 0 ? get_course($pdo, $courseId) : null;
$categories = $pdo ? get_all_categories($pdo) : [];
$message = trim($_GET['msg'] ?? '');

if (!$course) {
    redirect_with_message('admin.php', 'Course not found.');
}

$dateValue = date('Y-m-d\TH:i', strtotime($course['date']));
require 'includes/header.php';
?>
<section class="narrow">
  <h1>Edit Course</h1>
  <?php if ($message): ?><p class="notice"><?php echo e($message); ?></p><?php endif; ?>

  <form class="panel" method="post" action="course-edit-action.php" enctype="multipart/form-data">
    <input type="hidden" name="course_id" value="<?php echo e($course['course_id']); ?>">
    <input type="hidden" name="current_image" value="<?php echo e($course['course_image']); ?>">

    <label for="name">Course Name</label>
    <input id="name" name="name" type="text" value="<?php echo e($course['name']); ?>" required maxlength="150">

    <label for="category_id">Category</label>
    <select id="category_id" name="category_id" required>
      <?php foreach ($categories as $category): ?>
        <option value="<?php echo e($category['category_id']); ?>" <?php echo (int) $category['category_id'] === (int) $course['category_id'] ? 'selected' : ''; ?>><?php echo e($category['category_name']); ?></option>
      <?php endforeach; ?>
    </select>

    <label for="description">Course Description</label>
    <textarea id="description" name="description" rows="6" required><?php echo e($course['description']); ?></textarea>

    <label for="date">Date</label>
    <input id="date" name="date" type="datetime-local" value="<?php echo e($dateValue); ?>" required>

    <label for="capacity">Capacity</label>
    <input id="capacity" name="capacity" type="number" min="1" max="50" value="<?php echo e($course['capacity']); ?>" required>

    <figure class="image-preview">
      <img src="uploads/<?php echo e($course['course_image']); ?>" alt="Current image for <?php echo e($course['name']); ?>">
      <figcaption>Current image: <?php echo e($course['course_image']); ?></figcaption>
    </figure>

    <label for="courseimage">Image</label>
    <input id="courseimage" name="courseimage" type="file" accept=".jpg,.jpeg,.png,.gif">

    <button class="btn" type="submit">Save Course</button>
    <a class="btn btn_secondary" href="admin.php">Cancel</a>
  </form>
</section>
<?php require 'includes/footer.php'; ?>
