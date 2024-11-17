


<?php 
include "db.php";
// Start session and check if student is logged in
session_start();


if (!isset($_SESSION['student_id'])) {
    header("Location: student-login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch recent grades/results from the database
$results_stmt = $pdo->prepare("
    SELECT r.semester, r.marks, r.grade, s.subject_name 
    FROM results r 
    JOIN subjects s ON r.subject_id = s.subject_id 
    WHERE r.student_id = :student_id 
    ORDER BY r.exam_date DESC 
    LIMIT 2
");
$results_stmt->execute(['student_id' => $student_id]);
$results = $results_stmt->fetchAll();

// Fetch all notifications from the database
$notif_stmt = $pdo->prepare("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 2");
$notif_stmt->execute();
$notifications = $notif_stmt->fetchAll();


// Fetch grade distribution for the student
$grade_dist_stmt = $pdo->prepare("SELECT grade, COUNT(*) as count 
                                  FROM results 
                                  WHERE student_id = :student_id 
                                  GROUP BY grade 
                                  ORDER BY FIELD(grade, 'A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'F')");
$grade_dist_stmt->execute(['student_id' => $student_id]);
$grade_distribution = $grade_dist_stmt->fetchAll();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Notifications</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
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
    <a href="student_dash.php">Student Dashboard</a>
    <a href="stud-results.php">Results</a>
    <a href="notify.php">Notifications</a>
    <a href="profile.php">Profile</a>
    <a href="student_logout.php">Logout</a>
</div>

<!-- Navbar with Sidebar Toggle, Search, and Dark Mode Icon -->
<div class="navbar-custom">
    <div class="d-flex align-items-center">
        <span class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
        <span class="ml-3">Student Dashboard</span>
    </div>
    <div class="d-flex align-items-center">
        <!-- <span class="icon-btn mr-3" onclick="alert('Search functionality coming soon!')"><i class="fas fa-search"></i></span>
        <span class="icon-btn mr-3" onclick="toggleDarkMode()"><i class="fas fa-moon"></i></span>
        <img src="https://via.placeholder.com/40" alt="Profile Icon" class="rounded-circle"> -->
    </div>
</div>



<!-- Main Content for Dashboard -->

<div class="main-content">
    <div class="container mt-5">
        <h2>Welcome to Your Dashboard</h2>
        <p>Manage your academic progress and notifications here.</p>

        <!-- Recent Grades/Results Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4>Recent Grades</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($results)): ?>
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($results, 0, 5) as $result): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($result['subject_name']); ?></td>
                                    <td><?php echo htmlspecialchars($result['marks']); ?></td>
                                    <td><?php echo htmlspecialchars($result['grade']); ?></td>
                                    <td><?php echo htmlspecialchars($result['semester']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No grades available.</p>
                <?php endif; ?>
            </div>
        </div>


        <!-- Recent Grades/Results Section -->
<div class="card mb-4">
    <div class="card-header bg-success  text-white">
        <h4>Grade Classification</h4>
    </div>
    <div class="card-body">
        <?php if (!empty($latest_grades)): ?>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Subject</th>
                        <th>Marks</th>
                        <th>Grade</th>
                        <th>Semester</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($latest_grades as $grade): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($grade['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($grade['marks']); ?></td>
                            <td><?php echo htmlspecialchars($grade['grade']); ?></td>
                            <td><?php echo htmlspecialchars($grade['semester']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No grades available.</p>
        <?php endif; ?>

        <!-- Grade Distribution Table -->
        <h5 class="mt-4">Grade Distribution</h5>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Grade</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grade_distribution as $dist): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dist['grade']); ?></td>
                        <td><?php echo htmlspecialchars($dist['count']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


        <!-- Notifications Section -->
<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        Notifications
    </div>
    <div class="card-body">
        <?php if (!empty($notifications)): ?>
            <ul class="list-group">
                <?php foreach ($notifications as $notification): ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($notification['message']); ?>
                        <small class="text-muted float-right"><?php echo htmlspecialchars($notification['created_at']); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted">No notifications at the moment.</p>
        <?php endif; ?>
    </div>
</div>


        <!-- Quick Actions Section -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4>Quick Actions</h4>
            </div>
            <div class="card-body d-flex justify-content-around">
                <a href="stud-results.php" class="btn btn-primary btn-lg">Check Results</a>
                <a href="profile.php" class="btn btn-success btn-lg">View Profile</a>
                <a href="notify.php" class="btn btn-warning btn-lg">View Notifications</a>
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










<?php
// Start session and check if student is logged in
// session_start();
 include "db.php";

// if (!isset($_SESSION['student_id'])) {
//     header("Location: student-login.php");
//     exit();
// }

// $student_id = $_SESSION['student_id'];

// // Fetch student results from database
// $stmt = $pdo->prepare("SELECT r.semester, r.marks, r.grade, r.exam_date, s.subject_name 
//                        FROM results r 
//                        JOIN subjects s ON r.subject_id = s.subject_id 
//                        WHERE r.student_id = :student_id");
// $stmt->execute(['student_id' => $student_id]);
// $results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

<!-- Sidebar Menu csd-->
<!-- <div id="sidebar">
    <a href="Home.php">Home</a>
    <a href="student_dash.php">Dashboard</a>
    <a href="stud-results.php">Results</a>
    <a href="notify.php">Notifications</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div> -->

<!-- Navbar with Sidebar Toggle, Search, and Dark Mode Icon -->
<!-- <div class="navbar-custom">
    <div class="d-flex align-items-center">
        <span class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
        <span class="ml-3">Student Dashboard</span>
    </div>
    <div class="d-flex align-items-center">
        <!-- <span class="icon-btn mr-3" onclick="alert('Search functionality coming soon!')"><i class="fas fa-search"></i></span>
        <span class="icon-btn mr-3" onclick="toggleDarkMode()"><i class="fas fa-moon"></i></span>
        <img src="https://via.placeholder.com/40" alt="Profile Icon" class="rounded-circle"> -->
    </div>
</div> -->

<!-- Main Content for Results -->
<!-- <div class="main-content">
    <div class="container mt-5">
        <h2>Your Exam Results</h2>
        <p>Below is a table of your exam results by subject and semester.</p> -->

        <!-- Result Table (content to be fetched and displayed from database) -->
        <!-- <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Grade</th>
                    <th>Semester</th>
                     <th>Exam Date</th> 
                </tr>
            </thead>
            <tbody> -->
                <!-- Example data (Replace with actual database data) -->
                <?php //foreach ($results as $result): ?>
                            <tr>
                                <td><?php //echo htmlspecialchars($result['subject_name']); ?></td>
                                <td><?php //echo htmlspecialchars($result['marks']); ?></td>
                                <td><?php // echo htmlspecialchars($result['grade']); ?></td>
                                <td><?php //echo htmlspecialchars($result['semester']); ?></td>
                                <!-- <td><?php //echo htmlspecialchars($result['exam_date']); ?></td> -->
                            </tr>
                        <?php //endforeach; ?>
            </tbody>
        </table>
    </div>
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

<!-- Bootstrap JS and Font Awesome -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

</body>
</html>






<?php
// Start session and check if student is logged in
// session_start();
// include "db.php";

// if (!isset($_SESSION['student_id'])) {
//     header("Location: student-login.php");
//     exit();
// }

// $student_id = $_SESSION['student_id'];

// // Fetch student results from database
// $stmt = $pdo->prepare("SELECT r.semester, r.marks, r.grade, r.exam_date, s.subject_name 
//                        FROM results r 
//                        JOIN subjects s ON r.subject_id = s.subject_id 
//                        WHERE r.student_id = :student_id");
// $stmt->execute(['student_id' => $student_id]);
// $results = $stmt->fetchAll();
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Results</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> <!-- Ensure font-awesome works here -->
</head>
<body> -->
    <!-- Hamburger Menu and Navbar -->
    <!-- <div class="d-flex">
        <nav id="sidebar" class="bg-dark">
            <ul class="list-unstyled">
                <li><a href="StudentDash.php">Dashboard</a></li>
                <li><a href="StudentResults.php">Results</a></li>
                <li><a href="notifications.php">Notifications</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <div class="container-fluid"> -->
            <!-- Top Navbar with profile icon and search -->
            <!-- <nav class="navbar navbar-expand navbar-light bg-light">
                <button id="sidebarToggle" class="btn"><i class="fa fa-bars"></i></button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="fa fa-search"></i></a>
                    </li>
                    <li class="nav-item">
                        <a href="profile.php" class="nav-link"><i class="fa fa-user"></i></a>
                    </li>
                    <li class="nav-item">
                        <a href="toggle-darkmode.php" class="nav-link"><i class="fa fa-moon"></i></a>
                    </li>
                </ul>
            </nav> -->

            <!-- Student Results Content -->
            <!-- <div class="container mt-4">
                <h2>Results Overview</h2>
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Semester</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Grade</th>
                            <th>Exam Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $result): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($result['semester']); ?></td>
                                <td><?php echo htmlspecialchars($result['subject_name']); ?></td>
                                <td><?php echo htmlspecialchars($result['marks']); ?></td>
                                <td><?php echo htmlspecialchars($result['grade']); ?></td>
                                <td><?php echo htmlspecialchars($result['exam_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->

    <!-- <script>
        document.getElementById("sidebarToggle").onclick = function() {
            document.getElementById("sidebar").classList.toggle("active");
        };
    </script>
</body>
</html> -->
