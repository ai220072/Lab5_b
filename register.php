<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = $_POST['matric'];
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
        die('Invalid name. Only letters and spaces are allowed.<br><a href=register.php>Back</a>');
    }

    if (!preg_match('/^[a-zA-Z0-9]{8}$/', $matric)) {
        die('Invalid matric number. Must be 8 alphanumeric characters.<br><a href=register.php>Back</a>');
    }
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $password)) {
        die('Password must be at least 8 characters long and include at least one letter, one number,
         and one special character.<br><a href=register.php>Back</a>');
    }
    

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare('INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $matric, $name, $passwordHash, $role);

    if ($stmt->execute()) {
        header('Location: login.php');
    } else {
        echo 'Error: ' . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=devide-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <div id="container">
    <form method="POST">
        <label>Matric:</label><br>
        <input type="text" name="matric" required><br>
        <label>Name:</label><br>
        <input type="text" name="name" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <label>Role:</label>
        <select name="role" required>
            <option value="student">Student</option>
            <option value="lecturer">Lecturer</option>
        </select><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php" class="link">Login Here!</a></p>
    </div>
</body>
</html>
