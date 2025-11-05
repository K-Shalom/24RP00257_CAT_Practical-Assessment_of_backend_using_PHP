<?php
include 'config.php';

$errors = [
  'general' => '',
  'username' => '',
  'email' => '',
  'student_id' => '',
  'password' => '',
  'confirm' => ''
];

$old = [
  'username' => htmlspecialchars($_POST['username'] ?? ''),
  'email' => htmlspecialchars($_POST['email'] ?? ''),
  'student_id' => htmlspecialchars($_POST['student_id'] ?? '')
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = trim($_POST['username'] ?? '');
  $e = trim($_POST['email'] ?? '');
  $sid = trim($_POST['student_id'] ?? '');
  $p = trim($_POST['password'] ?? '');
  $c = trim($_POST['confirm'] ?? '');

  if ($u === '') { $errors['username'] = 'Username is required'; }
  if ($e === '' || !filter_var($e, FILTER_VALIDATE_EMAIL)) { $errors['email'] = 'Valid email is required'; }
  if ($sid === '') { $errors['student_id'] = 'Student ID is required'; }
  if ($p === '' || strlen($p) < 6) { $errors['password'] = 'Password must be at least 6 characters'; }
  if ($c === '' || $p !== $c) { $errors['confirm'] = 'Passwords do not match'; }

  if (!$errors['username'] && !$errors['email'] && !$errors['student_id'] && !$errors['password'] && !$errors['confirm']) {
    $stmt = $conn->prepare('SELECT 1 FROM users WHERE username = ? OR email = ? OR student_id = ? LIMIT 1');
    $stmt->bind_param('sss', $u, $e, $sid);
    $stmt->execute();
    $dup = $stmt->get_result();
    if ($dup->num_rows > 0) {
      $errors['general'] = 'Username, email, or student ID already exists';
    } else {
      $hash = password_hash($p, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users(username,email,student_id,password,role) VALUES(?,?,?,?, 'student')");
      $stmt->bind_param('ssss', $u, $e, $sid, $hash);
      if ($stmt->execute()) {
        header('Location: login.php');
        exit;
      } else {
        $errors['general'] = 'Failed to register';
      }
    }
  }
}

include 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h3 class="card-title mb-3">Create your account</h3>
        <?php if ($errors['general']): ?><div class="alert alert-danger"><?php echo $errors['general']; ?></div><?php endif; ?>
        <form method="post" novalidate>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control <?php echo $errors['username']? 'is-invalid':''; ?>" value="<?php echo $old['username']; ?>" required>
        <?php if ($errors['username']): ?><div class="invalid-feedback"><?php echo $errors['username']; ?></div><?php endif; ?>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control <?php echo $errors['email']? 'is-invalid':''; ?>" value="<?php echo $old['email']; ?>" required>
        <?php if ($errors['email']): ?><div class="invalid-feedback"><?php echo $errors['email']; ?></div><?php endif; ?>
      </div>
      <div class="mb-3">
        <label class="form-label">Student ID</label>
        <input type="text" name="student_id" class="form-control <?php echo $errors['student_id']? 'is-invalid':''; ?>" value="<?php echo $old['student_id']; ?>" required>
        <?php if ($errors['student_id']): ?><div class="invalid-feedback"><?php echo $errors['student_id']; ?></div><?php endif; ?>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control <?php echo $errors['password']? 'is-invalid':''; ?>" required minlength="6">
        <?php if ($errors['password']): ?><div class="invalid-feedback"><?php echo $errors['password']; ?></div><?php endif; ?>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm" class="form-control <?php echo $errors['confirm']? 'is-invalid':''; ?>" required>
        <?php if ($errors['confirm']): ?><div class="invalid-feedback"><?php echo $errors['confirm']; ?></div><?php endif; ?>
      </div>
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-primary" type="submit">Sign Up</button>
        <a class="btn btn-link" href="login.php">Already have an account?</a>
      </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
