<?php
include 'config.php';
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
$uid = $_SESSION['user']['id'];
$stmt = $conn->prepare('SELECT b.title, b.author, bb.borrow_date, bb.return_date FROM borrowed_books bb JOIN books b ON b.book_id = bb.book_id WHERE bb.student_id = ? ORDER BY bb.borrow_date DESC');
$stmt->bind_param('i', $uid);
$stmt->execute();
$borrows = $stmt->get_result();
include 'header.php';
?>
<div class="card shadow-sm">
  <div class="card-body p-4">
    <h3 class="card-title mb-2">Dashboard</h3>
    <p class="text-muted mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>.</p>
    <h5 class="mb-3">Your Borrowed Books</h5>
    <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead><tr><th>Title</th><th>Author</th><th>Borrowed</th><th>Return Date</th></tr></thead>
      <tbody>
        <?php while ($r = $borrows->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($r['title']); ?></td>
            <td><?php echo htmlspecialchars($r['author']); ?></td>
            <td><?php echo htmlspecialchars($r['borrow_date']); ?></td>
            <td><?php echo htmlspecialchars($r['return_date'] ?? ''); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    </div>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
      <a class="btn btn-sm btn-primary" href="admin_books.php">Manage Books</a>
    <?php endif; ?>
  </div>
</div>
<?php include 'footer.php'; ?>
