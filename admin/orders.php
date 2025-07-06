<?php
session_start();
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
$title='Pesanan';
include '../header.php';
?>
<h1 class="text-2xl font-bold mb-4">Pesanan</h1>
<table class="w-full text-sm">
  <thead><tr><th class="border px-2 py-1">Kode</th><th class="border px-2 py-1">Nama</th><th class="border px-2 py-1">Total</th><th class="border px-2 py-1">Status</th><th class="border px-2 py-1">Tanggal</th></tr></thead>
  <tbody>
    <?php while($o=$orders->fetch_assoc()): ?>
    <tr>
      <td class="border px-2 py-1"><?= htmlspecialchars($o['order_code']) ?></td>
      <td class="border px-2 py-1"><?= htmlspecialchars($o['customer_name']) ?></td>
      <td class="border px-2 py-1">Rp<?= number_format($o['total'],0,',','.') ?></td>
      <td class="border px-2 py-1"><?= $o['status'] ?></td>
      <td class="border px-2 py-1"><?= $o['created_at'] ?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include '../footer.php'; ?>
