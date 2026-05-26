# Course Pal

Course Pal is a PHP/MySQL course booking website built to match the Ada Computer Science **Web project: Course Pal** brief.

## Software requirements

- PHP 8+
- MySQL or MariaDB
- A local PHP server, MAMP, Raspberry Pi Apache/PHP, or PHP-capable hosting

## Local setup

1. Put this folder in your PHP web root.
2. Import the database:

   ```bash
   mysql -uroot < database.sql
   ```

3. If your database username/password are not `root` with no password, set environment variables before starting PHP or edit `includes/connectdb.php`:

   ```bash
   export COURSEPAL_DB_HOST=localhost
   export COURSEPAL_DB_NAME=coursepal
   export COURSEPAL_DB_USER=root
   export COURSEPAL_DB_PASS=
   ```

4. Start a local server:

   ```bash
   php -S 127.0.0.1:8000
   ```

5. Open `http://127.0.0.1:8000/index.php`.

## Demo accounts

Both seed accounts use password `horsebatterystaple`.

| Role | Username | Password |
| --- | --- | --- |
| Admin | `adalovelace` | `horsebatterystaple` |
| User | `cbabbage` | `horsebatterystaple` |

## Project structure

```text
account.php
admin.php
book.php
cancel-booking-action.php
class-list.php
course.php
courses.php
course-add.php
course-add-action.php
course-edit.php
course-edit-action.php
edit-account.php
edit-account-action.php
index.php
login.php
logout.php
register.php
register-action.php
reports.php
css/styles.css
images/logo.svg
includes/connectdb.php
includes/featured-courses.php
includes/footer.php
includes/header.php
uploads/
database.sql
docs/
```

The root contains the PHP pages and action scripts listed by the Ada Course Pal design section. The removed `vercel_static` demo and hardcoded fallback data are not part of the PHP/MySQL submission.

## Main pages

- `index.php` — home page, login form, introductory text, and eight recommended courses.
- `courses.php` — searchable and sortable full course catalogue.
- `course.php?id=...` — course detail page with image, capacity, booking count, and booking button.
- `register.php` / `register-action.php` — registration with preferred categories and password hashing.
- `account.php`, `edit-account.php`, `edit-account-action.php` — account details, bookings, cancellation links, and profile/category editing.
- `admin.php`, `course-add.php`, `course-edit.php`, `class-list.php`, `reports.php` — administrator-only course management, printable class lists, and popularity report.

## Testing

Run syntax checks:

```bash
find . -name "*.php" -not -path "./vercel_static/*" -print0 | xargs -0 -n1 php -l
```

See `docs/testing.md` for the completed test table and actual results recorded for this repository.
