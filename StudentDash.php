<?php
// session_start();
// include('db.php');

// // Check if the user is logged in as a student
// if (!isset($_SESSION['student_id'])) {
//     header("Location: studentLogin.php"); // Redirect to student login if not logged in
//     exit();
// }

// // Get student data from the session
// $student_id = $_SESSION['student_id'];

// // Fetch student information from the database
// $query = "SELECT * FROM students WHERE Student_Id = '$student_id'";
// $result = mysqli_query($conn, $query);
// $student = mysqli_fetch_assoc($result);

// // Fetch exam results for the student
// $resultsQuery = "SELECT * FROM results WHERE student_id = '$student_id'";
// $results = mysqli_query($conn, $resultsQuery);


session_start();

// Check if the student is logged in, if not, redirect to login page
if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student_login.php");
    exit();
}

// Fetch student details from session
$student_id = $_SESSION['student_id'];
$first_name = $_SESSION['first_name'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Student Dashboard</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="studentDashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Welcome, <?php echo $student['first_name'] . ' ' . $student['last_name']; ?>!</h2>
        <p><strong>Student ID:</strong> <?php echo $student['Student_Id']; ?></p>
        <p><strong>Department:</strong> <?php echo $student['department']; ?></p>
        <p><strong>Email:</strong> <?php echo $student['email']; ?></p>

        <hr>
        <h3>Your Exam Results:</h3>
        <?php if (mysqli_num_rows($results) > 0) : ?>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Semester</th>
                        <th>Marks</th>
                        <th>Grade</th>
                        <th>Exam Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($resultRow = mysqli_fetch_assoc($results)) : ?>
                        <tr>
                            <td><?php echo $resultRow['subject_id']; // Replace with subject name ?></td>
                            <td><?php echo $resultRow['semester']; ?></td>
                            <td><?php echo $resultRow['marks']; ?></td>
                            <td><?php echo $resultRow['grade']; ?></td>
                            <td><?php echo $resultRow['exam_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No exam results found.</p>
        <?php endif; ?>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
