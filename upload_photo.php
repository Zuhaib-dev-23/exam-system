<?php
// // session_start();
// include "db.php";

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
//     $studentID = $_SESSION['student_ID'];
//     $file = $_FILES['profile_picture'];

//     // Define upload path and allowed file types
//     $uploadDir = 'uploads/';
//     $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
//     $fileType = mime_content_type($file['tmp_name']);
    
//     if (in_array($fileType, $allowedTypes)) {
//         $fileName = $uploadDir . 'profile_' . $studentID . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
//         move_uploaded_file($file['tmp_name'], $fileName);

//         // Update the database with the new profile picture path
//         $stmt = $pdo->prepare("UPDATE students SET profile_picture = :profile_picture WHERE student_ID = :student_ID");
//         $stmt->execute(['profile_picture' => $fileName, 'student_ID' => $studentID]);

//         header("Location: test_prof_pic.php");  // Redirect to profile page
//         exit;
//     } else {
//         echo "Invalid file type. Please upload a JPEG, PNG, or GIF image.";
//     }
// }
?>

<?php
// session_start();
include "db.php";  // Include your database connection
// include "profile.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $studentID = $_POST['student_id'];  // Get student ID
    $targetDirectory = "uploads/";  // Directory to store uploaded images
    $targetFile = $targetDirectory . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        exit;
    }

    // Check file size (e.g., limit to 2MB)
    if ($_FILES["profile_picture"]["size"] > 2097152) {
        echo "Sorry, your file is too large.";
        exit;
    }

    // Allow only certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit;
    }

    // Move uploaded file to target directory
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
        // Update the profile picture path in the database
        $stmt = $pdo->prepare("UPDATE students SET profile_picture = :profile_picture WHERE student_ID = :student_ID");
        $stmt->execute([
            'profile_picture' => $targetFile,
            'student_ID' => $studentID
        ]);
        // echo "The file " . htmlspecialchars(basename($_FILES["profile_picture"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    echo "No file uploaded or invalid request.";
}
?>

