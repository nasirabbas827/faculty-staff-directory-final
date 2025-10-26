<?php
session_start();
include "config.php";

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
if ($id <= 0) {
    header("Location: view_faculty.php");
    exit();
}

// Fetch faculty details
$stmt = $conn->prepare("SELECT * FROM faculty WHERE faculty_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: view_faculty.php");
    exit();
}
$faculty = $result->fetch_assoc();
$stmt->close();

// Fetch all courses
$all_courses = $conn->query("SELECT * FROM courses ORDER BY course_name ASC");

// Fetch selected courses for this faculty
$selected_courses = [];
$res = $conn->prepare("SELECT course_id FROM faculty_courses WHERE faculty_id = ?");
$res->bind_param("i", $id);
$res->execute();
$res_result = $res->get_result();
while ($row = $res_result->fetch_assoc()) {
    $selected_courses[] = $row['course_id'];
}
$res->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $designation = $_POST['designation'];
    $dept_name = $_POST['dept_name'];
    $expertise = $_POST['expertise'];
    $professional_interest = $_POST['professional_interest'];
    $selected_courses = isset($_POST['courses']) ? $_POST['courses'] : [];

    // Update faculty info
    $stmt = $conn->prepare("UPDATE faculty SET full_name=?, email=?, phone=?, designation=?, dept_name=?, expertise=?, professional_interest=? WHERE faculty_id=?");
    $stmt->bind_param("sssssssi", $full_name, $email, $phone, $designation, $dept_name, $expertise, $professional_interest, $id);
    $stmt->execute();
    $stmt->close();

    // Update courses — clear old entries first
    $conn->query("DELETE FROM faculty_courses WHERE faculty_id = $id");

    // Insert new selected courses
    foreach ($selected_courses as $course_id) {
        $stmt = $conn->prepare("INSERT INTO faculty_courses (faculty_id, course_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $id, $course_id);
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
    <title>Edit Faculty</title>
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
        <h1>Edit Faculty Member</h1>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" value="<?php echo htmlspecialchars($faculty['full_name']); ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($faculty['email']); ?>" required>
            <input type="text" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($faculty['phone']); ?>">
            <input type="text" name="designation" placeholder="Designation" value="<?php echo htmlspecialchars($faculty['designation']); ?>">
            <input type="text" name="dept_name" placeholder="Department Name" value="<?php echo htmlspecialchars($faculty['dept_name']); ?>">
            <textarea name="expertise" placeholder="Areas of Expertise"><?php echo htmlspecialchars($faculty['expertise']); ?></textarea>
            <textarea name="professional_interest" placeholder="Professional Interests"><?php echo htmlspecialchars($faculty['professional_interest']); ?></textarea>

            <label><strong>Select Courses:</strong></label>
            <div class="course-list">
                <?php while ($row = $all_courses->fetch_assoc()): ?>
                    <label class="course-item">
                        <input type="checkbox" name="courses[]" value="<?php echo $row['course_id']; ?>"
                            <?php echo in_array($row['course_id'], $selected_courses) ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($row['course_name']); ?>
                    </label>
                <?php endwhile; ?>
            </div>

            <br>
            <button type="submit">Update Faculty</button>
        <a href="view_faculty.php" class="back-link">← Back to Faculty List</a>

        </form>
    </div>
</body>
</html>
