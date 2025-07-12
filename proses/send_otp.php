<?php
session_start();
include '../connection.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email_address'] ?? '';

if (empty($email)) {
  $_SESSION['forgot_error'] = "Email address is required.";
  header("Location: ../forgot_password.php");
  exit;
}

// Cek apakah email terdaftar
$query = mysqli_query($koneksi, "SELECT * FROM tbl_users WHERE email_address = '$email'");

if ($query && mysqli_num_rows($query) > 0) {
  // Generate OTP dan simpan ke session
  $otp = rand(100000, 999999);
  $_SESSION['otp'] = $otp;
  $_SESSION['email_reset'] = $email;
  $_SESSION['otp_generated_at'] = time();

  // Konfigurasi PHPMailer
  $mail = new PHPMailer(true);

  try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'anonymousz.sender111@gmail.com';  // Ganti dengan email kamu
    $mail->Password = 'shhgavwmgjxxckme';              // Ganti dengan app password Gmail kamu
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('noreply@e-leave.com', 'E-Leave System');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code for Password Reset';
    $mail->Body = "
      <div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
        <div style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05);'>
          <h2 style='color: #0d6efd;'>E-Leave System</h2>
          <p>Hello,</p>
          <p>You requested to reset your password. Use the OTP code below to proceed:</p>
          <h1 style='text-align: center; color: #333; letter-spacing: 5px;'>$otp</h1>
          <p style='margin-top: 20px;'>This OTP is valid for the next <strong>10 minutes</strong>. Please do not share this code with anyone.</p>
          <p>If you didn’t request a password reset, you can safely ignore this email.</p>
          <hr style='margin: 30px 0;'>
          <p style='font-size: 12px; color: #777;'>E-Leave System &copy; " . date('Y') . ". All rights reserved.</p>
        </div>
      </div>
    ";

    $mail->send();

    $_SESSION['show_otp_success'] = true;
    $_SESSION['forgot_success'] = "OTP has been sent to your email address.";
    header("Location: ../forgot_password.php");
    exit;

  } catch (Exception $e) {
    $_SESSION['forgot_error'] = "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
    header("Location: ../forgot_password.php");
    exit;
  }

} else {
  $_SESSION['forgot_error'] = "Email address not found.";
  header("Location: ../forgot_password.php");
  exit;
}
