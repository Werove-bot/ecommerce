<?php
session_start();
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
$title='Dashboard';
include '../header.php';
?>
<h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>
<div class="space-x-4">
  <a href="products.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded">Kelola Produk</a>
  <a href="orders.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded">Pesanan</a>
  <a href="members.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded">Anggota Tim</a>
</div>
<?php include '../footer.php'; ?>
