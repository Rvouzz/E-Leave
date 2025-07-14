<?php
session_start();

$timeout_duration = 900; // 900 detik = 15 menit
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
  session_unset();
  session_destroy();
  session_start(); // restart session untuk pesan error
  $_SESSION['login_error'] = "Session expired. Please log in again.";
  header("Location: ../authentication-login.php");
  exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // reset waktu aktivitas jika belum kedaluwarsa

if (!isset($_SESSION['email_address'])) {
  session_unset();
  session_destroy();
  header("Location: ../index.php");
  exit();
}

$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$email_address = isset($_SESSION['email_address']) ? $_SESSION['email_address'] : '';
$get_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

$get_open = "SELECT COUNT(*) AS total FROM tbl_approval WHERE spv_name = '$name' AND approval_spv = 'Open'";
$result_open = mysqli_query($koneksi, $get_open);
$count_pending = 0;

if ($result_open && mysqli_num_rows($result_open) > 0) {
  $data = mysqli_fetch_assoc($result_open);
  $count_pending = $data['total'];
}

$get_hrd = "SELECT COUNT(*) AS total FROM tbl_approval WHERE approval_hrd = 'Open'";
$result_hrd = mysqli_query($koneksi, $get_hrd);
$count_hrd = 0;

if ($result_hrd && mysqli_num_rows($result_hrd) > 0) {
  $data = mysqli_fetch_assoc($result_hrd);
  $count_hrd = $data['total'];
}

$get_admin = "SELECT COUNT(*) AS total FROM tbl_users WHERE status_account = 'Pending'";
$result_admin = mysqli_query($koneksi, $get_admin);
$count_admin = 0;

if ($result_admin && mysqli_num_rows($result_admin) > 0) {
  $data = mysqli_fetch_assoc($result_admin);
  $result_admin = $data['total'];
}
?>

<style>
  .badge {
    font-size: 0.75rem;
    padding: 5px 10px;
    border-radius: 50rem;
  }
</style>

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="#" class="logo d-flex align-items-center">
        <i class="fas fa-user-clock text-white me-2 fs-5"></i> <!-- Icon representing E-Leave -->
        <span class="text-white fw-bold">E-Leave</span> <!-- App name -->
      </a>
      <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
        <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
      </div>
      <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
    </div>

    <!-- End Logo Header -->
  </div>

  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
          <!-- Dashboard -->
          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <a href="dashboard.php">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'user_approval.php' ? 'active' : '' ?>">
            <a href="user_approval.php" class="d-flex justify-content-between align-items-center">
              <div>
                <i class="fas fa-user-check"></i>
                <p>User Approval</p>
              </div>
              <?php if ($result_admin > 0): ?>
                <span class="badge bg-danger ms-2"><?= $result_admin ?></span>
              <?php endif; ?>
            </a>
          </li>

          <!-- User Management -->
          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'user_management.php' ? 'active' : '' ?>">
            <a href="user_management.php">
              <i class="fas fa-users"></i>
              <p>User Management</p>
            </a>
          </li>

          <!-- Section: Components -->
          <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Master Data</h4>
          </li>

          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'department.php' ? 'active' : '' ?>">
            <a href="department.php">
              <i class="fas fa-building"></i> <!-- Changed icon to represent a department -->
              <p>Department</p> <!-- Updated menu label -->
            </a>
          </li>

        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Employee'): ?>
          <!-- Dashboard -->
          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <a href="dashboard.php">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- Form Request -->
          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'form_request.php' ? 'active' : '' ?>">
            <a href="form_request.php">
              <i class="fas fa-edit"></i>
              <p>Form Request</p>
            </a>
          </li>

          <!-- Request List -->
          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'request_list.php' ? 'active' : '' ?>">
            <a href="request_list.php">
              <i class="fas fa-list"></i>
              <p>Request List</p>
            </a>
          </li>
        <?php endif; ?>


        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Supervisor'): ?>
          <!-- Dashboard -->
          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <a href="dashboard.php">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'approval_leave.php' ? 'active' : '' ?>">
            <a href="approval_leave.php" class="d-flex justify-content-between align-items-center">
              <div>
                <i class="fas fa-check"></i>
                <p>My Approval</p>
              </div>
              <?php if ($count_pending > 0): ?>
                <span class="badge bg-danger ms-2"><?= $count_pending ?></span>
              <?php endif; ?>
            </a>
          </li>

          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'history_leave.php' ? 'active' : '' ?>">
            <a href="history_leave.php">
              <i class="fas fa-history"></i> <!-- Changed icon to represent a department -->
              <p>History</p> <!-- Updated menu label -->
            </a>
          </li>

          <!-- User Management -->
          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'user_management.php' ? 'active' : '' ?>">
            <a href="user_management.php">
              <i class="fas fa-users"></i>
              <p>Employee</p>
            </a>
          </li>

          <!-- Section: Components -->
          <!-- <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Main Section</h4>
          </li> -->

        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'HRD'): ?>
          <!-- Dashboard -->
          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <a href="dashboard.php">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'approval_leave.php' ? 'active' : '' ?>">
            <a href="approval_leave.php" class="d-flex justify-content-between align-items-center">
              <div>
                <i class="fas fa-check"></i>
                <p>My Approval</p>
              </div>
              <?php if ($count_hrd > 0): ?>
                <span class="badge bg-danger ms-2"><?= $count_hrd ?></span>
              <?php endif; ?>
            </a>
          </li>

          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'history_leave.php' ? 'active' : '' ?>">
            <a href="history_leave.php">
              <i class="fas fa-history"></i> <!-- Changed icon to represent a department -->
              <p>History</p> <!-- Updated menu label -->
            </a>
          </li>

          <!-- Section: Components -->
          <!-- <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Main Section</h4>
          </li> -->

        <?php endif; ?>

        <!-- <li class="nav-item">
          <a href="#" id="logoutButton">
            <i class="fas fa-sign-out-alt"></i>
            <p>Logout</p>
          </a>
        </li> -->


      </ul>
    </div>
  </div>
