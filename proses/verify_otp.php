<?php
session_start();

if ($_POST['otp_input'] == $_SESSION['otp']) {
  $_SESSION['otp_verified'] = true;
  header("Location: ../reset_password.php");
} else {
  $_SESSION['otp_error'] = "Invalid OTP. Try again.";
  header("Location: ../verify_otp.php");
}
