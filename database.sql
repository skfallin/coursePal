CREATE DATABASE IF NOT EXISTS coursepal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE coursepal;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS user_categories;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  user_id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(255) NOT NULL,
  registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_admin INT NOT NULL DEFAULT 0,
  PRIMARY KEY (user_id),
  UNIQUE KEY unique_username (username),
  UNIQUE KEY unique_email (email)
) ENGINE=InnoDB;

CREATE TABLE categories (
  category_id INT NOT NULL AUTO_INCREMENT,
  category_name VARCHAR(100) NOT NULL,
  category_description VARCHAR(255) NOT NULL,
  category_colour VARCHAR(255) NOT NULL,
  PRIMARY KEY (category_id),
  UNIQUE KEY unique_category_name (category_name)
) ENGINE=InnoDB;

CREATE TABLE courses (
  course_id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  description MEDIUMTEXT NOT NULL,
  category_id INT NOT NULL,
  capacity INT NOT NULL,
  date DATETIME NOT NULL,
  course_image TEXT NOT NULL,
  PRIMARY KEY (course_id),
  CONSTRAINT fk_courses_category FOREIGN KEY (category_id) REFERENCES categories(category_id),
  CONSTRAINT check_course_capacity CHECK (capacity > 0)
) ENGINE=InnoDB;

CREATE TABLE bookings (
  booking_id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  booking_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (booking_id),
  UNIQUE KEY unique_user_course (user_id, course_id),
  CONSTRAINT fk_bookings_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_bookings_course FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user_categories (
  user_id INT NOT NULL,
  category_id INT NOT NULL,
  PRIMARY KEY (user_id, category_id),
  CONSTRAINT fk_user_categories_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_user_categories_category FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO categories (category_id, category_name, category_description, category_colour) VALUES
(1, 'Programming', 'Courses focusing on coding and software development.', '#2563eb'),
(2, 'Web Development', 'Learn how to build and maintain websites.', '#16a34a'),
(3, 'Data Science', 'Explore data analysis, visualisation, and machine learning.', '#0891b2'),
(4, 'Cybersecurity', 'Learn to protect systems and data from cyber threats.', '#c026d3'),
(5, 'Cloud Computing', 'Courses about cloud infrastructure and services.', '#38bdf8'),
(6, 'Networking', 'Understand computer networks and connectivity.', '#7f1d1d'),
(7, 'Artificial Intelligence', 'Dive into AI, machine learning, and neural networks.', '#dc2626'),
(8, 'Graphic Design', 'Learn design principles and creative tools.', '#ca8a04'),
(9, 'IT Support', 'Troubleshoot and maintain IT systems.', '#f97316'),
(10, 'Mobile App Development', 'Build applications for iOS and Android platforms.', '#7c3aed');

INSERT INTO courses (course_id, name, description, category_id, capacity, date, course_image) VALUES
(1, 'Introduction to Python', 'Unlock the power of coding with Python. Start your journey into programming today. It is simple, fun, and perfect for beginners.', 1, 30, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 10 DAY), 'introduction_to_python.jpg'),
(2, 'Advanced JavaScript', 'Unlock the full power of JavaScript. Dive into advanced concepts and cutting-edge techniques.', 1, 32, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 95 DAY), 'advanced_javascript.png'),
(3, 'HTML & CSS for Beginners', 'Create responsive websites with semantic HTML, external CSS, flexible grids, and media queries.', 2, 6, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 12 DAY), 'html_and_css_for_beginners.jpg'),
(4, 'React Framework Basics', 'Master essentials of React and build sleek, modern apps that stand out.', 2, 20, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 20 DAY), 'react_framework_basics.jpg'),
(5, 'Data Analysis with Python', 'Analyse patterns, uncover insights, and create clear visualisations with Python.', 3, 35, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 18 DAY), 'data_science.jpg'),
(6, 'Machine Learning with R', 'Learn core machine learning concepts in a clear, hands-on way using R.', 3, 30, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 35 DAY), 'machine_learning_with_r.jpg'),
(7, 'Network Security Essentials', 'Protect computer networks from threats and start mastering cybersecurity basics.', 4, 20, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 40 DAY), 'cybersecurity.png'),
(8, 'Penetration Testing', 'Master practical penetration testing techniques and security strategies.', 4, 15, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 60 DAY), 'penetration_testing.jpg'),
(9, 'AWS Cloud Practitioner', 'Discover AWS cloud services, core concepts, billing, security, and deployment options.', 5, 25, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 16 DAY), 'aws_cloud_practitioner.jpg'),
(10, 'Azure Fundamentals', 'Master the essentials of Microsoft Azure and begin your cloud journey.', 5, 20, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 46 DAY), 'azure_fundamentals.png'),
(11, 'Introduction to Networking', 'Build a strong foundation in network devices, addressing, routing, and connectivity.', 6, 2, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 22 DAY), 'networking.png'),
(12, 'Cisco Networking Basics', 'Gain hands-on experience with industry-leading Cisco networking technologies.', 6, 25, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 54 DAY), 'networking.png'),
(13, 'Deep Learning Fundamentals', 'Get hands-on experience with deep learning and unlock new AI possibilities.', 7, 20, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 70 DAY), 'artificial_intelligence.jpg'),
(14, 'AI for Beginners', 'Discover artificial intelligence concepts with beginner-friendly practical examples.', 7, 27, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 82 DAY), 'artificial_intelligence.jpg'),
(15, 'Graphic Design with Photoshop', 'Master Photoshop fundamentals and create stunning visuals that stand out.', 8, 40, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 15 DAY), 'photoshop.png'),
(16, 'Illustrator Essentials', 'Create vector artwork, icons, and designs using Illustrator essentials.', 8, 35, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 33 DAY), 'illustrator_essentials.jpg'),
(17, 'IT Support Fundamentals', 'Gain the skills to solve common technology issues with confidence.', 9, 50, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 48 DAY), 'it_support.png'),
(18, 'Windows System Administration', 'Manage and configure Windows systems with secure administration techniques.', 9, 25, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 75 DAY), 'it_support.png'),
(19, 'Android App Development', 'Create powerful, user-friendly Android applications from scratch.', 10, 30, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 28 DAY), 'android_app_development.jpg'),
(20, 'iOS App Development', 'Master Swift and bring iOS app ideas to life.', 10, 25, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 58 DAY), 'ios_app_development.png'),
(21, 'Django Web Development', 'Build secure, scalable web apps with the Django framework.', 2, 15, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 90 DAY), 'django_web_development.jpg'),
(22, 'Cybersecurity for Beginners', 'Master the essentials of system security and protect devices and data.', 4, 30, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 88 DAY), 'cybersecurity.png'),
(23, 'Python for Data Science', 'Process information and uncover insights with Python data science tools.', 3, 25, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 62 DAY), 'data_science.jpg'),
(24, 'Blockchain Basics', 'Understand blockchain ideas, digital trust, and distributed records.', 5, 20, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 110 DAY), 'artificial_intelligence.jpg'),
(25, 'Cloud Computing with Google', 'Explore Google Cloud services and cloud deployment patterns.', 5, 15, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 100 DAY), 'cloud_computing.png'),
(26, 'Network Troubleshooting', 'Tackle network challenges with structured diagnostic strategies.', 6, 20, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 44 DAY), 'networking.png'),
(27, 'Advanced Photoshop Techniques', 'Transform creative ideas into professional visual compositions.', 8, 30, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 72 DAY), 'photoshop.png'),
(28, 'Linux Administration', 'Control Linux systems and keep services running smoothly.', 9, 20, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 105 DAY), 'it_support.png'),
(29, 'Vue.js for Beginners', 'Create interactive web interfaces with Vue.js fundamentals.', 2, 12, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 85 DAY), 'vuejs_for_beginners.jpg'),
(30, 'Kotlin for Android Development', 'Master Kotlin and build modern Android experiences.', 10, 25, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 66 DAY), 'kotlin_for_android_development.png');

