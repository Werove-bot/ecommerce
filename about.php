<?php
$title = 'Tentang Kami';
include './header.php';

$members = $conn->query("SELECT * FROM members");
?>
<h1 class="text-2xl font-bold mb-4">Tentang Kami</h1>
<p class="mb-6">Kami adalah tim yang berdedikasi untuk menyediakan produk terbaik.</p>

<h2 class="text-xl font-semibold mb-2">Anggota Tim</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
  <?php while($m = $members->fetch_assoc()): ?>
  <div class="bg-white rounded shadow p-4">
    <img src="uploads/<?= htmlspecialchars($m['photo'] ?: 'no-image.png') ?>" class="h-32 w-32 rounded-full object-cover mx-auto mb-2" alt="">
    <h3 class="font-semibold text-center"><?= htmlspecialchars($m['name']) ?></h3>
    <p class="text-sm text-center"><?= htmlspecialchars($m['role']) ?></p>
  </div>
  <?php endwhile; ?>
</div>
<?php include './footer.php'; ?>
