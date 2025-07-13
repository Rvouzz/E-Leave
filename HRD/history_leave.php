<?php
session_start(); // pastikan session dimulai
$judul = 'History';
include '../proses/check_hrd.php';
include '../connection.php';
$no = 1;
?>
<?php include '../header.php'; ?>
<div class="wrapper"> <!-- INI WAJIB -->
  <?php include '../sidebar.php'; ?> <!-- file sidebar.php kamu -->
  <div class="container">
    <div class="page-inner">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row card-tools-still-right">
                <div class="card-tools">
                  <a href="proses/export_department_pdf.php" target="_blank"
                    class="btn btn-label-info btn-round btn-sm me-2" title="Export PDF">
                    <span class="btn-label"><i class="fa fa-print"></i></span>
                    Print
                  </a>
                  <!-- <a href="proses/export_department.php" class="btn btn-label-success btn-sm me-2">
                    <span class="btn-label"><i class="fa fa-file-export"></i></span>
                    Export
                  </a> -->
                </div>

              </div>
            </div>
            <div class="card-body px-4 py-3"> <!-- Added padding here -->
              <div class="table-responsive">
                <!-- Projects table -->
                <?php
                $query = "SELECT a.id_pengajuan, a.employee_name, a.email_address, a.type, a.date_from, a.date_to, a.email_spv, a.timestamp, a.status, b.hrd_name, b.approval_hrd 
                          FROM tbl_pengajuan a 
                          LEFT JOIN tbl_approval b ON a.id_pengajuan = b.id_pengajuan 
                          WHERE b.approval_hrd IS NOT NULL 
                            AND b.approval_hrd != '' 
                            AND b.approval_hrd != 'Open'";
                $result = mysqli_query($koneksi, $query);

                $no = 1;
                ?>

                <table id="deptTable" class="table align-items-center mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col" class="text-center">No</th>
                      <th scope="col" class="text-center">Employee Name</th>
                      <th scope="col" class="text-center">Email Address</th>
                      <th scope="col" class="text-center">Type</th>
                      <th scope="col" class="text-center">Date From</th>
                      <th scope="col" class="text-center">Date To</th>
                      <th scope="col" class="text-center">Status</th>
                      <th scope="col" class="text-center">Approved By</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['employee_name']); ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['email_address']); ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['type']); ?></td>
                        <td class="text-center"><?= date('M d, Y', strtotime($row['date_from'])); ?></td>
                        <td class="text-center"><?= date('M d, Y', strtotime($row['date_to'])); ?></td>
                        <td class="text-center">
                          <span
                            class="badge 
            <?= $row['approval_hrd'] === 'Approved' ? 'bg-success' : ($row['approval_hrd'] === 'Rejected' ? 'bg-danger' : 'bg-secondary'); ?>">
                            <?= htmlspecialchars($row['approval_hrd']); ?>
                          </span>
                        </td>
                        <td class="text-center">
                          <?= htmlspecialchars(!empty($row['hrd_name']) ? $row['hrd_name'] : '-'); ?>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <?php include '../footer.php' ?>
</div>
</div> <!-- Penutup .wrapper -->




<script>
  $(document).ready(function () {
    $('#deptTable').DataTable();
  });
</script>