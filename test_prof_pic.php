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

// Fetch student information, including profile picture
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_ID = :student_ID");
$stmt->execute(['student_ID' => $studentID]);
$student = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Profile Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <style>
        .profile-picture-container {
            position: relative;
            display: inline-block;
            text-align: center;
            margin-top: 20px;
        }
        .profile-picture-container img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .camera-icon {
            position: absolute;
            top: 70%;
            left: 75%;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container main-content">
    <h1>Welcome, <?php echo htmlspecialchars($firstName); ?>!</h1>
    
    <div class="profile-picture-container">
        <img src="<?php echo !empty($student['profile_picture']) ? htmlspecialchars($student['profile_picture']) : 'default-profile.png'; ?>" 
             alt="Profile Picture" 
             class="rounded-circle">

        <!-- Camera icon with upload link -->
        <form action="upload_photo.php" method="POST" enctype="multipart/form-data">
            <label for="profilePictureUpload" class="camera-icon">
                <i class="fas fa-camera" style="font-size: 24px;"></i>
            </label>
            <input type="file" name="profile_picture" id="profilePictureUpload" style="display: none;" onchange="this.form.submit();">
            <input type="hidden" name="student_id" value="<?php echo $studentID; ?>">
        </form>
    </div>
</div>
</body>
</html>
