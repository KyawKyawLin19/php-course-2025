<?php
// register.php - Handles registration for employees and admins
session_start();
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // 'employee' or 'admin'

    $allow_emails = ($role == 'admin') ? (isset($_POST['allow_emails']) ? 1 : 0) : 1; // Employees don't need this

    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, allow_emails) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $email, $role, $allow_emails);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
    <h2>Register</h2>
    <?php if (isset($_SESSION['message'])) { echo $_SESSION['message']; unset($_SESSION['message']); } ?>
    <form method="POST">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <label>Role: 
            <select name="role">
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
            </select>
        </label><br>
        <label>If Admin, Allow Emails? <input type="checkbox" name="allow_emails" checked></label><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>