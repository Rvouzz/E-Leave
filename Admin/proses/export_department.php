<?php
include '../../connection.php';

// Set header agar browser tahu ini file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="department_list.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Open output stream
$output = fopen('php://output', 'w');

// Header kolom
fputcsv($output, ['No', 'Department Name', 'Last Update']);

// Query data
$query = "SELECT id_dept, name_dept, last_update FROM mst_dept ORDER BY name_dept ASC";
$result = mysqli_query($koneksi, $query);

// Isi data
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $no++,
        $row['name_dept'],
        date('Y-m-d H:i:s', strtotime($row['last_update']))
    ]);
}

// Tutup output
fclose($output);
exit;
