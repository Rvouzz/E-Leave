<?php
include '../../connection.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id          = intval($_POST['user_id']);
  $name        = mysqli_real_escape_string($koneksi, $_POST['name']);
  $email       = mysqli_real_escape_string($koneksi, $_POST['email_address']);
  $dept        = mysqli_real_escape_string($koneksi, $_POST['department']);
  $email_spv   = mysqli_real_escape_string($koneksi, $_POST['email_spv']);
  $role        = mysqli_real_escape_string($koneksi, $_POST['role']);
  $password    = $_POST['password'];

  if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $query = "UPDATE tbl_users SET name='$name', email_address='$email', department='$dept', email_spv='$email_spv', role='$role', password='$hashed' WHERE user_id=$id";
  } else {
    $query = "UPDATE tbl_users SET name='$name', email_address='$email', department='$dept', email_spv='$email_spv', role='$role' WHERE user_id=$id";
  }

  if (mysqli_query($koneksi, $query)) {
    $response['success'] = true;
    $response['message'] = 'Data user berhasil diupdate.';
  } else {
    $response['message'] = 'Gagal mengupdate data.';
  }
}

echo json_encode($response);
