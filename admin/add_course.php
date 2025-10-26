<?php
session_start();
include "config.php";

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name']);

    if (!empty($course_name)) {
        $stmt = $conn->prepare("INSERT INTO courses (course_name) VALUES (?)");
        $stmt->bind_param("s", $course_name);
        $stmt->execute();
        $stmt->close();
        header("Location: view_courses.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container">
        <h1>Add New Course</h1>
        <form method="POST">
            <input type="text" name="course_name" placeholder="Course Name" required>
            <button type="submit">Add Course</button>
        <a href="view_courses.php">View Courses</a>

        </form>
    </div>
</body>
</html>
