<?php
require 'db.php';
if (!isset($_SESSION['user_id']))
    die("Unauthorized");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chapter_id = $_POST['chapter_id'] ?? null;
    $type = $_POST['type'] ?? null;
    $status = $_POST['status'] ?? null;

    $allowed_types = ['theory' => 'theory_done', 'practice' => 'practice_done', 'pyq' => 'pyq_done'];

    if ($chapter_id && isset($allowed_types[$type]) && isset($status)) {
        $column = $allowed_types[$type];
        $stmt = $pdo->prepare("UPDATE chapters SET $column = ? WHERE id = ?");
        if ($stmt->execute([$status, $chapter_id]))
            echo "Success";
    }
}
?>