<?php
session_start();
include "db.php"; // Include your DB connection file

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");  // Redirect to login if not logged in
    exit;
}

$studentID = $_SESSION['student_id'];
$firstName = $_SESSION['first_name'];

// Fetch notifications from the database
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE id = :id ORDER BY created_at DESC");
$stmt->execute(['id' => $studentID]);
$notifications = $stmt->fetchAll();

// Fetch student profile information (for example)
$stmt2 = $pdo->prepare("SELECT * FROM students WHERE student_ID = :student_ID");
$stmt2->execute(['student_ID' => $studentID]);
$student = $stmt2->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
            z-index: 1050; /* Ensure it appears over the main content */
        }
        #sidebar.show {
            left: 0;
        }
        #sidebar a {
            padding: 15px 20px;
            display: block;
            color: white;
        }
        .main-content {
            padding: 20px;
            margin-top: 20px;
        }
        .profile-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2em;
            color: #333;
            margin-right: 20px;
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

<!-- Navbar with Sidebar Toggle -->
<div class="navbar-custom">
    <div class="d-flex align-items-center">
        <span class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
        <span class="ml-3">Student Profile</span>
    </div>
</div>

<div class="container main-content">
<div class="profile-container">
    <div class="profile-picture-container text-center">
        <!-- Display the current profile picture or a default one -->
        <img src="<?php echo !empty($student['profile_picture']) ? htmlspecialchars($student['profile_picture']) : 'default-profile.png'; ?>" 
             alt="Profile Picture" 
             class="rounded-circle" 
             width="150" height="150">
        
        <!-- Camera icon with upload form -->
        <form action="upload_photo.php" method="POST" enctype="multipart/form-data" style="position: relative; display: inline-block;">
            <label for="profilePictureUpload" class="camera-icon" style="position: absolute; top: 70%; left: 75%; cursor: pointer;">
                <i class="fas fa-camera" style="font-size: 24px;"></i>
            </label>
            <input type="file" name="profile_picture" id="profilePictureUpload" style="display: none;" onchange="this.form.submit();">
            <input type="hidden" name="student_id" value="<?php echo $studentID; ?>">
        </form>
    </div>
    <div>
        <h1>Welcome, <?php echo htmlspecialchars($firstName); ?>!</h1>
        <p>Your Student ID: <?php echo htmlspecialchars($studentID); ?></p>
    </div>
</div>

    <!-- Profile Picture and Name -->
    <!-- <div class="profile-container">
       <?php // include "upload_photo.php"; ?>
       
        <div class="profile-pic">
            <i class="fas fa-user"></i>
        </div>
        <div>
            <h1>Welcome, <?php echo htmlspecialchars($firstName); ?>!</h1>
            <p>Your Student ID: <?php echo htmlspecialchars($studentID); ?></p>
        </div>
    </div> -->

    <!-- Personal Information Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3>Profile Information</h3>
        </div>
        <div class="card-body">
            <ul class="list-unstyled">
            <li><strong>Full Name:</strong> <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></li>

                <li><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></li>
                <li><strong>Department:</strong> <?php echo htmlspecialchars($student['department']); ?></li>
            </ul>
        </div>
    </div>

    <!-- Notifications Card -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h3>Notifications</h3>
        </div>
        <div class="card-body">
            <?php if ($notifications): ?>
                <ul class="list-group">
                    <?php foreach ($notifications as $notification): ?>
                        <li class="list-group-item">
                            <?php echo htmlspecialchars($notification['message']); ?>
                            <small class="text-muted float-right"><?php echo $notification['created_at']; ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">No notifications at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("show");
        document.getElementById("sidebar-overlay").style.display = 
            document.getElementById("sidebar").classList.contains("show") ? "block" : "none";
    }

    function toggleDarkMode() {
        document.body.classList.toggle("dark-mode");
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
