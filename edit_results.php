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

    // Fetch the existing result details
    $stmt = $pdo->prepare("SELECT * FROM results WHERE resultId = :resultId");
    $stmt->execute(['resultId' => $resultId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo "Result not found.";
        exit();
    }

    // Handle form submission to update result
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $student_id = $_POST['student_id'];
        $subject_id = $_POST['subject_id'];
        $semester = $_POST['semester'];
        $marks = $_POST['marks'];
        $grade = $_POST['grade'];

        try {
            $sql = "UPDATE results SET student_id = :student_id, subject_id = :subject_id, semester = :semester, marks = :marks, grade = :grade WHERE resultId = :resultId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':student_id' => $student_id,
                ':subject_id' => $subject_id,
                ':semester' => $semester,
                ':marks' => $marks,
                ':grade' => $grade,
                ':resultId' => $resultId
            ]);
            header("Location: manage_results.php?message=Result updated successfully!");
            exit();
        } catch (PDOException $e) {
            $error = "Error updating result: " . $e->getMessage();
        }
    }
} else {
    echo "Invalid result ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Result</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Result</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" name="student_id" class="form-control" value="<?php echo htmlspecialchars($result['student_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="subject_id">Subject ID</label>
                <input type="text" name="subject_id" class="form-control" value="<?php echo htmlspecialchars($result['subject_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="semester">Semester</label>
                <input type="text" name="semester" class="form-control" value="<?php echo htmlspecialchars($result['semester']); ?>" required>
            </div>
            <div class="form-group">
                <label for="marks">Marks</label>
                <input type="number" name="marks" class="form-control" value="<?php echo htmlspecialchars($result['marks']); ?>" required>
            </div>
            <div class="form-group">
                <label for="grade">Grade</label>
                <input type="text" name="grade" class="form-control" value="<?php echo htmlspecialchars($result['grade']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Result</button>
            <a href="manage_results.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
