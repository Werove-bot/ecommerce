<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

require_once '../includes/db.php';   // ganti jika db.php pindah

/* ========= Handle aksi CRUD ========= */
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {

        /* ----- INSERT ----- */
        case 'add':
            $name = $_POST['name'];  $desc = $_POST['description'];
            $price = $_POST['price']; $stock = $_POST['stock'];
            $rec  = isset($_POST['recommended']) ? 1 : 0;
            $img  = '';

            if (!empty($_FILES['image']['name'])) {
                $img = time().'_'.$_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/'.$img);
            }
            $stmt = $conn->prepare(
              "INSERT INTO products(name,description,price,stock,image,recommended)
               VALUES (?,?,?,?,?,?)");
            $stmt->bind_param('ssdisi', $name,$desc,$price,$stock,$img,$rec);
            $stmt->execute();
            break;

        /* ----- UPDATE ----- */
        case 'update':
            $id   = $_POST['id'];
            $name = $_POST['name'];  $desc = $_POST['description'];
            $price = $_POST['price']; $stock = $_POST['stock'];
            $rec  = isset($_POST['recommended']) ? 1 : 0;
            $set_img = '';

            if (!empty($_FILES['image']['name'])) {
                $img = time().'_'.$_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'],'../uploads/'.$img);
                $set_img = ", image='$img'";
            }
            $conn->query(
              "UPDATE products SET
               name='$name', description='$desc', price=$price,
               stock=$stock, recommended=$rec $set_img
               WHERE id=$id");
            break;

        /* ----- DELETE ----- */
        case 'delete':
            $id = $_POST['id'];
            $conn->query("DELETE FROM products WHERE id=$id");
            break;
    }
    header('Location: products.php');  /* hindari re‑POST */
    exit;
}

/* ========= Ambil data ========= */
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");

$title = 'Kelola Produk';
include '../header.php';   // ubah sesuai lokasi header Anda
?>

<h1 class="text-2xl font-bold mb-6">Kelola Produk</h1>

<div class="flex justify-between items-center mb-4">
  <button data-modal-target="addModal" data-modal-toggle="addModal"
          class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
    + Tambah Produk
  </button>

  <input id="searchInput" type="text" placeholder="Cari produk…"
         class="border px-3 py-1 rounded w-64">
</div>

<!-- ========= Tabel produk ========= -->
<div class="overflow-x-auto">
<table id="productTable" class="min-w-full text-sm text-left text-gray-700">
  <thead class="bg-gray-100 text-xs uppercase sticky top-0">
    <tr>
      <th class="border px-3 py-2">ID</th>
      <th class="border px-3 py-2">Gambar</th>
      <th class="border px-3 py-2">Nama</th>
      <th class="border px-3 py-2">Harga</th>
      <th class="border px-3 py-2">Stok</th>
      <th class="border px-3 py-2">Rekomendasi</th>
      <th class="border px-3 py-2 text-center">Aksi</th>
    </tr>
  </thead>
  <tbody class="divide-y">
    <?php while ($p = $products->fetch_assoc()): ?>
    <tr class="hover:bg-gray-50">
      <td class="px-3 py-2"><?= $p['id'] ?></td>
      <td class="px-3 py-2">
        <?php if ($p['image']): ?>
          <img src="../uploads/<?= htmlspecialchars($p['image']) ?>"
               class="h-10 w-10 object-cover rounded">
        <?php else: ?>‑<?php endif; ?>
      </td>
      <td class="px-3 py-2"><?= htmlspecialchars($p['name']) ?></td>
      <td class="px-3 py-2">Rp<?= number_format($p['price'],0,',','.') ?></td>
      <td class="px-3 py-2"><?= $p['stock'] ?></td>
      <td class="px-3 py-2 text-center"><?= $p['recommended'] ? '✓' : '-' ?></td>
      <td class="px-3 py-2 text-center space-x-2">
        <!-- tombol EDIT -->
        <button type="button"
          data-modal-target="editModal" data-modal-toggle="editModal"
          class="editBtn bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded"
          data-id="<?= $p['id'] ?>"
          data-name="<?= htmlspecialchars($p['name'],ENT_QUOTES) ?>"
          data-description="<?= htmlspecialchars($p['description'],ENT_QUOTES) ?>"
          data-price="<?= $p['price'] ?>"
          data-stock="<?= $p['stock'] ?>"
          data-recommended="<?= $p['recommended'] ?>">
          Edit
        </button>

        <!-- tombol DELETE -->
        <form method="post" class="inline">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= $p['id'] ?>">
          <button onclick="return confirm('Hapus produk ini?')"
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
            Hapus
          </button>
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</div>

<!-- ========= Modal Tambah ========= -->
<div id="addModal" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-y-auto
            bg-black/50 h-modal md:h-full">
  <div class="relative w-full max-w-lg mx-auto h-full md:h-auto">
    <div class="relative bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-bold mb-4">Tambah Produk</h2>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">

        <div class="mb-3">
          <label class="block text-sm">Nama</label>
          <input name="name" required class="border w-full px-2 py-1 rounded">
        </div>
        <div class="mb-3">
          <label class="block text-sm">Deskripsi</label>
          <textarea name="description" class="border w-full px-2 py-1 rounded"></textarea>
        </div>
        <div class="mb-3 flex space-x-4">
          <div class="flex-1">
            <label class="block text-sm">Harga</label>
            <input type="number" step="0.01" name="price" required
                   class="border w-full px-2 py-1 rounded">
          </div>
          <div class="flex-1">
            <label class="block text-sm">Stok</label>
            <input type="number" name="stock" required
                   class="border w-full px-2 py-1 rounded">
          </div>
        </div>
        <div class="mb-3">
          <label class="inline-flex items-center">
            <input type="checkbox" name="recommended" class="mr-2"> Rekomendasi
          </label>
        </div>
        <div class="mb-4">
          <label class="block text-sm">Gambar</label>
          <input type="file" name="image" accept="image/*">
        </div>

        <div class="text-right">
          <button type="button" data-modal-hide="addModal"
                  class="mr-2 px-4 py-2 rounded border">Batal</button>
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========= Modal Edit ========= -->
<div id="editModal" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-y-auto
            bg-black/50 h-modal md:h-full">
  <div class="relative w-full max-w-lg mx-auto h-full md:h-auto">
    <div class="relative bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-bold mb-4">Edit Produk</h2>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="">

        <div class="mb-3">
          <label class="block text-sm">Nama</label>
          <input name="name" required class="border w-full px-2 py-1 rounded">
        </div>
        <div class="mb-3">
          <label class="block text-sm">Deskripsi</label>
          <textarea name="description" class="border w-full px-2 py-1 rounded"></textarea>
        </div>
        <div class="mb-3 flex space-x-4">
          <div class="flex-1">
            <label class="block text-sm">Harga</label>
            <input type="number" step="0.01" name="price" required
                   class="border w-full px-2 py-1 rounded">
          </div>
          <div class="flex-1">
            <label class="block text-sm">Stok</label>
            <input type="number" name="stock" required
                   class="border w-full px-2 py-1 rounded">
          </div>
        </div>
        <div class="mb-3">
          <label class="inline-flex items-center">
            <input type="checkbox" name="recommended" class="mr-2"> Rekomendasi
          </label>
        </div>
        <div class="mb-4">
          <label class="block text-sm">Ganti Gambar (opsional)</label>
          <input type="file" name="image" accept="image/*">
        </div>

        <div class="text-right">
          <button type="button" data-modal-hide="editModal"
                  class="mr-2 px-4 py-2 rounded border">Batal</button>
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Update
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========= JS pencarian & isi modal edit ========= -->
<script>
/* --- pencarian baris --- */
document.getElementById('searchInput')
  .addEventListener('keyup', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#productTable tbody tr').forEach(tr => {
      tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });

/* --- populate modal edit --- */
document.querySelectorAll('.editBtn').forEach(btn => {
  btn.addEventListener('click', () => {
    const m = document.getElementById('editModal');
    m.querySelector('input[name=id]').value          = btn.dataset.id;
    m.querySelector('input[name=name]').value        = btn.dataset.name;
    m.querySelector('textarea[name=description]').value = btn.dataset.description;
    m.querySelector('input[name=price]').value       = btn.dataset.price;
    m.querySelector('input[name=stock]').value       = btn.dataset.stock;
    m.querySelector('input[name=recommended]').checked = (btn.dataset.recommended === '1');
  });
});
</script>

<?php include '../footer.php'; /* ubah jika footer di /includes */ ?>
