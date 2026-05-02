document.addEventListener('DOMContentLoaded', () => {
    // Dark Mode Toggle
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const html = document.documentElement;
            const isDark = html.getAttribute('data-bs-theme') === 'dark';
            html.setAttribute('data-bs-theme', isDark ? 'light' : 'dark');
            themeToggle.innerHTML = isDark ? '<i class="bi bi-moon"></i>' : '<i class="bi bi-sun"></i>';
        });
    }

    calculateProgress();

    // Checkbox Tracker
    document.querySelectorAll('.tracker-check').forEach(box => {
        box.addEventListener('change', (e) => {
            const chapterId = e.target.closest('form').getAttribute('data-chapter');
            const type = e.target.getAttribute('data-type');
            const isChecked = e.target.checked ? 1 : 0;

            calculateProgress();

            fetch('update_progress.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `chapter_id=${chapterId}&type=${type}&status=${isChecked}`
            });
        });
    });

    // Video Tracker (Checkboxes)
    document.querySelectorAll('.video-checkbox').forEach(input => {
        input.addEventListener('change', (e) => {
            const chapterId = e.target.getAttribute('data-chapter');
            const checkboxes = document.querySelectorAll(`.video-checkbox[data-chapter="${chapterId}"]`);
            
            let completedVideos = 0;
            let completedIds = [];
            
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    completedVideos++;
                    completedIds.push(cb.value);
                }
            });

            // Update the count text
            const countSpan = document.getElementById(`video-count-${chapterId}`);
            if (countSpan) {
                countSpan.innerText = completedVideos;
            }

            fetch('update_videos.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `chapter_id=${chapterId}&completed=${completedVideos}&completed_video_ids=${completedIds.join(',')}`
            });
        });
    });

    function calculateProgress() {
        let totalBoxes = document.querySelectorAll('.tracker-check').length;
        
        if (totalBoxes > 0) {
            let totalChecked = document.querySelectorAll('.tracker-check:checked').length;
            let overallPerc = Math.round((totalChecked / totalBoxes) * 100);
            const overallBar = document.getElementById('overallProgress');
            if (overallBar) {
                overallBar.style.width = `${overallPerc}%`;
                overallBar.innerText = `${overallPerc}%`;
            }

            document.querySelectorAll('.card').forEach(card => {
                let subBoxes = card.querySelectorAll('.tracker-check').length;
                let subChecked = card.querySelectorAll('.tracker-check:checked').length;
                let subPerc = subBoxes === 0 ? 0 : Math.round((subChecked / subBoxes) * 100);

                let progressText = card.querySelector('.progress-text');
                if (progressText) progressText.innerText = `${subPerc}% Completed`;
            });
        }
    }
});