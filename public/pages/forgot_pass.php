<?php
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/Includes/functions_user.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $message = forgot_pass($email); // sends email
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <?php if ($message): ?>
            <p style="margin-bottom:1rem; color:#e1306c;"><?= $message ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="submit" value="Send Reset Link">
        </form>
    </div>
</body>

</html>