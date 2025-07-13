<?php
include '../../connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $id_approval = isset($_POST['id_approval']) ? intval($_POST['id_approval']) : 0;

  // Validasi awal
  if ($id_approval <= 0 || !in_array($action, ['Approved', 'Rejected'])) {
    http_response_code(400);
    echo 'Invalid request data.';
    exit;
  }

  $status_hrd = $action;
  $hrd_name = $_SESSION['name'] ?? 'Unknown HRD';

  // Update tbl_approval
  $query = "UPDATE tbl_approval SET approval_hrd = ?, hrd_name = ?, date_hrd = NOW() WHERE id_approval = ?";
  $stmt = $koneksi->prepare($query);

  if (!$stmt) {
    http_response_code(500);
    echo 'Failed to prepare approval statement.';
    exit;
  }

  $stmt->bind_param("ssi", $status_hrd, $hrd_name, $id_approval);

  if (!$stmt->execute()) {
    http_response_code(500);
    echo 'Failed to update approval.';
    exit;
  }

  $stmt->close();

  // Ambil id_pengajuan dari tbl_approval
  $result = $koneksi->query("SELECT id_pengajuan FROM tbl_approval WHERE id_approval = $id_approval");
  if ($result && $row = $result->fetch_assoc()) {
    $id_pengajuan = $row['id_pengajuan'];
    $status_pengajuan = $action === 'Approved' ? 'Completed' : 'Rejected';

    // Update tbl_pengajuan
    $update_pengajuan = $koneksi->prepare("UPDATE tbl_pengajuan SET status = ? WHERE id_pengajuan = ?");
    if ($update_pengajuan) {
      $update_pengajuan->bind_param("si", $status_pengajuan, $id_pengajuan);
      $update_pengajuan->execute();
      $update_pengajuan->close();
    }
  }

  $koneksi->close();
  http_response_code(200);
  echo 'Success';
}
?>
