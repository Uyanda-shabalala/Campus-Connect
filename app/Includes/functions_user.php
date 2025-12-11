<?php

use LDAP\Result;
include __DIR__ . '/../config/database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../../vendor/phpmailer/phpmailer/src/Exception.php";
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';





function validate_input($conn, $name, $username, $password, $email, $Campus)
{
    if (empty($name) || empty($username) || empty($password) || empty($email) || empty($Campus)) {
        header("Location: ../../public/pages/register.php?error=emptyinput");
        exit();
    } else
        if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {    //if the username is valid aka one cap, small letter or number

            header("Location: ../../public/pages/register.php?error=invalidusername");
            exit();
        } else            //if the email is valid aka if it contains a @ and a .com or .org etc 

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header("Location: ../../public/pages/register.php?error=invalidemail");
                exit();
            }



    return true;

}
function register_user($conn, $name, $username, $password, $email, $Campus, $profile_pic)
{
    // Function to register a new user


    //setting user id for session]
    $sql = "SELECT userid from users_info WHERE userUsername=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {//if the statement failed
        header("Location: ../../public/pages/register.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) { //if the username exists
        $_SESSION['user_Id'] = $row['userid'];
    }
    ;


    include __DIR__ . '/../config/constants.php'
    ;
    if (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{8,}$/", $password)) {

        header("Location: ../../public/pages/register.php?error=weakpassword");
        exit();
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    }


    //file upload handling



    //check if username  exists 
    $sql = "SELECT * FROM users_info WHERE userUsername = ? OR userEmail = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {//if the statement failed
        header("Location: ../../public/pages/register.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) { //if the username exists
        header("Location: ../../public/pages/register.php?error=usernametaken");
        exit();
    }
    mysqli_stmt_close($stmt);

    if ($profile_pic && $profile_pic["error"] == 0) {


        $fileName = uniqid() . "_" . $profile_pic["name"];

        //creating the full path for the file
        $fileTmpName = $profile_pic["tmp_name"];
        $fileDestination = $profile_pics_path . $fileName;
        move_uploaded_file($fileTmpName, $fileDestination);
        $filePathForDB = "uploads/user_pp/" . $fileName;
        $profile_pic = $filePathForDB;//path to be stored in the database

    } else {
        $profile_pic = "uploads/user_pp/default_pp.png"; //default profile picture

    }


    //insert the user into the database
    $sql = "INSERT INTO  users_info (userName, userUsername, userPwd, userEmail, userCampus, userProfilePic) VALUES (?, ?, ?, ?, ?, ?);";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {//if the statement failed
        header("Location: ../../public/pages/register.php?error=stmtfailed");
        $_SESSION['error'] = "Statement failed";
        exit();
    } else {
        $_SESSION['error'] = "Statement prepared successfully";
    }


    mysqli_stmt_bind_param($stmt, "ssssss", $name, $username, $hashed_password, $email, $Campus, $profile_pic);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION['error'] = "User registered successfully";
    $_SESSION['username'] = [$username];
    $_SESSION['loggedin'] = true;
    $_SESSION['name'] = [$name];
    $_SESSION['profile_pic'] = [$profile_pic];
    $_SESSION['campus'] = [$Campus];





    return true;
    //end of function register_user
}



//login function

function login_user($username, $password, $conn)
{
    //function to log in a user
    //change session staus to logged in 
    //check if the account is on the database 
    // check if the password matches the usernam

    $sql = "SELECT * FROM users_info WHERE userUsername=? OR userEmail=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {//if the statement failed
        header("Location: ../../public/pages/login.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) { //check the password
        $hashed_password = $row['userPwd'];

        $checkPwd = password_verify($password, $hashed_password);
        if ($checkPwd === false) {
            header("Location: ../../public/pages/login.php?error=wrongpassword");

            exit();
        } else if ($checkPwd === true) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_Id'] = $row['userId'];
            $_SESSION['username'] = $row['userUsername'];
            $_SESSION['name'] = $row['userName'];
            $_SESSION['profile_pic'] = $row['userProfilePic'];
            $_SESSION['campus'] = $row['userCampus'];
            header("Location: dashboard.php");
        }
    } else {
        header("Location: ../../public/pages/login.php?error=nouser");
        exit();
    }

    return true;
}


