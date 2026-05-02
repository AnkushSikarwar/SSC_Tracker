<?php
require 'db.php';

// SECURITY CHECK: Agar login nahi hai, toh login page par fek do
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSC Journey Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            transition: background-color 0.3s, color 0.3s;
        }

        .progress {
            height: 25px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-bullseye"></i> SSC Tracker</a>
            <div class="ms-auto d-flex align-items-center">
                <span class="text-light me-3">Hi, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                <button class="btn btn-outline-light me-2" id="themeToggle"><i class="bi bi-moon"></i></button>
                <a href="logout.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Hero Section: Overall Progress -->
        <div class="p-4 mb-4 bg-light rounded-3 border shadow-sm text-center">
            <h2>Target: GST Inspector 🎯</h2>
            <div class="progress mt-3">
                <div id="overallProgress" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                    style="width: 0%;">0%</div>
            </div>
        </div>

        <div class="row">
            <?php
            $stmt = $pdo->query("SELECT * FROM subjects");
            $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($subjects as $subject):
                $subId = $subject['id'];
                $color = $subject['color_class'];

                $chapStmt = $pdo->prepare("SELECT * FROM chapters WHERE subject_id = ?");
                $chapStmt->execute([$subId]);
                $chapters = $chapStmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <!-- Subject Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header <?= $color ?> text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?= htmlspecialchars($subject['name']) ?></h5>
                            <div>
                                <span class="badge bg-light text-dark progress-text me-2" data-subject="<?= $subId ?>">0%</span>
                                <button class="btn btn-sm btn-light py-0 px-1" title="Add Chapter" data-bs-toggle="modal" data-bs-target="#addChapterModal" data-subject-id="<?= $subId ?>" data-subject-name="<?= htmlspecialchars($subject['name']) ?>">
                                    <i class="bi bi-plus-circle"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="acc-<?= $subId ?>">
                                <?php foreach ($chapters as $index => $chapter): ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#chap-<?= $chapter['id'] ?>">
                                                <?= htmlspecialchars($chapter['chapter_name']) ?>
                                            </button>
                                        </h2>
                                        <div id="chap-<?= $chapter['id'] ?>" class="accordion-collapse collapse"
                                            data-bs-parent="#acc-<?= $subId ?>">
                                            <div class="accordion-body">
                                                <form class="chapter-form" data-chapter="<?= $chapter['id'] ?>">

                                                    <!-- VIDEO TRACKER -->
                                                    <div class="p-2 mb-3 bg-light border rounded">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <label class="mb-0 fw-bold">📹 Videos Watched: <span id="video-count-<?= $chapter['id'] ?>"><?= $chapter['completed_videos'] ?? 0 ?></span> / <?= $chapter['total_videos'] ?? 20 ?></label>
                                                        </div>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            <?php
                                                            $totalVids = $chapter['total_videos'] ?? 20;
                                                            $completedVidsList = [];
                                                            if (!empty($chapter['completed_video_ids'])) {
                                                                $completedVidsList = explode(',', $chapter['completed_video_ids']);
                                                            }
                                                            for ($i = 1; $i <= $totalVids; $i++):
                                                                $isChecked = in_array($i, $completedVidsList) ? 'checked' : '';
                                                            ?>
                                                                <div class="form-check form-check-inline m-0">
                                                                    <input class="form-check-input video-checkbox" type="checkbox" id="vid-<?= $chapter['id'] ?>-<?= $i ?>" value="<?= $i ?>" data-chapter="<?= $chapter['id'] ?>" <?= $isChecked ?>>
                                                                    <label class="form-check-label" style="font-size: 0.85rem;" for="vid-<?= $chapter['id'] ?>-<?= $i ?>">V<?= $i ?></label>
                                                                </div>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>

                                                    <!-- CHECKBOXES -->
                                                    <div class="form-check">
                                                        <input class="form-check-input tracker-check" type="checkbox"
                                                            data-type="theory" <?= $chapter['theory_done'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label">Theory Concepts</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input tracker-check" type="checkbox"
                                                            data-type="practice" <?= $chapter['practice_done'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label">Practice Questions</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input tracker-check" type="checkbox"
                                                            data-type="pyq" <?= $chapter['pyq_done'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label">Previous Year Questions (PYQs)</label>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Chapter Modal -->
    <div class="modal fade" id="addChapterModal" tabindex="-1" aria-labelledby="addChapterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="add_chapter.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addChapterModalLabel">Add New Chapter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="subject_id" id="modalSubjectId">
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" id="modalSubjectName" readonly disabled>
                    </div>
                    <div class="mb-3">
                        <label for="chapter_name" class="form-label">Chapter Name</label>
                        <input type="text" class="form-control" id="chapter_name" name="chapter_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="total_videos" class="form-label">Total Videos</label>
                        <input type="number" class="form-control" id="total_videos" name="total_videos" value="20" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Chapter</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        const addChapterModal = document.getElementById('addChapterModal');
        if (addChapterModal) {
            addChapterModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const subjectId = button.getAttribute('data-subject-id');
                const subjectName = button.getAttribute('data-subject-name');
                
                document.getElementById('modalSubjectId').value = subjectId;
                document.getElementById('modalSubjectName').value = subjectName;
            });
        }
    </script>
</body>

</html>