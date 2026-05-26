# Course Pal requirements checklist

Source of truth: Ada Computer Science Course Pal topic and linked pages for Getting started, Analysis, Design, Implementation, Testing, and Evaluation.

| Area | Requirement | Initial status | Final status | Evidence |
| --- | --- | --- | --- | --- |
| Structure | Use the Ada PHP/MySQL starter-style structure with root PHP pages/action scripts, `css/`, `images/`, `uploads/`, and `includes/`. | Partially compliant | Compliant | Root has the 19 PHP files listed in the Ada structure plus required folders. |
| Structure | Remove static/demo-only deployment artifacts not in the brief. | Non-compliant | Compliant | Removed `vercel_static`, `.DS_Store`, hardcoded fallback include files, and delete-course extra. |
| Includes | Modular shared header/footer/database/recommendation code. | Partially compliant | Compliant | `includes/header.php`, `footer.php`, `connectdb.php`, `featured-courses.php`. |
| Database | Five SQL tables: `users`, `courses`, `categories`, `bookings`, `user_categories`. | Partially compliant | Compliant | `database.sql`. |
| Database | Required fields including `registration_date`, `category_description`, `category_colour`, course image, capacity, date. | Partially compliant | Compliant | `database.sql`. |
| Database | Primary keys, foreign keys, composite key for `user_categories`, duplicate username prevention, duplicate booking prevention. | Partially compliant | Compliant | `database.sql` constraints. |
| Database | Hashed seed passwords and enough seed data. | Partially compliant | Compliant | 3 users, 10 categories, 30 courses, bookings, user categories in `database.sql`. |
| Registration | First name, last name, username, password, repeat password, email, preferred categories. | Partially compliant | Compliant | `register.php`, `register-action.php`. |
| Registration | Server-side validation, password hash, duplicate username/email handling. | Partially compliant | Compliant | `register-action.php`. |
| Login/session | Login validates username/password and sets session variables. | Partially compliant | Compliant | `login.php`; `isLoggedIn`, `username`, `userid`, `isAdmin`. |
| Navigation | Navigation differs for guest, registered user, and admin. | Partially compliant | Compliant | `includes/header.php`. |
| Home | Welcome/introductory text and featured/recommended courses. | Partially compliant | Compliant | `index.php`, `includes/featured-courses.php`. |
| Recommendations | Next 3 months, user categories first, exclude already booked/full courses, date order, limit 8. | Partially compliant | Compliant | `includes/featured-courses.php`. |
| All Courses | Search, database data, sortable headings ID/category/name/description/date, default date order. | Partially compliant | Compliant | `courses.php`. |
| Course detail | Image with alt, title, description, category, date/time, capacity, bookings, booking button, missing ID handling. | Partially compliant | Compliant | `course.php`. |
| Booking | Login required, duplicate/full checks, insert booking, account view, cancellation ownership check. | Partially compliant | Compliant | `book.php`, `account.php`, `cancel-booking-action.php`. |
| My Account | Show user details and booked courses; edit names, username, password, email, preferred categories. | Partially compliant | Compliant | `account.php`, `edit-account.php`, `edit-account-action.php`. |
| Admin | Admin-only access, admin nav links, list all courses, add/edit course fields. | Partially compliant | Compliant | `admin.php`, `course-add.php`, `course-edit.php` and action scripts. |
| Class list | Admin-only printable class list with user ID, names, email, booking date. | Partially compliant | Compliant | `class-list.php`, print CSS and Print button. |
| Reports | Admin-only visual popularity report sorted by bookings with category colours and table. | Partially compliant | Compliant | `reports.php`. |
| CSS/responsive | External CSS, fixed syntax errors, mobile `<600px` and desktop `>=600px` media queries. | Partially compliant | Compliant | `css/styles.css`. |
| Accessibility | Semantic HTML, headings, meaningful image alt text. | Partially compliant | Compliant | PHP templates and CSS. |
| Design docs | Structure, wireframes, media-query plan, algorithms, query design/data dictionary. | Partially compliant | Compliant | `docs/design.md`, `docs/wireframes.md`, `project_documentation.md`. |
| UML/ERD | Use case diagram and ERD matching implementation. | Non-compliant | Compliant | `docs/uml_erd.md`. |
| Testing | Completed test table with actual results and evidence. | Partially compliant | Compliant | `docs/testing.md`. |
| Evaluation | Fitness for purpose, robustness/maintainability, limits, future improvements. | Partially compliant | Compliant | `docs/evaluation.md`. |
| README | Setup, import, credentials, structure, pages, testing. | Non-compliant | Compliant | `README.md`. |
