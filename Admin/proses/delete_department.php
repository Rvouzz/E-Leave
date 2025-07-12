<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id_dept'];

  $query = "DELETE FROM mst_dept WHERE id_dept = ?";
  $stmt = $koneksi->prepare($query);
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete department.']);
  }

  $stmt->close();
  $koneksi->close();
}
?>