<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Verify OTP | E-Leave</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/styles.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: linear-gradient(135deg, #e0e0e0, #ffffff);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .otp-input {
      width: 3rem;
      height: 3rem;
      font-size: 1.5rem;
      text-align: center;
      margin: 0 0.3rem;
      border: 2px solid #ccc;
      border-radius: 0.5rem;
      outline: none;
      transition: border-color 0.2s;
    }

    .otp-input:focus {
      border-color: #0d6efd;
    }

    .otp-box {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }

    .card {
      border-radius: 1rem;
      padding: 2rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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

    .btn-success {
      font-size: 1rem;
      padding: 0.7rem;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card p-4">
          <div class="card-body text-center">
            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
            <h4 class="mb-3 fw-bold">Enter OTP</h4>
            <p class="text-muted mb-4">Please enter the 6-digit code we sent to your email.</p>

            <form action="proses/verify_otp.php" method="POST" id="otpForm">
              <div class="otp-box d-flex justify-content-between mb-4">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                  <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="\d*" inputmode="numeric"
                    required>
                <?php endfor; ?>
              </div>
              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-check-circle me-1"></i> Verify OTP
              </button>
            </form>

            <div class="text-center mt-3">
              <a href="forgot_password.php" class="text-decoration-none text-primary">
                <i class="fas fa-arrow-left me-1"></i> Back to Forgot Password
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- OTP Input Styling -->
  <style>
    .otp-box .otp-input {
      width: 45px;
      height: 50px;
      font-size: 1.5rem;
      border: 2px solid #ddd;
      border-radius: 8px;
      transition: border-color 0.2s ease;
    }

    .otp-box .otp-input:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
      outline: none;
    }
  </style>

  <!-- Auto Focus & Merge -->
  <script>
    const inputs = document.querySelectorAll(".otp-input");

    inputs.forEach((input, index) => {
      input.addEventListener("input", (e) => {
        if (e.inputType !== 'deleteContentBackward' && input.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
      });

      input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && !input.value && index > 0) {
          inputs[index - 1].focus();
        }
      });
    });

    document.getElementById("otpForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const otpValues = Array.from(inputs).map(input => input.value.trim()).join('');
      const hiddenInput = document.createElement("input");
      hiddenInput.type = "hidden";
      hiddenInput.name = "otp_input";
      hiddenInput.value = otpValues;
      this.appendChild(hiddenInput);
      this.submit();
    });
  </script>

  <!-- SweetAlert Error -->
  <?php if (isset($_SESSION['otp_error'])): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Invalid OTP',
        text: '<?= $_SESSION['otp_error'] ?>',
        confirmButtonColor: '#d33'
      });
    </script>
    <?php unset($_SESSION['otp_error']); ?>
  <?php endif; ?>
</body>


</html>