function update_profile_pic($conn, $NewProfilePic): bool
{

    include __DIR__ . '/../config/constants.php';
    //error_log("Current profile picture path" . $_SESSION[""]);    



    if (isset($NewProfilePic) && $NewProfilePic["error"] == 0) {
        $fileTmpName = $NewProfilePic['tmp_name'];
        $fileType = mime_content_type($fileTmpName);

        if (!str_starts_with($fileType, "image/")) {
            error_log("Invalid file type uploaded: " . $fileType);
            header("Location: ../../public/pages/profile.php?error=invalidfiletype");
            exit();
        }

        $newFileName = uniqid() . "_" . $NewProfilePic['name'];
        $fileDestination = $profile_pics_path . $newFileName;
        move_uploaded_file($fileTmpName, $fileDestination);

        $newPathForDB = "uploads/user_pp/" . $newFileName;
        $NewProfilePic = $newPathForDB;
        if ($currentProfile_path !== "uploads/user_pp/default_pp.png") {
            //delete the old profile picture from the server
            $fullCurrentPath = __DIR__ . '/../../public/' . $currentProfile_path;
            if (file_exists($fullCurrentPath)) {
                unlink($fullCurrentPath);
            }
        }

        $sql = "UPDATE users_info Set userProfilePic=? where userId=? ";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {//if the statement failed
            header("Location: ../../public/pages/profile.php?error=stmtFfaild");
            error_log("Statement failed ");
        }

        mysqli_stmt_bind_param($stmt, 'si', $NewProfilePic, $_SESSION['user_Id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['profile_pic'] = $NewProfilePic;
        return true;
    }

    return false;

}



function update_username($conn, $newusername)
{

    $currentUserName = $_SESSION['username'];

    if ($currentUserName == $newusername) {
        echo "<p class= error style='color:red'> New username can not be the same as old </p>";
        exit();
    } else {

        //check if the username already exists 

        $sql = "SELECT * FROM users_info WHERE userUsername = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {//if the statement failed
            header("Location: ../../public/pages/register.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $newusername);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) { //if the username is found in the db and stored in row 
            echo "<p class= error style='color:red'>Username already in use </p>";
            exit();
        }
        mysqli_stmt_close($stmt);



        $sql = "UPDATE users_info SET userUsername=? WHERE userId=?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {//if the statement failed
            header("Location: ../../public/pages/register.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "si", $newusername, $_SESSION['user_Id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['username'] = $newusername;

    }

}


function search_user($username, $conn)
{

    $sql = "SELECT * from users_info where userUsername=? OR userName=?;";
    $stmt = mysqli_stmt_init($conn);


    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("Statement failed: " . mysqli_error($conn));
        return false;
    }
    mysqli_stmt_bind_param($stmt, 'ss', $username, $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        error_log("User found");
        $_SESSION['receiver_Id'] = $row['userId'];
        $_SESSION['receiver_username'] = $row['userUsername'];
        $_SESSION['receiver_name'] = $row['userName'];
        $_SESSION['receiver_profile_pic'] = $row['userProfilePic'];
        $_SESSION['receiver_campus'] = $row['userCampus'];
        mysqli_stmt_close($stmt);
        return true;
    }

    error_log("user not found");
    mysqli_stmt_close($stmt);
    return false;
}
function forgot_pass($email)
{
    global $conn;

    // 1. Check if user exists
    $stmt = $conn->prepare("SELECT userId FROM users_info WHERE userEmail=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return false; // email not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['userId'];

    // 2. Generate token
    $token = bin2hex(random_bytes(16));

    // 3. Store token in DB (optional: add expiry column)
    $stmt = $conn->prepare("UPDATE users_info SET reset_token=? WHERE userEmail=?");
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    // 4. Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@gmail.com';
        $mail->Password = 'your_app_password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email@gmail.com', 'Campus Connect');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password';
        $mail->Body = "Click this link to reset your password: <a href='http://localhost/campus_connect/reset_password.php?token=$token'>Reset Password</a>";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}


?>