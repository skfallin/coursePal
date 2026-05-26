<?php
$pageTitle = "Course Pal - Register";
$activePage = "register";
require "includes/db.php";
require "includes/course_helpers.php";

$message = "";
$categories = $pdo ? get_all_categories($pdo) : [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST["first_name"] ?? "");
    $lastName = trim($_POST["last_name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $passwordVerify = $_POST["password_verify"] ?? "";
    $selectedCategories = $_POST["categories"] ?? [];

    if (!$firstName || !$lastName || !$username || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Enter your first name, last name, username, and a valid email address.";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";
    } elseif ($password !== $passwordVerify) {
        $message = "Password and password verification must match.";
    } elseif (!$selectedCategories) {
        $message = "Choose at least one course category that interests you.";
    } elseif ($pdo) {
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $statement = $pdo->prepare("
                INSERT INTO users (username, password, first_name, last_name, email)
                VALUES (?, ?, ?, ?, ?)
            ");
            $statement->execute([$username, $passwordHash, $firstName, $lastName, $email]);
            $userId = (int) $pdo->lastInsertId();

            $categoryStatement = $pdo->prepare("INSERT INTO user_categories (user_id, category_id) VALUES (?, ?)");
            foreach ($selectedCategories as $categoryId) {
                $categoryStatement->execute([$userId, (int) $categoryId]);
            }

            $message = "Account created. You can now login.";
        } catch (PDOException $exception) {
            $message = "That username or email address is already registered.";
        }
    } else {
        $message = "Preview mode: form ready, but database is not connected yet.";
    }
}

include "includes/header.php";
?>
<section class="narrow">
  <h1>Register</h1>
  <p>Create an account and choose the course categories that interest you.</p>

  <?php if ($message): ?>
    <p class="notice"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <form class="panel" method="post" action="register.php">
    <label for="first_name">First name</label>
    <input id="first_name" name="first_name" type="text" required>

    <label for="last_name">Last name</label>
    <input id="last_name" name="last_name" type="text" required>

    <label for="username">Username</label>
    <input id="username" name="username" type="text" required>

    <label for="email">Email address</label>
    <input id="email" name="email" type="email" required>

    <label for="password">Password</label>
    <input id="password" name="password" type="password" minlength="8" required>

    <label for="password_verify">Password verification</label>
    <input id="password_verify" name="password_verify" type="password" minlength="8" required>

    <fieldset class="categories">
      <legend>Course categories</legend>
      <?php foreach ($categories as $category): ?>
        <label>
          <input type="checkbox" name="categories[]" value="<?php echo htmlspecialchars($category["category_id"]); ?>">
          <?php echo htmlspecialchars($category["category_name"]); ?>
        </label>
      <?php endforeach; ?>
    </fieldset>

    <button class="btn" type="submit">Register</button>
  </form>
</section>
<?php include "includes/footer.php"; ?>
