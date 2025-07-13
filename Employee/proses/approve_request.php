<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $id_approval = isset($_POST['id_approval']) ? intval($_POST['id_approval']) : 0;

  // Validasi awal
  if ($id_approval <= 0 || !in_array($action, ['Approved', 'Rejected'])) {
    http_response_code(400);
    echo 'Invalid request data.';
    exit;
  }

  $status_spv = $action;
  $status_hrd = ($action === 'Rejected') ? 'Rejected' : 'Open';

  $query = "UPDATE tbl_approval SET approval_spv = ?, approval_hrd = ?, date_spv = NOW() WHERE id_approval = ?";
  $stmt = $koneksi->prepare($query);

  if (!$stmt) {
    http_response_code(500);
    echo 'Failed to prepare statement.';
    exit;
  }

  $stmt->bind_param("ssi", $status_spv, $status_hrd, $id_approval);

  if ($stmt->execute()) {
    http_response_code(200);
    echo 'Success';
  } else {
    http_response_code(500);
    echo 'Failed to update approval.';
  }

  $stmt->close();
  $koneksi->close();
}
?>