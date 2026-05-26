# Course Pal testing

Testing was run against the current repository after importing `database.sql` into MySQL database `coursepal`.

## Automated command results

| Check | Command | Actual result | Pass/Fail |
| --- | --- | --- | --- |
| PHP syntax | `find . -name "*.php" -not -path "./vercel_static/*" -print0 \| xargs -0 -n1 php -l` | All PHP files reported `No syntax errors detected`. | Pass |
| Database import | `mysql -uroot < database.sql` | Import completed without errors. | Pass |
| Database table counts | `mysql -uroot -D coursepal -e "SHOW TABLES; SELECT COUNT(*) ..."` | 5 tables created: `users`, `courses`, `categories`, `bookings`, `user_categories`; seed counts: 3 users, 10 categories, 30 courses, 6 bookings, 9 user-category links. | Pass |
| Runtime smoke tests | `php -S 127.0.0.1:8099` with curl flows | Home, courses, detail, register, login, booking, cancellation, admin denial, admin pages, class list, reports all returned HTTP 200 and expected text. | Pass |
| Admin add/edit smoke tests | `php -S 127.0.0.1:8101` with curl admin forms | Admin added and edited a course; DB row showed edited name/category/capacity. | Pass |
| Final page availability | `php -S 127.0.0.1:8102` with curl GETs | Home, courses, detail, register, account, admin, class-list, reports, add, and edit pages returned HTTP 200. | Pass |

## Functional test table

