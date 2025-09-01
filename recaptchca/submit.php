<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$secretKey = $_ENV['RECAPTCHA_SECRET_KEY'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);
    $token   = $_POST['recaptcha_token'] ?? '';

    if (empty($token)) {
        die("reCAPTCHA token missing. Try again.");
    }

    // Verify with Google using cURL
    $verifyURL = "https://www.google.com/recaptcha/api/siteverify";
    $postData = [
        'secret'   => $secretKey,
        'response' => $token,
    ];

    $ch = curl_init($verifyURL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($postData),
    ]);
    $response = curl_exec($ch);

    if ($response === false) {
        die("cURL error: " . curl_error($ch));
    }
    curl_close($ch);

    $responseData = json_decode($response, true);

    if ($responseData["success"] && $responseData["score"] >= 0.5 && $responseData["action"] === "submit") {
        // ✅ Passed
        echo "Form submitted successfully!<br>";
        echo "Name: " . htmlspecialchars($name) . "<br>";
        echo "Email: " . htmlspecialchars($email) . "<br>";
        echo "Message: " . nl2br(htmlspecialchars($message));
    } else {
        // ❌ Failed
        die("reCAPTCHA failed (score too low or invalid). Score: " . ($responseData["score"] ?? 'N/A'));
    }
} else {
    header("Location: index.php");
    exit;
}
