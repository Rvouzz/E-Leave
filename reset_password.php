<?php
session_start();

// Jangan redirect kalau sukses ingin ditampilkan dulu
if (
  (!isset($_SESSION['otp_verified']) || !isset($_SESSION['email_reset'])) &&
  !isset($_SESSION['trigger_reset_alert'])
) {
  header("Location: forgot_password.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Reset Password | E-Leave</title>
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
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .card {
      border-radius: 1rem;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.5s ease-in-out;
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

    .btn-primary {
      font-size: 1rem;
      padding: 0.65rem;
      font-weight: bold;
    }

    .input-icon {
      position: relative;
    }

    .input-icon i {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: #6c757d;
    }

    .input-icon input {
      padding-left: 2.5rem;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card p-4">
          <div class="card-body text-center">
            <i class="fas fa-unlock-alt fa-3x text-primary mb-3"></i>
            <h4 class="mb-3 fw-bold">Reset Your Password</h4>
            <p class="text-muted mb-4">Please enter a new password to continue</p>

            <form action="proses/update_password.php" method="POST">
              <div class="mb-3 input-icon text-start">
                <label for="new_password" class="form-label fw-semibold">New Password</label>
                <i class="fas fa-key" style="margin-top: 15px;"></i>
                <input type="password" class="form-control" name="new_password" id="new_password" required
                  placeholder="Enter new password">
              </div>
              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-sync-alt me-1"></i> Reset Password
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SweetAlert Success -->
  <?php if (isset($_SESSION['trigger_reset_alert']) && isset($_SESSION['reset_success'])): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Password Updated!',
        text: '<?= $_SESSION['reset_success'] ?>',
        confirmButtonText: 'Login Now',
        showClass: {
          popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp'
        }
      }).then(() => {
        window.location.href = 'authentication-login.php';
      });
    </script>
    <?php
    unset($_SESSION['reset_success']);
    unset($_SESSION['trigger_reset_alert']);
    ?>
  <?php endif; ?>

  <!-- SweetAlert Error -->
  <?php if (isset($_SESSION['otp_error'])): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '<?= $_SESSION['otp_error'] ?>',
        confirmButtonColor: '#d33'
      });
    </script>
    <?php unset($_SESSION['otp_error']); ?>
  <?php endif; ?>
</body>

</html>