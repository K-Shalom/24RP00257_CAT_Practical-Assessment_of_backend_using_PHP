<?php
include 'config.php';
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { header('Location: login.php'); exit; }
$book_id = (int)($_GET['book_id'] ?? 0);
if ($book_id <= 0) { header('Location: admin_book.php'); exit; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $author = trim($_POST['author'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $availability = isset($_POST['availability_status']) ? (int)$_POST['availability_status'] : 1;
  if ($title && $author && $category) {
    $stmt = $conn->prepare('UPDATE books SET title=?, author=?, category=?, availability_status=? WHERE book_id=?');
    $stmt->bind_param('sssii', $title,$author,$category,$availability,$book_id);
    if ($stmt->execute()) { header('Location: admin_book.php'); exit; }
    else { $err = 'Failed to update book'; }
  } else { $err = 'All fields are required'; }
}
$stmt = $conn->prepare('SELECT book_id, title, author, category, availability_status FROM books WHERE book_id=?');
$stmt->bind_param('i', $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();
if (!$book) { header('Location: admin_book.php'); exit; }
include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Edit Book</h3>
  <a class="btn btn-outline-secondary" href="admin_book.php">Back</a>
</div>
<div class="card shadow-sm"><div class="card-body p-4">
  <?php if ($err): ?><div class="alert alert-danger"><?php echo $err; ?></div><?php endif; ?>
  <form method="post">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Title</label>
        <input class="form-control" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Author</label>
        <input class="form-control" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Category</label>
        <input class="form-control" name="category" value="<?php echo htmlspecialchars($book['category']); ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Availability</label>
        <select class="form-select" name="availability_status">
          <option value="1" <?php echo ((int)$book['availability_status']===1)?'selected':''; ?>>Available</option>
          <option value="0" <?php echo ((int)$book['availability_status']===0)?'selected':''; ?>>Unavailable</option>
        </select>
      </div>
    </div>
    <div class="mt-4">
      <button class="btn btn-primary">Save Changes</button>
      <a class="btn btn-outline-secondary" href="admin_book.php">Cancel</a>
    </div>
  </form>
</div></div>
<?php include 'footer.php'; ?>
