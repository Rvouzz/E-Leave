<?php
include 'connection.php';
session_start();

// Auto login via cookie
if (isset($_COOKIE['email_address']) && isset($_COOKIE['password'])) {
  $email_address = mysqli_real_escape_string($koneksi, $_COOKIE['email_address']);
  $cookie_password = $_COOKIE['password'];

  $query = mysqli_query($koneksi, "SELECT * FROM tbl_users WHERE email_address = '$email_address' LIMIT 1");

  if ($query && mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);

    if (hash('sha512', $row['password']) === $cookie_password && $row['status_account'] === 'Active') {
      $_SESSION['email_address'] = $row['email_address'];
      $_SESSION['user_id'] = $row['user_id'];
      $_SESSION['role'] = $row['role'];
      $_SESSION['name'] = $row['name'];
      $_SESSION['department'] = $row['department'];
      $_SESSION['status_account'] = $row['status_account'];
      $_SESSION['login_success'] = "Welcome back, " . $row['name'] . "!";

      switch ($row['role']) {
        case 'Admin':
          $_SESSION['redirect_to'] = "Admin/dashboard.php";
          break;
        case 'Employee':
          $_SESSION['redirect_to'] = "Employee/dashboard.php";
          break;
        case 'Supervisor':
          $_SESSION['redirect_to'] = "Supervisor/dashboard.php";
          break;
        case 'HRD':
          $_SESSION['redirect_to'] = "HRD/dashboard.php";
          break;
        default:
          $_SESSION['login_error'] = "Invalid role.";
      }
    }
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>E-Leave</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <!-- Logo -->
                <a href="#" class="text-nowrap text-center d-block py-3 w-100 text-decoration-none">
                  <div class="d-flex justify-content-center align-items-center">
                    <i class="fas fa-user-clock text-primary me-2" style="font-size: 2rem;"></i>
                    <span class="fw-bold text-dark" style="font-size: 1.8rem;">E-Leave</span>
                  </div>
                </a>
                <p class="text-center mt-2">Employee Leave Management System</p>

                <!-- Login Form -->
                <form method="POST" action="proses/proses_login.php">
                  <div class="mb-3">
                    <label for="email_address" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email_address" name="email_address" required>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" name="flexCheckChecked"
                        id="flexCheckChecked">
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remember this Device
                      </label>
                    </div>
                    <a class="text-primary fw-bold" href="./index.html">Forgot Password?</a>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
                  <!-- <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">New to E-Leave?</p>
                    <a class="text-primary fw-bold ms-2" href="./authentication-register.php">Create an account</a>
                  </div> -->
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert Login Notifications -->
  <?php
  if (isset($_SESSION['login_success']) && isset($_SESSION['redirect_to'])) {
    echo "
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: '" . $_SESSION['login_success'] . "',
        confirmButtonText: 'Continue'
      }).then(() => {
        window.location.href = '" . $_SESSION['redirect_to'] . "';
      });
    </script>";
    unset($_SESSION['login_success']);
    unset($_SESSION['redirect_to']);
  }

  if (isset($_SESSION['login_error'])) {
    echo "
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '" . $_SESSION['login_error'] . "',
        confirmButtonText: 'Try Again'
      });
    </script>";
    unset($_SESSION['login_error']);
  }
  ?>
</body>

</html>