<?php
session_start();
include '../../connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['email_address'])) {
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit;
}

$email = $_SESSION['email_address'];
$password = $_POST['password'] ?? '';

if (strlen($password) < 6) {
  echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
  exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$update = mysqli_query($koneksi, "UPDATE tbl_users SET password = '$hashedPassword' WHERE email_address = '$email'");

if ($update) {
  echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
} else {
  echo json_encode(['success' => false, 'message' => 'Failed to update password']);
}
