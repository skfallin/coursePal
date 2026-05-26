<?php
$pageTitle = "Course Pal - Edit Course";
$activePage = "admin";
require "includes/db.php";
require "includes/auth.php";
require "includes/course_helpers.php";

require_admin();

$courseId = (int) ($_GET["id"] ?? 0);
$message = "";
$categories = $pdo ? get_all_categories($pdo) : [];
$course = [
    "name" => "",
    "description" => "",
    "category_id" => "",
    "capacity" => "",
    "date" => "",
    "course_image" => "html_and_css_for_beginners.jpg"
];

if ($pdo && $courseId > 0) {
    $existingCourse = get_course($pdo, $courseId);
    if ($existingCourse) {
        $course = $existingCourse;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $categoryId = (int) ($_POST["category_id"] ?? 0);
    $capacity = (int) ($_POST["capacity"] ?? 0);
    $date = $_POST["date"] ?? "";
    $courseImage = trim($_POST["course_image"] ?? "");

    if (!$name || !$description || !$categoryId || $capacity < 1 || !$date || !$courseImage) {
        $message = "Complete all fields. Capacity must be at least 1.";
    } elseif ($pdo) {
        if ($courseId > 0) {
            $statement = $pdo->prepare("
                UPDATE courses
                SET name = ?, description = ?, category_id = ?, capacity = ?, date = ?, course_image = ?
                WHERE course_id = ?
            ");
            $statement->execute([$name, $description, $categoryId, $capacity, $date, $courseImage, $courseId]);
        } else {
            $statement = $pdo->prepare("
                INSERT INTO courses (name, description, category_id, capacity, date, course_image)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $statement->execute([$name, $description, $categoryId, $capacity, $date, $courseImage]);
        }
        header("Location: admin.php");
        exit;
    }
}

$dateValue = $course["date"] ? date("Y-m-d\\TH:i", strtotime($course["date"])) : "";
include "includes/header.php";
?>
<section class="narrow">
  <h1><?php echo $courseId > 0 ? "Edit course" : "Add course"; ?></h1>
  <?php if ($message): ?>
    <p class="notice"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <form class="panel" method="post" action="edit_course.php<?php echo $courseId > 0 ? "?id=" . urlencode($courseId) : ""; ?>">
    <label for="name">Course Name</label>
    <input id="name" name="name" type="text" value="<?php echo htmlspecialchars($course["name"]); ?>" required>

    <label for="category_id">Category</label>
    <select id="category_id" name="category_id" required>
      <option value="">Choose a category</option>
      <?php foreach ($categories as $category): ?>
        <option value="<?php echo htmlspecialchars($category["category_id"]); ?>" <?php echo (int) $course["category_id"] === (int) $category["category_id"] ? "selected" : ""; ?>>
          <?php echo htmlspecialchars($category["category_name"]); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="description">Course Description</label>
    <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($course["description"]); ?></textarea>

    <label for="date">Date</label>
    <input id="date" name="date" type="datetime-local" value="<?php echo htmlspecialchars($dateValue); ?>" required>

    <label for="capacity">Capacity</label>
    <input id="capacity" name="capacity" type="number" min="1" value="<?php echo htmlspecialchars($course["capacity"]); ?>" required>

    <label for="course_image">Image</label>
    <input id="course_image" name="course_image" type="text" value="<?php echo htmlspecialchars($course["course_image"]); ?>" required>

    <button class="btn" type="submit">Save Course</button>
    <a class="btn btn_secondary" href="admin.php">Cancel</a>
  </form>
</section>
<?php include "includes/footer.php"; ?>
