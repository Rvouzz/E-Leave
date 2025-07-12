<?php
require('../../FPDF/fpdf.php');
include '../../connection.php';

// Query data
$query = "SELECT id_dept, name_dept, last_update FROM mst_dept ORDER BY name_dept ASC";
$result = mysqli_query($koneksi, $query);

// Init PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Department List', 0, 1, 'C');

// Header Table
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(200, 220, 255);

// Widths
$w_no = 15;
$w_name = 115;
$w_date = 60;

// Header row
$pdf->Cell($w_no, 10, 'No', 1, 0, 'C', true);
$pdf->Cell($w_name, 10, 'Department Name', 1, 0, 'C', true);
$pdf->Cell($w_date, 10, 'Last Update', 1, 1, 'C', true);

// Data Rows
$pdf->SetFont('Arial', '', 10);
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell($w_no, 8, $no++, 1, 0, 'C');
    $pdf->Cell($w_name, 8, $row['name_dept'], 1);
    $pdf->Cell($w_date, 8, date('M d, Y', strtotime($row['last_update'])), 1, 1);
}

// Output
$pdf->Output('I', 'department_list.pdf');
