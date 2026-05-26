# Course Pal project documentation

This documentation is aligned with the Ada Computer Science Course Pal brief and the current repository implementation.

## Official requirement source

The implementation was checked against the Ada Course Pal topic and all linked sections:

- Getting started
- Analysis
- Design
- Implementation: creating dynamic pages with a database
- Implementation: viewing data
- Implementation: editing data
- Implementation: data analysis
- Testing
- Evaluation

See `docs/requirements_checklist.md` for the traceability checklist.

## Implemented file structure

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

Static Vercel demo files and hardcoded fallback data were removed so the project functions as a PHP/MySQL website.

## Database

`database.sql` creates database `coursepal` with the five required tables:

1. `users`
2. `courses`
3. `categories`
4. `bookings`
5. `user_categories`

The schema includes primary keys, foreign keys, unique username/email constraints, a unique user/course booking constraint, a composite key for `user_categories`, and hashed seed user passwords.

Seed data includes:

- 3 users, including one administrator
- 10 course categories with descriptions and chart colours
- 30 courses with future dates and image filenames
- sample user-category preferences
- sample bookings for reports/class-list testing

## User functionality

- Guests can view all courses and course detail pages.
- Guests can register with required fields and preferred categories.
- Registered users can log in, log out, receive personalised navigation, book courses, view bookings, cancel bookings, and edit account details.
- Booking logic prevents unauthenticated bookings, duplicate bookings, and bookings on full courses.

## Administrator functionality

Administrators see `Admin` and `Reports` navigation links and can:

- view a searchable/sortable course admin table
- add new courses with image upload validation
- edit course name, description, date, category, capacity, and image
- view printable class lists
- view visual course popularity reports sorted by booking count and coloured by category

Course deletion is not implemented because the Ada implementation page states deletion is outside project scope and should be discussed as future work.

## Design artefacts

- `docs/design.md` — structure diagram, session variables, data dictionary, query designs, recommendation algorithm, responsive plan.
- `docs/wireframes.md` — low-fidelity wireframes for home, courses, course detail desktop/mobile, registration, edit course, account, admin, class list, and reports.
- `docs/uml_erd.md` — Mermaid use case diagram and ERD.

## Testing and evaluation

- `docs/testing.md` contains a completed test table with command/runtime results.
- `docs/evaluation.md` evaluates fitness for purpose, maintainability, robustness, limitations, and future improvements.

## Security and robustness decisions

- All user-input database operations use prepared statements.
- Dynamic output is escaped through `e()` in `includes/connectdb.php`.
- Passwords are hashed with `password_hash()` and verified with `password_verify()`.
- Role checks use `require_login()` and `require_admin()` before protected content is displayed.
- Sorting is limited to allow-listed SQL columns.
- File upload accepts only JPG/JPEG/PNG/GIF images up to 5MB.

## Verification summary

The following checks were run successfully:

```bash
find . -name "*.php" -not -path "./vercel_static/*" -print0 | xargs -0 -n1 php -l
mysql -uroot < database.sql
php -S 127.0.0.1:8099
php -S 127.0.0.1:8101
```

Runtime curl smoke tests verified registration, duplicate registration, password mismatch, login, booking, duplicate/full booking rejection, cancellation, admin access control, admin list, class list, reports, and admin add/edit course flows.
