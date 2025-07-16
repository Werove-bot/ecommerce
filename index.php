<?php
/* ---------- index.php ---------- */
$title = 'Beranda';
include __DIR__.'/header.php';   // memuat koneksi $conn

/* 1) Produk rekomendasi  (carousel) */
$reco = $conn->query(
  "SELECT * FROM products
   WHERE recommended = 1
   ORDER BY created_at DESC
   LIMIT 10"
)->fetch_all(MYSQLI_ASSOC);

/* 2) Semua produk          (grid)   */
$all  = $conn->query(
  "SELECT * FROM products
   ORDER BY created_at DESC"
)->fetch_all(MYSQLI_ASSOC);
?>

<h1 class="text-2xl font-bold mb-4">Selamat Datang di KacamataKu</h1>
<div class="bg-gradient-to-r from-blue-50 to-indigo-100 rounded-lg shadow-md p-6 mb-6 border-l-4 border-blue-500">
  <div class="flex items-start">
    <div class="flex-shrink-0 mr-4">
      <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
      </div>
    </div>
    <div class="flex-1">
      <h2 class="text-lg font-semibold text-gray-800 mb-2">Tentang KacamataKu</h2>
      <p class="text-gray-600 leading-relaxed">
        KacamataKu adalah toko kacamata terpercaya yang menyediakan berbagai pilihan kacamata berkualitas untuk kebutuhan sehari-hari, fashion, maupun kesehatan mata. Kami menghadirkan produk terbaik dengan harga terjangkau dan desain kekinian yang sesuai untuk segala usia.
      </p>
    </div>
  </div>
</div>

<!-- ===== CAROUSEL: hanya rekomendasi ===== -->
<?php if ($reco): ?>
<h2 class="text-xl font-semibold mb-2">Rekomendasi Produk</h2>

<div id="productCarousel" class="relative w-full mb-8"
     data-carousel="slide" data-carousel-autoplay="true">

  <div class="relative h-56 md:h-96 overflow-hidden rounded-lg">
    <?php foreach ($reco as $i => $p): ?>
      <div data-carousel-item
           class="<?= $i ? 'hidden' : '' ?> duration-700 ease-in-out">
        <img src="uploads/<?= $p['image'] ?: 'no-image.png' ?>"
             class="absolute block w-full h-full object-contain bg-white"
             alt="">
        <div class="absolute inset-0 bg-black/40 flex flex-col justify-end p-6">
          <h3 class="text-white text-2xl font-bold mb-1">
            <?= htmlspecialchars($p['name']) ?>
          </h3>
          <p class="text-white text-lg mb-4">
            Rp<?= number_format($p['price'],0,',','.') ?>
          </p>

          <button
            class="productBtn self-start bg-blue-500 hover:bg-blue-600
                   text-white px-4 py-2 rounded"
            data-id="<?= $p['id'] ?>"
            data-name="<?= htmlspecialchars($p['name'],ENT_QUOTES) ?>"
            data-desc="<?= htmlspecialchars($p['description'],ENT_QUOTES) ?>"
            data-price="<?= $p['price'] ?>"
            data-image="uploads/<?= $p['image'] ? htmlspecialchars($p['image']) : 'no-image.png' ?>"
            data-stock="<?= $p['stock'] ?>"
            data-modal-target="productModal" data-modal-toggle="productModal">
            Tambah ke Keranjang
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- prev / next -->
  <button data-carousel-prev
          class="absolute inset-y-0 left-0 z-30 flex items-center px-4">
    <span class="inline-flex w-10 h-10 rounded-full bg-white/30
                 hover:bg-white/50 justify-center items-center">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
           d="M15 19l-7-7 7-7"/></svg>
    </span>
  </button>
  <button data-carousel-next
          class="absolute inset-y-0 right-0 z-30 flex items-center px-4">
    <span class="inline-flex w-10 h-10 rounded-full bg-white/30
                 hover:bg-white/50 justify-center items-center">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
           d="M9 5l7 7-7 7"/></svg>
    </span>
  </button>
