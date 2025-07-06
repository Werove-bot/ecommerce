<?php
$title = 'Checkout';
include './header.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // process dummy payment
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) { header('Location: cart.php'); exit; }
    $code = 'INV'.time();
    $total = 0;
    $ids = implode(',', array_keys($cart));
    $products = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    while($p = $products->fetch_assoc()) {
        $total += $p['price'] * $cart[$p['id']];
    }
    $stmt = $conn->prepare("INSERT INTO orders(order_code,customer_name,customer_email,customer_phone,total,status) VALUES (?,?,?,?,?,'paid')");
    $stmt->bind_param('sssdi',$code,$name,$email,$phone,$total);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    // items
    foreach($cart as $pid=>$qty) {
        $product = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc();
        $price = $product['price'];
        $conn->query("INSERT INTO order_items(order_id,product_id,quantity,price) VALUES ($order_id,$pid,$qty,$price)");
        $conn->query("UPDATE products SET stock = stock - $qty WHERE id=$pid");
    }
    unset($_SESSION['cart']);
    $success=true;
}
?>
<h1 class="text-2xl font-bold mb-4">Checkout</h1>
<?php if(!isset($success)): ?>
<form method="post" class="max-w-md">
  <div class="mb-2">
    <label class="block">Nama</label>
    <input type="text" name="name" required class="border w-full px-2 py-1">
  </div>
  <div class="mb-2">
    <label class="block">Email</label>
    <input type="email" name="email" required class="border w-full px-2 py-1">
  </div>
  <div class="mb-2">
    <label class="block">No. Telepon</label>
    <input type="text" name="phone" required class="border w-full px-2 py-1">
  </div>
  <button class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Bayar Sekarang</button>
</form>
<?php else: ?>
<div class="bg-green-100 border border-green-400 text-green-700 p-4 rounded">
  <p>Pembayaran berhasil! Pesanan Anda diproses.</p>
</div>
<?php endif; ?>
<?php include './footer.php'; ?>
