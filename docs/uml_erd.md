# UML and ERD

## Use case diagram

```mermaid
flowchart LR
    Unregistered[Unregistered user]
    Registered[Registered user]
    Admin[Registered administrator]

    Browse((Browse courses))
    Register((Register))
    Login((Log in))
    Book((Book on course))
    ViewMine((View my courses))
    Cancel((Cancel booking))
    EditAccount((Edit account))
    Auth((Authenticate user))
    Availability((Check course availability))
    AddCourse((Add course))
    EditCourse((Edit course))
    ClassList((View class list))
    Reports((View popularity reports))

    Unregistered --- Browse
    Unregistered --- Register
    Registered --- Browse
    Registered --- Login
    Registered --- Book
    Registered --- ViewMine
    Registered --- Cancel
    Registered --- EditAccount
    Admin --- AddCourse
    Admin --- EditCourse
    Admin --- ClassList
    Admin --- Reports
    Admin --- Browse

    Book -. include .-> Auth
    Book -. include .-> Availability
    ViewMine -. include .-> Auth
    Cancel -. include .-> Auth
    EditAccount -. include .-> Auth
    AddCourse -. include .-> Auth
    EditCourse -. include .-> Auth
    ClassList -. include .-> Auth
    Reports -. include .-> Auth
```

## Entity relationship diagram

```mermaid
erDiagram
    USERS ||--o{ BOOKINGS : makes
    COURSES ||--o{ BOOKINGS : receives
    USERS ||--o{ USER_CATEGORIES : chooses
    CATEGORIES ||--o{ USER_CATEGORIES : selected_in
    CATEGORIES ||--o{ COURSES : classifies

    USERS {
        int user_id PK
        varchar username UK
        varchar password
        varchar first_name
        varchar last_name
        varchar email UK
        datetime registration_date
        int is_admin
    }

    CATEGORIES {
        int category_id PK
        varchar category_name UK
        varchar category_description
        varchar category_colour
    }

    COURSES {
        int course_id PK
        varchar name
        mediumtext description
        int category_id FK
        int capacity
        datetime date
        text course_image
    }

    BOOKINGS {
        int booking_id PK
        int user_id FK
        int course_id FK
        datetime booking_date
    }

    USER_CATEGORIES {
        int user_id PK,FK
        int category_id PK,FK
    }
```

## Relationship notes

- One user can make many bookings; each booking belongs to one user.
- One course can have many bookings; each booking belongs to one course.
- One category can contain many courses; each course belongs to one category.
- Users and categories have a many-to-many relationship implemented by `user_categories`.
- Duplicate bookings are prevented by the unique key on `(user_id, course_id)`.
- Duplicate usernames and duplicate emails are prevented by unique keys on `users`.
