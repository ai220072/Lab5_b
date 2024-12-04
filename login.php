<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Correct query to fetch matric, password, and role
    $stmt = $conn->prepare('SELECT matric, password, role FROM users WHERE matric = ?');
    $stmt->bind_param('s', $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_matric'] = $user['matric'];
        $_SESSION['user_role'] = $user['role'];
        
        // Redirect to the main page
        header('Location: main.php');
        exit();
    } else {
        //$error = '';
        die('Invalid matric or password.<a href="login.php">Back</a>');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <div id="container">
        <form method="POST">
            <label>Matric:</label><br>
            <input type="text" name="matric" required><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Do not have an account? <a href="register.php" class="link">Register Here</a></p>
    </div>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
</body>
</html>