</div>
<?php endif; ?>

<!-- ===== GRID: semua produk ===== -->
<h2 class="text-xl font-semibold mb-2">Semua Produk</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
<?php foreach ($all as $p): ?>
  <button
    class="productBtn bg-white rounded shadow p-4 text-left hover:ring-2
           hover:ring-blue-400 flex flex-col"
    data-id="<?= $p['id'] ?>"
    data-name="<?= htmlspecialchars($p['name'],ENT_QUOTES) ?>"
    data-desc="<?= htmlspecialchars($p['description'],ENT_QUOTES) ?>"
    data-price="<?= $p['price'] ?>"
    data-image="uploads/<?= $p['image'] ? htmlspecialchars($p['image']) : 'no-image.png' ?>"
    data-stock="<?= $p['stock'] ?>"
    data-modal-target="productModal" data-modal-toggle="productModal">

    <div class="h-40 w-full rounded overflow-hidden mb-2">
      <img src="uploads/<?= $p['image'] ?: 'no-image.png' ?>"
           class="w-full h-full object-contain bg-white" alt="">
    </div>

    <h3 class="font-semibold mb-1"><?= htmlspecialchars($p['name']) ?></h3>
    <p class="font-bold mb-3">Rp<?= number_format($p['price'],0,',','.') ?></p>

    <span class="mt-auto bg-blue-500 text-white text-center py-1 rounded">
      Lihat Detail
    </span>
  </button>
<?php endforeach; ?>
</div>

<!-- ===== MODAL detail + qty (gambar contain, radius rapi) ===== -->
<div id="productModal" tabindex="-1" aria-hidden="true"
     class="fixed inset-0 z-50 hidden w-full p-4 bg-black/50 overflow-y-auto
            h-modal md:h-full">
  <div class="relative w-full max-w-lg mx-auto h-full md:h-auto">
    <div class="relative bg-white rounded-lg shadow p-6">
      <button type="button"
              class="absolute top-2 right-2 text-gray-400 hover:text-gray-600"
              data-modal-hide="productModal">&times;</button>

      <div class="flex flex-col md:flex-row">
        <div class="flex-shrink-0 w-full md:w-60 h-56 rounded-lg
                    md:rounded-l-lg md:rounded-r-none overflow-hidden
                    mb-4 md:mb-0 bg-white">
          <img id="mImage" src="" class="w-full h-full object-contain" alt="">
        </div>

        <div class="flex-1 md:pl-6">
          <h3 id="mName"  class="text-2xl font-bold mb-2"></h3>
          <p  id="mDesc"  class="text-gray-700 mb-4"></p>
          <p  id="mPrice" class="text-xl font-semibold mb-4"></p>

          <form action="add_to_cart.php" method="post" class="space-y-4">
            <input type="hidden" name="id" value="">
            <div>
              <label class="block text-sm mb-1">Jumlah</label>
              <input type="number" name="qty" value="1" min="1"
                     class="border w-full px-2 py-1 rounded" required>
              <small id="mStock" class="text-xs text-gray-500"></small>
            </div>
            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded">
              Tambahkan ke Keranjang
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== SCRIPT: isi modal ===== -->
<script>
document.querySelectorAll('.productBtn').forEach(btn => {
  btn.addEventListener('click', () => {
    const m = document.getElementById('productModal');
    m.querySelector('#mImage').src         = btn.dataset.image;
    m.querySelector('#mName' ).textContent = btn.dataset.name;
    m.querySelector('#mDesc' ).textContent = btn.dataset.desc;
    m.querySelector('#mPrice').textContent =
      'Rp' + Number(btn.dataset.price).toLocaleString('id-ID');
    m.querySelector('input[name=id]').value  = btn.dataset.id;
    m.querySelector('input[name=qty]').value = 1;
    m.querySelector('input[name=qty]').max   = btn.dataset.stock;
    m.querySelector('#mStock').textContent   =
      'Stok tersedia: ' + btn.dataset.stock;
  });
});
</script>

<?php include __DIR__.'/footer.php'; ?>
