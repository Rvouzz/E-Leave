<?php
session_start();
$judul = 'My Approval';
include '../proses/check_supervisor.php';
include '../connection.php';
?>
<?php include '../header.php'; ?>

<style>
  .hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
  }

  .transition {
    transition: all 0.3s ease-in-out;
  }

  .avatar-circle {
    border: 4px solid #f1f1f1;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .badge-date {
    font-size: 0.85rem;
    background-color: #eef2f7;
    color: #333;
    padding: 6px 12px;
    border-radius: 50rem;
  }

  .btn-approve,
  .btn-reject {
    transition: all 0.2s ease-in-out;
  }

  .btn-approve:hover {
    background-color: #198754;
    color: #fff;
  }

  .btn-reject:hover {
    background-color: #dc3545;
    color: #fff;
  }
</style>

<div class="wrapper">
  <?php include '../sidebar.php'; ?>

  <div class="container">
    <div class="page-inner">
      <?php
      $sql = "SELECT a.id_approval, b.employee_name, b.email_address, b.type, b.date_from, b.date_to FROM tbl_approval a LEFT JOIN tbl_pengajuan b ON a.id_pengajuan = b.id_pengajuan WHERE b.email_spv = '$email_address' AND a.approval_spv = 'Open'";
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
              placeholder="Search by name, type, or date...">
          </div>
        </div>
      </div>

      <!-- Card Container -->
      <div class="row" id="approvalList">
        <?php
        $sql = "SELECT a.id_approval, b.employee_name, b.email_address, b.type, b.date_from, b.date_to 
          FROM tbl_approval a 
          LEFT JOIN tbl_pengajuan b ON a.id_pengajuan = b.id_pengajuan 
          WHERE b.email_spv = '$email_address' AND a.approval_spv = 'Open'";
        $result = mysqli_query($koneksi, $sql);

        if (mysqli_num_rows($result) > 0):
          while ($row = mysqli_fetch_assoc($result)):
            $avatar = "https://ui-avatars.com/api/?name=" . urlencode($row['employee_name']) . "&background=random&color=fff&rounded=true&size=128";
            ?>
            <div class="col-lg-4 col-md-6 mb-4 approval-card">
              <div class="card shadow-sm border-0 h-100 hover-shadow transition p-4" style="border-top: 4px solid #0d6efd;">
                <div class="text-center mb-3">
                  <img src="<?= $avatar ?>" class="rounded-circle avatar-circle mb-3"
                    style="width:85px;height:85px;object-fit:cover;">
                  <h5 class="fw-semibold mb-1 fs-5"><?= htmlspecialchars($row['employee_name']) ?></h5>
                  <span class="text-muted d-block mb-2 fs-6"><?= htmlspecialchars($row['type']) ?></span>
                </div>
                <div class="text-center mb-3">
                  <div class="badge-date mb-1">From: <?= date('M d, Y', strtotime($row['date_from'])) ?></div>
                  <div class="badge-date mt-1">To: <?= date('M d, Y', strtotime($row['date_to'])) ?></div>
                </div>
                <div class="d-flex justify-content-center gap-2 mt-auto">
                  <button onclick="handleApproval(<?= $row['id_approval'] ?>, 'Approved', this)"
                    class="btn btn-outline-success btn-sm w-50 btn-approve" title="Approve request">
                    <i class="fas fa-check-circle me-1"></i> Approve
                  </button>
                  <button onclick="handleApproval(<?= $row['id_approval'] ?>, 'Rejected', this)"
                    class="btn btn-outline-danger btn-sm w-50 btn-reject" title="Reject request">
                    <i class="fas fa-times-circle me-1"></i> Reject
                  </button>
                </div>
              </div>
            </div>
          <?php endwhile; else: ?>
          <div class="col-12">
            <div class="card text-center border-0 shadow-sm py-5">
              <div class="card-body">
                <h5 class="card-title text-muted">No leave requests to approve at the moment.</h5>
                <p class="text-secondary small">All leave requests have been processed or none submitted yet.</p>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>





    </div>
  </div>

  <?php include '../footer.php'; ?>
</div>

<script>
  function handleApproval(id_approval, action, btn) {
    const isApprove = action === 'Approved';
    const actionText = isApprove ? 'approve' : 'reject';
    const confirmButtonText = isApprove ? 'Yes, approve it!' : 'Yes, reject it!';
    const successTitle = isApprove ? 'Leave Approved!' : 'Leave Rejected!';
    const successText = isApprove ? 'The leave request has been approved.' : 'The leave request has been rejected.';

    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to ${actionText} this leave request.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: isApprove ? '#28a745' : '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: confirmButtonText,
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-success me-2',
        cancelButton: 'btn btn-secondary'
      },
      buttonsStyling: false,
      showClass: {
        popup: 'animate__animated animate__fadeInDown'
      },
      hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Processing...',
          text: 'Please wait while we update the approval status.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        $.ajax({
          url: './proses/approve_request.php',
          type: 'POST',
          data: {
            id_approval: id_approval,
            action: action
          },
          success: function () {
            Swal.fire({
              title: successTitle,
              text: successText,
              icon: 'success',
              timer: 2000,
              showConfirmButton: false
            });

            // Hapus kartu dari UI
            $(btn).closest('.col-md-6, .col-lg-4').fadeOut(300, function () {
              $(this).remove();

              // Jika tidak ada lagi kartu approval
              if ($('.col-md-6, .col-lg-4').length === 0) {
                $('.row').html(`
                <div class="col-12">
                  <div class="card text-center border-0 shadow-sm py-5">
                    <div class="card-body">
                      <h5 class="card-title text-muted">
                        No employee leave requests are waiting for your approval.
                      </h5>
                      <p class="text-secondary small mb-0">
                        All leave requests have been processed or there are no new submissions at the moment.
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

  document.getElementById('searchInput').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    const cards = document.querySelectorAll('.approval-card');
    let matchCount = 0;

    cards.forEach(card => {
      const text = card.textContent.toLowerCase();
      const match = text.includes(keyword);
      card.style.display = match ? 'block' : 'none';
      if (match) matchCount++;
    });

    // Tangani kartu "no result"
    let noResultCard = document.getElementById('noResultsCard');

    // Kalau belum ada, buat
    if (!noResultCard) {
      noResultCard = document.createElement('div');
      noResultCard.id = 'noResultsCard';
      noResultCard.className = 'col-12';
      noResultCard.innerHTML = `
        <div class="card text-center border-0 shadow-sm py-5 bg-light">
          <div class="card-body">
            <div class="mb-3 text-danger" style="font-size: 2rem;">
              <i class="fas fa-search-minus"></i>
            </div>
            <h5 class="card-title text-muted mb-2 fw-semibold">
              No matching results found.
            </h5>
            <p class="text-secondary small">
              Try a different name, department, or email.
            </p>
          </div>
        </div>
      `;
      // Sisipkan ke dalam row
      document.querySelector('.row').appendChild(noResultCard);
    }

    // Tampilkan / sembunyikan
    noResultCard.style.display = matchCount === 0 ? 'block' : 'none';
  });


</script>