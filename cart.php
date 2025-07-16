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

<style>
  .cart-animation {
    animation: fadeInUp 0.6s ease-out;
  }
  
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .cart-item {
    transition: all 0.3s ease;
  }
  
  .cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  }
  
  .quantity-btn {
    transition: all 0.2s ease;
  }
  
  .quantity-btn:hover {
    transform: scale(1.1);
  }
  
  .checkout-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: all 0.3s ease;
  }
  
  .checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
  }
  
  .empty-cart {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .price-highlight {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
</style>

<div class="max-w-7xl mx-auto px-4 py-8">
  <!-- Header Section -->
  <div class="cart-animation mb-8">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Keranjang Belanja</h1>
        <p class="text-gray-600">Kelola produk pilihan Anda</p>
      </div>
      <div class="hidden md:flex items-center space-x-4">
        <div class="bg-purple-100 p-3 rounded-full">
          <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
        </div>
        <div>
          <p class="text-sm text-gray-500">Total Item</p>
          <p class="text-2xl font-bold text-purple-600"><?= count($items) ?></p>
        </div>
      </div>
    </div>
  </div>

  <?php if ($items): ?>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Cart Items Section -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-2xl shadow-xl p-6 cart-animation">
        <h2 class="text-2xl font-semibold mb-6 flex items-center">
          <i class="fas fa-list-ul mr-3 text-purple-600"></i>
          Produk dalam Keranjang
        </h2>
        
        <div class="space-y-4">
          <?php foreach ($items as $index => $it): ?>
          <div class="cart-item bg-gray-50 rounded-xl p-4 border border-gray-200">
            <div class="flex items-center space-x-4">
              <!-- Product Image -->
              <div class="relative">
                <img src="uploads/<?= htmlspecialchars($it['image'] ?: 'no-image.png') ?>"
                     class="h-20 w-20 object-cover rounded-xl shadow-md">
                <div class="absolute -top-2 -right-2 bg-purple-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                  <?= $it['qty'] ?>
                </div>
              </div>
              
              <!-- Product Details -->
              <div class="flex-1">
                <h3 class="font-semibold text-lg text-gray-900 mb-1">
                  <?= htmlspecialchars($it['name']) ?>
                </h3>
                <p class="text-gray-600 text-sm mb-2">Kacamata Premium</p>
                <div class="flex items-center space-x-4">
                  <span class="price-highlight text-lg font-bold">
                    Rp<?= number_format($it['price'], 0, ',', '.') ?>
                  </span>
                  <span class="text-sm text-gray-500">per item</span>
                </div>
              </div>
              
              <!-- Quantity Controls -->
              <div class="flex items-center space-x-3">
                <div class="flex items-center bg-white rounded-lg border border-gray-300 shadow-sm">
                  <button class="quantity-btn w-10 h-10 flex items-center justify-center text-purple-600 hover:bg-purple-50 rounded-l-lg">
                    <i class="fas fa-minus"></i>
                  </button>
                  <span class="w-12 text-center font-semibold"><?= $it['qty'] ?></span>
                  <button class="quantity-btn w-10 h-10 flex items-center justify-center text-purple-600 hover:bg-purple-50 rounded-r-lg">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>
              
              <!-- Subtotal -->
              <div class="text-right">
                <p class="text-sm text-gray-500 mb-1">Subtotal</p>
                <p class="text-xl font-bold text-gray-900">
                  Rp<?= number_format($it['subtotal'], 0, ',', '.') ?>
                </p>
              </div>
              
              <!-- Remove Button -->
              <button onclick="removeItem(<?= $it['id'] ?>)" 
                      class="w-10 h-10 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        
        <!-- Cart Actions -->
        <div class="mt-6 pt-6 border-t border-gray-200">
          <div class="flex justify-between items-center">
            <button onclick="clearCart()" 
                    class="flex items-center space-x-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
              <i class="fas fa-trash-alt"></i>
              <span>Kosongkan Keranjang</span>
            </button>
            <a href="index.php" 
               class="flex items-center space-x-2 px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors">
              <i class="fas fa-arrow-left"></i>
              <span>Lanjut Belanja</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Summary Section -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-2xl shadow-xl p-6 cart-animation sticky top-6">
        <h2 class="text-2xl font-semibold mb-6 flex items-center">
          <i class="fas fa-calculator mr-3 text-green-600"></i>
          Ringkasan Pesanan
        </h2>
        
        <!-- Order Details -->
        <div class="space-y-4 mb-6">
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Subtotal (<?= count($items) ?> item)</span>
            <span class="font-semibold">Rp<?= number_format($total, 0, ',', '.') ?></span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Ongkos Kirim</span>
            <span class="font-semibold text-green-600">Gratis</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Pajak</span>
            <span class="font-semibold">Rp0</span>
          </div>
          <hr class="border-gray-200">
          <div class="flex justify-between items-center text-lg">
            <span class="font-bold text-gray-900">Total</span>
            <span class="font-bold text-2xl price-highlight">
              Rp<?= number_format($total, 0, ',', '.') ?>
            </span>
          </div>
        </div>
        
        <!-- Promo Code -->
        <div class="mb-6">
          <div class="flex space-x-2">
            <input type="text" placeholder="Kode Promo" 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <button class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
              Terapkan
            </button>
          </div>
        </div>
        
        <!-- Checkout Button -->
        <button type="button"
                data-modal-target="checkoutModal" data-modal-toggle="checkoutModal"
                class="checkout-btn w-full text-white py-4 rounded-xl font-semibold text-lg shadow-lg">
          <i class="fas fa-credit-card mr-2"></i>
          Checkout Sekarang
        </button>
        
        <!-- Security Info -->
        <div class="mt-4 flex items-center justify-center space-x-2 text-sm text-gray-500">
          <i class="fas fa-shield-alt text-green-500"></i>
          <span>Pembayaran 100% Aman</span>
        </div>
      </div>
    </div>
  </div>

  <?php else: ?>
  <!-- Empty Cart State -->
  <div class="cart-animation text-center py-16">
    <div class="max-w-md mx-auto">
      <div class="mb-8">
        <i class="fas fa-shopping-cart text-8xl text-gray-300 mb-4"></i>
        <h2 class="empty-cart text-3xl font-bold mb-4">Keranjang Kosong</h2>
        <p class="text-gray-600 text-lg">Sepertinya Anda belum menambahkan produk ke keranjang</p>
      </div>
      
      <div class="space-y-4">
        <a href="index.php" 
           class="inline-flex items-center space-x-2 px-8 py-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-semibold transition-all transform hover:scale-105">
          <i class="fas fa-arrow-left"></i>
          <span>Mulai Belanja</span>
        </a>
        
        <div class="flex items-center justify-center space-x-8 text-sm text-gray-500 mt-8">
          <div class="flex items-center space-x-2">
            <i class="fas fa-truck text-green-500"></i>
            <span>Gratis Ongkir</span>
          </div>
          <div class="flex items-center space-x-2">
            <i class="fas fa-undo text-blue-500"></i>
            <span>30 Hari Retur</span>
          </div>
          <div class="flex items-center space-x-2">
            <i class="fas fa-medal text-yellow-500"></i>
            <span>Garansi Resmi</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- ===== MODAL Checkout ===== -->
<div id="checkoutModal" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 bg-black/50 backdrop-blur-sm
            overflow-y-auto h-modal md:h-full">
  <div class="relative w-full max-w-2xl mx-auto h-full md:h-auto">
    <div class="relative bg-white rounded-2xl shadow-2xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
            <i class="fas fa-credit-card text-purple-600"></i>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-gray-900">Checkout</h2>
            <p class="text-gray-600">Lengkapi data pengiriman</p>
          </div>
        </div>
        <button type="button"
                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                data-modal-hide="checkoutModal">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <!-- Modal Body -->
      <div class="p-6">
        <form action="process_checkout.php" method="post" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Info -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-user mr-2 text-purple-600"></i>
                Nama Lengkap
              </label>
              <input name="name" required 
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2 text-purple-600"></i>
                Email
              </label>
              <input type="email" name="email" required
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-phone mr-2 text-purple-600"></i>
                No. Telepon
              </label>
              <input name="phone" required
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>
                Kota
              </label>
              <select name="city" required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option value="">Pilih Kota</option>
                <option value="jakarta">Jakarta</option>
                <option value="bandung">Bandung</option>
                <option value="surabaya">Surabaya</option>
                <option value="medan">Medan</option>
                <option value="makassar">Makassar</option>
              </select>
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-home mr-2 text-purple-600"></i>
              Alamat Lengkap
            </label>
            <textarea name="address" required rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                      placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan"></textarea>
          </div>
          
          <!-- Payment Method -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-3">
              <i class="fas fa-credit-card mr-2 text-purple-600"></i>
              Metode Pembayaran
            </label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
              <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="payment" value="bank" required class="mr-3">
                <i class="fas fa-university text-blue-600 mr-2"></i>
                <span>Transfer Bank</span>
              </label>
              <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="payment" value="ewallet" required class="mr-3">
                <i class="fas fa-mobile-alt text-green-600 mr-2"></i>
                <span>E-Wallet</span>
              </label>
              <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="payment" value="cod" required class="mr-3">
                <i class="fas fa-hand-holding-usd text-orange-600 mr-2"></i>
                <span>COD</span>
              </label>
            </div>
          </div>
          
          <!-- Order Summary in Modal -->
          <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-semibold text-gray-900 mb-2">Ringkasan Pesanan</h3>
            <div class="flex justify-between items-center text-sm text-gray-600 mb-1">
              <span>Subtotal</span>
              <span>Rp<?= number_format($total, 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between items-center text-sm text-gray-600 mb-1">
              <span>Ongkos Kirim</span>
              <span class="text-green-600">Gratis</span>
            </div>
            <hr class="my-2">
            <div class="flex justify-between items-center font-semibold text-lg">
              <span>Total</span>
              <span class="price-highlight">Rp<?= number_format($total, 0, ',', '.') ?></span>
            </div>
          </div>
          
          <!-- Submit Button -->
          <button type="submit"
                  class="w-full checkout-btn text-white py-4 rounded-xl font-semibold text-lg shadow-lg">
            <i class="fas fa-lock mr-2"></i>
            Bayar Sekarang - Rp<?= number_format($total, 0, ',', '.') ?>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function removeItem(id) {
  if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
    window.location.href = 'cart.php?remove=' + id;
  }
}

function clearCart() {
  if (confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')) {
    window.location.href = 'cart.php?clear=1';
  }
}
</script>

<?php include __DIR__.'/footer.php'; ?>