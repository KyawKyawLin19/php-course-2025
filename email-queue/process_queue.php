<?php
// process_queue.php - Script to process email queue (run via cron or manually)
require_once 'config.php';
require 'vendor/autoload.php'; // Composer autoload for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM email_queue WHERE status = 'pending' LIMIT 10"); // Process in batches

while ($email = $result->fetch_assoc()) {
    $mailer = new PHPMailer(true);
    try {
        $mailer->isSMTP();
        $mailer->Host = SMTP_HOST;
        $mailer->SMTPAuth = true;
        $mailer->Username = SMTP_USER;
        $mailer->Password = SMTP_PASS;
        $mailer->SMTPSecure = 'tls';
        $mailer->Port = SMTP_PORT;

        $mailer->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mailer->addAddress($email['to_email']);
        $mailer->Subject = $email['subject'];
        $mailer->Body = $email['body'];

        $mailer->send();

        $stmt = $conn->prepare("UPDATE email_queue SET status = 'sent' WHERE id = ?");
        $stmt->bind_param("i", $email['id']);
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) {
        $stmt = $conn->prepare("UPDATE email_queue SET status = 'failed' WHERE id = ?");
        $stmt->bind_param("i", $email['id']);
        $stmt->execute();
        $stmt->close();
        echo "Email failed: " . $mailer->ErrorInfo;
    }
}

$conn->close();
echo "Queue processed.";
?>