<?php
session_start();
include 'db.php'; // Database connection

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student-login.php"); // Redirect if not logged in
    exit;
}

// Get the logged-in student ID from the session
$student_id = $_SESSION['student_id'];

// Fetch the student's results from the database
$query = "SELECT r.subject_id, r.marks, r.grade, r.semester, r.exam_date, s.subject_name
          FROM results r
          JOIN subjects s ON r.subject_id = s.subject_id
          WHERE r.student_id = :student_id";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Bootstrap 4.5 -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<style>
        /* General and Dark Mode */
        body.dark-mode {
            background-color: #3C3D37;
            color: white;
            
        }
        /* body.darkmode {
            color: black;
        } */
        /* body.dark-mode, tbody{
            color: white;
        } */
        /* Navbar Styling */
        .navbar-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #333;
            padding: 10px;
            color: white;
            position: relative;
            z-index: 1001;
        }

        /* Sidebar Styling */
        #sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: -250px;
            background-color: #222;
            color: white;
            transition: all 0.3s;
            z-index: 1002;
        }

        #sidebar.show {
            left: 0;
        }

        #sidebar a {
            padding: 15px 20px;
            display: block;
            color: white;
            text-decoration: none;
        }

        #sidebar a:hover {
            background-color: #575757;
        }

        /* Overlay for Sidebar */
        #sidebar-overlay {
            position: fixed;
            display: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        /* Content */
        .main-content {
            padding: 20px;
            color: black;
        }

        .toggle-btn {
            font-size: 1.5em;
            cursor: pointer;
            color: white;
        }

        .icon-btn {
            font-size: 1.2em;
            cursor: pointer;
            color: white;
        }
    </style>
</head>
<body>

<!-- Sidebar Overlay -->
<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- Sidebar Menu -->
<div id="sidebar">
    <a href="Home.php">Home</a>
    <a href="student_dash.php">Dashboard</a>
    <a href="stud-results.php">Results</a>
    <a href="notify.php">Notifications</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Navbar with Sidebar Toggle, Search, and Dark Mode Icon -->
<div class="navbar-custom">
    <div class="d-flex align-items-center">
        <span class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
        <span class="ml-3">Student Results</span>
    </div>
    <div class="d-flex align-items-center">
        <!-- <span class="icon-btn mr-3" onclick="alert('Search functionality coming soon!')"><i class="fas fa-search"></i></span> -->
        <!-- <span class="icon-btn mr-3" onclick="toggleDarkMode()"><i class="fas fa-moon"></i></span> -->
        <!-- <img src="https://via.placeholder.com/40" alt="Profile Icon" class="rounded-circle"> -->
    </div>
</div>

<!-- Results Table -->
<div class="container mt-5">
    <h2>Your Exam Results</h2>
    <p>Below is a table of your exam results by subject and semester.</p>

    <?php if (count($results) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Grade</th>
                    <th>Semester</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($result['subject_name']); ?></td>
                        <td><?php echo htmlspecialchars($result['marks']); ?></td>
                        <td><?php echo htmlspecialchars($result['grade']); ?></td>
                        <td><?php echo htmlspecialchars($result['semester']); ?></td>
                        <td><?php //echo htmlspecialchars($result['exam_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-info">No results available.</p>
    <?php endif; ?>
</div>

<script>
    // Toggle Sidebar function
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("show");
        document.getElementById("sidebar-overlay").style.display = 
            document.getElementById("sidebar").classList.contains("show") ? "block" : "none";
    }

    // Toggle Dark Mode function
    function toggleDarkMode() {
        document.body.classList.toggle("dark-mode");
    }
</script>
<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->
<!-- 
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

</body>
</html>
