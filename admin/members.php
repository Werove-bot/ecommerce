<?php
session_start();
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';

// handle CRUD
if(isset($_POST['action'])) {
    if($_POST['action']=='add') {
        $name=$_POST['name']; $role=$_POST['role']; $bio=$_POST['bio'];
        $photo='';
        if(isset($_FILES['photo']['name']) && $_FILES['photo']['name']) {
            $photo=time().'_'.$_FILES['photo']['name'];
            move_uploaded_file($_FILES['photo']['tmp_name'],'../uploads/'.$photo);
        }
        $stmt=$conn->prepare("INSERT INTO members(name,role,photo,bio) VALUES(?,?,?,?)");
        $stmt->bind_param('ssss',$name,$role,$photo,$bio);
        $stmt->execute();
    }
    if($_POST['action']=='delete') {
        $id=$_POST['id'];
        $conn->query("DELETE FROM members WHERE id=$id");
    }
}
$members=$conn->query("SELECT * FROM members");
$title='Anggota Tim';
include '../header.php';
?>
<h1 class="text-2xl font-bold mb-4">Anggota Tim</h1>
<button onclick="document.getElementById('mModal').classList.remove('hidden')" class="bg-green-500 text-white px-4 py-2 rounded mb-4">Tambah Anggota</button>
<table class="w-full text-sm">
  <thead><tr><th class="border px-2 py-1">ID</th><th class="border px-2 py-1">Nama</th><th class="border px-2 py-1">Peran</th><th class="border px-2 py-1">Aksi</th></tr></thead>
  <tbody>
    <?php while($m=$members->fetch_assoc()): ?>
    <tr>
      <td class="border px-2 py-1"><?= $m['id'] ?></td>
      <td class="border px-2 py-1"><?= htmlspecialchars($m['name']) ?></td>
      <td class="border px-2 py-1"><?= htmlspecialchars($m['role']) ?></td>
      <td class="border px-2 py-1">
        <form method="post" class="inline">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= $m['id'] ?>">
          <button class="text-red-600" onclick="return confirm('Hapus anggota ini?')">Hapus</button>
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<div id="mModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white p-6 rounded w-96 relative">
    <button class="absolute top-2 right-2 text-xl" onclick="document.getElementById('mModal').classList.add('hidden')">&times;</button>
    <h2 class="text-xl font-bold mb-4">Tambah Anggota</h2>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add">
      <div class="mb-2">
        <label class="block">Nama</label>
        <input class="border w-full px-2 py-1" name="name" required>
      </div>
      <div class="mb-2">
        <label class="block">Peran</label>
        <input class="border w-full px-2 py-1" name="role" required>
      </div>
      <div class="mb-2">
        <label class="block">Bio</label>
        <textarea class="border w-full px-2 py-1" name="bio"></textarea>
      </div>
      <div class="mb-4">
        <label class="block">Foto</label>
        <input type="file" name="photo" accept="image/*">
      </div>
      <button class="bg-blue-500 text-white py-1 px-4 rounded">Simpan</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('keydown', function(e){
  if(e.key==='Escape') document.getElementById('mModal').classList.add('hidden');
});
</script>

<?php include '../footer.php'; ?>
