<?php
// dashboard.php - Employee dashboard for sending emails
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'employee') {
    header("Location: login.php");
    exit;
}

require_once 'config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get list of admins
$admins = [];
$result = $conn->query("SELECT id, username, email FROM users WHERE role = 'admin'");
while ($row = $result->fetch_assoc()) {
    $admins[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    if (isset($_POST['admin_id']) && $_POST['admin_id'] == 'all') {
        // Queue emails to all admins who allow
        $stmt = $conn->prepare("SELECT email FROM users WHERE role = 'admin' AND allow_emails = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            queue_email($conn, $row['email'], $subject, $body);
        }
        $stmt->close();
        $_SESSION['message'] = "Emails queued for all allowing admins.";
    } else {
        // Specific admin
        $admin_id = $_POST['admin_id'];
        $stmt = $conn->prepare("SELECT email FROM users WHERE id = ? AND role = 'admin'");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        if ($admin) {
            queue_email($conn, $admin['email'], $subject, $body);
            $_SESSION['message'] = "Email queued for the selected admin.";
        }
        $stmt->close();
    }
}

function queue_email($conn, $to, $subject, $body) {
    $stmt = $conn->prepare("INSERT INTO email_queue (to_email, subject, body) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $to, $subject, $body);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head><title>Employee Dashboard</title></head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <?php if (isset($_SESSION['message'])) { echo $_SESSION['message']; unset($_SESSION['message']); } ?>
    <form method="POST">
        <label>Subject: <input type="text" name="subject" required></label><br>
        <label>Body: <textarea name="body" required></textarea></label><br>
        <label>Select Admin: 
            <select name="admin_id">
                <option value="all">All Allowing Admins</option>
                <?php foreach ($admins as $admin): ?>
                    <option value="<?php echo $admin['id']; ?>"><?php echo $admin['username']; ?> (<?php echo $admin['email']; ?>)</option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <input type="submit" value="Queue Email">
    </form>
    <a href="logout.php">Logout</a>
</body>
</html>