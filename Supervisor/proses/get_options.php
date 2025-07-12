<?php
include '../../connection.php';
header('Content-Type: application/json');

$departments = [];
$supervisors = [];

$dept_query = mysqli_query($koneksi, "SELECT name_dept FROM mst_dept ORDER BY name_dept ASC");
while ($d = mysqli_fetch_assoc($dept_query)) {
  $departments[] = $d;
}

$spv_query = mysqli_query($koneksi, "SELECT name, email_address FROM tbl_users WHERE role = 'Supervisor' ORDER BY name ASC");
while ($s = mysqli_fetch_assoc($spv_query)) {
  $supervisors[] = $s;
}

echo json_encode([
  'departments' => $departments,
  'supervisors' => $supervisors
]);
