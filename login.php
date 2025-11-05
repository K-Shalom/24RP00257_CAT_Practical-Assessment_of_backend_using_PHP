<?php
include 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');
  if ($username === '' || $password === '') {
    $error = 'All fields are required';
  } else {
    $stmt = $conn->prepare('SELECT id, username, password, role, student_id FROM users WHERE username = ? OR email = ?');
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
      if (password_verify($password, $row['password'])) {
        $_SESSION['user'] = ['id'=>$row['id'],'username'=>$row['username'],'role'=>$row['role'],'student_id'=>$row['student_id']];
        header('Location: dashboard.php');
        exit;
      }
    }
    $error = 'Invalid credentials';
  }
}
include 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h3 class="card-title mb-3">Login</h3>
        <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <form method="post" novalidate>
          <div class="mb-3">
            <label class="form-label">Username or Email</label>
            <input type="text" class="form-control" name="username" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <button class="btn btn-primary" type="submit">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
