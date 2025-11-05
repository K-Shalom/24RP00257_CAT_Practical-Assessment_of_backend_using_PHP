<?php
include 'config.php';
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { header('Location: login.php'); exit; }
$book_id = (int)($_GET['book_id'] ?? 0);
if ($book_id > 0) {
  $stmt = $conn->prepare('DELETE FROM books WHERE book_id=?');
  $stmt->bind_param('i', $book_id);
  $stmt->execute();
}
header('Location: admin_book.php');
