<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentMatric = $_POST['currentMatric'];
    $newMatric = trim($_POST['newMatric']);
    $newName = trim($_POST['newName']);
    $newRole = $_POST['newRole'];

    // Input validation
    if (!preg_match('/^[a-zA-Z\s]+$/', $newName)) {
        die('Invalid name. Only letters and spaces are allowed.');
    }

    if (!preg_match('/^[a-zA-Z0-9]{8}$/', $newMatric)) {
        die('Invalid matric number. Must be 8 alphanumeric characters.');
    }

    // Update query
    $stmt = $conn->prepare('UPDATE users SET name = ?, matric = ?, role = ? WHERE matric = ?');
    $stmt->bind_param('ssss', $newName, $newMatric, $newRole, $currentMatric);

    if ($stmt->execute()) {
        echo 'User updated successfully. <a href="main.php">Back to dashboard</a>.';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Fetch the current data for the user
if (!isset($_GET['matric'])) {
    die('Invalid request.');
}

$currentMatric = $_GET['matric'];

$stmt = $conn->prepare('SELECT matric, name, role FROM users WHERE matric = ?');
$stmt->bind_param('s', $currentMatric);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die('User not found.');
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Update User</title>
</head>
<body>
    <div id="container">
        <h2>Update User Information</h2>
        <form method="POST">
            <!-- Current Matric -->
            <input type="hidden" name="currentMatric" value="<?= htmlspecialchars($user['matric']); ?>">

            <!-- New Matric -->
            <label>New Matric:</label><br>
            <input type="text" name="newMatric" value="<?= htmlspecialchars($user['matric']); ?>" required><br>

            <!-- New Name -->
            <label>New Name:</label><br>
            <input type="text" name="newName" value="<?= htmlspecialchars($user['name']); ?>" required><br>

            <!-- New Role -->
            <label>Role:</label><br>
            <select name="newRole" required>
                <option value="student" <?= $user['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
                <option value="lecturer" <?= $user['role'] === 'lecturer' ? 'selected' : ''; ?>>Lecturer</option>
            </select><br><br>

            <button type="submit">Update</button>
        </form>
        <a href="main.php">Cancel</a>

    </div>
</body>
</html>
