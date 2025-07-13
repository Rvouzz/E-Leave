<?php
session_start();
if (isset($_SESSION['email_address'])) {
  header("Location: " . $_SESSION['role'] . "/dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Welcome to E-Leave</title>
  <link rel="shortcut icon" href="assets/images/logos/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
      color: #333;
    }

    .navbar {
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .navbar-brand {
      color: #0d6efd !important;
      font-weight: bold;
      font-size: 1.5rem;
    }

    .hero {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      padding: 120px 20px 80px;
      color: #fff;
      text-align: center;
    }

    .hero h1 {
      font-size: 3.5rem;
      font-weight: 700;
    }

    .hero p {
      font-size: 1.2rem;
      margin-top: 1rem;
      max-width: 600px;
      margin-inline: auto;
    }

    .login-btn {
      margin-top: 2rem;
      padding: 0.75rem 2rem;
      font-weight: 600;
      font-size: 1.1rem;
      background-color: #fff;
      color: #0d6efd;
      border-radius: 50px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .login-btn:hover {
      background-color: #f0f0f0;
    }

    .features {
      padding: 80px 20px;
      background-color: #fdfdfd;
    }

    .feature-card {
      background: #fff;
      border: 1px solid #eee;
      border-radius: 1rem;
      padding: 40px 30px;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    .feature-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .feature-card i {
      font-size: 3rem;
      color: #0d6efd;
      margin-bottom: 1rem;
    }

    .feature-card h5 {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    footer {
      background: #f8f9fa;
      text-align: center;
      padding: 1.2rem;
      font-size: 0.9rem;
      color: #666;
      border-top: 1px solid #eaeaea;
    }

    @media (max-width: 576px) {
      .hero h1 {
        font-size: 2.2rem;
      }

      .feature-card {
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top py-3 px-4">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <i class="fas fa-user-clock fa-lg me-2"></i> E-Leave
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero">
    <h1>Welcome to E-Leave</h1>
    <p>Simplify your employee leave process with a secure, modern, and efficient platform built for companies of all sizes.</p>
    <a href="authentication-login.php" class="login-btn">
      <i class="fas fa-sign-in-alt me-2"></i>Login Now
    </a>
  </section>

  <!-- Features -->
  <section class="features">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold">Key Features</h2>
        <p class="text-muted">Empower your HR and employees with a seamless leave management experience</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="feature-card">
            <i class="fas fa-user-edit"></i>
            <h5>Quick Leave Application</h5>
            <p>Request leave in seconds with a simple and intuitive form.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card">
            <i class="fas fa-user-shield"></i>
            <h5>Supervisor Approval</h5>
            <p>Approvals are easy to manage and track in real time.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card">
            <i class="fas fa-chart-line"></i>
            <h5>Analytics & Reports</h5>
            <p>Monitor leave trends and generate reports for insights.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    &copy; <?= date('Y'); ?> PT. XYZ BATAM — Powered by E-Leave System
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
