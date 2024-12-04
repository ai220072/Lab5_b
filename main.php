<?php
session_start();
include 'db.php';

//timeout session 5min
$timeout=300; //5min

//check if conn is active
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'])>$timeout)
{
    //session expired
    session_unset();
    session_destroy();
    header('Location: login.php?message=Session expired. Please log in again.');
    exit();
}
//endure user is logged in
if (!isset($_SESSION['user_matric'])) {
    header('Location: login.php');
    exit();
}

//fetch all users
$result = $conn->query('SELECT matric, name, role FROM users');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $matricToDelete = $_POST['delete'];
    $stmt = $conn->prepare('DELETE FROM users WHERE matric = ?');
    $stmt->bind_param('s', $matricToDelete);
    $stmt->execute();
    header('Location: main.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?= $_SESSION['user_matric']; ?></h2>
    <h3>All Users</h3>
    <table border="1">
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['matric']); ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['role']); ?></td>
            <td>
                <a href="update.php?matric=<?= urlencode($row['matric']); ?>"class="up-link">Update</a>
                <form method="POST" style="display:inline;">
                    <button type="submit" name="delete" class="del" value="<?= $row['matric']; ?>">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <footer>
        <p><a href="login.php" class="logOut">Log Out</a></p>
    </footer>
</body>
</html>
