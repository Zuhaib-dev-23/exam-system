<?php
// db.php
$host = 'localhost';
$dbname = 'Exam_System';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>


<?php 
// include 'db.php';  // Include your database connection

// // Define admin credentials (username and password)
// $username = 'suhaib';  // Replace with your desired admin username
// $password = '1234';  // Replace with your desired admin password

// // Hash the password for secure storage
// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// // Insert the admin into the database
// $stmt = $pdo->prepare('INSERT INTO admins (username, password) VALUES (?, ?)');
// $stmt->execute([$username, $hashedPassword]);

// echo "Admin user created successfully.";
// ?>

?>