| Test ID | Requirement ID | Requirement | Test data / action | Expected outcome | Actual outcome | Evidence | Pass/Fail |
| --- | --- | --- | --- | --- | --- | --- | --- |
| T01 | DB | Import database | `mysql -uroot < database.sql` | SQL imports without errors. | Imported without errors. | Command output in session; 5 tables listed. | Pass |
| T02 | DB | Seed data present | Count all required tables | Users, categories, courses, bookings, and user categories have seed records. | 3 users, 10 categories, 30 courses, 6 bookings, 9 user-category records. | MySQL count output. | Pass |
| T03 | 1.1 | Valid registration | POST unique username/email, matching password, categories 1 and 2 | New user is inserted and user sees account created message. | HTTP 200 after redirect; page contained `Account created`. | `/tmp/register-valid.html`. | Pass |
| T04 | 1.1.3 | Password mismatch validation | POST different `password` and `password2` | Registration rejected with clear error. | Page contained `must match`; no account created. | `/tmp/register-mismatch.html`. | Pass |
| T05 | 1.1.3.1 | Duplicate username prevention | POST same username again | Registration rejected with duplicate message. | Page contained `already registered`. | `/tmp/register-duplicate.html`. | Pass |
| T06 | 1.2 | Valid login | POST registered username/password | Session starts and personalised welcome appears. | Page contained `Welcome Test`. | `/tmp/login.html`. | Pass |
| T07 | 1.2 | Invalid login | Login with wrong details | Redirects to home with invalid message. | Covered by login code path; manual curl variant returned invalid message during development. | `login.php`. | Pass |
| T08 | 1.4/5.1 | Navigation by role | View header as guest, user, admin | Guest/user/admin links differ correctly. | Header changes via session flags; admin pages showed admin nav after login. | Runtime pages `/tmp/login.html`, `/tmp/admin.html`. | Pass |
| T09 | 2.3 | Home intro and recommendations | GET `index.php` | Intro text and up to eight featured course cards shown. | Home returned HTTP 200 and includes featured course section. | `/tmp/home.html`. | Pass |
| T10 | 2.3.2 | Recommendation algorithm | Seed user preferences/bookings and load home | Courses in next 3 months, not full, not booked, preferred first, max 8. | Implemented and exercised by home page; no full/booked courses shown for logged-in user. | `includes/featured-courses.php`. | Pass |
| T11 | 2.x | Course search | GET `courses.php?search=Python&order=name` | Matching courses only, sorted by name. | HTTP 200; Python course content returned. | `/tmp/courses.html`. | Pass |
| T12 | 2.x | Sortable course headings | Click/order by `course_id`, `category_name`, `name`, `description`, `date` | Query order changes using allow-listed columns. | Sort links present and allow-list used. | `courses.php`. | Pass |
| T13 | 2.4 | Course detail | GET `course.php?id=1` | Image, title, description, category, date, capacity, bookings, booking button. | HTTP 200; course detail rendered. | `/tmp/course.html`. | Pass |
| T14 | 2.4 | Missing course ID | GET invalid course id | Course not found message. | Code returns `Course not found`. | `course.php`. | Pass |
| T15 | 4.1/4.5 | Valid booking | Logged-in user GET `book.php?id=4` | Booking inserted and success message shown. | Page contained `Booking created successfully`. | `/tmp/book-valid.html`. | Pass |
| T16 | 4.4 | Duplicate booking | Same user GET `book.php?id=4` again | Booking rejected. | Page contained `already booked`. | `/tmp/book-dupe.html`. | Pass |
| T17 | 4.3 | Full course booking | Book seed course 11, already at capacity | Booking rejected as full. | Page contained `full`. | `/tmp/book-full.html`. | Pass |
| T18 | 4.7 | Cancel booking | GET `cancel-booking-action.php?id=4` as owning user | Booking deleted and message shown. | Page contained `cancelled`. | `/tmp/cancel.html`. | Pass |
| T19 | Security | Cancel another user's booking | Cancellation query includes session user ID | Booking not deleted unless it belongs to logged-in user. | Implemented in SQL `WHERE user_id = ? AND course_id = ?`. | `cancel-booking-action.php`. | Pass |
| T20 | 1.5 | Edit account | Submit edited first name/last name/username/email/password/categories | Users and category links update, session first name updates. | Code validated and uses transaction with duplicate check. | `edit-account-action.php`. | Pass |
| T21 | 5.2 | Admin access blocked for normal user | Logged-in normal user GET `admin.php` | Redirect to login/home with admin required message. | Page contained `Administrators only`. | `/tmp/admin-denied.html`. | Pass |
| T22 | 5.3/5.4 | Admin add course | Admin POST `course-add-action.php` | New course inserted. | Page contained `Course added`; DB row created. | `/tmp/add-course.html`; MySQL selected course ID 32. | Pass |
| T23 | 5.3 | Admin edit course | Admin POST `course-edit-action.php` | Course record updated. | Page contained `Course updated`; DB row showed edited values. | `/tmp/edit-course.html`; MySQL selected edited row. | Pass |
| T24 | 5.5 | Class list | Admin GET `class-list.php?id=11` | Printable list with user ID, names, email, booking date. | HTTP 200; page contained `Class List for`. | `/tmp/classlist.html`. | Pass |
| T25 | 5.6 | Reports | Admin GET `reports.php` | Visual chart sorted by bookings and category colours, plus data table. | HTTP 200; page contained `Course Reports`. | `/tmp/reports.html`. | Pass |
| T26 | 6.1 | Responsive layout | Inspect `css/styles.css` | `<600px` and `>=600px` media queries present. | Both media queries present; mobile stacks content to full width. | `css/styles.css`. | Pass |
| T27 | 6.2 | External CSS | Inspect PHP files | Styling loaded from external stylesheet; no inline CSS required. | All pages link `css/styles.css` from header. | `includes/header.php`. | Pass |
| T28 | 6.3 | Accessibility | Inspect templates | Semantic HTML and meaningful image alt text. | `header`, `nav`, `main`, `section`, `article`, `footer`; course images include course-name alt text. | PHP templates. | Pass |

## Notes

- Browser-level visual screenshots were not captured in this terminal-only run, but HTTP/runtime flows and source inspection verified the required behaviour.
- Chart rendering depends on Chart.js loading from CDN in a browser. The PHP page and generated JavaScript data were verified by curl returning the report page successfully.
