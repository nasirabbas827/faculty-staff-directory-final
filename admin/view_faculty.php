<?php
session_start();
include "config.php";

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

// Delete faculty
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM faculty WHERE faculty_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_faculty.php");
    exit();
}

// Fetch faculty with joined course names
$query = "SELECT f.*, GROUP_CONCAT(c.course_name SEPARATOR ', ') AS courses
          FROM faculty f
          LEFT JOIN faculty_courses fc ON f.faculty_id = fc.faculty_id
          LEFT JOIN courses c ON fc.course_id = c.course_id
          GROUP BY f.faculty_id
          ORDER BY f.faculty_id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Faculty</title>
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
            if (confirm("Are you sure you want to delete this faculty record?")) {
                window.location.href = "view_faculty.php?delete=" + id;
            }
        }
    </script>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container">
        <h1>Faculty Directory</h1>
        <a href="add_faculty.php" class="btn">+ Add New Faculty</a>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Courses</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['faculty_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['dept_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['courses']); ?></td>
                            <td>
                                <a href="edit_faculty.php?edit=<?php echo $row['faculty_id']; ?>">Edit</a> |
                                <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['faculty_id']; ?>)">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">No faculty found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
