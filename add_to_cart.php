<?php
session_start();
require_once 'includes/db.php';

$id  = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
$qty = (int) ($_POST['qty'] ?? 1);
if ($id < 1 || $qty < 1) { header('Location: index.php'); exit; }

/* ── pastikan stok cukup ── */
$row = $conn->query("SELECT stock FROM products WHERE id=$id")->fetch_assoc();
if (!$row) { header('Location: index.php'); exit; }

$qty = min($qty, $row['stock']);          // batasi ke stok tersedia
$_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;

header('Location: cart.php');              // kembali ke keranjang
exit;
