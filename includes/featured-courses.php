<?php
$featuredCourses = [];

if ($pdo) {
    $userId = is_logged_in() ? (int) $_SESSION['userid'] : 0;
    $userCourses = [];
    $userCategories = [];

    if ($userId > 0) {
        $bookedStatement = $pdo->prepare('SELECT course_id FROM bookings WHERE user_id = ?');
        $bookedStatement->execute([$userId]);
        $userCourses = array_map('intval', $bookedStatement->fetchAll(PDO::FETCH_COLUMN));

        $categoryStatement = $pdo->prepare('SELECT category_id FROM user_categories WHERE user_id = ?');
        $categoryStatement->execute([$userId]);
        $userCategories = array_map('intval', $categoryStatement->fetchAll(PDO::FETCH_COLUMN));
    }

    $statement = $pdo->prepare(
        'SELECT courses.*, categories.category_name,
                (SELECT COUNT(bookings.booking_id) FROM bookings WHERE bookings.course_id = courses.course_id) AS total_bookings
         FROM courses
         INNER JOIN categories ON courses.category_id = categories.category_id
         WHERE courses.date BETWEEN CURRENT_TIMESTAMP AND DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 3 MONTH)
         ORDER BY courses.date ASC'
    );
    $statement->execute();

    $preferred = [];
    $other = [];

    while ($course = $statement->fetch()) {
        if ((int) $course['total_bookings'] >= (int) $course['capacity']) {
            continue;
        }
        if (in_array((int) $course['course_id'], $userCourses, true)) {
            continue;
        }

        if ($userId > 0 && in_array((int) $course['category_id'], $userCategories, true)) {
            $course['subtitle'] = 'Recommended';
            $preferred[] = $course;
        } else {
            $course['subtitle'] = 'Starting soon!';
            $other[] = $course;
        }
    }

    usort($preferred, fn(array $a, array $b): int => strtotime($a['date']) <=> strtotime($b['date']));
    usort($other, fn(array $a, array $b): int => strtotime($a['date']) <=> strtotime($b['date']));
    $featuredCourses = array_slice(array_merge($preferred, $other), 0, 8);
}
?>
<section class="featured-courses" aria-labelledby="featured-heading">
  <h2 id="featured-heading">Featured Courses</h2>
  <div class="course-grid compact-grid">
    <?php foreach ($featuredCourses as $course): ?>
      <article class="course-card">
        <img src="uploads/<?php echo e($course['course_image']); ?>" alt="<?php echo e($course['name']); ?> course image">
        <div class="course-card-body">
          <h3><?php echo e($course['subtitle']); ?></h3>
          <h4><?php echo e($course['name']); ?></h4>
          <p class="tag"><?php echo e($course['category_name']); ?></p>
          <p><?php echo e($course['description']); ?></p>
          <p class="course_date"><?php echo e(format_course_date($course['date'])); ?></p>
          <a href="course.php?id=<?php echo e($course['course_id']); ?>">Read more</a>
        </div>
      </article>
    <?php endforeach; ?>
    <?php if (!$featuredCourses): ?>
      <p class="notice">No recommended courses are available in the next three months.</p>
    <?php endif; ?>
  </div>
</section>
