
<?php
// session_start();
// include "db.php"; // Include your DB connection file

// // Check if student is logged in
// if (!isset($_SESSION['student_id'])) {
//     header("Location: student_login.php");  // Redirect to login if not logged in
//     exit;
// }

// $studentID = $_SESSION['student_id'];
// $firstName = $_SESSION['first_name'];

// // Fetch notifications from the database
// $stmt = $pdo->prepare("SELECT * FROM notifications WHERE id = :id ORDER BY created_at DESC");
// $stmt->execute(['id' => $studentID]);
// $notifications = $stmt->fetchAll();

// // Fetch student profile information (for example)
// $stmt2 = $pdo->prepare("SELECT * FROM students WHERE student_ID = :student_ID");
// $stmt2->execute(['student_ID' => $studentID]);
// $student = $stmt2->fetch();
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- 
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
    </style> -->
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
        <span class="ml-3">Student Profile</span>
    </div>
    <div class="d-flex align-items-center">
        <!-- <span class="icon-btn mr-3" onclick="alert('Search functionality coming soon!')"><i class="fas fa-search"></i></span>
        <span class="icon-btn mr-3" onclick="toggleDarkMode()"><i class="fas fa-moon"></i></span> -->
        <!-- <img src="https://via.placeholder.com/40" alt="Profile Icon" class="rounded-circle"> -->
    </div>
</div>


    <div class="container mt-5">
        <h1>Welcome, <?php echo htmlspecialchars($firstName); ?>!</h1>
        <p>Your Student ID: <?php echo htmlspecialchars($studentID); ?></p>

        <!-- Display student profile information -->
        <h3>Profile Information:</h3>
        <ul>
            <li>First Name: <?php echo htmlspecialchars($student['first_name']); ?></li>
            <li>Last Name: <?php echo htmlspecialchars($student['last_name']); ?></li>
            <li>Email: <?php echo htmlspecialchars($student['email']); ?></li>
            <li>Department: <?php echo htmlspecialchars($student['department']); ?></li>
        </ul>

        <!-- Notifications Section -->
        <h3>Notifications:</h3>
        <?php if ($notifications): ?>
            <ul>
                <?php foreach ($notifications as $notification): ?>
                    <li><?php echo htmlspecialchars($notification['message']); ?> <small>(<?php echo $notification['created_at']; ?>)</small></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No notifications at the moment.</p>
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>

