<?php
session_start();
$judul = 'Dashboard';
include '../proses/check_employee.php';
include '../connection.php';
$email_address = $_SESSION['email_address'];
?>
<?php include '../header.php'; ?>
<div class="wrapper">
  <?php include '../sidebar.php'; ?>
  <div class="container">
    <div class="page-inner">

      <style>
        .timeline li {
          position: relative;
          padding-left: 30px;
        }

        .timeline li::before {
          content: '';
          position: absolute;
          top: 6px;
          left: 10px;
          width: 10px;
          height: 10px;
          background-color: #0d6efd;
          border-radius: 50%;
        }

        .timeline li:not(:last-child)::after {
          content: '';
          position: absolute;
          top: 18px;
          left: 14px;
          height: 100%;
          width: 2px;
          background-color: #dee2e6;
        }

        ul.timeline.list-unstyled.ps-0::before {
          display: none !important;
        }
      </style>

      <!-- Statistic Cards -->
      <div class="row">
        <div class="col-sm-6 col-md-3 mb-3">
          <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
              <div class="text-info bg-light rounded-circle p-3 me-3">
                <i class="fas fa-file-alt fa-2x"></i>
              </div>
              <div>
                <p class="mb-1 text-muted small">Total Submissions</p>
                <h4 class="mb-0 fw-bold">
                  <?php
                  $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE email_address = '$email_address'"));
                  echo $q['total'];
                  ?>
                </h4>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-3 mb-3">
          <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
              <div class="text-primary bg-light rounded-circle p-3 me-3">
                <i class="fas fa-hourglass-start fa-2x"></i>
              </div>
              <div>
                <p class="mb-1 text-muted small">Waiting Approval</p>
                <h4 class="mb-0 fw-bold">
                  <?php
                  $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE status = 'Open' AND email_address = '$email_address'"));
                  echo $q['total'];
                  ?>
                </h4>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-3 mb-3">
          <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
              <div class="text-success bg-light rounded-circle p-3 me-3">
                <i class="fas fa-check-circle fa-2x"></i>
              </div>
              <div>
                <p class="mb-1 text-muted small">Completed</p>
                <h4 class="mb-0 fw-bold">
                  <?php
                  $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE status = 'Completed' AND email_address = '$email_address'"));
                  echo $q['total'];
                  ?>
                </h4>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-3 mb-3">
          <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
              <div class="text-danger bg-light rounded-circle p-3 me-3">
                <i class="fas fa-times-circle fa-2x"></i>
              </div>
              <div>
                <p class="mb-1 text-muted small">Rejected</p>
                <h4 class="mb-0 fw-bold">
                  <?php
                  $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE status = 'Rejected' AND email_address = '$email_address'"));
                  echo $q['total'];
                  ?>
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Leave Insights -->
      <?php
      $totalDays = 0;
      $totalReq = 0;
      $q = mysqli_query($koneksi, "SELECT DATEDIFF(date_to, date_from) + 1 AS days FROM tbl_pengajuan WHERE email_address = '$email_address'");
      while ($row = mysqli_fetch_assoc($q)) {
        $totalDays += $row['days'];
        $totalReq++;
      }
      $averageDays = $totalReq > 0 ? round($totalDays / $totalReq, 1) : 0;
      ?>
      <div class="row" style="margin-top: -1.5rem">
        <div class="col-md-6">
          <div class="alert alert-primary shadow-sm"><strong>Total Leave Days:</strong> <?= $totalDays ?> day</div>
        </div>
        <div class="col-md-6">
          <div class="alert alert-info shadow-sm"><strong>Average Days per Leave:</strong> <?= $averageDays ?> day
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <!-- Recent Leave Requests -->
        <div class="col-md-6">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white text-center">
              <h5 class="mb-0">Recent Leave Requests</h5>
            </div>
            <div class="card-body">
              <?php
              $query = mysqli_query($koneksi, "SELECT * FROM tbl_pengajuan WHERE email_address = '$email_address' ORDER BY timestamp DESC LIMIT 2");
              if (mysqli_num_rows($query) > 0):
                while ($row = mysqli_fetch_assoc($query)):
                  $badgeClass = match ($row['status']) {
                    'Open' => 'warning',
                    'Completed' => 'success',
                    'Rejected' => 'danger',
                    default => 'secondary',
                  };
                  $progress = match ($row['status']) {
                    'Open' => 33,
                    'Completed' => 100,
                    'Rejected' => 100,
                    default => 0,
                  };
                  $statusLabel = $row['status'] === 'Rejected' ? 'Rejected ❌' : ($row['status'] === 'Completed' ? 'Completed ✅' : 'In Progress ⏳');
                  ?>
                  <div class="mb-3 border rounded shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div>
                        <strong><?= htmlspecialchars($row['type']) ?></strong>
                        <div class="text-muted small">From: <?= $row['date_from'] ?> → <?= $row['date_to'] ?></div>
                      </div>
                      <span class="badge bg-<?= $badgeClass ?>"><?= $statusLabel ?></span>
                    </div>
                    <div class="progress mb-1" style="height: 6px;">
                      <div class="progress-bar bg-<?= $badgeClass ?>" role="progressbar" style="width: <?= $progress ?>%;"
                        aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">Submitted: <?= date("d M Y", strtotime($row['timestamp'])) ?></small>
                  </div>
                  <?php
                endwhile;
              else:
                echo "<p class='text-muted text-center'>No recent leave requests.</p>";
              endif;
              ?>
            </div>
          </div>
        </div>

        <!-- Approval Timeline -->
        <div class="col-md-6">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
              <h5 class="mb-0 text-center">Approval Timeline (Latest)</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <?php
                $sql = "SELECT 
          p.type, p.timestamp AS submit_time, p.status, 
          a.approval_spv, a.spv_name, a.date_spv, 
          a.approval_hrd, a.hrd_name, a.date_hrd
        FROM tbl_pengajuan p
        LEFT JOIN tbl_approval a ON p.id_pengajuan = a.id_pengajuan
        WHERE p.email_address = '$email_address'
        ORDER BY p.timestamp DESC LIMIT 2";
                $result = mysqli_query($koneksi, $sql);

                if (mysqli_num_rows($result) > 0):
                  while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <div class="col-md-6 mb-4">
                      <h6 class="text-center text-primary mb-3"><?= htmlspecialchars($row['type']) ?> Request</h6>
                      <ul class="timeline list-unstyled ps-0">
                        <li class="mb-3">
                          <div>
                            <span class="badge bg-secondary"><i class="fas fa-paper-plane me-1"></i> Submitted</span>
                          </div>
                          <small class="text-muted"><?= date("d M Y", strtotime($row['submit_time'])) ?></small>
                        </li>

                        <li class="mb-3">
                          <div>
                            <?php if ($row['approval_spv'] === 'Approved'): ?>
                              <span class="badge bg-success"><i class="fas fa-user-check me-1"></i> Approved by SPV</span>
                              <small
                                class="text-muted d-block mt-1"><?= htmlspecialchars($row['spv_name']) ?><br><?= date("d M Y", strtotime($row['date_spv'])) ?></small>
                            <?php elseif ($row['approval_spv'] === 'Rejected'): ?>
                              <span class="badge bg-danger"><i class="fas fa-user-times me-1"></i> Rejected by SPV</span>
                              <small
                                class="text-muted d-block mt-1"><?= htmlspecialchars($row['spv_name']) ?><br><?= date("d M Y", strtotime($row['date_spv'])) ?></small>
                            <?php else: ?>
                              <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Waiting SPV
                                Approval</span>
                            <?php endif; ?>
                          </div>
                        </li>

                        <li>
                          <div>
                            <?php if ($row['approval_hrd'] === 'Approved'): ?>
                              <span class="badge bg-success"><i class="fas fa-user-check me-1"></i> Approved by HRD</span>
                              <small
                                class="text-muted d-block mt-1"><?= htmlspecialchars($row['hrd_name']) ?><br><?= date("d M Y", strtotime($row['date_hrd'])) ?></small>
                            <?php elseif ($row['approval_hrd'] === 'Rejected'): ?>
                              <span class="badge bg-danger"><i class="fas fa-user-times me-1"></i> Rejected by HRD</span>
                              <small
                                class="text-muted d-block mt-1"><?= htmlspecialchars($row['hrd_name']) ?><br><?= date("d M Y", strtotime($row['date_hrd'])) ?></small>
                            <?php else: ?>
                              <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Waiting HRD
                                Approval</span>
                            <?php endif; ?>
                          </div>
                        </li>
                      </ul>
                    </div>
                    <?php
                  endwhile;
                else:
                  ?>
                  <div class="col-12">
                    <p class="text-muted text-center">No leave requests found.</p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>


      </div>

      <!-- Leave Status Donut Chart -->
      <?php
      $openCount = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE status = 'Open' AND email_address = '$email_address'"))['total'];
      $completedCount = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE status = 'Completed' AND email_address = '$email_address'"))['total'];
      $rejectedCount = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE status = 'Rejected' AND email_address = '$email_address'"))['total'];
      ?>
      <div class="row mt-4">
        <div class="col-md-6">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
              <h5 class="mb-0 text-center">Leave Status Overview</h5>
            </div>
            <div class="card-body">
              <canvas id="leaveChart" height="150px"></canvas>
            </div>
          </div>
        </div>

        <!-- Approval Duration Insights -->
        <div class="col-md-6">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-white text-center">
              <h5 class="mb-0">Approval Duration Insights</h5>
            </div>
            <div class="card-body">
              <?php
              // Inisialisasi nilai total & counter
              $totalSpvHours = 0;
              $countSpv = 0;
              $totalHrdHours = 0;
              $countHrd = 0;

              $query = mysqli_query($koneksi, "
        SELECT p.timestamp AS submitted, 
               a.date_spv, a.date_hrd
        FROM tbl_pengajuan p
        LEFT JOIN tbl_approval a ON p.id_pengajuan = a.id_pengajuan
        WHERE p.email_address = '$email_address'
          AND p.status = 'Completed'
      ");

              while ($row = mysqli_fetch_assoc($query)) {
                $submitted = strtotime($row['submitted']);

                if (!empty($row['date_spv'])) {
                  $spvApproved = strtotime($row['date_spv']);
                  $totalSpvHours += ($spvApproved - $submitted) / 3600; // dalam jam
                  $countSpv++;
                }

                if (!empty($row['date_hrd'])) {
                  $hrdApproved = strtotime($row['date_hrd']);
                  $totalHrdHours += ($hrdApproved - $submitted) / 3600; // dalam jam
                  $countHrd++;
                }
              }

              $avgSpv = $countSpv > 0 ? round($totalSpvHours / $countSpv, 2) : '-';
              $avgHrd = $countHrd > 0 ? round($totalHrdHours / $countHrd, 2) : '-';
              ?>

              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Average SPV Approval Time:
                  <span class="badge bg-info rounded-pill"><?= is_numeric($avgSpv) ? $avgSpv . ' hrs' : 'N/A' ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Average HRD Approval Time:
                  <span
                    class="badge bg-primary rounded-pill"><?= is_numeric($avgHrd) ? $avgHrd . ' hrs' : 'N/A' ?></span>
                </li>
              </ul>
            </div>
          </div>
        </div>


      </div>

    </div>
  </div>
  <?php include '../footer.php'; ?>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('leaveChart').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Open', 'Completed', 'Rejected'],
        datasets: [{
          label: 'Leave Requests',
          data: [<?= $openCount ?>, <?= $completedCount ?>, <?= $rejectedCount ?>],
          backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  });
</script>