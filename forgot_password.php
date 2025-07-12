<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Forgot Password | E-Leave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png">
  <link rel="stylesheet" href="assets/css/styles.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: linear-gradient(135deg, #e0e0e0, #ffffff);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .card {
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: bold;
      color: #343a40;
    }

    .form-control {
      padding: 0.75rem;
    }

    .btn-primary {
      padding: 0.75rem;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card p-4">
          <div class="card-body">
            <div class="text-center mb-4">
              <i class="fas fa-lock fa-3x text-primary mb-2"></i>
              <h4 class="card-title">Forgot Password</h4>
              <p class="text-muted mb-0">We'll send you an OTP to reset your password</p>
            </div>
            <form action="proses/send_otp.php" method="POST">
              <div class="mb-3">
                <label for="email_address" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email_address" name="email_address"
                  placeholder="Enter your email" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-paper-plane me-2"></i> Send OTP
              </button>
              <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none text-primary">
                  <i class="fas fa-arrow-left me-1"></i> Back to Login
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SweetAlert notification -->
  <?php
  if (isset($_SESSION['show_otp_success']) && isset($_SESSION['forgot_success'])) {
    echo "<script>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '{$_SESSION['forgot_success']}',
      confirmButtonColor: '#3085d6'
    }).then(() => {
      window.location.href = 'verify_otp.php';
    });
  </script>";
    unset($_SESSION['forgot_success']);
    unset($_SESSION['show_otp_success']);
  }

  if (isset($_SESSION['forgot_error'])) {
    echo "<script>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{$_SESSION['forgot_error']}',
        confirmButtonColor: '#d33'
      });
    </script>";
    unset($_SESSION['forgot_error']);
  }
  ?>
</body>

</html>