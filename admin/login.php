<?php
session_start();
require_once '../includes/db.php';
if (isset($_SESSION['admin'])) {
    header('Location: index.php'); exit;
}
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param('s',$u);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($usr = $res->fetch_assoc()) {
        if (hash('sha256',$p)===$usr['password']) {
            $_SESSION['admin']=$usr['username'];
            header('Location: index.php'); exit;
        }
    }
    $error='Username atau password salah';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
  <form method="post" class="bg-white p-6 rounded shadow w-80">
    <h1 class="text-xl font-bold mb-4 text-center">Login Admin</h1>
    <?php if($error): ?><p class="text-red-500 text-sm mb-2"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <input class="border w-full px-2 py-1 mb-2" name="username" placeholder="Username" required>
    <input type="password" class="border w-full px-2 py-1 mb-4" name="password" placeholder="Password" required>
    <button class="bg-blue-500 text-white py-2 w-full rounded hover:bg-blue-600">Login</button>
  </form>
</body>
</html>
