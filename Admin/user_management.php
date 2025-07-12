<?php
session_start(); // pastikan session dimulai
$judul = 'Dashboard';
include '../proses/check_admin.php';
include '../connection.php';

$query = "
SELECT 
  u.user_id,
  u.name,
  u.email_address,
  u.password,
  u.department,
  u.email_spv,
  spv.name AS supervisor_name,
  u.role,
  u.status_account,
  u.timestamp
FROM tbl_users u
LEFT JOIN tbl_users spv ON u.email_spv = spv.email_address
WHERE u.status_account IN ('Active', 'Inactive')
";

$result = mysqli_query($koneksi, $query);
$no = 1;

$dept_query = "SELECT `name_dept` FROM `mst_dept`";
$dept_result = mysqli_query($koneksi, $dept_query);

$spv_query = "SELECT `name`, `email_address` FROM `tbl_users` WHERE `role` = 'Supervisor'";
$spv_result = mysqli_query($koneksi, $spv_query);
?>

<style>
  /* Fix tinggi dropdown agar sejajar dengan form-control */
  .select2-container--default .select2-selection--single {
    height: 38px !important;
    padding: 0.375rem 0.75rem !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    display: flex;
    align-items: center;
    font-size: 14px;
  }

  /* Fix posisi panah dropdown */
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
    top: 1px;
    right: 4px;
  }

  /* Fix posisi text */
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.5 !important;
    padding-left: 0 !important;
  }
</style>

<?php include '../header.php'; ?>
<div class="wrapper"> <!-- INI WAJIB -->
  <?php include '../sidebar.php'; ?> <!-- file sidebar.php kamu -->
  <div class="container">
    <div class="page-inner">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row card-tools-still-right">
                <div class="card-tools">
                  <!-- <button class="btn btn-label-info btn-round btn-sm me-2" title="Print Data">
                    <span class="btn-label">
                      <i class="fa fa-print"></i>
                    </span>
                    Print
                  </button>
                  <button class="btn btn-label-success btn-round btn-sm me-2" title="Export Data">
                    <span class="btn-label">
                      <i class="fa fa-file-export"></i>
                    </span>
                    Export
                  </button>
                  <button class="btn btn-label-secondary btn-round btn-sm me-2" title="Import Data">
                    <span class="btn-label">
                      <i class="fa fa-file-import"></i>
                    </span>
                    Import
                  </button> -->
                  <button class="btn btn-label-primary btn-round btn-sm" title="Add New Department"
                    data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <span class="btn-label">
                      <i class="fa fa-plus"></i>
                    </span>
                    Add
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body px-4 py-3"> <!-- Added padding here -->
              <div class="table-responsive">
                <!-- Projects table -->
                <table id="user_table" class="table align-items-center mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col" class="text-center">Employee Name</th>
                      <th scope="col" class="text-center">Email Address</th>
                      <th scope="col" class="text-center">Department</th>
                      <th scope="col" class="text-center">Supervisor</th>
                      <th scope="col" class="text-center">Role</th>
                      <th scope="col" class="text-center">Status</th>
                      <th scope="col" class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                      <tr>
                        <td class="text-start">
                          <div class="d-flex align-items-center gap-2">
                            <?php
                            $name = htmlspecialchars($row['name']);
                            $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($name) . "&background=random&color=fff&rounded=true&size=32";
                            ?>
                            <img src="<?= $avatar_url ?>" alt="Avatar" class="rounded-circle" width="32" height="32">
                            <span class="fw-semibold"><?= $name ?></span>
                          </div>
                        </td>

                        <td class="text-center"><?= htmlspecialchars($row['email_address']); ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['department']); ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['supervisor_name'] ?? '-'); ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['role']); ?></td>
                        <?php
                        $status = $row['status_account'];
                        switch ($status) {
                          case 'Active':
                            $btnClass = 'btn-success';
                            break;
                          case 'Inactive':
                            $btnClass = 'btn-danger';
                            break;
                          case 'Pending':
                            $btnClass = 'btn-warning';
                            break;
                          case 'Rejected':
                            $btnClass = 'btn-secondary';
                            break;
                          default:
                            $btnClass = 'btn-light';
                        }
                        ?>
                        <td class="text-center">
                          <button type="button" class="btn btn-sm <?= $btnClass ?>" style="width: 90px;" disabled>
                            <?= $status ?>
                          </button>
                        </td>
                        <td class="text-center">
                          <button class="btn btn-sm btn-success me-1" title="Edit"
                            onclick="editUser(<?= $row['user_id']; ?>)">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn btn-sm btn-danger" title="Delete"
                            onclick="deleteUser(<?= $row['user_id']; ?>)">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                      </tr>
                    <?php endwhile; ?>
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

