<?php
$pageTitle = "Course Pal - Courses";
$activePage = "courses";
require "includes/db.php";
require "includes/data.php";
require "includes/course_helpers.php";

$courses = $demoCourses;
$search = trim($_GET["search"] ?? "");
$allowedColumns = [
    "date" => "courses.date",
    "course_id" => "courses.course_id",
    "name" => "courses.name",
    "description" => "courses.description",
    "category_name" => "categories.category_name"
];
$order = $_GET["order"] ?? "date";
$sortOrder = $allowedColumns[$order] ?? "courses.date";

if ($pdo) {
    $sql = "
        SELECT courses.*, categories.category_name, COUNT(bookings.booking_id) AS num_bookings
        FROM courses
        INNER JOIN categories ON courses.category_id = categories.category_id
        LEFT JOIN bookings ON courses.course_id = bookings.course_id
    ";
    $params = [];

    if ($search !== "") {
        $sql .= " WHERE categories.category_name LIKE ? OR courses.name LIKE ? OR courses.description LIKE ?";
        $params = ["%$search%", "%$search%", "%$search%"];
    }

    $sql .= " GROUP BY courses.course_id ORDER BY $sortOrder";
    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    $courses = $statement->fetchAll(PDO::FETCH_ASSOC);
}

function sort_link(string $label, string $order, string $search): string
{
    $query = http_build_query(array_filter(["order" => $order, "search" => $search]));
    return "<a href=\"courses.php?$query\">$label</a>";
}

include "includes/header.php";
?>
<section>
  <div class="section-heading">
    <div>
      <h1>Courses List</h1>
      <p>Search the Course Pal catalogue and choose a course to view more details.</p>
    </div>
    <a class="btn" href="register.php">Register</a>
  </div>

  <div class="searchbox panel">
    <form method="get" action="courses.php">
      <label for="search">Search:</label>
      <input id="search" type="text" name="search" value="<?php echo htmlspecialchars($search); ?>">
      <button class="btn" type="submit">Search</button>
    </form>
  </div>

  <table class="course-table">
    <tr>
      <th><?php echo sort_link("ID", "course_id", $search); ?></th>
      <th><?php echo sort_link("Category", "category_name", $search); ?></th>
      <th><?php echo sort_link("Course Name", "name", $search); ?></th>
      <th><?php echo sort_link("Description", "description", $search); ?></th>
      <th><?php echo sort_link("Date", "date", $search); ?></th>
    </tr>
    <?php foreach ($courses as $course): ?>
      <tr>
        <td><?php echo htmlspecialchars($course["course_id"]); ?></td>
        <td><?php echo htmlspecialchars($course["category_name"]); ?></td>
        <td><?php echo htmlspecialchars($course["name"]); ?></td>
        <td><?php echo htmlspecialchars($course["description"]); ?></td>
        <td><?php echo htmlspecialchars(format_course_date($course["date"])); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <div class="course-grid">
    <?php foreach ($courses as $course): ?>
      <article class="course-card">
        <img src="uploads/<?php echo htmlspecialchars($course["course_image"]); ?>" alt="<?php echo htmlspecialchars($course["name"]); ?>">
        <div class="course-card-body">
          <p class="tag"><?php echo htmlspecialchars($course["category_name"]); ?></p>
          <h2><?php echo htmlspecialchars($course["name"]); ?></h2>
          <p><?php echo htmlspecialchars($course["description"]); ?></p>
          <p class="course_date">Date: <?php echo htmlspecialchars(format_course_date($course["date"])); ?></p>
          <p>Capacity: <?php echo htmlspecialchars($course["capacity"]); ?> | Bookings: <?php echo htmlspecialchars($course["num_bookings"] ?? 0); ?></p>
          <a class="btn" href="course.php?id=<?php echo urlencode($course["course_id"]); ?>">View course</a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>
<?php include "includes/footer.php"; ?>
