<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['email_reset']) || !isset($_POST['new_password'])) {
  header("Location: ../forgot_password.php");
  exit;
}

$email = $_SESSION['email_reset'];
$new_password = $_POST['new_password'];
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$query = mysqli_query($koneksi, "UPDATE tbl_users SET password = '$hashed_password' WHERE email_address = '$email'");

if ($query) {
  $_SESSION['reset_success'] = "Your password has been updated successfully.";
  $_SESSION['trigger_reset_alert'] = true;
  unset($_SESSION['otp_verified'], $_SESSION['email_reset']);
  header("Location: ../reset_password.php");
  exit;
} else {
  $_SESSION['otp_error'] = "Failed to update password.";
  header("Location: ../reset_password.php");
  exit;
}
