<?php
include '../../connection.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $result = mysqli_query($koneksi, "SELECT u.*, s.name AS name_spv FROM tbl_users u LEFT JOIN tbl_users s ON u.email_spv = s.email_address WHERE u.user_id = $id");
  if ($data = mysqli_fetch_assoc($result)) {
    echo json_encode($data);
  } else {
    echo json_encode(null);
  }
}
