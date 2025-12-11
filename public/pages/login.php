<?php
session_start();
include_once '../../app/config/database.php';

?>

<head>
    <title>login - Campus Connect</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<main class="container">
    <h2>Login to Campus Connect</h2>


    <form action="" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>

    <?php
    include_once '../../app/Includes/functions_user.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!login_user($email, $password, $conn)) {
            echo '<p class="error" style=color:red;>Incorrect password or email.Please Try again.</p>';
        } else {
            header("Location: dashboard.php");
            exit();
        }
    } ?>

    <p>
        Forgot your password?
        <a href="forgot_pass.php">Reset here</a>
    </p>

    <p>
        Don’t have an account?
        <a href="register.php" name="register">Register now</a>
    </p>
</main>
<?php include '../../app/Includes/components/footer.php'; ?>