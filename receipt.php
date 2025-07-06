<?php
$title = 'Receipt';
include './header.php';

$code  = $_GET['code'] ?? '';
$code  = $conn->real_escape_string($code);
$order = $conn->query("SELECT * FROM orders WHERE order_code='$code'")->fetch_assoc();

if (!$order) {
    echo '<p>Receipt tidak ditemukan.</p>';
    include './footer.php';
    exit;
}

$items = $conn->query("
    SELECT oi.*, p.name
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = {$order['id']}
");
?>

<div class="bg-white shadow rounded p-6 max-w-2xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Receipt</h1>

  <div class="mb-4">
    <p><strong>Kode&nbsp;Order:</strong> <?= htmlspecialchars($order['order_code']) ?></p>
    <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
    <p><strong>Pelanggan:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
    <p><strong>No. Telepon:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
  </div>

  <table class="w-full text-sm mb-4">
    <thead>
      <tr>
        <th class="border px-2 py-1">Produk</th>
        <th class="border px-2 py-1">Qty</th>
        <th class="border px-2 py-1">Harga</th>
        <th class="border px-2 py-1">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($it = $items->fetch_assoc()): ?>
      <tr>
        <td class="border px-2 py-1"><?= htmlspecialchars($it['name']) ?></td>
        <td class="border px-2 py-1"><?= $it['quantity'] ?></td>
        <td class="border px-2 py-1">Rp<?= number_format($it['price'], 0, ',', '.') ?></td>
        <td class="border px-2 py-1">
          Rp<?= number_format($it['price'] * $it['quantity'], 0, ',', '.') ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <p class="text-right font-bold text-lg">
    Total : Rp<?= number_format($order['total'], 0, ',', '.') ?>
  </p>

  <button onclick="window.print()"
          class="mt-6 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
    Cetak / Print
  </button>
  <a href="index.php"
     class="mt-6 ml-3 inline-block text-blue-600 hover:underline">Kembali ke Beranda</a>
</div>

<?php include './footer.php'; ?>
