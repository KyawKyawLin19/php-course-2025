<?php
// config.php - Database and email configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'EBP!23ebp');
define('DB_NAME', 'mail_queue_db');

// PHPMailer SMTP settings
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'dreamhr8299@gmail.com');
define('SMTP_PASS', 'lcntrzelmuhhvfjo');
define('SMTP_PORT', 587);
define('SMTP_FROM_EMAIL', 'dreamhr8299@gmail.com');
define('SMTP_FROM_NAME', 'Employee Mail Queue System');
// config.php - ADD THIS LINE
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>