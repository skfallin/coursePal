<?php
require 'includes/connectdb.php';
$pageTitle = 'Course Pal - All Courses';
$activePage = 'courses';

$search = trim($_GET['search'] ?? '');
$allowedColumns = [
    'course_id' => 'courses.course_id',
    'category_name' => 'categories.category_name',
    'name' => 'courses.name',
    'description' => 'courses.description',
    'date' => 'courses.date',
];
$order = $_GET['order'] ?? 'date';
$sortOrder = $allowedColumns[$order] ?? 'courses.date';
$courses = [];

if ($pdo) {
    $sql = 'SELECT courses.*, categories.category_name,
                   (SELECT COUNT(bookings.booking_id) FROM bookings WHERE bookings.course_id = courses.course_id) AS total_bookings
            FROM courses
            INNER JOIN categories ON courses.category_id = categories.category_id';
    $params = [];

    if ($search !== '') {
        $sql .= ' WHERE categories.category_name LIKE ? OR courses.name LIKE ? OR courses.description LIKE ?';
        $params = ["%{$search}%", "%{$search}%", "%{$search}%"];
    }

    $sql .= " ORDER BY {$sortOrder}";
    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    $courses = $statement->fetchAll();
}

function sort_link(string $label, string $order, string $search): string
{
    $query = http_build_query(array_filter(['order' => $order, 'search' => $search], fn($value) => $value !== ''));
    return '<a href="courses.php?' . e($query) . '">' . e($label) . '</a>';
}

require 'includes/header.php';
?>
<section>
  <h1>Courses List</h1>
  <p>Browse every Course Pal course. Use the search box or select a column heading to change the order.</p>

  <form class="searchbox panel" method="get" action="courses.php">
    <label for="search">Search</label>
    <input id="search" type="text" name="search" value="<?php echo e($search); ?>">
    <button class="btn" type="submit">Search</button>
  </form>

  <table class="course-table">
    <caption>Number of courses found: <?php echo e(count($courses)); ?></caption>
    <tr>
      <th><?php echo sort_link('ID', 'course_id', $search); ?></th>
      <th><?php echo sort_link('Category', 'category_name', $search); ?></th>
      <th><?php echo sort_link('Course Name', 'name', $search); ?></th>
      <th><?php echo sort_link('Description', 'description', $search); ?></th>
      <th><?php echo sort_link('Date', 'date', $search); ?></th>
    </tr>
    <?php foreach ($courses as $course): ?>
      <tr>
        <td><a href="course.php?id=<?php echo e($course['course_id']); ?>"><?php echo e($course['course_id']); ?></a></td>
        <td><?php echo e($course['category_name']); ?></td>
        <td><?php echo e($course['name']); ?></td>
        <td><?php echo e($course['description']); ?></td>
        <td><?php echo e(format_course_date($course['date'])); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <div class="course-grid">
    <?php foreach ($courses as $course): ?>
      <article class="course-card">
        <a href="course.php?id=<?php echo e($course['course_id']); ?>">
          <img src="uploads/<?php echo e($course['course_image']); ?>" alt="<?php echo e($course['name']); ?> course image">
        </a>
        <div class="course-card-body">
          <p class="tag"><?php echo e($course['category_name']); ?></p>
          <h2><a href="course.php?id=<?php echo e($course['course_id']); ?>"><?php echo e($course['name']); ?></a></h2>
          <p><?php echo e($course['description']); ?></p>
          <p class="course_date">Date: <?php echo e(format_course_date($course['date'])); ?></p>
          <p>Capacity: <?php echo e($course['capacity']); ?> | Bookings: <?php echo e($course['total_bookings']); ?></p>
          <a class="btn" href="course.php?id=<?php echo e($course['course_id']); ?>">Read more</a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
