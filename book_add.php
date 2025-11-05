<?php
include 'config.php';
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { header('Location: login.php'); exit; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $author = trim($_POST['author'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $availability = isset($_POST['availability_status']) ? (int)$_POST['availability_status'] : 1;
  if ($title && $author && $category) {
    $stmt = $conn->prepare('INSERT INTO books(title,author,category,availability_status) VALUES(?,?,?,?)');
    $stmt->bind_param('sssi', $title,$author,$category,$availability);
    if ($stmt->execute()) { header('Location: admin_book.php'); exit; }
    else { $err = 'Failed to add book'; }
  } else { $err = 'All fields are required'; }
}
include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Add Book</h3>
  <a class="btn btn-outline-secondary" href="admin_book.php">Back</a>
</div>
<div class="card shadow-sm"><div class="card-body p-4">
  <?php if ($err): ?><div class="alert alert-danger"><?php echo $err; ?></div><?php endif; ?>
  <form method="post">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Title</label>
        <input class="form-control" name="title" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Author</label>
        <input class="form-control" name="author" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Category</label>
        <input class="form-control" name="category" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Availability</label>
        <select class="form-select" name="availability_status">
          <option value="1" selected>Available</option>
          <option value="0">Unavailable</option>
        </select>
      </div>
    </div>
    <div class="mt-4">
      <button class="btn btn-primary">Save</button>
      <a class="btn btn-outline-secondary" href="admin_book.php">Cancel</a>
    </div>
  </form>
</div></div>
<?php include 'footer.php'; ?>
