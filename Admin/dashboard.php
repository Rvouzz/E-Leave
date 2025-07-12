<?php
session_start(); // pastikan session dimulai
$judul = 'Dashboard';
include '../proses/check_admin.php';
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
        <!-- Departments (NEW) -->
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-warning bubble-shadow-small">
                    <i class="fas fa-building"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Departments</p>
                    <h4 class="card-title">
                      <?php
                      $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mst_dept"));
                      echo $q['total'];
                      ?>
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Employees -->
        <div class="col-sm-6 col-md-3">
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
                      $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_users WHERE status_account = 'Active'"));
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
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-primary bubble-shadow-small">
                    <i class="fas fa-user"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Pending Approval</p>
                    <h4 class="card-title">
                      <?php
                      $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_users WHERE status_account = 'Pending'"));
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
        <div class="col-sm-6 col-md-3">
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
                    <h4 class="card-title">1</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row">
                <h5 class="card-title text-center w-100" id="chartTitle">Leave Application Summary</h5>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-container" style="min-height: 375px">
                <div id="chart1"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row">
                <h5 class="card-title text-center w-100" id="chartTitle1">Leave Application by Department Summary</h5>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-container" style="min-height: 375px">
                <div id="chart2"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row card-tools-still-right">
                <div class="card-title text-center w-100">Submissions History</div>
              </div>
            </div>
            <div class="card-body p-3">
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
              FROM tbl_pengajuan a LEFT JOIN tbl_approval b ON a.id_pengajuan = b.id_pengajuan";

                    $result = mysqli_query($koneksi, $query);

                    if (mysqli_num_rows($result) > 0) {
                      while ($row = mysqli_fetch_assoc($result)) {
                        $name = $row['employee_name'];
                        $name_encoded = urlencode($name);
                        $avatar_url = "https://ui-avatars.com/api/?name={$name_encoded}&background=random&color=fff&rounded=true";
                        $timestamp = date("M d, Y, g:i A", strtotime($row['timestamp']));
                        $type = $row['type'];
                        $status = $row['status'];

                        // Badge styling based on status
                        $badgeClass = 'btn-secondary';
                        if ($status === 'Completed')
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
                          <td class="text-center"><?= $timestamp ?></td>
                          <td class="text-center"><?= htmlspecialchars($type) ?></td>
                          <td class="text-center">
                            <button class="btn btn-sm <?= $badgeClass ?>" disabled style="width: 70%;"><?= htmlspecialchars($status) ?></button>
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
  const currentYear = new Date().getFullYear();
  document.getElementById("chartTitle").innerText = `Leave Application Summary - ${currentYear}`;
  document.getElementById("chartTitle1").innerText = `Leave Application Department Summary - ${currentYear}`;

  $(document).ready(function () {
    $('#approvalTable').DataTable({
      "pageLength": 5,
      "lengthChange": false,
      "ordering": true
    });
  });

  document.addEventListener("DOMContentLoaded", function () {
    fetch('./proses/get_chart.php')
      .then(response => response.json())
      .then(chartData => {
        Highcharts.chart('chart1', {
          chart: {
            type: 'column',
            backgroundColor: '#ffffff'
          },
          title: {
            text: null
          },
          xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
              'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            crosshair: true,
            labels: { style: { color: '#000000' } }
          },
          yAxis: {
            min: 0,
            allowDecimals: false, // ⬅️ tidak gunakan desimal
            title: {
              text: 'Total Requests',
              style: { color: '#000000' }
            },
            labels: {
              style: { color: '#000000' },
              formatter: function () {
                return Math.round(this.value); // ⬅️ dibulatkan
              }
            }
          },
          tooltip: {
            shared: true,
            valueSuffix: ' requests',
            style: { color: '#ffffff' }
          },
          credits: { enabled: false },
          legend: {
            itemStyle: { color: '#000000' }
          },
          plotOptions: {
            column: {
              pointPadding: 0.2,
              borderWidth: 0
            }
          },
          series: [
            {
              name: 'Completed',
              data: chartData.Completed,
              color: '#198754'
            },
            {
              name: 'Rejected',
              data: chartData.Rejected,
              color: '#dc3545'
            },
            {
              name: 'Open',
              data: chartData.Open,
              color: '#ffc107'
            }
          ]
        });
      })
      .catch(error => {
        console.error("Error fetching chart data:", error);
      });

    fetch('./proses/get_department_chart.php')
      .then(response => response.json())
      .then(chartData => {
        Highcharts.chart('chart2', {
          chart: {
            type: 'bar',
            backgroundColor: '#ffffff'
          },
          title: {
            text: null
          },
          xAxis: {
            categories: chartData.departments,
            title: {
              text: 'Departments',
              style: { color: '#000000' }
            },
            labels: {
              style: { color: '#000000' },
              rotation: -30 // ⬅️ label department (bar chart) diputar
            },
            gridLineWidth: 1,
            lineWidth: 0
          },
          yAxis: {
            min: 0,
            tickInterval: 1, // ⬅️ Tambahkan ini untuk paksa skala 1, 2, 3, ...
            title: {
              text: 'Total Requests',
              style: { color: '#000000' }
            },
            labels: {
              style: { color: '#000000' },
              formatter: function () {
                return Math.round(this.value);
              }
            },
            gridLineWidth: 0
          },
          tooltip: {
            shared: true,
            valueSuffix: ' requests',
            style: { color: '#ffffff' }
          },
          plotOptions: {
            bar: {
              borderRadius: 0, // ⬅️ diubah dari 5 menjadi 0
              dataLabels: {
                enabled: true,
                style: { color: '#000000' }
              },
              groupPadding: 0.1
            }
          },
          legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            backgroundColor: '#ffffff',
            itemStyle: { color: '#000000' }
          },
          credits: {
            enabled: false
          },
          series: [
            {
              name: 'Completed',
              data: chartData.Completed,
              color: '#198754'
            },
            {
              name: 'Rejected',
              data: chartData.Rejected,
              color: '#dc3545'
            },
            {
              name: 'Open',
              data: chartData.Open,
              color: '#ffc107'
            }
          ]
        });
      })
      .catch(error => {
        console.error("Error fetching department chart data:", error);
      });


  });


</script>