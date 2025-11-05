<?php
include 'config.php';
$q = trim($_GET['q'] ?? '');
$sql = 'SELECT book_id, title, author, category, availability_status FROM books';
$params = [];
$types = '';
if ($q !== '') {
  $sql .= ' WHERE title LIKE ? OR author LIKE ? OR category LIKE ?';
  $like = "%$q%";
  $params = [$like,$like,$like];
  $types = 'sss';
}
$sql .= ' ORDER BY title';
$stmt = $conn->prepare($sql);
if ($types) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$books = $stmt->get_result();
include 'header.php';
?>
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
      <h3 class="mb-2 mb-sm-0">Books</h3>
      <form class="row g-2" method="get">
        <div class="col-auto">
          <input type="text" class="form-control" name="q" placeholder="Search by title, author, category" value="<?php echo htmlspecialchars($q); ?>">
        </div>
        <div class="col-auto"><button class="btn btn-primary">Search</button></div>
      </form>
    </div>
    <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Title</th><th>Author</th><th>Category</th><th>Status</th><th></th></tr></thead>
      <tbody>
    <?php while ($b = $books->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($b['title']); ?></td>
        <td><?php echo htmlspecialchars($b['author']); ?></td>
        <td><?php echo htmlspecialchars($b['category']); ?></td>
        <td><?php echo (int)$b['availability_status'] === 1 ? '<span class="badge bg-success">Available</span>' : '<span class="badge bg-secondary">Unavailable</span>'; ?></td>
        <td>
          <?php if (!empty($_SESSION['user']) && (int)$b['availability_status'] === 1): ?>
            <form method="post" action="borrow.php" class="d-inline">
              <input type="hidden" name="book_id" value="<?php echo (int)$b['book_id']; ?>">
              <button class="btn btn-sm btn-outline-primary">Borrow</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
      </tbody>
    </table>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
