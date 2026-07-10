# Online Exam System (PHP + MySQL)

A simple and clean **Online Exam System** built with **Plain PHP + MySQL**.
This project includes an **Admin Panel** for managing exams and questions, and a **User Panel** for taking exams, viewing results, and reviewing answers.



## Features

* Admin login
* User registration and login
* Create exams
* Add **MCQ** questions
* Add **True/False** questions
* Random question order during exams
* Exam timer
* User exam result page
* Answer review after exam
* Admin dashboard
* User dashboard


## Technologies Used

* PHP
* MySQL
* HTML5
* CSS3
* Bootstrap 5
* JavaScript



## Installation

1. **Clone or download** this repository.
2. Move the project folder into your web server directory:

   * **XAMPP:** `htdocs`
3. Create a new MySQL database.
4. Import the SQL file from the **`database/`** folder.
5. Update the database connection settings in your config file.
6. Start **Apache** and **MySQL** from XAMPP.
7. Open the project in your browser.

Example:

bash
http://localhost/online_exam_system/


## Default Setup

Before running the project, configure your database credentials inside the project files.

Example database settings:

php
$host = "localhost";
$user = "root";
$password = "";
$database = "online_exam_system";




## Project Structure

bash
online_exam_system/
│── admin/         # Admin panel files
│── user/          # User panel files
│── includes/      # Common includes, layouts, auth files
│── assets/        # CSS, JS, images
│── database/      # SQL database file
│── index.php      # Homepage
│── README.md




## Screenshots

### Home Page

![Home Page](https://raw.githubusercontent.com/AshikurRahman-Peters/online_exam_system/main/screenshots/home.png)

### Admin Dashboard

![Admin Dashboard](https://raw.githubusercontent.com/AshikurRahman-Peters/online_exam_system/main/screenshots/admin-dashboard.png)

### User Dashboard

![User Dashboard](https://raw.githubusercontent.com/AshikurRahman-Peters/online_exam_system/main/screenshots/user-dashboard.png)

### Exam Page

![Exam Page](https://raw.githubusercontent.com/AshikurRahman-Peters/online_exam_system/main/screenshots/exam-page.png)

### Result Page

![Result Page](https://raw.githubusercontent.com/AshikurRahman-Peters/online_exam_system/main/screenshots/result-page.png)



## Main Modules

### Admin Panel

* Login as admin
* Create and manage exams
* Add MCQ and True/False questions
* View all exam results
* Manage question lists

### User Panel

* Register and login
* View available exams
* Start exam with timer
* Submit answers
* View result and answer review



## Future Improvements

You can improve this project by adding:

* Negative marking
* Exam categories / subjects
* User profile page
* Password hashing with `password_hash()`
* Admin exam edit/delete options
* Export results to PDF/Excel
* Leaderboard / rank system
* Email verification and password reset



## Author

**Ashikur Rahman Peters**

GitHub: [AshikurRahman-Peters](https://github.com/AshikurRahman-Peters)
