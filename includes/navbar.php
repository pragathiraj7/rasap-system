<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Role-Based Navigation Bar (includes/navbar.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Renders role-specific top navigation menus for Student, Faculty, Coordinator,
 * and Parent views. Fully aligned with Proposal Sections 4, 5 & 7.
 */

if (!isset($currentRole)) {
    $currentUser = getCurrentUser();
    $currentRole = $currentUser['role'] ?? 'student';
}

$activePage = basename($_SERVER['PHP_SELF']);
?>

<!-- Main Role-Based Top Navigation Bar -->
<nav class="main-nav-bar">
  <div class="nav-container">
    <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle Navigation Menu">&#9776;</button>
    
    <ul class="nav-menu" id="mainNavMenu">
      
      <?php if ($currentRole === 'student'): ?>
        <!-- STUDENT NAVIGATION MENU -->
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>student/dashboard.php" class="nav-link <?php echo $activePage === 'dashboard.php' && strpos($_SERVER['PHP_SELF'], 'student/') !== false ? 'active' : ''; ?>">
            📊 Student Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>student/edocet-upload.php" class="nav-link <?php echo $activePage === 'edocet-upload.php' ? 'active' : ''; ?>">
            📤 Upload Certificate
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>student/activity-submit.php" class="nav-link <?php echo $activePage === 'activity-submit.php' ? 'active' : ''; ?>">
            🏆 Submit Activity
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>student/exit-profile.php" class="nav-link <?php echo $activePage === 'exit-profile.php' ? 'active' : ''; ?>">
            🎓 3rd-Year Exit Profile
          </a>
        </li>

      <?php elseif (in_array($currentRole, ['faculty_coordinator', 'rasap_coordinator', 'faculty', 'rasap_faculty'])): ?>
        <!-- COORDINATOR & FACULTY NAVIGATION MENU -->
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>coordinator/dashboard.php" class="nav-link <?php echo $activePage === 'dashboard.php' && strpos($_SERVER['PHP_SELF'], 'coordinator/') !== false ? 'active' : ''; ?>">
            📈 Department Analytics
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>coordinator/document-review.php" class="nav-link <?php echo $activePage === 'document-review.php' ? 'active' : ''; ?>">
            📋 Document Review Queue
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>coordinator/mentoring.php" class="nav-link <?php echo $activePage === 'mentoring.php' ? 'active' : ''; ?>">
            🤝 Faculty Mentoring Logs
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>student/exit-profile.php" class="nav-link <?php echo $activePage === 'exit-profile.php' ? 'active' : ''; ?>">
            📄 Exit-Interview Report
          </a>
        </li>

      <?php elseif ($currentRole === 'parent'): ?>
        <!-- PARENT NAVIGATION MENU -->
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>parent/dashboard.php" class="nav-link <?php echo $activePage === 'dashboard.php' && strpos($_SERVER['PHP_SELF'], 'parent/') !== false ? 'active' : ''; ?>">
            👨‍👩‍👦 Ward Progress Overview
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>parent/dashboard.php#ward-documents" class="nav-link">
            📁 Verified E-Docets
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo $basePath; ?>parent/dashboard.php#academic-timeline" class="nav-link">
            🤝 Mentor Summaries
          </a>
        </li>
      <?php endif; ?>

    </ul>

    <!-- Right Side Active Role Pill -->
    <div style="color: var(--gold-accent); font-size: 0.88rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
      <span style="display: inline-block; width: 8px; height: 8px; background-color: #10B981; border-radius: 50%;"></span>
      <span>Session: <?php echo htmlspecialchars($currentUser['batch'] ?? '2024-2027'); ?></span>
    </div>
  </div>
</nav>
