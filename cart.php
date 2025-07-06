<?php
/* ---------- cart.php ---------- */
session_start();
include __DIR__.'/header.php';   // header & koneksi DB sudah di-include di file ini

/* ---------------------------
   Handle aksi hapus / clear
   --------------------------- */
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);      // hapus produk tertentu
    header('Location: cart.php');
    exit;
}

if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);           // kosongkan seluruh keranjang
    header('Location: cart.php');
    exit;
}

/* ---------------------------
   Siapkan data keranjang
   --------------------------- */
$cart   = $_SESSION['cart'] ?? [];
$items  = [];
$total  = 0;

if ($cart) {
    $ids = implode(',', array_keys($cart));
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $row['qty']      = $cart[$row['id']];
        $row['subtotal'] = $row['qty'] * $row['price'];
        $total          += $row['subtotal'];
        $items[]         = $row;
    }
}
?>

<h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

<?php if ($items): ?>
<div class="overflow-x-auto shadow rounded-lg">
<table class="min-w-full text-sm text-left bg-white">
  <thead class="bg-gray-100 text-xs uppercase sticky top-0">
    <tr>
      <th class="px-4 py-2">Produk</th>
      <th class="px-4 py-2">Harga</th>
      <th class="px-4 py-2">Jumlah</th>
      <th class="px-4 py-2">Subtotal</th>
      <th class="px-4 py-2 text-center">Aksi</th>
    </tr>
  </thead>
  <tbody class="divide-y">
  <?php foreach ($items as $it): ?>
    <tr class="hover:bg-gray-50">
      <td class="px-4 py-3 flex items-center space-x-3">
        <img src="uploads/<?= htmlspecialchars($it['image'] ?: 'no-image.png') ?>"
             class="h-10 w-10 object-cover rounded">
        <span><?= htmlspecialchars($it['name']) ?></span>
      </td>
      <td class="px-4 py-3">Rp<?= number_format($it['price'], 0, ',', '.') ?></td>
      <td class="px-4 py-3"><?= $it['qty'] ?></td>
      <td class="px-4 py-3 font-semibold">
        Rp<?= number_format($it['subtotal'], 0, ',', '.') ?>
      </td>
      <td class="px-4 py-3 text-center">
        <a href="cart.php?remove=<?= $it['id'] ?>"
           onclick="return confirm('Hapus produk ini?')"
           class="text-red-600 hover:underline">Hapus</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>

<!-- total & tombol -->
<div class="mt-4 flex items-center space-x-3">
  <p class="text-xl font-bold">Total : Rp<?= number_format($total, 0, ',', '.') ?></p>

  <!-- Kosongkan -->
  <a href="cart.php?clear=1"
     onclick="return confirm('Kosongkan seluruh keranjang?')"
     class="ml-auto bg-gray-300 hover:bg-gray-400 text-sm px-3 py-1 rounded">
    Kosongkan
  </a>

  <!-- Checkout (memicu modal checkoutModal yang sudah ada di cart.php lama) -->
  <button type="button"
          data-modal-target="checkoutModal" data-modal-toggle="checkoutModal"
          class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded">
    Checkout
  </button>
</div>

<?php else: ?>
<p>Keranjang Anda kosong.</p>
<?php endif; ?>
<!-- ===== MODAL Checkout ===== -->
<div id="checkoutModal" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 bg-black/50
            overflow-y-auto h-modal md:h-full">
  <div class="relative w-full max-w-md mx-auto h-full md:h-auto">
    <div class="relative bg-white rounded-lg shadow p-6">
      <!-- tombol close -->
      <button type="button"
              class="absolute top-2 right-2 text-gray-400 hover:text-gray-600"
              data-modal-hide="checkoutModal">&times;</button>

      <h2 class="text-xl font-bold mb-4">Checkout</h2>
      <form action="process_checkout.php" method="post" class="space-y-4">
        <div>
          <label class="block text-sm mb-1">Nama</label>
          <input name="name" required class="border w-full px-2 py-1 rounded">
        </div>
        <div>
          <label class="block text-sm mb-1">Email</label>
          <input type="email" name="email" required
                 class="border w-full px-2 py-1 rounded">
        </div>
        <div>
          <label class="block text-sm mb-1">No. Telepon</label>
          <input name="phone" required class="border w-full px-2 py-1 rounded">
        </div>
        <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded">
          Bayar Sekarang
        </button>
      </form>
    </div>
  </div>
</div>


<?php include __DIR__.'/footer.php'; ?>
