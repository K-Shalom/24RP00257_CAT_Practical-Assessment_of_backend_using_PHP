<?php include 'config.php'; include 'header.php'; ?>
<div class="row g-4">
  <div class="col-12">
    <div class="p-5 mb-4 bg-white border rounded-3 shadow-sm">
      <div class="container-fluid py-4">
        <h1 class="display-6 fw-bold mb-2">RP Karongi Library</h1>
        <p class="col-lg-8 mb-3">Welcome. Manage library books online. Create an account and login to continue.</p>
        <?php if (empty($_SESSION['user'])): ?>
          <a class="btn btn-primary me-2" href="signup.php">Register</a>
          <a class="btn btn-outline-primary" href="login.php">Login</a>
        <?php else: ?>
          <a class="btn btn-primary me-2" href="dashboard.php">Go to Dashboard</a>
          <a class="btn btn-outline-primary" href="books.php">Browse Books</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php if (!empty($_SESSION['user'])): ?>
    <?php $isAdmin = ($_SESSION['user']['role'] === 'admin'); ?>
    <div class="col-md-4">
      <div class="card card-hover h-100">
        <div class="card-body">
          <h5 class="card-title">Books</h5>
          <p class="card-text">Search and borrow available books.</p>
          <a href="books.php" class="btn btn-outline-primary">Open Books</a>
        </div>
      </div>
    </div>
    <?php if ($isAdmin): ?>
    <div class="col-md-4">
      <div class="card card-hover h-100">
        <div class="card-body">
          <h5 class="card-title">Manage Books</h5>
          <p class="card-text">Add, edit, and delete books in the catalog.</p>
          <a href="admin_book.php" class="btn btn-outline-primary">Admin</a>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <div class="col-md-4">
      <div class="card card-hover h-100">
        <div class="card-body">
          <h5 class="card-title">Dashboard</h5>
          <p class="card-text">View your borrowed books and activity.</p>
          <a href="dashboard.php" class="btn btn-outline-primary">Open Dashboard</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
