<?php
require 'db.php';
if (!isset($_SESSION['user_id']))
    die("Unauthorized");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chapter_id = $_POST['chapter_id'] ?? null;
    $completed = $_POST['completed'] ?? null;
    $completed_video_ids = $_POST['completed_video_ids'] ?? '';

    if ($chapter_id !== null && $completed !== null) {
        $stmt = $pdo->prepare("UPDATE chapters SET completed_videos = ?, completed_video_ids = ? WHERE id = ?");
        if ($stmt->execute([$completed, $completed_video_ids, $chapter_id]))
            echo "Success";
    }
}
?>