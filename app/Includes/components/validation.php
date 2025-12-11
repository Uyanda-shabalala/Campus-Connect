<?php
if (!$_SESSION['loggedin']) {
    header("Location: login.php");
    exit();
}
?>