<?php
session_start();
include '../../connection.php';

$name = $_SESSION['name'];
$email = $_SESSION['email_address'];

$type = $_POST['type'];
$date_from = $_POST['date_from'];
$date_to = $_POST['date_to'];
$reason = mysqli_real_escape_string($koneksi, $_POST['reason']);

// Ambil email SPV dari tbl_users
$getSpv = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT email_spv FROM tbl_users WHERE email_address = '$email'"));
$email_spv = $getSpv ? $getSpv['email_spv'] : null;

// Ambil nama SPV berdasarkan email_spv
$spv_name = null;
if ($email_spv) {
  $getSpvName = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT name FROM tbl_users WHERE email_address = '$email_spv'"));
  $spv_name = $getSpvName ? $getSpvName['name'] : null;
}

// Handle file upload (jika Sick Leave)
$proofPath = null;
if ($type === 'Sick Leave' && isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {
  $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
  if (in_array($_FILES['proof']['type'], $allowedTypes)) {
    $uploadDir = '../../Upload/';
    if (!is_dir($uploadDir))
      mkdir($uploadDir, 0777, true);

    $ext = pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION);
    $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', $name);
    $fileName = 'MC_' . time() . '.' . $ext;
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['proof']['tmp_name'], $targetPath)) {
      $proofPath = $fileName;
    }
  } else {
    http_response_code(400);
    echo "Invalid file type. Only JPG, PNG, or PDF allowed.";
    exit();
  }
}

// Insert ke tbl_pengajuan
$insertPengajuan = "INSERT INTO tbl_pengajuan (
  email_address, employee_name, type, date_from, date_to, reason, status, timestamp, proof_file, email_spv
) VALUES (
  '$email', '$name', '$type', '$date_from', '$date_to', '$reason', 'Open', NOW(), " .
  ($proofPath ? "'$proofPath'" : "NULL") . ", " .
  ($email_spv ? "'$email_spv'" : "NULL") . ")";

if (mysqli_query($koneksi, $insertPengajuan)) {
  $id_pengajuan = mysqli_insert_id($koneksi);

  // Insert ke tbl_approval dengan nama SPV yang sudah diambil
  $insertApproval = "INSERT INTO tbl_approval (id_pengajuan, approval_spv, spv_name) 
                     VALUES ($id_pengajuan, 'Open', " . ($spv_name ? "'" . mysqli_real_escape_string($koneksi, $spv_name) . "'" : "NULL") . ")";

  if (mysqli_query($koneksi, $insertApproval)) {
    echo "Request submitted successfully.";
  } else {
    http_response_code(500);
    echo "Failed to insert approval.";
  }
} else {
  http_response_code(500);
  echo "Failed to submit request.";
}
