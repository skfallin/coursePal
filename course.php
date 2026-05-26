<?php
require 'includes/connectdb.php';
$pageTitle = 'Course Pal - Course Details';
$activePage = 'courses';
$courseId = (int) ($_GET['id'] ?? 0);
$course = $pdo && $courseId > 0 ? get_course($pdo, $courseId) : null;
require 'includes/header.php';
?>
<section>
  <?php if ($course): ?>
    <h1><?php echo e($course['name']); ?></h1>
    <article class="course-card-main panel">
      <img src="uploads/<?php echo e($course['course_image']); ?>" alt="<?php echo e($course['name']); ?> course image">
      <section class="course-details" aria-label="Course details">
        <p class="tag"><?php echo e($course['category_name']); ?></p>
        <h2>Course details</h2>
        <p><?php echo nl2br(e($course['description'])); ?></p>
        <dl>
          <div><dt>Date and time</dt><dd><?php echo e(format_course_date($course['date'])); ?></dd></div>
          <div><dt>Capacity</dt><dd><?php echo e($course['capacity']); ?></dd></div>
          <div><dt>Current bookings</dt><dd><?php echo e($course['total_bookings']); ?></dd></div>
        </dl>
        <a class="btn" href="book.php?id=<?php echo e($course['course_id']); ?>">Book on course</a>
        <a class="btn btn_secondary" href="courses.php">All courses</a>
      </section>
    </article>
  <?php else: ?>
    <h1>Course not found</h1>
    <p>The selected course ID does not exist.</p>
    <a class="btn" href="courses.php">All courses</a>
  <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
