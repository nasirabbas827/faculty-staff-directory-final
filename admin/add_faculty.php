<?php
session_start();
include "config.php";

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

// Fetch all courses for selection
$courses = $conn->query("SELECT * FROM courses ORDER BY course_name ASC");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $designation = $_POST['designation'];
    $dept_name = $_POST['dept_name'];
    $expertise = $_POST['expertise'];
    $professional_interest = $_POST['professional_interest'];
    $selected_courses = isset($_POST['courses']) ? $_POST['courses'] : [];

    // Insert faculty
    $stmt = $conn->prepare("INSERT INTO faculty (full_name, email, phone, designation, dept_name, expertise, professional_interest, created_by) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $full_name, $email, $phone, $designation, $dept_name, $expertise, $professional_interest, $_SESSION['admin_id']);
    $stmt->execute();
    $faculty_id = $stmt->insert_id;
    $stmt->close();

    // Insert selected courses into faculty_courses
    foreach ($selected_courses as $course_id) {
        $stmt = $conn->prepare("INSERT INTO faculty_courses (faculty_id, course_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $faculty_id, $course_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: view_faculty.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Faculty</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        .course-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .course-item {
            background: #f8f9fa;
            border: 1px solid #ccc;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .course-item input {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container">
        <h1>Add Faculty Member</h1>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone">
            <input type="text" name="designation" placeholder="Designation">
            <input type="text" name="dept_name" placeholder="Department Name">
            <textarea name="expertise" placeholder="Areas of Expertise"></textarea>
            <textarea name="professional_interest" placeholder="Professional Interests"></textarea>

            <label><strong>Select Courses:</strong></label>
            <div class="course-list">
                <?php while ($row = $courses->fetch_assoc()): ?>
                    <label class="course-item">
                        <input type="checkbox" name="courses[]" value="<?php echo $row['course_id']; ?>">
                        <?php echo htmlspecialchars($row['course_name']); ?>
                    </label>
                <?php endwhile; ?>
            </div>

            <br>
            <button type="submit">Add Faculty</button>
        <a href="view_faculty.php" class="back-link">Faculty List</a>

        </form>
    </div>
</body>
</html>
