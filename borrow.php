<?php
include 'config.php';
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
$book_id = (int)($_POST['book_id'] ?? 0);
if ($book_id > 0) {
  $conn->begin_transaction();
  try {
    $stmt = $conn->prepare('SELECT availability_status FROM books WHERE book_id=? FOR UPDATE');
    $stmt->bind_param('i', $book_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
      if ((int)$row['availability_status'] === 1) {
        $stmt = $conn->prepare('UPDATE books SET availability_status=0 WHERE book_id=?');
        $stmt->bind_param('i', $book_id);
        $stmt->execute();
        $stmt = $conn->prepare('INSERT INTO borrowed_books(student_id,book_id,borrow_date) VALUES(?,?,NOW())');
        $stmt->bind_param('ii', $_SESSION['user']['id'], $book_id);
        $stmt->execute();
        $conn->commit();
      } else { $_SESSION['flash'] = 'Book unavailable'; }
    }
  } catch (Exception $e) {
    $conn->rollback();
    $_SESSION['flash'] = 'Borrow failed';
  }
}
header('Location: books.php');
