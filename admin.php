<?php
require 'includes/connectdb.php';
require_admin();
$pageTitle = 'Course Pal - Admin';
$activePage = 'admin';

$search = trim($_GET['search'] ?? '');
$allowedColumns = [
    'course_id' => 'courses.course_id',
    'category_name' => 'categories.category_name',
    'name' => 'courses.name',
    'description' => 'courses.description',
    'total_bookings' => 'total_bookings',
    'capacity' => 'courses.capacity',
    'date' => 'courses.date',
];
$order = $_GET['order'] ?? 'date';
$sortOrder = $allowedColumns[$order] ?? 'courses.date';
$courses = [];

if ($pdo) {
    $sql = 'SELECT courses.*, categories.category_name,
                   COUNT(bookings.booking_id) AS total_bookings
            FROM courses
            INNER JOIN categories ON courses.category_id = categories.category_id
            LEFT JOIN bookings ON bookings.course_id = courses.course_id';
    $params = [];

    if ($search !== '') {
        $sql .= ' WHERE categories.category_name LIKE ? OR courses.name LIKE ? OR courses.description LIKE ?';
        $params = ["%{$search}%", "%{$search}%", "%{$search}%"];
    }

    $sql .= " GROUP BY courses.course_id, categories.category_name ORDER BY {$sortOrder}";
    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    $courses = $statement->fetchAll();
}

function admin_sort_link(string $label, string $order, string $search): string
{
    $query = http_build_query(array_filter(['order' => $order, 'search' => $search], fn($value) => $value !== ''));
    return '<a href="admin.php?' . e($query) . '">' . e($label) . '</a>';
}

$message = trim($_GET['msg'] ?? '');

require 'includes/header.php';
?>
<section>
  <?php if ($message): ?><p class="notice"><?php echo e($message); ?></p><?php endif; ?>
  <div class="section-heading">
    <div>
      <h1>Course Admin</h1>
      <p>Search, view, add, edit, and print class lists for courses.</p>
    </div>
    <a class="btn btn_admin" href="course-add.php">Add new course</a>
  </div>

  <form class="searchbox panel" method="get" action="admin.php">
    <label for="search">Search</label>
    <input id="search" name="search" type="text" value="<?php echo e($search); ?>">
    <button class="btn" type="submit">Search</button>
  </form>

  <table>
    <caption>Number of courses found: <?php echo e(count($courses)); ?></caption>
    <tr>
      <th><?php echo admin_sort_link('ID', 'course_id', $search); ?></th>
      <th><?php echo admin_sort_link('Category', 'category_name', $search); ?></th>
      <th>Image</th>
      <th><?php echo admin_sort_link('Course Name', 'name', $search); ?></th>
      <th><?php echo admin_sort_link('Description', 'description', $search); ?></th>
      <th><?php echo admin_sort_link('Bookings', 'total_bookings', $search); ?></th>
      <th><?php echo admin_sort_link('Capacity', 'capacity', $search); ?></th>
      <th><?php echo admin_sort_link('Date', 'date', $search); ?></th>
      <th>Actions</th>
    </tr>
    <?php foreach ($courses as $course): ?>
      <tr>
        <td><?php echo e($course['course_id']); ?></td>
        <td><?php echo e($course['category_name']); ?></td>
        <td><img class="thumb" src="uploads/<?php echo e($course['course_image']); ?>" alt="<?php echo e($course['name']); ?> course image"></td>
        <td><?php echo e($course['name']); ?></td>
        <td><?php echo e($course['description']); ?></td>
        <td><?php echo e($course['total_bookings']); ?></td>
        <td><?php echo e($course['capacity']); ?></td>
        <td><?php echo e(format_course_date($course['date'])); ?></td>
        <td class="actions">
          <a class="btn" href="course.php?id=<?php echo e($course['course_id']); ?>">View</a>
          <a class="btn btn_admin" href="course-edit.php?id=<?php echo e($course['course_id']); ?>">Edit</a>
          <a class="btn btn_secondary" href="class-list.php?id=<?php echo e($course['course_id']); ?>">Class List</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>
<?php require 'includes/footer.php'; ?>
