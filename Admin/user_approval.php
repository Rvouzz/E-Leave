<?php
session_start();
$judul = 'User Approval';
include '../proses/check_admin.php';
include '../connection.php';
include '../header.php';
?>

<style>
  .hover-shadow:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
  }

  .card .avatar-img {
    transition: transform 0.3s ease;
  }

  .card:hover .avatar-img {
    transform: scale(1.05);
  }

  .transition {
    transition: all 0.3s ease-in-out;
  }

  .card h5 {
    font-size: 1.1rem;
  }

  .card .text-muted,
  .card .text-secondary {
    font-size: 0.875rem;
  }

  .card-title {
    font-size: 1.1rem;
  }

  .card-body i {
    opacity: 0.7;
  }
</style>

<div class="wrapper">
  <?php include '../sidebar.php'; ?>

  <div class="container">
    <div class="page-inner">
      <?php
      $sql = "SELECT `user_id`, `name`, `email_address`, `department` FROM `tbl_users` WHERE status_account = 'Pending'";
      $result = mysqli_query($koneksi, $sql);
      ?>

      <!-- Search Input -->
      <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
          <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0">
              <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" id="searchInput" class="form-control form-control-md border-start-0"
              placeholder="Search user by name, department, or email...">
          </div>
        </div>
      </div>

      <!-- Cards -->
      <div class="row" id="userCardContainer">
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
            $name = htmlspecialchars($row['name']);
            $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($name) . "&background=random&color=fff&rounded=true&size=128";
            ?>
            <div class="col-md-6 col-lg-4 mb-4 user-card">
              <div
                class="card h-100 border-0 shadow-sm p-4 rounded-4 position-relative overflow-hidden hover-shadow transition"
                style="border-top: 5px solid #0d6efd;">

                <!-- Avatar -->
                <div class="text-center">
                  <img src="<?= $avatar_url ?>" alt="Avatar" class="rounded-circle shadow avatar-img mb-3"
                    style="width: 80px; height: 80px; object-fit: cover;" />
                  <h5 class="fw-bold mb-1 user-name"><?= $name ?></h5>
                  <div class="text-muted small mb-1 user-dept"><?= htmlspecialchars($row['department']) ?></div>
                  <div class="text-secondary small mb-3 user-email"><?= htmlspecialchars($row['email_address']) ?></div>
                </div>

                <!-- Approve & Reject Buttons -->
                <div class="d-flex justify-content-center gap-2">
                  <button onclick="handleApproval(<?= $row['user_id'] ?>, 'Active', this)"
                    class="btn btn-outline-success btn-sm w-50 shadow-sm" data-bs-toggle="tooltip" title="Approve user">
                    <i class="fas fa-check-circle me-1"></i> Approve
                  </button>
                  <button onclick="handleApproval(<?= $row['user_id'] ?>, 'Rejected', this)"
                    class="btn btn-outline-danger btn-sm w-50 shadow-sm" data-bs-toggle="tooltip" title="Reject user">
                    <i class="fas fa-times-circle me-1"></i> Reject
                  </button>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="col-12">
            <div class="card text-center border-0 shadow-sm py-5 bg-light rounded-4">
              <div class="card-body">
                <div class="mb-3 text-primary" style="font-size: 2.5rem;">
                  <i class="fas fa-user-clock"></i>
                </div>
                <h5 class="card-title text-muted mb-2 fw-semibold">
                  No users pending approval at the moment.
                </h5>
                <p class="text-secondary small">
                  All user requests have been processed or there are currently no new submissions.
                </p>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <div id="noResultsCard" class="col-12 d-none">
        <div class="card text-center border-0 shadow-sm py-5 bg-light">
          <div class="card-body">
            <div class="mb-3 text-primary" style="font-size: 2.5rem;">
              <i class="fas fa-search-minus"></i>
            </div>
            <h5 class="card-title text-muted mb-2 fw-semibold">
              No matching results found.
            </h5>
            <p class="text-secondary small">
              Try adjusting your search keywords.
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>

  <?php include '../footer.php'; ?>
</div>

<!-- JS -->
<script>
  function handleApproval(userId, action, btn) {
    const actionText = action === 'Active' ? 'approve' : 'reject';
    const confirmButtonText = action === 'Active' ? 'Yes, approve!' : 'Yes, reject!';

    Swal.fire({
      title: `Are you sure?`,
      text: `You are about to ${actionText} this user.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: action === 'Active' ? '#28a745' : '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: confirmButtonText,
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-success me-2',
        cancelButton: 'btn btn-secondary'
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Processing...',
          text: 'Please wait while we update the user status.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        $.ajax({
          url: './proses/approve_user.php',
          type: 'POST',
          data: {
            user_id: userId,
            action: action
          },
          success: function () {
            Swal.fire({
              title: `User ${action === 'Active' ? 'Approved' : 'Rejected'}!`,
              text: `User has been successfully ${actionText}d.`,
              icon: 'success',
              timer: 2000,
              showConfirmButton: false
            });

            // Remove card
            $(btn).closest('.user-card').fadeOut(300, function () {
              $(this).remove();
              if ($('.user-card:visible').length === 0) {
                $('#userCardContainer').html(`
                  <div class="col-12">
                    <div class="card text-center border-0 shadow-sm py-5 bg-light rounded-4">
                      <div class="card-body">
                        <div class="mb-3 text-primary" style="font-size: 2.5rem;">
                          <i class="fas fa-user-clock"></i>
                        </div>
                        <h5 class="card-title text-muted mb-2 fw-semibold">
                          No users pending approval at the moment.
                        </h5>
                        <p class="text-secondary small">
                          All user requests have been processed or there are currently no new submissions.
                        </p>
                      </div>
                    </div>
                  </div>
                `);
              }
            });
          },
          error: function () {
            Swal.fire({
              title: 'Error!',
              text: 'Something went wrong. Please try again.',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        });
      }
    });
  }

  // Search filter
  document.getElementById('searchInput').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    const cards = document.querySelectorAll('.user-card');

    let visibleCount = 0;
    cards.forEach(card => {
      const name = card.querySelector('.user-name')?.textContent.toLowerCase() || '';
      const dept = card.querySelector('.user-dept')?.textContent.toLowerCase() || '';
      const email = card.querySelector('.user-email')?.textContent.toLowerCase() || '';

      if (name.includes(keyword) || dept.includes(keyword) || email.includes(keyword)) {
        card.style.display = 'block';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    const existingMsg = document.getElementById('emptySearchMessage');

    if (visibleCount === 0) {
      if (!existingMsg) {
        const div = document.createElement('div');
        div.id = 'emptySearchMessage';
        div.className = 'col-12';

        div.innerHTML = `
        <div class="card text-center border-0 shadow-sm py-5 bg-light rounded-4">
          <div class="card-body">
            <div class="mb-3 text-danger" style="font-size: 2.5rem;">
              <i class="fas fa-search-minus"></i>
            </div>
            <h5 class="card-title text-muted mb-2 fw-semibold">
              No users found matching your search.
            </h5>
            <p class="text-secondary small">
              Try a different name, department, or email.
            </p>
          </div>
        </div>
      `;
        document.getElementById('userCardContainer').appendChild(div);
      }
    } else {
      if (existingMsg) existingMsg.remove();
    }
  });

</script>