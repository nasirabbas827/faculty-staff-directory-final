<?php
session_start();
// Include the configuration file, which should contain $conn (database connection)
include "config.php";

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

// --- 1. Handle DELETE operation ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $faculty_id_to_delete = intval($_GET['delete']);
    
    // SQL to delete the faculty member (ON DELETE CASCADE should handle faculty_courses)
    $delete_sql = "DELETE FROM faculty WHERE faculty_id = ?";
    
    if ($stmt = $conn->prepare($delete_sql)) {
        $stmt->bind_param("i", $faculty_id_to_delete);
        
        if ($stmt->execute()) {
            // Success: Redirect to dashboard to refresh list and remove delete parameter
            header("Location: admin_dashboard.php?status=deleted");
            exit();
        } else {
            // Error handling (for development/debugging)
            echo "Error deleting record: " . $stmt->error;
        }
        $stmt->close();
    }
}


// --- 2. Fetch total faculty (already present) ---
$total_faculty_query = $conn->query("SELECT COUNT(*) AS total FROM faculty");
$total_faculty = $total_faculty_query->fetch_assoc()['total'];

// --- 3. Fetch faculty with courses (already present) ---
$faculty_query = "
    SELECT 
        f.faculty_id, f.full_name, f.dept_name, f.designation,
        f.email, f.phone, f.expertise, f.professional_interest,
        GROUP_CONCAT(c.course_name SEPARATOR ', ') AS courses
    FROM faculty f
    LEFT JOIN faculty_courses fc ON f.faculty_id = fc.faculty_id
    LEFT JOIN courses c ON fc.course_id = c.course_id
    GROUP BY f.faculty_id
    ORDER BY f.faculty_id DESC
";
$faculty = $conn->query($faculty_query);

if (!$faculty) {
    die("Database query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | LearSphere</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        /* General Styles */
        body { background: #f0f2f5; color: #333; }
        .container { max-width: 1250px; margin: 0 auto; padding: 20px; }
        .header {
            background: #fff; padding: 20px; border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 25px;
        }
        .header h1 { color: #2c3e50; font-size: 24px; margin: 0; }
        .logout-btn {
            background: #e74c3c; color: #fff; padding: 8px 16px;
            border-radius: 5px; text-decoration: none; transition: 0.3s;
        }
        .logout-btn:hover { background: #c0392b; }

        /* Stat Card */
        .stat-card {
            background: #fff; padding: 20px; border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center; width: 250px; margin-bottom: 25px;
        }
        .stat-card h3 { font-size: 32px; color: #2c3e50; margin-bottom: 10px; }
        .stat-card p { color: #7f8c8d; font-size: 14px; }

        /* Section & Add Button */
        .section {
            background: #fff; padding: 20px; border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow-x: auto; /* Handle overflow for wide tables */
        }
        .section h2 {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 15px; color: #2c3e50;
        }
        .add-btn {
            background: #2ecc71; color: #fff; padding: 8px 16px;
            border-radius: 5px; text-decoration: none; transition: 0.3s;
        }
        .add-btn:hover { background: #27ae60; }
        
        /* Table Styles */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
        th { background: #2c3e50; color: #fff; font-weight: 600; text-transform: uppercase; }
        tr:hover { background: #f8f9fa; }
        
        /* Action Buttons */
        .action-links a { 
            margin-right: 10px; 
            text-decoration: none; 
            font-weight: bold; 
            padding: 5px 10px;
            border-radius: 3px;
            transition: background 0.2s;
            display: inline-block;
        }
        .edit-btn { 
            color: #2980b9; /* Blue */
        }
        .edit-btn:hover { 
            background: #ecf0f1; 
        }
        .delete-btn { 
            color: #c0392b; /* Red */
        }
        .delete-btn:hover { 
            background: #ecf0f1; 
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container">
        <div class="header">
            <h1>Admin Dashboard </h1>
        </div>

        <div class="stat-card">
            <h3><?php echo $total_faculty; ?></h3>
            <p>Total Faculty Members</p>
        </div>
            <h2>
                Faculty Profiles
            </h2>
        <div class="section">

            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Courses Taught</th>
                        <th>Expertise</th>
                        <th>Interests</th>
                        <th>Contact</th>
                        <th>Actions</th> <!-- Added Actions header -->
                    </tr>
                </thead>
                <tbody>
                    <?php if ($faculty->num_rows > 0): ?>
                        <?php while ($row = $faculty->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['faculty_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['designation']); ?></td>
                                <td><?php echo htmlspecialchars($row['dept_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['courses'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['expertise']); ?></td>
                                <td><?php echo htmlspecialchars($row['professional_interest']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($row['email']); ?><br>
                                    <span style="color:#7f8c8d;"><?php echo htmlspecialchars($row['phone']); ?></span>
                                </td>
                                <td class="action-links">
                                    <!-- Edit Link -->
                                    <a href="add_faculty.php?edit=<?php echo $row['faculty_id']; ?>" class="edit-btn">Edit</a>
                                    <!-- Delete Link with JavaScript confirmation -->
                                    <a href="admin_dashboard.php?delete=<?php echo $row['faculty_id']; ?>" 
                                       class="delete-btn" 
                                       onclick="return confirm('WARNING: Are you sure you want to permanently delete <?php echo addslashes($row['full_name']); ?>?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9" style="text-align:center;">No faculty found. Click 'Add New Faculty' to start.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>