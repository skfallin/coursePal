# Course Pal wireframes

Low-fidelity wireframes show layout only, not visual styling.

## Home page — desktop

```text
+------------------------------------------------------------------+
| Logo Course Pal                         Welcome username         |
|                                      All Courses Login Register   |
+------------------------------------------------------------------+
| +-----------------------------+  +-----------------------------+ |
| | Welcome to Course Pal       |  | Featured Courses            | |
| | Intro paragraph             |  | + course card: image/title  | |
| | Intro paragraph             |  | + course card: image/title  | |
| | Login form / account msg    |  | + course card: image/title  | |
| | Register button             |  | + up to 8 recommendations   | |
| +-----------------------------+  +-----------------------------+ |
+------------------------------------------------------------------+
| Footer left                                      Footer right     |
+------------------------------------------------------------------+
```

## Home page — mobile

```text
+-----------------------------+
| Logo Course Pal             |
| Welcome username            |
| All Courses / Login / ...   |
+-----------------------------+
| Welcome to Course Pal       |
| Intro text                  |
| Login form / account msg    |
+-----------------------------+
| Featured Courses            |
| Course card                 |
| Course card                 |
| Course card                 |
+-----------------------------+
| Footer stacked              |
+-----------------------------+
```

## Courses page

```text
+------------------------------------------------------------------+
| Shared header/nav                                                 |
+------------------------------------------------------------------+
| Courses List                                                      |
| Search [________________] [Search]                                |
| Sortable table headings: ID | Category | Course | Description | Date|
| Course table rows link to detail page                             |
|                                                                  |
| +----------+ +----------+ +----------+                            |
| | Image    | | Image    | | Image    |                            |
| | Title    | | Title    | | Title    |                            |
| | Category | | Category | | Category |                            |
| | Date     | | Date     | | Date     |                            |
| | Read more| | Read more| | Read more|                            |
| +----------+ +----------+ +----------+                            |
+------------------------------------------------------------------+
| Shared footer                                                     |
+------------------------------------------------------------------+
```

On mobile, the search fields and course cards stack to one column and the table scrolls horizontally if needed.

## Course detail page — desktop

```text
+------------------------------------------------------------------+
| Shared header/nav                                                 |
+------------------------------------------------------------------+
| Course title                                                      |
| +-------------------------+ +------------------------------------+ |
| | Course image            | | Category                           | |
| |                         | | Details heading                    | |
| |                         | | Description paragraph(s)           | |
| |                         | | Date/time                          | |
| |                         | | Capacity                           | |
| |                         | | Current bookings                   | |
| |                         | | [Book on course] [All courses]     | |
| +-------------------------+ +------------------------------------+ |
+------------------------------------------------------------------+
| Shared footer                                                     |
+------------------------------------------------------------------+
```

## Course detail page — mobile

```text
+-----------------------------+
| Shared header/nav           |
+-----------------------------+
| Course title                |
| Course image (full width)   |
| Category                    |
| Description                 |
| Date/time                   |
| Capacity / bookings         |
| [Book on course]            |
+-----------------------------+
| Shared footer               |
+-----------------------------+
```

## Registration page

```text
+------------------------------------------------------------------+
| Shared header/nav                                                 |
+------------------------------------------------------------------+
| Register                                                          |
| First Name [____________________]                                  |
| Last Name  [____________________]                                  |
| UserName   [____________________]                                  |
| Email      [____________________]                                  |
| Password   [____________________]                                  |
| Repeat     [____________________]                                  |
| Course categories: [ ] Programming [ ] Web Development ...        |
| [Register]                                                        |
+------------------------------------------------------------------+
| Shared footer                                                     |
+------------------------------------------------------------------+
```

## Edit course page

```text
+------------------------------------------------------------------+
| Shared admin header/nav                                           |
+------------------------------------------------------------------+
| Edit Course                                                       |
| Course Name [____________________]                                |
| Category    [ dropdown        v ]                                 |
| Description [ textarea          ]                                 |
| Date        [ datetime-local    ]                                 |
| Capacity    [ number           ]                                  |
| Current image preview                                             |
| Image       [Choose file]                                         |
| [Save Course] [Cancel]                                            |
+------------------------------------------------------------------+
| Shared footer                                                     |
+------------------------------------------------------------------+
```

## Account page

```text
+------------------------------------------------------------------+
| Shared user header/nav                                            |
+------------------------------------------------------------------+
| My Account                                                        |
| +--------------------------+ +-----------------------------------+ |
| | Account details          | | Booked courses table              | |
| | User ID/name/email       | | Course | date | booking | actions | |
| | [Edit account details]   | | [Learn more] [Cancel booking]     | |
| +--------------------------+ +-----------------------------------+ |
+------------------------------------------------------------------+
```

## Admin page

```text
+------------------------------------------------------------------+
| Shared admin header/nav                                           |
+------------------------------------------------------------------+
| Course Admin                                      [Add new course] |
| Search [________________] [Search]                                |
| ID | Category | Image | Course | Description | Bookings | Capacity |
| Date | Actions: [View] [Edit] [Class List]                        |
+------------------------------------------------------------------+
```

## Class list page

```text
+------------------------------------------------------------------+
| Shared admin header/nav                                 [Print]   |
+------------------------------------------------------------------+
| Class List for Course Name on Course Date                         |
| Number of students booked: N                                      |
| User ID | First name | Last name | Email address | Booking date   |
+------------------------------------------------------------------+
```

The print stylesheet hides navigation, buttons, and footer for a clean printed register.

## Reports page

```text
+------------------------------------------------------------------+
| Shared admin header/nav                                           |
+------------------------------------------------------------------+
| Course Reports                                                    |
| Number of courses: N                                              |
| Horizontal bar chart sorted by booking count                      |
| Course labels link to course detail pages                         |
| Data table: Course | Category | Bookings | Class list             |
+------------------------------------------------------------------+
```
