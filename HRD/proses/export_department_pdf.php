<?php
require('../../FPDF/fpdf.php');
include '../../connection.php';

$pdf = new FPDF('L', 'mm', 'A4'); // Landscape mode
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Department Leave Report (HRD Approved/Rejected)', 0, 1, 'C');
$pdf->Ln(5);

// Total table width = 252 mm, so center it on A4 (297mm) => left margin = (297-252)/2 = 22.5
$leftMargin = (297 - 252) / 2;

// Table Header
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->SetX($leftMargin);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Name', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Email', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Type', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'From', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'To', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Approved By', 1, 0, 'C', true);
$pdf->Cell(22, 8, 'Status', 1, 1, 'C', true);

// Fetch Data
$query = "SELECT a.id_pengajuan, a.employee_name, a.email_address, a.type, a.date_from, a.date_to, a.email_spv, a.timestamp, a.status, b.hrd_name, b.approval_hrd 
          FROM tbl_pengajuan a 
          LEFT JOIN tbl_approval b ON a.id_pengajuan = b.id_pengajuan 
          WHERE b.approval_hrd IS NOT NULL 
            AND b.approval_hrd != '' 
            AND b.approval_hrd != 'Open'";
$result = mysqli_query($koneksi, $query);

$pdf->SetFont('Arial', '', 10);
$no = 1;

while ($row = mysqli_fetch_assoc($result)) {
  $hrd_name = (!empty($row['hrd_name'])) ? $row['hrd_name'] : '-';

  $pdf->SetX($leftMargin); // Center each row too
  $pdf->Cell(10, 8, $no++, 1, 0, 'C');
  $pdf->Cell(50, 8, substr($row['employee_name'], 0, 35), 1, 0, 'C');
  $pdf->Cell(45, 8, substr($row['email_address'], 0, 35), 1, 0, 'C');
  $pdf->Cell(25, 8, $row['type'], 1, 0, 'C');
  $pdf->Cell(25, 8, $row['date_from'], 1, 0, 'C');
  $pdf->Cell(25, 8, $row['date_to'], 1, 0, 'C');
  $pdf->Cell(50, 8, substr($hrd_name, 0, 35), 1, 0, 'C');
  $pdf->Cell(22, 8, $row['status'], 1, 1, 'C');
}

$pdf->Output('I', 'leave_report.pdf');
