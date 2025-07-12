<?php
session_start(); // pastikan session dimulai
$judul = 'Dashboard';
include '../proses/check_admin.php';
include '../connection.php';

$query = "SELECT `id_dept`, `name_dept`, `last_update` FROM `mst_dept`";
$result = mysqli_query($koneksi, $query);
$no = 1;
?>
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
                  <a href="proses/export_department_pdf.php" target="_blank"
                    class="btn btn-label-info btn-round btn-sm me-2" title="Export PDF">
                    <span class="btn-label"><i class="fa fa-print"></i></span>
                    Print
                  </a>
                  <a href="proses/export_department.php" class="btn btn-label-success btn-sm me-2">
                    <span class="btn-label"><i class="fa fa-file-export"></i></span>
                    Export
                  </a> 
                  <button class="btn btn-label-primary btn-round btn-sm" title="Add New Department"
                    data-bs-toggle="modal" data-bs-target="#addDeptModal">
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
                <table id="deptTable" class="table align-items-center mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col" class="text-center">No</th>
                      <th scope="col" class="text-center">Department Name</th>
                      <th scope="col" class="text-center">Date & Time</th>
                      <th scope="col" class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                      <tr>
                        <th class="text-center"><?= $no++; ?></th>
                        <th class="text-center"><?= htmlspecialchars($row['name_dept']); ?></th>
                        <td class="text-center"><?= date('M d, Y, g:i A', strtotime($row['last_update'])); ?></td>
                        <td class="text-center">
                          <button type="button" class="btn btn-sm btn-success me-1" title="Edit"
                            onclick="editDepartment('<?= $row['id_dept']; ?>', '<?= htmlspecialchars($row['name_dept'], ENT_QUOTES); ?>')">
                            <i class="fas fa-edit"></i>
                          </button>

                          <button class="btn btn-sm btn-danger" title="Delete"
                            onclick="deleteDepartment(<?= $row['id_dept']; ?>)">
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
</div> <!-- Penutup .wrapper -->

<!-- Modal -->
<div class="modal fade" id="addDeptModal" tabindex="-1" aria-labelledby="addDeptModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addDeptModalLabel"><i class="fa fa-plus me-2"></i> Add New Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name_dept" class="form-label">Department Name<span class="text-danger">*</span></label>
            <input type="text" name="name_dept" id="name_dept" class="form-control" placeholder="Enter department name"
              required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="editDeptModal" tabindex="-1" aria-labelledby="editDeptModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editDeptForm" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDeptModalLabel"><i class="fa fa-edit me-2"></i> Edit Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_dept" id="edit_id_dept">
          <div class="form-group">
            <label for="edit_name_dept" class="form-label">Department Name</label>
            <input type="text" name="name_dept" id="edit_name_dept" class="form-control" required />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
        </div>
      </div>
    </form>
  </div>
</div>



<script>
  $(document).ready(function () {
    $('#deptTable').DataTable();
  });

  $(document).ready(function () {
    $('form').on('submit', function (e) {
      e.preventDefault(); // Mencegah form submit biasa

      const name_dept = $('#name_dept').val().trim();

      if (name_dept === '') {
        Swal.fire('Oops!', 'Department name is required.', 'warning');
        return;
      }

      $.ajax({
        url: './proses/insert_department.php',
        method: 'POST',
        data: { name_dept: name_dept },
        success: function (response) {
          try {
            const res = JSON.parse(response);

            if (res.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Department added successfully.',
                timer: 2000,
                showConfirmButton: false
              }).then(() => {
                $('#addDeptModal').modal('hide');
                $('#name_dept').val('');
                location.reload(); // atau reload DataTable ajax jika digunakan
              });
            } else {
              Swal.fire('Failed', res.message, 'error');
            }
          } catch (e) {
            Swal.fire('Error', 'Unexpected response from server.', 'error');
          }
        },
        error: function () {
          Swal.fire('Error', 'An error occurred while adding department.', 'error');
        }
      });
    });
  });

  function deleteDepartment(deptId) {
    Swal.fire({
      title: 'Are you sure?',
      text: "This will permanently delete the department!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#aaa',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: './proses/delete_department.php',
          type: 'POST',
          data: { id_dept: deptId },
          success: function (response) {
            try {
              const res = JSON.parse(response);
              if (res.status === 'success') {
                Swal.fire({
                  icon: 'success',
                  title: 'Deleted!',
                  text: 'Department has been deleted.',
                  timer: 1500,
                  showConfirmButton: false
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire('Failed', res.message, 'error');
              }
            } catch (e) {
              Swal.fire('Error', 'Invalid response from server.', 'error');
            }
          },
          error: function () {
            Swal.fire('Error', 'Something went wrong.', 'error');
          }
        });
      }
    });
  }

  function editDepartment(id, name) {
    // Isi data ke dalam modal
    $('#edit_id_dept').val(id);
    $('#edit_name_dept').val(name);

    // Tampilkan modal
    $('#editDeptModal').modal('show');
  }

  $('#editDeptForm').on('submit', function (e) {
    e.preventDefault();

    const id = $('#edit_id_dept').val();
    const name = $('#edit_name_dept').val();

    $.ajax({
      url: './proses/update_department.php',
      type: 'POST',
      data: {
        id_dept: id,
        name_dept: name
      },
      success: function (response) {
        try {
          const res = JSON.parse(response);
          if (res.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Updated!',
              text: 'Department updated successfully.',
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              location.reload(); // atau refresh table aja jika pakai DataTables
            });
          } else {
            Swal.fire('Failed', res.message, 'error');
          }
        } catch (e) {
          Swal.fire('Error', 'Invalid response from server.', 'error');
        }
      },
      error: function () {
        Swal.fire('Error', 'Something went wrong.', 'error');
      }
    });
  });

</script>