</div>
<!-- End Sidebar -->

<div class="main-panel">
  <div class="main-header">
    <!-- Logo Header -->
    <div class="main-header-logo">
      <div class="logo-header" data-background-color="dark">
        <a href="index.html" class="logo">
          <img src="../assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
        </a>
        <div class="nav-toggle">
          <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
          <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
        </div>
        <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
      </div>
    </div>
    <!-- End Logo Header -->

    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
      <div class="container-fluid">
        <h5>Employee Leave Management System</h5>
        <!-- Topbar Icons -->
        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

          <!-- User Profile -->
          <li class="nav-item topbar-user dropdown hidden-caret">
            <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#">
              <div class="avatar-sm">
                <?php
                $name_encoded = urlencode($name);
                $avatar_url = "https://ui-avatars.com/api/?name={$name_encoded}&background=random&color=fff&rounded=true";
                ?>
                <img src="<?= $avatar_url ?>" alt="User Avatar" class="avatar-img rounded-circle" />
              </div>
              <span class="profile-username">
                <span class="op-7">Hi,</span>
                <span class="fw-bold"><?= htmlspecialchars($name) ?></span>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-user animated fadeIn">
              <div class="dropdown-user-scroll scrollbar-outer">
                <li>
                  <div class="user-box">
                    <div class="avatar-lg">
                      <?php
                      $name_encoded = urlencode($name);
                      $avatar_url = "https://ui-avatars.com/api/?name={$name_encoded}&background=random&color=fff&rounded=true";
                      ?>
                      <img src="<?= $avatar_url ?>" alt="User Avatar" class="avatar-img rounded-circle" />
                    </div>
                    <div class="u-text">
                      <h4><?= htmlspecialchars($name) ?></h4>
                      <p class="text-muted"><?= htmlspecialchars($email_address) ?></p>
                      <a href="profile.php" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                    </div>
                  </div>
                </li>
                <li>
                  <!-- <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Account Setting</a> -->
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#" id="logoutButton">Logout</a>
                </li>
              </div>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End Navbar -->
  </div>