<?php
session_start();
include '../../app/Includes/components/header.php';

?>


<head>
    <title>Register - Campus Connect</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<main class="container">
    <h2>Create your account</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <?php
        include_once '../../app/Includes/functions_user.php';

        if (isset($_GET['error']) && $_GET['error'] == 'weakpassword') {
            echo '<p class="error" style=color: red ; >password is too weak,Must comprise of</p>';
            echo '<ul style=color: red; >
                    <li>At least 8 characters</li>
                    <li>At least one uppercase letter</li>
                    <li>At least one lowercase letter</li>
                    <li>At least one number</li>
                    <li>At least one special character</li>
                  </ul>';
        }
        ?>
        <input type="text" name="username" placeholder="Username" required>
        <?php


        if (isset($_GET['error']) && $_GET['error'] == 'usernametaken') {
            echo '<p class="error" style=color:red;>That username is already taken. Try another one.</p>';
        }

        ?>
        <label>
            Campus
        </label>

        <select name="Campus" required class="campus">

            <?php
            include __DIR__ . '/../../app/config/constants.php';
            foreach ($campuses as $campus) {
                echo "<option value='$campus'>$campus</option>";
            }
            ?>
        </select>
        <br>

        <label for="profilePicture">Profile Picture</label>
        <input type="file" name="profile_pic" accept="image">
        <input type="submit" value="sign up" name="submit">
    </form>

    <?php


    include_once '../../app/config/database.php';


    if (isset($_POST['submit'])) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['fullname'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $Campus = $_POST['Campus'];
            $profile_pic = $_FILES['profile_pic'];


            if (!register_user($conn, $name, $username, $password, $email, $Campus, $profile_pic)) {
                echo "Error registering user.";
            } else {


                header("Location: ../../public/pages/dashboard.php");
                exit();
            }

        }

    } ?>

    <p>
        Already have an account?
        <a href="login.php">Login here</a>
    </p>

</main>
<?php include '../../app/Includes/components/footer.php'; ?>