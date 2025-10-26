<?php
session_start();
include "config.php";

// Search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// SQL query to fetch faculty with joined courses
$query = "
    SELECT f.faculty_id, f.full_name, f.email, f.phone, f.designation, f.dept_name, 
           f.expertise, f.professional_interest,
           GROUP_CONCAT(c.course_name SEPARATOR ', ') AS courses_taught
    FROM faculty f
    LEFT JOIN faculty_courses fc ON f.faculty_id = fc.faculty_id
    LEFT JOIN courses c ON fc.course_id = c.course_id
";

// Apply search filter
if (!empty($search)) {
    $query .= " WHERE 
        f.full_name LIKE ? OR 
        f.dept_name LIKE ? OR 
        f.expertise LIKE ? OR 
        f.professional_interest LIKE ? OR 
        c.course_name LIKE ?";
}

$query .= " GROUP BY f.faculty_id ORDER BY f.full_name ASC";

$stmt = $conn->prepare($query);

if (!empty($search)) {
    $param = "%$search%";
    $stmt->bind_param("sssss", $param, $param, $param, $param, $param);
}

$stmt->execute();
$faculty_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearSphere - Online Learning Platform</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { line-height: 1.6; color: #333; background: #f9f9f9; }
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('./images/img.jpg');
            background-size: cover; background-position: center; color: white;
            height: 500px; display: flex; align-items: center; justify-content: center; text-align: center;
        }
        .hero-content { max-width: 800px; padding: 20px; }
        .hero h1 { font-size: 3em; margin-bottom: 20px; }
        .hero p { font-size: 1.2em; margin-bottom: 30px; }
        .cta-button {
            display: inline-block; padding: 15px 30px; background: #2ecc71; color: white;
            text-decoration: none; border-radius: 5px; transition: background 0.3s;
        }
        .cta-button:hover { background: #27ae60; }
        .section { padding: 60px 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section-title { text-align: center; margin-bottom: 40px; }
        .section-title h2 { font-size: 2em; color: #2c3e50; }
        .faculty {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px; padding: 20px;
        }
        .faculty-card {
            background: white; border-radius: 10px; padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s; text-align: center;
        }
        .faculty-card:hover { transform: translateY(-5px); }
        .faculty-card h3 { margin-bottom: 10px; color: #2c3e50; }
        .faculty-card p { color: #555; font-size: 0.9em; margin: 5px 0; }
        footer {
            background: #2c3e50; color: white; padding: 40px 20px; text-align: center;
        }
        .search-box {
            max-width: 600px; margin: 0 auto 30px; display: flex; gap: 10px;
        }
        .search-box input {
            flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px;
        }
        .search-box button {
            padding: 10px 20px; background: #2ecc71; border: none; border-radius: 5px; color: white;
            cursor: pointer;
        }
        .search-box button:hover { background: #27ae60; }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <!-- Hero -->
    <div class="hero">
        <div class="hero-content">
            <h1>Welcome to LearSphere</h1>
            <p>Empowering Learning Anytime, Anywhere</p>
            <a href="#faculty" class="cta-button">Explore Faculty</a>
        </div>
    </div>

    <!-- Faculty Directory -->
    <section class="section" id="faculty">
        <div class="container">
            <div class="section-title">
                <h2>Our Faculty</h2>
                <p>Meet our dedicated faculty members and explore their expertise</p>
            </div>

            <!-- Search -->
            <form class="search-box" method="GET" action="#faculty">
                <input type="text" name="search" placeholder="Search by name, department, courses, expertise" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>

            <div class="faculty">
                <?php if ($faculty_result && $faculty_result->num_rows > 0): ?>
                    <?php while ($row = $faculty_result->fetch_assoc()): ?>
                        <div class="faculty-card">
                            <h3><?php echo htmlspecialchars($row['full_name']); ?></h3>
                            <p><strong>Designation:</strong> <?php echo htmlspecialchars($row['designation']); ?></p>
                            <p><strong>Department:</strong> <?php echo htmlspecialchars($row['dept_name']); ?></p>
                            <p><strong>Courses:</strong> <?php echo htmlspecialchars($row['courses_taught'] ?: 'N/A'); ?></p>
                            <p><strong>Expertise:</strong> <?php echo htmlspecialchars($row['expertise']); ?></p>
                            <p><strong>Interests:</strong> <?php echo htmlspecialchars($row['professional_interest']); ?></p>
                            <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align:center; color:#777;">No faculty members found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> LearSphere. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
