CREATE DATABASE IF NOT EXISTS course_pal;
USE course_pal;

DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS user_categories;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(80) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(80) NOT NULL UNIQUE
);

CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    category_id INT NOT NULL,
    capacity INT NOT NULL,
    date DATETIME NOT NULL,
    course_image VARCHAR(255) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE user_categories (
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (user_id, category_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    booking_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_course (user_id, course_id)
);

INSERT INTO categories (category_name) VALUES
('Programming'),
('Web Development'),
('Cybersecurity'),
('Cloud Computing'),
('Data Science'),
('Graphic Design'),
('Mobile Development'),
('Networking');

INSERT INTO courses (name, description, category_id, capacity, date, course_image) VALUES
('HTML and CSS for Beginners', 'Learn how to create responsive webpages with semantic HTML and external CSS. This course introduces layout, colour, typography, images, and media queries.', 2, 12, '2026-06-12 10:00:00', 'html_and_css_for_beginners.jpg'),
('Introduction to Python', 'Start programming with variables, selection, iteration, functions, and files. Suitable for learners who are new to coding.', 1, 15, '2026-06-18 09:30:00', 'introduction_to_python.jpg'),
('Advanced JavaScript', 'Build interactive web features using modern JavaScript, events, arrays, objects, and browser APIs.', 2, 10, '2026-07-02 13:00:00', 'advanced_javascript.png'),
('Cybersecurity Essentials', 'Explore common threats, password security, network risks, and practical defensive techniques for organisations.', 3, 8, '2026-07-09 10:00:00', 'cybersecurity.png'),
('AWS Cloud Practitioner', 'Understand cloud computing concepts, storage, compute services, billing, and security foundations.', 4, 12, '2026-07-16 09:30:00', 'aws_cloud_practitioner.jpg'),
('Data Science Foundations', 'Analyse data, visualise patterns, and use simple models to support decision making.', 5, 10, '2026-07-23 10:00:00', 'data_science.jpg'),
('Photoshop Essentials', 'Create and edit digital images using layers, masks, selections, and export settings.', 6, 8, '2026-08-04 13:00:00', 'photoshop.png'),
('Illustrator Essentials', 'Design vector graphics, icons, and simple brand assets using professional workflows.', 6, 8, '2026-08-11 13:00:00', 'illustrator_essentials.jpg'),
('Android App Development', 'Design and build Android applications using modern mobile development techniques.', 7, 10, '2026-08-20 09:30:00', 'android_app_development.jpg'),
('Networking Fundamentals', 'Learn how networks are structured, configured, secured, and tested.', 8, 12, '2026-09-03 10:00:00', 'networking.png');

INSERT INTO users (username, password, first_name, last_name, email, is_admin) VALUES
('admin', '$2y$12$hd9Xx57WjrnCVmq8XSPPJ.RRNZxW9gIOX625DqvhZe67N7WBalMk6', 'Admin', 'User', 'admin@coursepal.example', TRUE),
('charles', '$2y$12$hd9Xx57WjrnCVmq8XSPPJ.RRNZxW9gIOX625DqvhZe67N7WBalMk6', 'Charles', 'Brown', 'charles@example.com', FALSE);

INSERT INTO user_categories (user_id, category_id) VALUES
(2, 6),
(2, 1),
(2, 2);
