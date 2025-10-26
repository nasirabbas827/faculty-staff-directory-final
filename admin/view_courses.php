<?php
session_start();
include "config.php";

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

// Delete course (if requested)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_courses.php");
    exit();
}

// Fetch all courses
$result = $conn->query("SELECT * FROM courses ORDER BY course_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courses</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        .btn{
            margin: 30px;
            text-decoration: none;
            float: right;
        }
    </style>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this course?")) {
                window.location.href = "view_courses.php?delete=" + id;
            }
        }
    </script>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container">
        <h1>All Courses</h1>
        <a href="add_course.php" class="btn">+ Add New Course</a>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['course_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td>
                                <a href="edit_course.php?edit=<?php echo $row['course_id']; ?>">Edit</a> |
                                <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['course_id']; ?>)">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3">No courses found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
