<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Course Pal - Update Account";
$activePage = "account";
require "includes/db.php";
require "includes/auth.php";
require "includes/course_helpers.php";

require_login();

$message = "";
$user = ["username" => "", "first_name" => "", "last_name" => "", "email" => ""];
$categories = $pdo ? get_all_categories($pdo) : [];
$selectedCategoryIds = [];

if ($pdo) {
    $statement = $pdo->prepare("SELECT username, first_name, last_name, email FROM users WHERE user_id = ?");
    $statement->execute([$_SESSION["user_id"]]);
    $user = $statement->fetch(PDO::FETCH_ASSOC) ?: $user;

    $categoryStatement = $pdo->prepare("SELECT category_id FROM user_categories WHERE user_id = ?");
    $categoryStatement->execute([$_SESSION["user_id"]]);
    $selectedCategoryIds = array_map("intval", $categoryStatement->fetchAll(PDO::FETCH_COLUMN));
}

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
    } elseif ($password && strlen($password) < 8) {
        $message = "New password must be at least 8 characters.";
    } elseif ($password && $password !== $passwordVerify) {
        $message = "Password and password verification must match.";
    } elseif (!$selectedCategories) {
        $message = "Choose at least one preferred course category.";
    } elseif ($pdo) {
        try {
            if ($password) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $statement = $pdo->prepare("UPDATE users SET username = ?, password = ?, first_name = ?, last_name = ?, email = ? WHERE user_id = ?");
                $statement->execute([$username, $passwordHash, $firstName, $lastName, $email, $_SESSION["user_id"]]);
            } else {
                $statement = $pdo->prepare("UPDATE users SET username = ?, first_name = ?, last_name = ?, email = ? WHERE user_id = ?");
                $statement->execute([$username, $firstName, $lastName, $email, $_SESSION["user_id"]]);
            }

            $deleteStatement = $pdo->prepare("DELETE FROM user_categories WHERE user_id = ?");
            $deleteStatement->execute([$_SESSION["user_id"]]);
            $insertStatement = $pdo->prepare("INSERT INTO user_categories (user_id, category_id) VALUES (?, ?)");
            foreach ($selectedCategories as $categoryId) {
                $insertStatement->execute([$_SESSION["user_id"], (int) $categoryId]);
            }

            $_SESSION["username"] = $username;
            $_SESSION["first_name"] = $firstName;
            header("Location: account.php");
            exit;
        } catch (PDOException $exception) {
            $message = "That username or email address is already used by another account.";
        }
    }
}

include "includes/header.php";
?>
<section class="narrow">
  <h1>Update account</h1>
  <?php if ($message): ?>
    <p class="notice"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <form class="panel" method="post" action="update_account.php">
    <label for="first_name">First name</label>
    <input id="first_name" name="first_name" type="text" value="<?php echo htmlspecialchars($user["first_name"]); ?>" required>

    <label for="last_name">Last name</label>
    <input id="last_name" name="last_name" type="text" value="<?php echo htmlspecialchars($user["last_name"]); ?>" required>

    <label for="username">Username</label>
    <input id="username" name="username" type="text" value="<?php echo htmlspecialchars($user["username"]); ?>" required>

    <label for="email">Email address</label>
    <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($user["email"]); ?>" required>

    <label for="password">New password</label>
    <input id="password" name="password" type="password" minlength="8">

    <label for="password_verify">Password verification</label>
    <input id="password_verify" name="password_verify" type="password" minlength="8">

    <fieldset class="categories">
      <legend>Preferred course categories</legend>
      <?php foreach ($categories as $category): ?>
        <label>
          <input type="checkbox" name="categories[]" value="<?php echo htmlspecialchars($category["category_id"]); ?>" <?php echo in_array((int) $category["category_id"], $selectedCategoryIds, true) ? "checked" : ""; ?>>
          <?php echo htmlspecialchars($category["category_name"]); ?>
        </label>
      <?php endforeach; ?>
    </fieldset>

    <button class="btn" type="submit">Save account</button>
    <a class="btn btn_secondary" href="account.php">Cancel</a>
  </form>
</section>
<?php include "includes/footer.php"; ?>
