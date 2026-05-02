<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$subId = $_GET['id'] ?? null;
if (!$subId) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
$stmt->execute([$subId]);
$subject = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subject) {
    header("Location: index.php");
    exit;
}

$color = $subject['color_class'];

$chapStmt = $pdo->prepare("SELECT * FROM chapters WHERE subject_id = ?");
$chapStmt->execute([$subId]);
$chapters = $chapStmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = htmlspecialchars($subject['name']);
require 'includes/header.php';
?>

        <div class="mb-3 d-flex align-items-center">
            <a href="index.php" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
            <h4 class="mb-0 text-muted">Chapters</h4>
        </div>

        <!-- Subject Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header <?= $color ?> text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><?= htmlspecialchars($subject['name']) ?></h4>
                <div>
                    <span class="badge bg-light text-dark progress-text me-2" data-subject="<?= $subId ?>">0%</span>
                    <button class="btn btn-sm btn-light py-0 px-2 fw-bold" title="Add Chapter" data-bs-toggle="modal" data-bs-target="#addChapterModal" data-subject-id="<?= $subId ?>" data-subject-name="<?= htmlspecialchars($subject['name']) ?>">
                        <i class="bi bi-plus-circle"></i> Add Chapter
                    </button>
                </div>
            </div>
            <div class="progress" style="height: 8px; border-radius: 0;">
                <div class="progress-bar bg-success subject-progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($chapters)): ?>
                    <div class="p-4 text-center">
                        <div class="alert alert-info d-inline-block">No chapters added yet. Click "Add Chapter" to begin.</div>
                    </div>
                <?php else: ?>
                    <div class="accordion accordion-flush" id="acc-<?= $subId ?>">
                        <?php foreach ($chapters as $index => $chapter): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#chap-<?= $chapter['id'] ?>">
                                        <?= htmlspecialchars($chapter['chapter_name']) ?>
                                    </button>
                                </h2>
                                <div id="chap-<?= $chapter['id'] ?>" class="accordion-collapse collapse"
                                    data-bs-parent="#acc-<?= $subId ?>">
                                    <div class="accordion-body bg-light border-top">
                                        <form class="chapter-form" data-chapter="<?= $chapter['id'] ?>">

                                            <!-- VIDEO TRACKER -->
                                            <div class="p-3 mb-3 bg-white border rounded shadow-sm">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <label class="mb-0 fw-bold"><i class="bi bi-camera-video text-primary"></i> Videos Watched: <span id="video-count-<?= $chapter['id'] ?>"><?= $chapter['completed_videos'] ?? 0 ?></span> / <?= $chapter['total_videos'] ?? 20 ?></label>
                                                </div>
                                                <div class="d-flex flex-wrap gap-2 mt-3">
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
                                            <div class="d-flex flex-column gap-2 p-3 bg-white border rounded shadow-sm">
                                                <div class="form-check">
                                                    <input class="form-check-input tracker-check" type="checkbox" id="theory-<?= $chapter['id'] ?>"
                                                        data-type="theory" <?= $chapter['theory_done'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="theory-<?= $chapter['id'] ?>">Theory Concepts</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input tracker-check" type="checkbox" id="practice-<?= $chapter['id'] ?>"
                                                        data-type="practice" <?= $chapter['practice_done'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="practice-<?= $chapter['id'] ?>">Practice Questions</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input tracker-check" type="checkbox" id="pyq-<?= $chapter['id'] ?>"
                                                        data-type="pyq" <?= $chapter['pyq_done'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="pyq-<?= $chapter['id'] ?>">Previous Year Questions (PYQs)</label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
                    <input type="hidden" name="return_to_subject" value="1">
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

<?php require 'includes/footer.php'; ?>
