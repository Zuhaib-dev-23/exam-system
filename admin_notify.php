<?php
session_start();
include('db.php'); // Ensure the path to db.php is correct

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-login.php");
    exit();
}

$message = "";

// Handle adding a new notification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addNotification'])) {
    $title = $_POST['title'];
    $messageText = $_POST['message'];

    try {
        $sql = "INSERT INTO notifications (title, message) VALUES (:title, :message)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':message' => $messageText,
        ]);
        $message = "Notification added successfully!";
    } catch (PDOException $e) {
        $message = "Error adding notification: " . $e->getMessage();
    }
}

// Fetch all notifications
try {
    $stmt = $pdo->query("SELECT * FROM notifications ORDER BY created_at DESC");
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching notifications: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Notifications</title>
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
    <a href="AdminDash.php">Admin Dashboard</a>
    <a href="manage_results.php">Results</a>
    <a href="manage_students.php">Students</a>
    <a href="admin_notify.php">Notifications</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Navbar with Sidebar Toggle, Search, and Dark Mode Icon -->
<div class="navbar-custom">
    <div class="d-flex align-items-center">
        <span class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
        <span class="ml-3">Manage Notifications</span>
    </div>
    <div class="d-flex align-items-center">
        <!-- <span class="icon-btn mr-3" onclick="alert('Search functionality coming soon!')"><i class="fas fa-search"></i></span>
        <span class="icon-btn mr-3" onclick="toggleDarkMode()"><i class="fas fa-moon"></i></span>
        <img src="https://via.placeholder.com/40" alt="Profile Icon" class="rounded-circle"> -->
    </div>
</div>




    <div class="container mt-5">
        <h2>Manage Notifications</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" class="form-control" required></textarea>
            </div>
            <button type="submit" name="addNotification" class="btn btn-primary">Add Notification</button>
        </form>

        <h3>All Notifications</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($notification['title']); ?></td>
                        <td><?php echo htmlspecialchars($notification['message']); ?></td>
                        <td><?php echo htmlspecialchars($notification['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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

</body>
</html>
