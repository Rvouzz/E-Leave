<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>E-Leave</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    .select2-container .select2-selection--single {
      height: 38px;
      padding: 5px 12px;
    }
  </style>
</head>

<body>
  <!-- Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <!-- E-Leave Logo -->
                <a href="#" class="text-nowrap text-center d-block py-3 w-100 text-decoration-none">
                  <div class="d-flex justify-content-center align-items-center">
                    <i class="fas fa-user-clock text-primary me-2" style="font-size: 2rem;"></i>
                    <span class="fw-bold text-dark" style="font-size: 1.8rem;">E-Leave</span>
                  </div>
                </a>
                <p class="text-center mt-2">Create your E-Leave account</p>

                <form>
                  <div class="mb-3">
                    <label for="inputName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="inputName" required>
                  </div>
                  <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="inputEmail" required>
                  </div>
                  <div class="mb-3">
                    <label for="inputDepartment" class="form-label">Department</label>
                    <select class="form-select select2" id="inputDepartment" required>
                      <option selected disabled>Choose Department</option>
                      <option value="HR">Human Resources</option>
                      <option value="IT">Information Technology</option>
                      <option value="Finance">Finance</option>
                      <option value="Production">Production</option>
                      <option value="QA">Quality Assurance</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="inputPassword" required>
                  </div>
                  <a href="./index.html" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign Up</a>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Already have an account?</p>
                    <a class="text-primary fw-bold ms-2" href="./authentication-login.php">Sign In</a>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function () {
      $('.select2').select2({
        width: '100%'
      });
    });
  </script>
</body>

</html>
