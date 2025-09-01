# 📌reCAPTCHA v3 Overview
- reCAPTCHA v3 helps protect your website from bots without showing a captcha challenge to most users.
- Unlike v2 (with checkbox), reCAPTCHA v3 is invisible.
- It runs in the background, analyzes the user’s interaction (mouse movements, typing, browsing behavior, etc.), and returns a score (0.0–1.0).
- The higher the score → more likely it’s a human.
Example:
```
0.9 → very likely human ✅
0.1 → likely bot ❌
```

## 🔄 reCAPTCHA v3 Lifecycle
1. Initialization
- Load the reCAPTCHA script in your frontend:
  ```
    <script src="https://www.google.com/recaptcha/api.js?render=your_site_key"></script>
  ```
2. Generate Token (Client-side)
- When a user submits a form, call grecaptcha.execute() to request a token from Google:
  ```
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
  ```
- ```<?php echo $siteKey; ?>``` = Your public site key

- {action: 'submit'} = Context label (e.g., "login", "register", "checkout")

- token = Short-lived (2 minutes) encrypted string proving Google scored the request
  
3. Send Token to Server
The form will now send both:
- User data (name, email, etc.)
- reCAPTCHA token
Example:
```
<input type="hidden" name="recaptcha_token" id="recaptcha_token">
```

4. Verify Token (Server-side)
- Your server validates the token by calling Google’s API:
```
<?php
$secretKey = "YOUR_SECRET_KEY";
$token = $_POST['recaptcha_token'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
  'secret' => $secretKey,
  'response' => $token,
  'remoteip' => $_SERVER['REMOTE_ADDR']
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if ($result['success'] && $result['score'] >= 0.5 && $result['action'] === 'submit') {
    // ✅ Human confirmed
    echo "Form submitted successfully.";
} else {
    // ❌ Bot detected or verification failed
    echo "reCAPTCHA verification failed.";
}
```

5. Interpret the Response
- The JSON response from Google looks like:
    ```
    {
        "success": true,
        "score": 0.9,
        "action": "submit",
        "challenge_ts": "2025-08-29T10:10:00Z",
        "hostname": "yourdomain.com"
    }
    ```
- success: Whether the request is valid
- score: Human-likelihood score
- action: Must match the frontend action
- challenge_ts: Timestamp of verification
- hostname: Must match your domain

## 📖 Example Flow
- User fills form → clicks Submit
- Frontend calls grecaptcha.execute() → gets token
- Token sent to server with form data
- Server verifies token with Google API
- Google responds with score + success
- Server decides → accept or reject request