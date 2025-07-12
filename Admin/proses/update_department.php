<?php
include '../../connection.php';

$id = $_POST['id_dept'] ?? '';
$name = trim($_POST['name_dept'] ?? '');

if ($id && $name) {
  // Cek apakah nama sudah dipakai oleh ID lain
  $checkQuery = "SELECT COUNT(*) FROM mst_dept WHERE name_dept = ? AND id_dept != ?";
  $checkStmt = $koneksi->prepare($checkQuery);
  $checkStmt->bind_param("si", $name, $id);
  $checkStmt->execute();
  $checkStmt->bind_result($count);
  $checkStmt->fetch();
  $checkStmt->close();

  if ($count > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Department name already exists.']);
    exit;
  }

  // Lanjut update
  $stmt = $koneksi->prepare("UPDATE mst_dept SET name_dept = ?, last_update = NOW() WHERE id_dept = ?");
  $stmt->bind_param("si", $name, $id);
  
  if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update.']);
  }
  $stmt->close();
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
}
?>
