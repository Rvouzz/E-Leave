<?php
session_start();
$judul = 'Form Request';
include '../proses/check_employee.php';
include '../connection.php';

// Ambil data dari session
$name = $_SESSION['name'];
$email = $_SESSION['email_address'];
?>

<?php include '../header.php'; ?>
<div class="wrapper">
  <?php include '../sidebar.php'; ?>
  <div class="container">
    <div class="page-inner">
      <form id="leaveForm" enctype="multipart/form-data" class="card shadow-sm p-4">
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Employee Name</label>
            <input type="text" readonly class="form-control" name="name" value="<?= htmlspecialchars($name) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email" readonly class="form-control" name="email" value="<?= htmlspecialchars($email) ?>">
          </div>
        </div>

        <div class="mb-3">
          <label for="type" class="form-label">Leave Type</label>
          <select name="type" id="type" class="form-select" required>
            <option value="">-- Select Leave Type --</option>
            <option value="Annual Leave">Annual Leave</option>
            <option value="Sick Leave">Sick Leave</option>
            <option value="Maternity Leave">Maternity Leave</option>
            <option value="Unpaid Leave">Unpaid Leave</option>
          </select>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">From</label>
            <input type="date" name="date_from" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">To</label>
            <input type="date" name="date_to" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Reason</label>
          <textarea name="reason" rows="3" class="form-control" required></textarea>
        </div>

        <div class="mb-3 d-none" id="proofDiv">
          <label for="proof" class="form-label">Medical Proof (JPG, PNG, PDF)</label>
          <input type="file" name="proof" id="proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        </div>


        <div class="text-end">
          <button type="submit" class="btn btn-primary">Submit Request</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include '../footer.php'; ?>

<script>
  // Show/hide proof field based on type
  $('#type').on('change', function () {
    if ($(this).val() === 'Sick Leave') {
      $('#proofDiv').removeClass('d-none');
      $('#proof').attr('required', true);
    } else {
      $('#proofDiv').addClass('d-none');
      $('#proof').val('');
      $('#proof').removeAttr('required');
    }
  });

  // Submit form using AJAX
  $('#leaveForm').submit(function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const file = $('#proof')[0].files[0];
    const type = $('#type').val();

    // Validasi file untuk Sick Leave
    if (type === 'Sick Leave' && file) {
      const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
      if (!validTypes.includes(file.type)) {
        Swal.fire({
          icon: 'error',
          title: 'Invalid File',
          text: 'Only JPG, PNG, or PDF files are allowed.',
          confirmButtonColor: '#dc3545'
        });
        return;
      }
    }

    // Konfirmasi sebelum submit
    Swal.fire({
      title: 'Submit Leave Request?',
      text: "Are you sure all the information is correct?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Submit',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#0d6efd',
      cancelButtonColor: '#6c757d'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'proses/submit_request.php',
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function (res) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: res,
              confirmButtonColor: '#0d6efd',
              timer: 2000,
              showConfirmButton: false
            }).then(() => {
              window.location.href = 'request_list.php'; // ✅ Redirect ke halaman
            });
          },
          error: function () {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Failed to submit request.',
              confirmButtonColor: '#dc3545'
            });
          }
        });
      }
    });
  });


</script>