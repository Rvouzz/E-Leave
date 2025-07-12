<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_POST['user_id'];
  $action = $_POST['action'];
  $status = ($action === 'Active') ? 'Active' : 'Rejected';

  $query = "UPDATE tbl_users SET status_account = ? WHERE user_id = ?";
  $stmt = $koneksi->prepare($query);
  $stmt->bind_param("si", $status, $userId);

  if ($stmt->execute()) {
    http_response_code(200);
    echo 'Success';
  } else {
    http_response_code(500);
    echo 'Failed';
  }

  $stmt->close();
  $koneksi->close();
}
?>