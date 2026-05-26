<?php
function get_all_categories(PDO $pdo): array
{
    $statement = $pdo->query("SELECT * FROM categories ORDER BY category_name");
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function get_course(PDO $pdo, int $courseId): ?array
{
    $statement = $pdo->prepare("
        SELECT courses.*, categories.category_name,
               COUNT(bookings.booking_id) AS num_bookings
        FROM courses
        INNER JOIN categories ON courses.category_id = categories.category_id
        LEFT JOIN bookings ON courses.course_id = bookings.course_id
        WHERE courses.course_id = ?
        GROUP BY courses.course_id
    ");
    $statement->execute([$courseId]);
    $course = $statement->fetch(PDO::FETCH_ASSOC);

    return $course ?: null;
}

function get_booking_count(PDO $pdo, int $courseId): int
{
    $statement = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE course_id = ?");
    $statement->execute([$courseId]);
    return (int) $statement->fetchColumn();
}

function is_user_booked_on_course(PDO $pdo, int $userId, int $courseId): bool
{
    $statement = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND course_id = ?");
    $statement->execute([$userId, $courseId]);
    return (int) $statement->fetchColumn() > 0;
}

function format_course_date(string $date): string
{
    return (new DateTime($date))->format("D j F Y H:i");
}
?>
