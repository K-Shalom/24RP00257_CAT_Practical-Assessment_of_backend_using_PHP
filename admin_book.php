<?php
include 'config.php';
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { header('Location: login.php'); exit; }
$res = $conn->query('SELECT book_id, title, author, category, availability_status FROM books ORDER BY title');
include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Books</h3>
  <a class="btn btn-primary" href="book_add.php">Add Book</a>
</div>
<div class="table-responsive">
  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Category</th>
        <th>Availability</th>
        <th style="width:160px">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($b = $res->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($b['title']); ?></td>
          <td><?php echo htmlspecialchars($b['author']); ?></td>
          <td><?php echo htmlspecialchars($b['category']); ?></td>
          <td><?php echo ((int)$b['availability_status']===1) ? 'Available' : 'Unavailable'; ?></td>
          <td>
            <a class="btn btn-sm btn-outline-primary" href="book_edit.php?book_id=<?php echo (int)$b['book_id']; ?>">Edit</a>
            <a class="btn btn-sm btn-outline-danger" href="book_delete.php?book_id=<?php echo (int)$b['book_id']; ?>" onclick="return confirm('Are sure you want to delete this book this boook?');">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include 'footer.php'; ?>
