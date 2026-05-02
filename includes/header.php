<?php
// Determine the current page
$currentPage = basename($_SERVER['PHP_SELF']);
$currentSubjectId = $_GET['id'] ?? null;

// Fetch subjects for sidebar
$sidebarStmt = $pdo->query("SELECT * FROM subjects");
$sidebarSubjects = $sidebarStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'SSC Tracker' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            transition: background-color 0.3s, color 0.3s;
            overflow-x: hidden;
            background-color: var(--bs-body-bg);
        }

        .progress {
            height: 25px;
        }

        /* Layout styles */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #212529; /* Dark theme sidebar */
            color: #fff;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #sidebar ul.components {
            padding: 20px 0;
            flex-grow: 1;
        }

        #sidebar ul li a {
            padding: 12px 20px;
            display: block;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: 0.3s;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #0d6efd; /* Primary color */
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0; /* Important for preventing overflow */
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
                position: fixed;
                height: 100vh;
                z-index: 1040; /* Above navbar */
            }
            #sidebar.active {
                margin-left: 0;
                box-shadow: 0 0 15px rgba(0,0,0,0.5);
            }
            /* Backdrop for mobile sidebar */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1030;
            }
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fs-5"><i class="bi bi-bullseye text-primary"></i> SSC Tracker</h4>
                <button type="button" id="sidebarCollapseClose" class="btn btn-sm btn-outline-light d-md-none border-0">
                    <i class="bi bi-x fs-5"></i>
                </button>
            </div>

            <ul class="list-unstyled components">
                <li class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">
                    <a href="index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                </li>
                
                <li class="px-3 pt-3 pb-2 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Subjects</li>
                
                <?php foreach ($sidebarSubjects as $sub): ?>
                    <li class="<?= ($currentPage == 'subject.php' && $currentSubjectId == $sub['id']) ? 'active' : '' ?>">
                        <a href="subject.php?id=<?= $sub['id'] ?>">
                            <i class="bi bi-book-half me-2" style="color: var(--bs-<?= str_replace('bg-', '', $sub['color_class']) ?>);"></i>
                            <?= htmlspecialchars($sub['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- Page Content -->
        <div class="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand navbar-dark bg-dark sticky-top">
                <div class="container-fluid px-3">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark d-md-none me-2">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    
                    <a class="navbar-brand d-md-none m-0" href="index.php">SSC Tracker</a>
                    <div class="d-none d-md-block fw-bold text-white fs-5"><?= $pageTitle ?? 'Dashboard' ?></div>

                    <div class="ms-auto d-flex align-items-center">
                        <span class="text-light me-3 d-none d-sm-inline">Hi, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                        <button class="btn btn-outline-light me-2 border-0" id="themeToggle" title="Toggle Dark Mode"><i class="bi bi-moon"></i></button>
                        <a href="logout.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Logout</span></a>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content Area -->
            <div class="main-content container-fluid mt-3">
