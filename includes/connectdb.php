<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dbHost = getenv('COURSEPAL_DB_HOST') ?: 'localhost';
$dbName = getenv('COURSEPAL_DB_NAME') ?: 'coursepal';
$dbUser = getenv('COURSEPAL_DB_USER') ?: 'root';
$dbPass = getenv('COURSEPAL_DB_PASS') ?: '';

$pdo = null;
$dbError = null;

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $exception) {
    $dbError = 'Database connection failed. Check includes/connectdb.php credentials and import database.sql.';
}

function e(string|int|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function is_logged_in(): bool
{
    return ($_SESSION['isLoggedIn'] ?? false) === true && isset($_SESSION['userid']);
}

function is_admin_user(): bool
{
    return is_logged_in() && (int) ($_SESSION['isAdmin'] ?? 0) === 1;
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: index.php?msg=' . urlencode('Please login or register to continue.'));
        exit;
    }
}

function require_admin(): void
{
    if (!is_admin_user()) {
        header('Location: index.php?msg=' . urlencode('Administrators only. Please login with an administrator account.'));
        exit;
    }
}

function format_course_date(?string $date): string
{
    if (!$date) {
        return '';
    }
    return (new DateTime($date))->format('D j F Y H:i');
}

function get_all_categories(PDO $pdo): array
{
    $statement = $pdo->query('SELECT * FROM categories ORDER BY category_name');
    return $statement->fetchAll();
}

function get_course(PDO $pdo, int $courseId): ?array
{
    $statement = $pdo->prepare(
        'SELECT courses.*, categories.category_name, categories.category_colour,
                (SELECT COUNT(bookings.booking_id) FROM bookings WHERE bookings.course_id = courses.course_id) AS total_bookings
         FROM courses
         INNER JOIN categories ON courses.category_id = categories.category_id
         WHERE courses.course_id = ?'
    );
    $statement->execute([$courseId]);
    $course = $statement->fetch();
    return $course ?: null;
}

function redirect_with_message(string $url, string $message): void
{
    $separator = str_contains($url, '?') ? '&' : '?';
    header('Location: ' . $url . $separator . 'msg=' . urlencode($message));
    exit;
}

function save_course_image_upload(string $fieldName, ?string $currentImage = null): array
{
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['name'] === '') {
        return [true, $currentImage ?: 'html_and_css_for_beginners.jpg', ''];
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return [false, $currentImage, 'Image upload failed.'];
    }

    if ($_FILES[$fieldName]['size'] > 5000000) {
        return [false, $currentImage, 'Image must be 5MB or smaller.'];
    }

    $originalName = basename($_FILES[$fieldName]['name']);
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($extension, $allowed, true)) {
        return [false, $currentImage, 'Image must be JPG, JPEG, PNG, or GIF.'];
    }

    $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
    $target = __DIR__ . '/../uploads/' . $safeName;

    if (!is_dir(__DIR__ . '/../uploads')) {
        mkdir(__DIR__ . '/../uploads', 0755, true);
    }

    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $target)) {
        return [false, $currentImage, 'Image could not be saved to uploads folder.'];
    }

    return [true, $safeName, 'The file ' . $safeName . ' has been uploaded.'];
}
?>
