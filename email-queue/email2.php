<?php
// contact.php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ------------------- CONFIG -------------------
$SMTP_HOST      = 'smtp.gmail.com';
$SMTP_USER      = 'dreamhr8299@gmail.com';
$SMTP_PASS      = 'lcntrzelmuhhvfjo';
$SMTP_PORT      = 587;
$FROM_NAME      = 'Contact Form';
$TO_EMAIL       = 'dreamhr8299@gmail.com';
$TO_NAME        = 'Admin';
// ---------------------------------------------

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // ---- simple validation ----
    if ($name === '')    $errors[] = 'Name is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if ($message === '') $errors[] = 'Message is required.';

    if (empty($errors)) {
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = $SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = $SMTP_USER;
            $mail->Password   = $SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $SMTP_PORT;

            // Recipients
            $mail->setFrom($SMTP_USER, $FROM_NAME);
            $mail->addAddress($TO_EMAIL, $TO_NAME);
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(false);
            $mail->Subject = "Contact Form: $name";
            $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

            $mail->send();
            $success = true;
        } catch (Exception $e) {
            $errors[] = "Mail could not be sent. Error: {$mail->ErrorInfo}";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Contact Form</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:20px;}
        .container{max-width:500px;margin:auto;background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,.1);}
        h2{text-align:center;}
        label{display:block;margin:10px 0 5px;font-weight:bold;}
        input, textarea{width:100%;padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:4px;}
        button{background:#007bff;color:#fff;padding:12px;border:none;border-radius:4px;cursor:pointer;width:100%;}
        button:hover{background:#0056b3;}
        .msg{color:green;margin:15px 0;}
        .err{color:red;margin:5px 0;}
    </style>
</head>
<body>
<div class="container">
    <h2>Contact Form</h2>

    <?php if ($success): ?>
        <p class="msg">Send Email Successfully!</p>
    <?php endif; ?>

    <?php foreach ($errors as $e): ?>
        <p class="err"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>

    <form method="POST" action="">
        <label>ชื่อ</label>
        <input type="text" name="name"  value="<?= htmlspecialchars($name ?? '') ?>">

        <label>อีเมล</label>
        <input type="email" name="email"  value="<?= htmlspecialchars($email ?? '') ?>">

        <label>ข้อความ</label>
        <textarea name="message" rows="5" ><?= htmlspecialchars($message ?? '') ?></textarea>

        <button type="submit">ส่งข้อความ</button>
    </form>
</div>
</body>
</html>