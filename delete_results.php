<?php
session_start();
include('db.php');

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin1.php");
    exit();
}

// Check if the result ID is provided in the URL
if (isset($_GET['id'])) {
    $resultId = $_GET['id'];

    // Attempt to delete the result
    try {
        $stmt = $pdo->prepare("DELETE FROM results WHERE resultId = :resultId");
        $stmt->execute(['resultId' => $resultId]);

        header("Location: manage_results.php?message=Result deleted successfully!");
        exit();
    } catch (PDOException $e) {
        echo "Error deleting result: " . $e->getMessage();
    }
} else {
    echo "Invalid result ID.";
    exit();
}
?>
