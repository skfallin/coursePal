<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return isset($_SESSION["user_id"]);
}

function is_admin_user(): bool
{
    return ($_SESSION["is_admin"] ?? false) === true;
}

function require_login(): void
{
    if (!is_logged_in()) {
        header("Location: index.php?message=login_required");
        exit;
    }
}

function require_admin(): void
{
    require_login();
    if (!is_admin_user()) {
        header("Location: index.php?message=admin_required");
        exit;
    }
}
?>
