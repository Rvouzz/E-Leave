<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name_dept']);

  if ($name !== '') {
    // Cek apakah nama department sudah ada
    $checkQuery = "SELECT COUNT(*) FROM mst_dept WHERE name_dept = ?";
    $checkStmt = mysqli_prepare($koneksi, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "s", $name);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_bind_result($checkStmt, $count);
    mysqli_stmt_fetch($checkStmt);
    mysqli_stmt_close($checkStmt);

    if ($count > 0) {
      echo json_encode(['status' => 'error', 'message' => 'Department name already exists']);
      exit;
    }

    // Insert jika belum ada
    $insertQuery = "INSERT INTO mst_dept (name_dept, last_update) VALUES (?, NOW())";
    $insertStmt = mysqli_prepare($koneksi, $insertQuery);
    mysqli_stmt_bind_param($insertStmt, "s", $name);

    if (mysqli_stmt_execute($insertStmt)) {
      echo json_encode(['status' => 'success', 'message' => 'Department added']);
    } else {
      echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
    }
    mysqli_stmt_close($insertStmt);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Department name is required']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
