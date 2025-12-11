<?php
session_start();

// Redirect logic must come before any HTML output
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  header("Location: pages/dashboard.php");
  exit();//
} else {
  header("Location: pages/login.php");
  exit();
}
?>