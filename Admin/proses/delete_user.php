<?php
include '../../connection.php'; // sesuaikan

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $user_id = intval($_POST['id']);

    // Bisa tambahkan validasi apakah user_id benar ada dulu (optional)
    $cek = mysqli_query($koneksi, "SELECT 1 FROM tbl_users WHERE user_id = $user_id");
    if (mysqli_num_rows($cek) == 0) {
      $response['message'] = 'User tidak ditemukan.';
      echo json_encode($response);
      exit;
    }

    $del = mysqli_query($koneksi, "DELETE FROM tbl_users WHERE user_id = $user_id");
    if ($del) {
      $response['success'] = true;
      $response['message'] = 'User berhasil dihapus.';
    } else {
      $response['message'] = 'Gagal menghapus user.';
    }
  } else {
    $response['message'] = 'ID user tidak valid.';
  }
} else {
  $response['message'] = 'Metode tidak diizinkan.';
}

echo json_encode($response);
