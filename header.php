<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__.'/includes/db.php';
$title = $title ?? 'My Store';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title) ?></title>

  <!-- Tailwind via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Flowbite (komponen Tailwind, termasuk carousel) -->
  <link  href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
  <!-- ===== NAVBAR ===== -->
  <nav class="bg-white shadow">
    <div class="container mx-auto px-4 py-3 flex justify-between">
      <a href="index.php" class="font-bold text-xl">KacamataKu</a>
      <div>
        <a class="mr-4 hover:underline" href="index.php">Beranda</a>
        <a class="mr-4 hover:underline" href="about.php">Tentang</a>
        <a class="hover:underline" href="cart.php">
          Keranjang (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)
        </a>
      </div>
    </div>
  </nav>

  <!-- ===== MAIN ===== -->
  <main class="container mx-auto flex-1 px-4 py-6">