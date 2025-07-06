<?php
session_start();
require_once 'includes/db.php';

$cart = $_SESSION['cart'] ?? [];
if (!$cart) { header('Location: cart.php'); exit; }

$name  = $_POST['name']  ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

if (!$name) { $_SESSION['error'] = 'Nama wajib diisi.'; header('Location: cart.php'); exit; }

$code  = 'INV'.date('YmdHis');
$total = 0;

$conn->begin_transaction();

try {
    /* ---- Cek stok & hitung total (lock baris FOR UPDATE) ---- */
    foreach ($cart as $pid => $qty) {
        $row = $conn->query("SELECT price, stock FROM products WHERE id=$pid FOR UPDATE")
                    ->fetch_assoc();
        if (!$row)          throw new Exception('Produk tidak ditemukan.');
        if ($row['stock'] < $qty)
            throw new Exception('Stok produk “'.$pid.'” tidak mencukupi.');
        $total += $row['price'] * $qty;
    }

    /* ---- Simpan order ---- */
    $stmt = $conn->prepare("INSERT INTO orders
        (order_code, customer_name, customer_email, customer_phone, total, status)
        VALUES (?,?,?,?,?,'paid')");
    $stmt->bind_param('sssdi', $code, $name, $email, $phone, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    /* ---- Simpan item & kurangi stok ---- */
    foreach ($cart as $pid => $qty) {
        $price = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc()['price'];
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price)
                      VALUES ($order_id, $pid, $qty, $price)");
        $conn->query("UPDATE products SET stock = stock - $qty WHERE id=$pid");
    }

    $conn->commit();
    unset($_SESSION['cart']);

    header("Location: receipt.php?code=$code");
    exit;
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = $e->getMessage();
    header('Location: cart.php');
    exit;
}