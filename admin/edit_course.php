<?php
session_start();
include "config.php";

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$course_name = "";

// Fetch existing data
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
        $course_name = $course['course_name'];
    } else {
        header("Location: view_courses.php");
        exit();
    }
    $stmt->close();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name']);
    if (!empty($course_name)) {
        $stmt = $conn->prepare("UPDATE courses SET course_name = ? WHERE course_id = ?");
        $stmt->bind_param("si", $course_name, $id);
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
    <title>Edit Course</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container">
        <h1>Edit Course</h1>
        <form method="POST">
            <input type="text" name="course_name" value="<?php echo htmlspecialchars($course_name); ?>" required>
            <button type="submit">Update Course</button>
        <a href="view_courses.php" class="back-link">← Back to Course List</a>

        </form>
    </div>
</body>
</html>