-- Password for both seed users: horsebatterystaple
INSERT INTO users (user_id, username, password, first_name, last_name, email, registration_date, is_admin) VALUES
(1, 'adalovelace', '$2y$10$wnQlqbbN046cZA8J4Ux8weqpD1bwKoPiF0P5.N9UKL4MIwJ7k4MKO', 'Ada', 'Lovelace', 'adalovelace@example.com', '2024-02-14 00:00:00', 1),
(2, 'cbabbage', '$2y$10$wnQlqbbN046cZA8J4Ux8weqpD1bwKoPiF0P5.N9UKL4MIwJ7k4MKO', 'Charles', 'Babbage', 'cbabbage@example.com', '2024-01-10 00:00:00', 0),
(3, 'aturing', '$2y$10$wnQlqbbN046cZA8J4Ux8weqpD1bwKoPiF0P5.N9UKL4MIwJ7k4MKO', 'Alan', 'Turing', 'aturing@example.com', '2024-03-01 00:00:00', 0);

INSERT INTO user_categories (user_id, category_id) VALUES
(1, 1), (1, 2), (1, 7),
(2, 1), (2, 2), (2, 8),
(3, 4), (3, 6), (3, 10);

INSERT INTO bookings (user_id, course_id, booking_date) VALUES
(2, 1, DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 5 DAY)),
(2, 3, DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 4 DAY)),
(2, 15, DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 3 DAY)),
(3, 11, DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 2 DAY)),
(1, 11, DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)),
(3, 7, CURRENT_TIMESTAMP);
