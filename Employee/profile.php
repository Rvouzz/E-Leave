<?php
session_start();
$judul = 'My Profile';
include '../proses/check_employee.php';
include '../connection.php';
include '../header.php';
?>

<style>
  .profile-header {
    background: linear-gradient(135deg, #0d6efd, #6f42c1);
    padding: 3rem 1.5rem 2rem;
    text-align: center;
    color: #fff;
    border-radius: 1rem 1rem 0 0;
    box-shadow: inset 0 -4px 15px rgba(0, 0, 0, 0.1);
  }

  .profile-header img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
  }

  .profile-header img:hover {
    transform: scale(1.05);
  }

  .profile-info {
    padding: 2rem;
    background-color: #fff;
    border-radius: 0 0 1rem 1rem;
  }

  .profile-info h4 {
    font-weight: 600;
    color: #343a40;
  }

  .info-item {
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
    color: #555;
  }

  .info-item i {
    color: #6c757d;
    width: 20px;
  }

  .badge-role {
    font-size: 0.85rem;
    padding: 6px 14px;
    border-radius: 50rem;
    display: inline-block;
    font-weight: 500;
  }

  .badge-role.admin {
    background-color: #dc3545;
    color: #fff;
  }

  .badge-role.supervisor {
    background-color: #0d6efd;
    color: #fff;
  }

  .badge-role.user {
    background-color: #198754;
    color: #fff;
  }

  .edit-btn {
    border-radius: 50rem;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
  }

  @media (max-width: 576px) {
    .profile-info {
      padding: 1.5rem;
    }

    .profile-header {
      padding: 2rem 1rem 1.5rem;
    }
  }
</style>

<div class="wrapper">
  <?php include '../sidebar.php'; ?>
  <div class="container">
    <div class="page-inner">
      <?php
      $email = $_SESSION['email_address'];
      $query = "SELECT name, email_address, department, role FROM tbl_users WHERE email_address = '$email'";
      $result = mysqli_query($koneksi, $query);
      $user = mysqli_fetch_assoc($result);

      $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($user['name']) . "&background=random&color=fff&rounded=true&size=128";
      $role_class = strtolower($user['role']);
      ?>

      <div class="row justify-content-center mt-5">
        <div class="col-md-8">
          <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- Profile Header -->
            <div class="profile-header">
              <img src="<?= $avatar_url ?>" alt="Avatar" class="rounded-circle mb-3">
              <h4 class="mb-1"><?= htmlspecialchars($user['name']) ?></h4>
              <p class="mb-0 small"><?= htmlspecialchars($user['email_address']) ?></p>
            </div>

            <!-- Profile Info -->
            <div class="profile-info">
              <div class="row">
                <div class="col-md-6 info-item">
                  <i class="fas fa-building me-2"></i>
                  <strong>Department:</strong>
                  <div><?= htmlspecialchars($user['department']) ?: '-' ?></div>
                </div>
                <div class="col-md-6 info-item">
                  <i class="fas fa-user-tag me-2"></i>
                  <strong>Role:</strong>
                  <div>
                    <span class="badge-role <?= $role_class ?>">
                      <?= ucfirst($user['role']) ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="text-end mt-4">
                <button class="btn btn-warning edit-btn shadow-sm" data-bs-toggle="modal"
                  data-bs-target="#changePasswordModal">
                  <i class="fas fa-key me-1"></i> Change Password
                </button>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
  <?php include '../footer.php'; ?>
</div>
<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 shadow">
      <form id="changePasswordForm">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold" id="changePasswordLabel">Change Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body pt-1">
          <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <div class="input-group">
              <input type="password" class="form-control" id="newPassword" placeholder="Enter new password" required>
              <span class="input-group-text toggle-password" onclick="togglePasswordVisibility('newPassword', this)">
                <i class="fas fa-eye-slash"></i>
              </span>
            </div>
          </div>

          <div class="mb-2">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <div class="input-group">
              <input type="password" class="form-control" id="confirmPassword" placeholder="Repeat new password"
                required>
              <span class="input-group-text toggle-password"
                onclick="togglePasswordVisibility('confirmPassword', this)">
                <i class="fas fa-eye-slash"></i>
              </span>
            </div>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="submit" class="btn btn-primary w-100 rounded-pill">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
  function togglePasswordVisibility(inputId, toggleIcon) {
    const input = document.getElementById(inputId);
    const icon = toggleIcon.querySelector('i');

    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    } else {
      input.type = "password";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    }
  }


  document.getElementById('changePasswordForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (newPassword !== confirmPassword) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Passwords do not match!'
      });
      return;
    }

    // Kirim AJAX
    fetch('./proses/change_password.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `password=${encodeURIComponent(newPassword)}`
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Password Changed',
            text: data.message
          }).then(() => {
            document.getElementById('changePasswordForm').reset();
            const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
            modal.hide();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: data.message
          });
        }
      })
      .catch(err => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Something went wrong!'
        });
      });
  });



</script>