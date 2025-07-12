<?php
include '../../connection.php'; // sesuaikan path koneksi

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = mysqli_real_escape_string($koneksi, $_POST['name']);
  $email_address = mysqli_real_escape_string($koneksi, $_POST['email_address']);
  $password_raw = $_POST['password'];
  $department = mysqli_real_escape_string($koneksi, $_POST['department']);
  $email_spv = mysqli_real_escape_string($koneksi, $_POST['email_spv']);
  $role = mysqli_real_escape_string($koneksi, $_POST['role']);

  // Validasi email sudah ada
  $cek = mysqli_query($koneksi, "SELECT 1 FROM tbl_users WHERE email_address = '$email_address'");
  if (mysqli_num_rows($cek) > 0) {
    $response['message'] = 'Email sudah digunakan.';
    echo json_encode($response);
    exit;
  }

  // Hash password dengan bcrypt (default)
  $password = password_hash($password_raw, PASSWORD_BCRYPT);

  $query = "INSERT INTO tbl_users 
              (name, email_address, password, department, email_spv, role, status_account, timestamp)
            VALUES 
              ('$name', '$email_address', '$password', '$department', '$email_spv', '$role', 'Pending', NOW())";

  if (mysqli_query($koneksi, $query)) {
    $response['success'] = true;
    $response['message'] = 'User berhasil ditambahkan.';
  } else {
    $response['message'] = 'Gagal menyimpan ke database.';
  }
} else {
  $response['message'] = 'Metode tidak diizinkan.';
}

echo json_encode($response);
