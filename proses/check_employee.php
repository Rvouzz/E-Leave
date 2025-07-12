<?php
if (!isset($_SESSION['email_address']) || $_SESSION['role'] !== 'Employee') {
  session_unset();
  session_destroy();
  header("Location: ../authentication-login.php");
  exit();
}