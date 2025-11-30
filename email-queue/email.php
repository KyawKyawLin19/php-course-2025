<?php
// contact.php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ------------------- CONFIG ------------------- */
$SMTP_HOST = 'smtp.gmail.com';
$SMTP_USER = 'dreamhr8299@gmail.com';
$SMTP_PASS = 'lcntrzelmuhhvfjo';
$SMTP_PORT = 587;
$FROM_NAME = 'Contact Form';
/* --------------------------------------------- */

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---- Grab & clean input ----
    $name    = trim($_POST['name'] ?? '');
    $toEmail = trim($_POST['to_email'] ?? '');   // <-- user-typed address
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // ---- Validation ----
    if ($name === '')               $errors[] = 'กรุณากรอกชื่อ';
    if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL))
        $errors[] = 'กรุณากรอกอีเมลที่ถูกต้อง';
    if ($subject === '')            $errors[] = 'กรุณากรอกหัวข้อ';
    if ($message === '')            $errors[] = 'กรุณากรอกข้อความ';

    // ---- Send mail if no errors ----
    if (empty($errors)) {
        $mail = new PHPMailer(true);
        try {
            // SMTP
            $mail->isSMTP();
            $mail->Host       = $SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = $SMTP_USER;
            $mail->Password   = $SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $SMTP_PORT;

            // From (your Gmail)
            $mail->setFrom($SMTP_USER, $FROM_NAME);
            // To (user-typed address)
            $mail->addAddress($toEmail);
            // Reply-to (visitor's name + email)
            $mail->addReplyTo($toEmail, $name);

            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body    = "จาก: $name <$toEmail>\n\n$message";

            $mail->send();
            $success = true;
        } catch (Exception $e) {
            $errors[] = "ส่งเมลไม่ได้: {$mail->ErrorInfo}";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ส่งเมลถึงผู้ใช้</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:20px;}
        .container{max-width:560px;margin:auto;background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,.1);}
        h2{text-align:center;color:#333;}
        label{display:block;margin:12px 0 6px;font-weight:bold;color:#555;}
        input, textarea{width:100%;padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;}
        button{background:#28a745;color:#fff;padding:12px;border:none;border-radius:4px;cursor:pointer;width:100%;font-size:1rem;}
        button:hover{background:#218838;}
        .msg{color:#155724;background:#d4edda;padding:12px;border-radius:4px;margin:15px 0;}
        .err{color:#721c24;background:#f8d7da;padding:8px;border-radius:4px;margin:5px 0;}
    </style>
</head>
<body>
<div class="container">
    <h2>ส่งข้อความถึงผู้ใช้</h2>

    <?php if ($success): ?>
        <div class="msg">ส่งข้อความเรียบร้อยแล้ว!</div>
    <?php endif; ?>

    <?php foreach ($errors as $e): ?>
        <div class="err"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST" action="">
        <label>ชื่อผู้ส่ง</label>
        <input type="text" name="name" required value="<?= htmlspecialchars($name ?? '') ?>">

        <label>อีเมลผู้รับ (พิมพ์อีเมลที่ต้องการส่งถึง)</label>
        <input type="email" name="to_email" required placeholder="example@domain.com"
               value="<?= htmlspecialchars($toEmail ?? '') ?>">

        <label>หัวข้อ</label>
        <input type="text" name="subject" required value="<?= htmlspecialchars($subject ?? '') ?>">

        <label>ข้อความ</label>
        <textarea name="message" rows="5" required><?= htmlspecialchars($message ?? '') ?></textarea>

        <button type="submit">ส่งเมล</button>
    </form>
</div>
</body>
</html>