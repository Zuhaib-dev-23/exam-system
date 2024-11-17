<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['university_id'])) {
    header("Location: login.php");
    exit;
}

$university_id = $_SESSION['university_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_number = $_POST['student_number'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $department = $_POST['department'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO students (university_id, student_number, first_name, last_name, department, email, password)
                           VALUES (:university_id, :student_number, :first_name, :last_name, :department, :email, :password)");
    $stmt->execute([
        'university_id' => $university_id,
        'student_number' => $student_number,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'department' => $department,
        'email' => $email,
        'password' => $password
    ]);

    echo "Student added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
</head>
<body>
    <h1>Manage Students</h1>
    <form method="POST" action="">
        <input type="text" name="student_number" placeholder="Student Number" required>
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="text" name="department" placeholder="Department" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Add Student</button>
    </form>
</body>
</html>
