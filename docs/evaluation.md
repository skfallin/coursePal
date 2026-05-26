# Course Pal evaluation

## Fitness for purpose

The completed Course Pal repository is fit for the Ada Computer Science Course Pal brief. It implements a PHP/MySQL website with the five required relational database tables, session-based user authentication, a searchable course catalogue, course detail pages, booking and cancellation, account editing, administrator-only course management, printable class lists, and a visual popularity report.

The final test plan in `docs/testing.md` records all executed command and runtime tests as passing. The three example failures discussed by Ada's evaluation page have been fixed here:

- Duplicate usernames are prevented by both server-side checks and a database unique key.
- The course page displays capacity and current booking count.
- Course and logo images include meaningful `alt` text.

## Requirement coverage

- **Users** can register with first name, last name, username, password, password verification, email, and preferred categories. Passwords are stored with `password_hash()`.
- **Registered users** can log in, receive personalised navigation and welcome text, view their bookings, edit details/categories/password, and cancel bookings.
- **Bookings** are protected by login checks, duplicate-booking checks, capacity checks, and database constraints.
- **Administrators** have a separate navigation menu and can view/search/sort courses, add courses, edit courses, see class lists, and view reports.
- **Reports** use a grouped SQL query across `courses`, `bookings`, and `categories`, sorted by booking count with category colours.
- **Responsive design** is implemented in external CSS for screens below 600px and at/above 600px.
- **Documentation** includes the structure, data dictionary, wireframes, UML/use-case diagram, ERD, testing, and this evaluation.

## Maintainability

The code is maintainable because common page structure is centralised:

- `includes/header.php` controls the shared HTML header and navigation.
- `includes/footer.php` controls the shared footer.
- `includes/connectdb.php` centralises the database connection, helper functions, authentication guards, escaping, date formatting, and image upload validation.
- `includes/featured-courses.php` isolates the recommendation algorithm from the home page.

Prepared statements are used for user input, and frequently repeated rules such as `require_login()` and `require_admin()` are shared instead of duplicated. The database schema is fully reproducible from `database.sql`.

## Robustness and security

The site handles key error cases:

- Invalid login returns a clear message.
- Unauthenticated booking redirects to login/register.
- Duplicate username/email registration is rejected.
- Duplicate bookings are rejected by code and by a unique database key.
- Full courses cannot be booked.
- Booking cancellation requires the logged-in user's ID in the delete query.
- Admin pages call `require_admin()` before any output.
- Dynamic output uses `htmlspecialchars()` via the shared `e()` helper.
- Course sorting uses allow-listed SQL column names to avoid injection in `ORDER BY`.

## Remaining limits

These limits are consistent with the Ada brief boundaries or are realistic future work:

- The site does not take payments.
- The site does not send email confirmations.
- There is no admin user-management screen for promoting users to administrator; seed data contains an admin and future work could add a controlled promotion feature.
- Course deletion is deliberately absent because Ada marks deletion as outside the current project scope.
- This automated fix did not collect new independent stakeholder feedback from real users. The repository includes the required implementation and test evidence; a classroom submission should add teacher/stakeholder comments after hands-on use.

## Future improvements

### Easy

- Add pagination for very long course lists.
- Add an export button for class lists as CSV.
- Add a user-management admin page for viewing registered users.

### Medium

- Add email confirmations for bookings and cancellations.
- Add stronger password rules with guidance.
- Add image resizing when administrators upload very large course images.

### Larger

- Add a secure payment flow.
- Add course waiting lists for full courses.
- Add audit logging for administrator changes.

## Project summary

The project now follows the full systems lifecycle expected by Ada: analysis-derived requirements, design artefacts, a relational database, PHP implementation, CSS media queries, testing, and evaluation. The most important technical corrections were removing static demo/fallback artefacts, aligning file names with the Ada structure, rebuilding the database schema with constraints and seed data, separating action scripts from forms, and adding the missing admin/report/class-list behaviour.

If further work were carried out, the priority would be gathering real stakeholder feedback and adding non-core production features such as email and payments without changing the core learning objectives of the exercise.
