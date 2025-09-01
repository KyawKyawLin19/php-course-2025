<?php
//die(var_dump(extension_loaded('curl')));
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$siteKey = $_ENV['RECAPTCHA_SITE_KEY'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Contact Form with reCAPTCHA v3</title>
</head>
<body>
  <form action="submit.php" method="post" id="contactForm">
    <label>Name:</label>
    <input type="text" name="name" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" required><br><br>

    <label>Message:</label>
    <textarea name="message" required></textarea><br><br>

    <!-- hidden input for recaptcha token -->
    <input type="hidden" name="recaptcha_token" id="recaptcha_token">

    <button type="submit">Send</button>
  </form>

  <!-- Load reCAPTCHA v3 -->
  <script src="https://www.google.com/recaptcha/api.js?render=<?php echo htmlspecialchars($siteKey); ?>"></script>
  <script>
    grecaptcha.ready(function() {
      document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        grecaptcha.execute('<?php echo $siteKey; ?>', {action: 'submit'}).then(function(token) {
          document.getElementById('recaptcha_token').value = token;
          e.target.submit(); // continue form submit
        });
      });
    });
  </script>
</body>
</html>
