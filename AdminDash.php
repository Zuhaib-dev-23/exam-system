<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['user_id'])) {
    header('Location: admin-login.php'); // Redirect to admin login if not logged in
    exit;
}

// Admin username
$username = $_SESSION['username'];

// Include your database connection file
include('db.php');

// Fetch total students
$stmt = $pdo->prepare("SELECT COUNT(*) FROM students");
$stmt->execute();
$total_students = $stmt->fetchColumn();

// Fetch total results
$stmt = $pdo->prepare("SELECT COUNT(*) FROM results");
$stmt->execute();
$total_results = $stmt->fetchColumn();

// Fetch total subjects
$stmt = $pdo->prepare("SELECT COUNT(*) FROM subjects");
$stmt->execute();
$total_subjects = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        body.dark-mode {
            background-color: #3C3D37;
            color: white;
        }
        
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

        .card {
            margin: 10px 0;
        }
    </style>
</head>

<body>

<!-- Sidebar Overlay -->
<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- Sidebar Menu -->
<div id="sidebar">
    <a href="Home.php">Home</a>
    <a href="AdminDash.php">Admin Dashboard</a>
    <a href="manage_results.php">Results</a>
    <a href="manage_students.php">Students</a>
    <a href="admin_notify.php">Notifications</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Navbar with Sidebar Toggle -->
<div class="navbar-custom">
    <div class="d-flex align-items-center">
        <span class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
        <span class="ml-3">Admin Dashboard</span>
    </div>
    <div class="d-flex align-items-center">
        <!-- Dark Mode Button and Profile Icon (optional) -->
    </div>
</div>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="text-center">Welcome, Admin: <?php echo htmlspecialchars($username); ?>!</h2>
    <p class="text-center">You are logged in and can manage the system.</p>

    <div class="row">
        <!-- Total Students Card -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <p class="card-text"><?php echo $total_students; ?></p>
                </div>
            </div>
        </div>

        <!-- Total Results Card -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Results</h5>
                    <p class="card-text"><?php echo $total_results; ?></p>
                </div>
            </div>
        </div>

        <!-- Total Subjects Card -->
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Subjects</h5>
                    <p class="card-text"><?php echo $total_subjects; ?></p>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Toggle Sidebar function
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("show");
        document.getElementById("sidebar-overlay").style.display = 
            document.getElementById("sidebar").classList.contains("show") ? "block" : "none";
    }
</script>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
