<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['user_id'])) {
    header('Location: admin1.php'); // Redirect to admin login if not logged in
    exit;
}

// Include the database connection
include "db.php";

// Fetch all students from the database
$stmt = $pdo->query('SELECT * FROM students');
$students = $stmt->fetchAll();

// Handle add/edit/delete operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_student'])) {
        // Add new student
        $student_id = $_POST['student_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // Insert new student into the database
        $stmt = $pdo->prepare('INSERT INTO students (Student_Id, first_name, last_name, department, email, password) 
                               VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$student_id, $first_name, $last_name, $department, $email, $password]);
        header('Location: manage_students.php'); // Refresh the page to show the new student
        exit;
    } elseif (isset($_POST['edit_student'])) {
        // Edit student
        $no = $_POST['No'];  // This is the primary key
        $student_id = $_POST['student_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $department = $_POST['department'];
        $email = $_POST['email'];

        // Update student data in the database
        $stmt = $pdo->prepare('UPDATE students SET Student_Id = ?, first_name = ?, last_name = ?, department = ?, email = ? 
                               WHERE No = ?');
        $stmt->execute([$student_id, $first_name, $last_name, $department, $email, $no]);
        header('Location: manage_students.php'); // Refresh the page after updating
        exit;
    } elseif (isset($_POST['delete_student'])) {
        // Delete student
        $no = $_POST['No'];  // Use No (Primary Key) to delete student
        $stmt = $pdo->prepare('DELETE FROM students WHERE No = ?');
        $stmt->execute([$no]);
        header('Location: manage_students.php'); // Refresh the page after deleting
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
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
        <span class="ml-3">Manage Students</span>
    </div>
    <div class="d-flex align-items-center">
        <!-- <span class="icon-btn mr-3" onclick="alert('Search functionality coming soon!')"><i class="fas fa-search"></i></span>
        <span class="icon-btn mr-3" onclick="toggleDarkMode()"><i class="fas fa-moon"></i></span>
        <img src="https://via.placeholder.com/40" alt="Profile Icon" class="rounded-circle"> -->
    </div>
</div>

    <div class="container mt-5">
        <h2 class="text-center">Manage Students</h2>

        <!-- Add Student Form -->
        <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add New Student</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['No']; ?></td>
                        <td><?php echo $student['student_ID']; ?></td>
                        <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                        <td><?php echo $student['department']; ?></td>
                        <td><?php echo $student['email']; ?></td>
                        <td>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editStudentModal"
                                data-no="<?php echo $student['No']; ?>"
                                data-student_id="<?php echo $student['student_ID']; ?>"
                                data-first_name="<?php echo $student['first_name']; ?>"
                                data-last_name="<?php echo $student['last_name']; ?>"
                                data-department="<?php echo $student['department']; ?>"
                                data-email="<?php echo $student['email']; ?>">Edit</button>

                            <form action="manage-students.php" method="POST" style="display:inline-block;">
                                <input type="hidden" name="No" value="<?php echo $student['No']; ?>">
                                <button type="submit" name="delete_student" class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="student_id" name="student_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="add_student" class="btn btn-success">Add Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                    <input type="hidden" id="edit_No" name="No">
                        <div class="mb-3">
                            <label for="edit_student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="edit_student_id" name="student_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="edit_department" name="department" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <button type="submit" name="edit_student" class="btn btn-primary">Save Changes</button>

                    </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Set values for the edit student modal
    var editModal = document.getElementById('editStudentModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var studentNo = button.getAttribute('data-no');
        var studentId = button.getAttribute('data-student_id');
        var firstName = button.getAttribute('data-first_name');
        var lastName = button.getAttribute('data-last_name');
        var department = button.getAttribute('data-department');
        var email = button.getAttribute('data-email');

        // Set the input fields in the modal
        document.getElementById('edit_No').value = studentNo;
        document.getElementById('edit_student_id').value = studentId;
        document.getElementById('edit_first_name').value = firstName;
        document.getElementById('edit_last_name').value = lastName;
        document.getElementById('edit_department').value = department;
        document.getElementById('edit_email').value = email;
    });
</script>

</body>

</html>
