<?php
require 'db.php';

// Agar pehle se login hai, toh index.php par bhej do
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Auto-Create Default User (First Time Setup)
$checkUser = $pdo->query("SELECT * FROM users");
if ($checkUser->rowCount() == 0) {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->query("INSERT INTO users (username, password) VALUES ('admin', '$hash')");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $userData = $stmt->fetch();

    if ($userData && password_verify($pass, $userData['password'])) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Galat Username ya Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - SSC Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">🔐 SSC Tracker Login</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Hint: admin">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Hint: admin123">
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>

</body>

</html>