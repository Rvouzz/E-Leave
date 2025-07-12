<?php
session_start(); // pastikan session dimulai
$judul = 'Dashboard';
include '../proses/check_supervisor.php';
include '../connection.php';
?>
<?php include '../header.php'; ?>
<div class="wrapper"> <!-- INI WAJIB -->
  <?php include '../sidebar.php'; ?> <!-- file sidebar.php kamu -->
  <div class="container">
    <div class="page-inner">
      <!-- <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
          <h3 class="fw-bold mb-3">Dashboard</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
          <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
          <a href="#" class="btn btn-primary btn-round">Add Customer</a>
        </div>
      </div> -->
      <div class="row">

        <!-- Employees -->
        <div class="col-sm-6 col-md-4">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-info bubble-shadow-small">
                    <i class="fas fa-users"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Employees</p>
                    <h4 class="card-title">
                      <?php
                      $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_users WHERE status_account = 'Active' AND email_spv = '$email_address'"));
                      echo $q['total'];
                      ?>
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Supervisors -->
        <div class="col-sm-6 col-md-4">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-primary bubble-shadow-small">
                    <i class="fas fa-hourglass-half"></i>

                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Waiting Approval</p>
                    <h4 class="card-title">
                      <?php
                      $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_pengajuan a LEFT JOIN tbl_approval b ON a.id_pengajuan = b.id_pengajuan WHERE  a.email_spv = '$email_address'"));
                      echo $q['total'];
                      ?>
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submissions -->
        <div class="col-sm-6 col-md-4">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-secondary bubble-shadow-small">
                    <i class="fas fa-file-signature"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Submissions</p>
                    <h4 class="card-title">
                      <?php
                      $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE email_spv = '$email_address'"));
                      echo $q['total'];
                      ?>
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-5">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row">
                <div class="card-title">Submissions Overview</div>
                <div class="card-tools">
                  <button class="btn btn-label-success btn-round btn-sm me-2" title="Export Data">
                    <span class="btn-label">
                      <i class="fa fa-file-export"></i>
                    </span>
                    Export
                  </button>
                  <a href="#" class="btn btn-label-info btn-round btn-sm">
                    <span class="btn-label">
                      <i class="fa fa-print"></i>
                    </span>
                    Print
                  </a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-container" style="min-height: 375px">
                <canvas id="statisticsChart"></canvas>
              </div>
              <div id="myChartLegend"></div>
            </div>
          </div>
        </div>

        <div class="col-md-7">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row card-tools-still-right">
                <div class="card-title">Approval History</div>
                <!-- <div class="card-tools">
                  <button class="btn btn-label-success btn-round btn-sm me-2" title="Export Data">
                    <span class="btn-label">
                      <i class="fa fa-file-export"></i>
                    </span>
                    Export
                  </button>
                  <a href="#" class="btn btn-label-info btn-round btn-sm">
                    <span class="btn-label">
                      <i class="fa fa-print"></i>
                    </span>
                    Print
                  </a>
                </div> -->
              </div>
            </div>
            <div class="card-body p-2">
              <div class="table-responsive">
                <!-- Projects table -->
                <table id="approvalTable" class="table align-items-center mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col" class="text-center">Employee Name</th>
                      <th scope="col" class="text-center">Date & Time</th>
                      <th scope="col" class="text-center">Type</th>
                      <th scope="col" class="text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = "SELECT a.id_pengajuan, a.employee_name, a.email_address, a.type, a.date_from, a.date_to, a.email_spv, a.timestamp, a.status, b.approval_spv
              FROM tbl_pengajuan a LEFT JOIN tbl_approval b ON a.id_pengajuan = b.id_pengajuan WHERE a.email_spv = '$email_address' AND b.approval_spv != 'Open'";

                    $result = mysqli_query($koneksi, $query);

                    if (mysqli_num_rows($result) > 0) {
                      while ($row = mysqli_fetch_assoc($result)) {
                        $name = $row['employee_name'];
                        $name_encoded = urlencode($name);
                        $avatar_url = "https://ui-avatars.com/api/?name={$name_encoded}&background=random&color=fff&rounded=true";
                        $timestamp = date("M d, Y, g:i A", strtotime($row['timestamp']));
                        $type = $row['type'];
                        $status = $row['approval_spv'];

                        // Badge styling based on status
                        $badgeClass = 'badge-secondary';
                        if ($status === 'Approved')
                          $badgeClass = 'badge-success';
                        elseif ($status === 'Rejected')
                          $badgeClass = 'badge-danger';
                        ?>
                        <tr>
                          <th scope="row" class="d-flex align-items-center">
                            <img src="<?= $avatar_url ?>" alt="Avatar" class="avatar-img rounded-circle me-2"
                              style="width: 35px; height: 35px;">
                            <?= htmlspecialchars($name) ?>
                          </th>
                          <td class="text-end"><?= $timestamp ?></td>
                          <td class="text-end" style="width: 20%;"><?= htmlspecialchars($type) ?></td>
                          <td class="text-end">
                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                          </td>
                        </tr>
                        <?php
                      }
                    } else {
                      echo '<tr><td colspan="4" class="text-center">No data available.</td></tr>';
                    }
                    ?>
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
    $('#approvalTable').DataTable({
      "pageLength": 5,
      "lengthChange": false,
      "ordering": true
    });
  });
</script>