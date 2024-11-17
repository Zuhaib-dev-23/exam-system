<?php
session_start();
include "db.php";

// If the student is already logged in, redirect to the student dashboard
if (isset($_SESSION['student_id'])) {
    header("Location: student_dash.php");
    exit;
}

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_ID = $_POST['student_ID'];
    $firstName = $_POST['first_name'];

    // Check if the student exists in the database by student_ID and first_name
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_ID = :student_ID AND first_name = :first_name");
    $stmt->execute(['student_ID' => $student_ID, 'first_name' => $firstName]);
    $student = $stmt->fetch();

    // If student exists, create session and redirect to the dashboard
    if ($student) {
        $_SESSION['student_id'] = $student['student_ID'];
        $_SESSION['first_name'] = $student['first_name'];
        header("Location: student_dash.php");
        exit;
    } else {
        $error = "Invalid Student ID or First Name"; // Error message for incorrect login
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow p-4 w-100" style="max-width: 400px;">
            <h2 class="text-center mb-4">Student Login</h2>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST" action="">
            <div class="mb-3">
                    <label for="password" class="form-label">Name</label>
                    <input type="text" class="form-control" name="first_name" id="first_name" required>
                </div>
                <div class="mb-3">
                    <label for="student_ID" class="form-label">Student ID</label>
                    <input type="text" class="form-control" name="student_ID" id="student_ID" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
