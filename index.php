<?php
require 'db.php';

// SECURITY CHECK: Agar login nahi hai, toh login page par fek do
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = 'Dashboard';
require 'includes/header.php';
?>

        <!-- Share Link Alert -->
        <div class="alert alert-info d-flex align-items-center justify-content-between mb-4 shadow-sm" role="alert">
            <div>
                <i class="bi bi-link-45deg fs-4 me-2"></i>
                <span><strong>Access Anywhere:</strong> Use this link from any device: <a href="https://ssctracker.infinityfreeapp.com/index.php" target="_blank" class="alert-link" id="shareLinkUrl">https://ssctracker.infinityfreeapp.com/index.php</a></span>
            </div>
            <button class="btn btn-sm btn-outline-info ms-3 text-nowrap" onclick="copyShareLink()" id="copyLinkBtn">
                <i class="bi bi-copy"></i> Copy Link
            </button>
        </div>

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
            // We already have $sidebarSubjects from header.php, but let's just use it or re-query if needed.
            // Actually $sidebarSubjects has exactly what we need.
            $subjects = $sidebarSubjects;

            // Fetch overall progress data
            $totalOverallTasks = 0;
            $completedOverallTasks = 0;

            foreach ($subjects as $subject):
                $subId = $subject['id'];
                $color = $subject['color_class'];

                // Calculate summary for this subject
                $chapStmt = $pdo->prepare("SELECT * FROM chapters WHERE subject_id = ?");
                $chapStmt->execute([$subId]);
                $chapters = $chapStmt->fetchAll(PDO::FETCH_ASSOC);

                $subjectTotalVideos = 0;
                $subjectCompletedVideos = 0;
                $subjectTotalCheckboxes = 0;
                $subjectCompletedCheckboxes = 0;

                foreach ($chapters as $ch) {
                    $subjectTotalVideos += ($ch['total_videos'] ?? 20);
                    $subjectCompletedVideos += ($ch['completed_videos'] ?? 0);
                    
                    // Count checkboxes for overall progress (Theory, Practice, PYQ)
                    $subjectTotalCheckboxes += 3; // 3 checkboxes per chapter
                    if ($ch['theory_done']) $subjectCompletedCheckboxes++;
                    if ($ch['practice_done']) $subjectCompletedCheckboxes++;
                    if ($ch['pyq_done']) $subjectCompletedCheckboxes++;
                }

                $totalOverallTasks += $subjectTotalCheckboxes + $subjectTotalVideos;
                $completedOverallTasks += $subjectCompletedCheckboxes + $subjectCompletedVideos;

                $remainingVideos = $subjectTotalVideos - $subjectCompletedVideos;
                
                $subjectTotal = $subjectTotalCheckboxes + $subjectTotalVideos;
                $subjectCompleted = $subjectCompletedCheckboxes + $subjectCompletedVideos;
                $subjectOverallPerc = $subjectTotal > 0 ? round(($subjectCompleted / $subjectTotal) * 100) : 0;
                ?>

                <!-- Subject Summary Card -->
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header <?= $color ?> text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fs-6"><?= htmlspecialchars($subject['name']) ?></h5>
                            <a href="subject.php?id=<?= $subId ?>" class="btn btn-sm btn-light py-0 px-2 fw-bold">
                                View <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row text-center mb-3">
                                <div class="col-6 border-end">
                                    <h3 class="mb-0 text-success"><?= $subjectCompletedVideos ?></h3>
                                    <small class="text-muted">Videos Completed</small>
                                </div>
                                <div class="col-6">
                                    <h3 class="mb-0 text-danger"><?= $remainingVideos ?></h3>
                                    <small class="text-muted">Videos Remaining</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="fw-bold">Overall Progress</small>
                                <small class="text-muted"><?= $subjectOverallPerc ?>%</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar <?= str_replace('bg-', 'bg-', $color) ?>" role="progressbar" style="width: <?= $subjectOverallPerc ?>%;" aria-valuenow="<?= $subjectOverallPerc ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php
    $overallPerc = $totalOverallTasks > 0 ? round(($completedOverallTasks / $totalOverallTasks) * 100) : 0;
    ?>
    
    <script>
        // Set overall progress on load based on server calculation
        document.addEventListener('DOMContentLoaded', () => {
            const overallBar = document.getElementById('overallProgress');
            if (overallBar && overallBar.style.width === '0%') {
                overallBar.style.width = '<?= $overallPerc ?>%';
                overallBar.innerText = '<?= $overallPerc ?>%';
            }
        });

        function copyShareLink() {
            const link = document.getElementById('shareLinkUrl').href;
            navigator.clipboard.writeText(link).then(() => {
                const btn = document.getElementById('copyLinkBtn');
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check2"></i> Copied!';
                btn.classList.replace('btn-outline-info', 'btn-info');
                btn.classList.add('text-white');
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.replace('btn-info', 'btn-outline-info');
                    btn.classList.remove('text-white');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                alert("Failed to copy link. Please select and copy manually.");
            });
        }
    </script>

<?php require 'includes/footer.php'; ?>