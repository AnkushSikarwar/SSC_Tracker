# SSC Journey Tracker 🎯

A personalized, dynamic web application designed to help aspirants systematically track their study progress for SSC (Staff Selection Commission) exams, with a specific focus on achieving targets like "GST Inspector".

## 🚀 Features

- **Authentication System**: Secure login system to ensure your progress remains private.
- **Interactive Dashboard**: A high-level overview of your journey. Displays an aggregate "Target" progress bar, alongside individual subject summary cards showing completed vs. remaining videos and tasks.
- **Dynamic Subject Pages**: Dedicated pages for each subject (e.g., Quantitative Aptitude, English Language) with collapsible chapter accordions.
- **Granular Progress Tracking**: 
  - Track individual video completions with dynamically generated checkboxes.
  - Track conceptual understanding using dedicated **Theory**, **Practice**, and **Previous Year Question (PYQ)** checkboxes per chapter.
- **Real-Time Calculation**: The application instantly recalculates and animates your progress bars and percentage badges using asynchronous JavaScript (`fetch` API) whenever a task is checked off.
- **Modern UI & UX**: Built with Bootstrap 5, featuring a responsive persistent sidebar, a sleek dark mode toggle, and mobile-friendly navigation.
- **Live Access Banner**: Easily share or access your live deployment link directly from the dashboard.

## 🛠️ Technology Stack

- **Frontend**: HTML5, Vanilla CSS, JavaScript, Bootstrap 5, Bootstrap Icons.
- **Backend**: PHP (PDO for secure database interactions).
- **Database**: MySQL.

## 📂 Project Structure

```text
SSC_Tracker/
├── assets/
│   └── js/
│       └── main.js          # Handles real-time progress calculations, dark mode, and AJAX requests
├── includes/
│   ├── header.php           # Shared layout component: Sidebar, Top Navbar, CSS imports
│   └── footer.php           # Shared layout component: JavaScript imports, sidebar toggle logic
├── db.php                   # Database connection configuration using PDO
├── index.php                # Main Dashboard displaying high-level subject summaries
├── subject.php              # Dynamic subject page displaying chapters and tracking tools
├── login.php                # User authentication page
├── logout.php               # Handles user logout and session destruction
├── add_chapter.php          # Backend script to add new chapters to the database
├── update_progress.php      # Backend script to update Theory/Practice/PYQ status
└── update_videos.php        # Backend script to update specific video completion status
```

## ⚙️ Setup Instructions

1. **Prerequisites**: Ensure you have a local server environment like XAMPP, WAMP, or MAMP installed with PHP and MySQL running.
2. **Clone the Repository**: Place the `SSC_Tracker` folder inside your server's root directory (e.g., `C:\xampp\htdocs\SSC_Tracker`).
3. **Database Configuration**:
   - Create a new MySQL database named `ssc_tracker`.
   - Ensure you have the following tables:
     - `subjects`: `id`, `name`, `color_class`
     - `chapters`: `id`, `subject_id`, `chapter_name`, `total_videos`, `completed_videos`, `completed_video_ids`, `theory_done`, `practice_done`, `pyq_done`
     - (And a users table for authentication if applicable).
   - Update `db.php` if your database credentials differ from the default (`root` with no password).
4. **Run the Application**: Navigate to `http://localhost/SSC_Tracker/index.php` in your browser.

## 💡 Usage

1. Start by navigating to a subject from the sidebar.
2. Click **"Add Chapter"** to define a new topic along with the total number of video lectures for that chapter.
3. As you study, check off the specific video numbers you have watched.
4. Mark the **Theory Concepts**, **Practice Questions**, and **PYQs** as done to complete the chapter.
5. Return to the **Dashboard** to see your overall Target progress bar grow!

---
*Built to maintain consistency and discipline during the rigorous SSC preparation phase.*
