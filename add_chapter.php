<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'] ?? null;
    $chapter_name = $_POST['chapter_name'] ?? null;
    $total_videos = $_POST['total_videos'] ?? 20;

    if ($subject_id && $chapter_name) {
        $stmt = $pdo->prepare("INSERT INTO chapters (subject_id, chapter_name, total_videos, completed_videos, theory_done, practice_done, pyq_done) VALUES (?, ?, ?, 0, 0, 0, 0)");
        $stmt->execute([$subject_id, $chapter_name, $total_videos]);
    }
}

header("Location: index.php");
exit;
?>