<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">
            <i class="fa fa-plus me-2"></i> Add New Employee
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body row g-3 px-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Employee Name<span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required />
          </div>

          <div class="col-md-6">
            <label for="email_address" class="form-label">Email Address<span class="text-danger">*</span></label>
            <input type="email" name="email_address" id="email_address" class="form-control" required />
          </div>

          <div class="col-md-6">
            <label for="email_spv" class="form-label">Department<span class="text-danger">*</span></label>
            <select name="department" id="select_department" class="form-select select2" required>
              <option value="" disabled selected>Select Department</option>
              <?php while ($dept = mysqli_fetch_assoc($dept_result)): ?>
                <option value="<?= htmlspecialchars($dept['name_dept']) ?>">
                  <?= htmlspecialchars($dept['name_dept']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label for="role" class="form-label">Supervisor Name<span class="text-danger">*</span></label>
            <select name="email_spv" id="select_email_spv" class="form-select select2" required>
              <option value="" disabled selected>Select Supervisor</option>
              <?php while ($spv = mysqli_fetch_assoc($spv_result)): ?>
                <option value="<?= htmlspecialchars($spv['email_address']) ?>">
                  <?= htmlspecialchars($spv['name']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label for="role" class="form-label">Role<span class="text-danger">*</span></label>
            <select name="role" id="select_role" class="form-select select2" required>
              <option value="" disabled selected>Select Role</option>
              <option value="Admin">Admin</option>
              <option value="Employee">Employee</option>
              <option value="Supervisor">Supervisor</option>
              <option value="HRD">HRD</option>
            </select>
          </div>

          <div class="col-md-6">
            <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="password" name="password" id="password" class="form-control" required />
              <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>


        </div>
        <div class="modal-footer px-3">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="formEditUser">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fa fa-edit me-2"></i> Edit Employee
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body row g-3 px-3">

          <input type="hidden" name="user_id" id="edit_user_id">

          <div class="col-md-6">
            <label for="edit_name" class="form-label">Employee Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required />
          </div>

          <div class="col-md-6">
            <label for="edit_email_address" class="form-label">Email Address</label>
            <input type="email" name="email_address" id="edit_email_address" class="form-control" required readonly/>
          </div>

          <div class="col-md-6">
            <label for="edit_department" class="form-label">Department</label>
            <select name="department" id="edit_department" class="form-select select2" required>
              <!-- opsi akan dimasukkan lewat JS -->
            </select>
          </div>

          <div class="col-md-6">
            <label for="edit_email_spv" class="form-label">Supervisor</label>
            <select name="email_spv" id="edit_email_spv" class="form-select select2" required>
              <!-- opsi akan dimasukkan lewat JS -->
            </select>
          </div>

          <div class="col-md-6">
            <label for="edit_role" class="form-label">Role</label>
            <select name="role" id="edit_role" class="form-select select2" required>
              <option value="Admin">Admin</option>
              <option value="Employee">Employee</option>
              <option value="Supervisor">Supervisor</option>
              <option value="HRD">HRD</option>
            </select>
          </div>

          <div class="col-md-6">
            <label for="edit_password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
            <input type="password" name="password" id="edit_password" class="form-control" />
          </div>

        </div>
        <div class="modal-footer px-3">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>



<script>
  $(document).ready(function () {
    $('#user_table').DataTable();

    $('#select_role').select2({
      placeholder: "Select Role",
      width: '100%',
      dropdownParent: $('#addUserModal')
    });

    $('#select_department').select2({
      placeholder: "Select Department",
      width: '100%',
      dropdownParent: $('#addUserModal')
    });

    $('#select_email_spv').select2({
      placeholder: "Select Supervisor",
      width: '100%',
      dropdownParent: $('#addUserModal') // ganti sesuai ID modalnya
    });

    $('#edit_department').select2({
      placeholder: "Select Department",
      width: '100%',
      dropdownParent: $('#editUserModal')
    });

    $('#edit_email_spv').select2({
      placeholder: "Select Supervisor",
      width: '100%',
      dropdownParent: $('#editUserModal') // ganti sesuai ID modalnya
    });

    $('#edit_role').select2({
      placeholder: "Select Role",
      width: '100%',
      dropdownParent: $('#editUserModal') // ganti sesuai ID modalnya
    });

    $('#togglePassword').on('click', function () {
      const passwordInput = $('#password');
      const icon = $(this).find('i');

      if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
      } else {
        passwordInput.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
      }
    });

    $('#addUserModal form').on('submit', function (e) {
      e.preventDefault();

      const form = $(this);
      const formData = form.serialize();

      $.ajax({
        type: 'POST',
        url: './proses/insert_user.php',
        data: formData,
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: response.message,
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              $('#addUserModal').modal('hide');
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: response.message,
            });
          }
        },
        error: function (xhr) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan server!',
          });
          console.error(xhr.responseText);
        }
      });
    });


    $('#formEditUser').on('submit', function (e) {
      e.preventDefault();
      const formData = $(this).serialize();

      $.ajax({
        url: './proses/update_user.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: response.message,
              timer: 1500,
              showConfirmButton: false
            }).then(() => location.reload());
          } else {
            Swal.fire('Gagal', response.message, 'error');
          }
        },
        error: function () {
          Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
        }
      });
    });

  });

  function deleteUser(userId) {
    Swal.fire({
      title: 'Yakin ingin menghapus user ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: './proses/delete_user.php',
          type: 'POST',
          data: { id: userId },
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Terhapus!',
                text: response.message,
                timer: 1500,
                showConfirmButton: false
              }).then(() => {
                // Reload halaman supaya data terbaru muncul
                location.reload();
              });
            } else {
              Swal.fire('Gagal', response.message, 'error');
            }
          },
          error: function () {
            Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
          }
        });
      }
    });
  }

  function editUser(userId) {
    $.ajax({
      url: './proses/get_user.php',
      type: 'GET',
      data: { id: userId },
      dataType: 'json',
      success: function (user) {
        if (user) {
          $('#edit_user_id').val(user.user_id);
          $('#edit_name').val(user.name);
          $('#edit_email_address').val(user.email_address);

          // Ambil opsi department & supervisor
          $.ajax({
            url: './proses/get_options.php',
            type: 'GET',
            dataType: 'json',
            success: function (options) {
              // Department
              let deptOptions = `<option disabled>Select Department</option>`;
              options.departments.forEach(d => {
                deptOptions += `<option value="${d.name_dept}" ${d.name_dept === user.department ? 'selected' : ''}>${d.name_dept}</option>`;
              });
              $('#edit_department').html(deptOptions).trigger('change');

              // Supervisor
              let spvOptions = `<option disabled>Select Supervisor</option>`;
              options.supervisors.forEach(s => {
                spvOptions += `<option value="${s.email_address}" ${s.email_address === user.email_spv ? 'selected' : ''}>${s.name}</option>`;
              });
              $('#edit_email_spv').html(spvOptions).trigger('change');
            }
          });

          $('#edit_role').val(user.role).trigger('change');
          $('#edit_password').val('');

          $('#editUserModal').modal('show');
        } else {
          Swal.fire('Error', 'Data user tidak ditemukan.', 'error');
        }
      },
      error: function () {
        Swal.fire('Error', 'Gagal mengambil data user.', 'error');
      }
    });
  }


</script>