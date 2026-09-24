<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Header Component (includes/header.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Renders HTML <head>, CSS links, top utility bar, vector SVG logo component,
 * and role-switcher dropdown navigation.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';
$currentUser = getCurrentUser();
$currentRole = $currentUser['role'] ?? 'student';

// Determine base URL path dynamically for nested subfolders (auth/, student/, coordinator/, parent/)
$depth = 0;
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
if (strpos($scriptName, '/auth/') !== false || 
    strpos($scriptName, '/student/') !== false || 
    strpos($scriptName, '/coordinator/') !== false || 
    strpos($scriptName, '/parent/') !== false) {
    $basePath = '../';
} else {
    $basePath = './';
}

$pageTitle = $pageTitle ?? 'RASAP - Rajagiri Study Abroad Program';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($pageTitle); ?> | Rajagiri College of Social Sciences</title>
  
  <!-- System CSS Dependencies -->
  <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/global.css">
  <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/navbar.css">
  <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/dashboard.css">
  
  <!-- Chart.js CDN for Analytics & KPI Visualizations -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>

  <!-- ========================================================================
       1. TOP INSTITUTIONAL UTILITY BAR
       ======================================================================== -->
  <div class="top-utility-bar">
    <div class="utility-container">
      <div class="utility-left">
        <div class="utility-item">
          <span>Institution:</span>
          <strong>Rajagiri College of Social Sciences (Autonomous), Kochi</strong>
        </div>
        <div class="utility-item">
          <span>Portal ID:</span>
          <strong>RASAP-SYS-2026</strong>
        </div>
      </div>
      <div class="utility-right">
        <span class="utility-badge">Official Portal</span>
        <div class="utility-item">
          <span>Contact:</span>
          <strong>rasap@rajagiri.edu</strong>
        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================================
       2. MAIN BRAND HEADER WITH CUSTOM VECTOR LOGO COMPONENT
       ======================================================================== -->
  <header class="main-header">
    <div class="header-container">
      <a href="<?php echo $basePath; ?>index.php" class="brand-wrapper" title="RASAP System Home">
        
        <!-- ==================================================================
             OFFICIAL RASAP VECTOR SVG LOGO COMPONENT
             Features: Curved RASAP navy text arc, graduation cap with gold tassel (#FFB800)
             on left, upward airplane silhouette on right, and center Rajagiri emblem.
             ================================================================== -->
        <svg viewBox="0 0 380 120" xmlns="http://www.w3.org/2000/svg" class="rasap-logo-component" aria-label="RASAP Official Emblem Logo">
          <defs>
            <!-- Top Text Arc Path -->
            <path id="rasap-text-path" d="M 50,88 A 115,115 0 0,1 330,88" fill="none"/>
            <!-- Bottom Text Arc Path for Emblem -->
            <path id="emblem-bottom-path" d="M 152,78 A 34,34 0 0,0 228,78" fill="none"/>
            <!-- Top Inner Arc Path for Emblem -->
            <path id="emblem-top-path" d="M 154,68 A 34,34 0 0,1 226,68" fill="none"/>
          </defs>

          <!-- 1. Center Double Ring Crest Emblem -->
          <g id="center-emblem">
            <!-- Outer Ring -->
            <circle cx="190" cy="72" r="44" fill="#FFFFFF" stroke="#1B365D" stroke-width="3.5"/>
            <!-- Inner Ring -->
            <circle cx="190" cy="72" r="38" fill="none" stroke="#1B365D" stroke-width="1.8"/>
            <circle cx="190" cy="72" r="30" fill="none" stroke="#1B365D" stroke-width="1" stroke-dasharray="3,2"/>
            
            <!-- Inner Crown Top Icon -->
            <path d="M 180,48 L 183,55 L 190,49 L 197,55 L 200,48 L 202,59 L 178,59 Z" fill="#1B365D"/>
            
            <!-- Open Book Center Icon -->
            <path d="M 181,66 Q 190,62 190,69 Q 190,62 199,66 L 199,76 Q 190,72 190,79 Q 190,72 181,76 Z" fill="none" stroke="#1B365D" stroke-width="1.8"/>
            <line x1="190" y1="69" x2="190" y2="79" stroke="#1B365D" stroke-width="1.5"/>

            <!-- Motto Ribbon Arc Text: LEARN SERVE EXCEL -->
            <text font-size="6.5" font-weight="800" fill="#1B365D" letter-spacing="1">
              <textPath href="#emblem-bottom-path" startOffset="50%" text-anchor="middle">LEARN • SERVE • EXCEL</textPath>
            </text>
            <text font-size="7" font-weight="900" fill="#1B365D" letter-spacing="1.5">
              <textPath href="#emblem-top-path" startOffset="50%" text-anchor="middle">RAJAGIRI</textPath>
            </text>
          </g>

          <!-- 2. Left Side: Graduation Cap (Mortarboard) with Gold Tassel (#FFB800) -->
          <g id="graduation-cap" transform="translate(48, 25)">
            <!-- Cap Top Diamond -->
            <polygon points="35,18 65,8 95,18 65,28" fill="#1B365D" stroke="#FFFFFF" stroke-width="2"/>
            <!-- Cap Under Base -->
            <path d="M 48,23 L 48,34 Q 65,42 82,34 L 82,23 Z" fill="#1B365D" stroke="#FFFFFF" stroke-width="1.5"/>
            <!-- Gold Tassel (#FFB800) -->
            <path d="M 65,18 Q 42,22 36,38" fill="none" stroke="#FFB800" stroke-width="3" stroke-linecap="round"/>
            <circle cx="35" cy="40" r="3.5" fill="#FFB800"/>
            <polygon points="31,40 39,40 37,52 33,52" fill="#FFB800"/>
          </g>

          <!-- 3. Right Side: Upward Angled Airplane Silhouette -->
          <g id="airplane-silhouette" transform="translate(295, 20) rotate(15)">
            <!-- Sleek Jet Silhouette -->
            <path d="M 35,5 L 42,22 L 65,18 L 46,28 L 52,48 L 44,44 L 38,32 L 26,35 L 28,42 L 22,44 L 20,36 L 12,38 L 18,29 L 35,5 Z" fill="#1B365D" stroke="#FFFFFF" stroke-width="1.5"/>
          </g>

          <!-- 4. Top Arched Title: "RASAP" in Bold Navy Lettering -->
          <text font-family="'Segoe UI', sans-serif" font-size="34" font-weight="900" fill="#1B365D" letter-spacing="6">
            <textPath href="#rasap-text-path" startOffset="50%" text-anchor="middle">RASAP</textPath>
          </text>
        </svg>

        <div class="brand-text">
          <h1>RASAP</h1>
          <p>Rajagiri Study Abroad Program &bull; Continuous Student Monitoring System</p>
        </div>
      </a>

      <!-- Quick Role Switcher Pill & Logout -->
      <div class="header-actions">
        <div class="role-switcher-container">
          <button class="role-switcher-btn" id="roleSwitcherBtn">
            <span>Role: <strong><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $currentRole))); ?></strong></span>
            <span style="font-size: 0.75rem;">&#9660;</span>
          </button>
          
          <div class="role-dropdown" id="roleDropdownMenu">
            <div class="role-dropdown-header">Switch Active Portal Role</div>
            <a href="<?php echo $basePath; ?>auth/login.php?switch_role=student" class="<?php echo $currentRole === 'student' ? 'active-role' : ''; ?>">🎓 Student Portal</a>
            <a href="<?php echo $basePath; ?>auth/login.php?switch_role=faculty" class="<?php echo $currentRole === 'faculty' ? 'active-role' : ''; ?>">👨‍🏫 Faculty View</a>
            <a href="<?php echo $basePath; ?>auth/login.php?switch_role=faculty_coordinator" class="<?php echo $currentRole === 'faculty_coordinator' ? 'active-role' : ''; ?>">📋 Faculty Coordinator</a>
            <a href="<?php echo $basePath; ?>auth/login.php?switch_role=rasap_coordinator" class="<?php echo $currentRole === 'rasap_coordinator' ? 'active-role' : ''; ?>">🌐 RASAP Coordinator</a>
            <a href="<?php echo $basePath; ?>auth/login.php?switch_role=rasap_faculty" class="<?php echo $currentRole === 'rasap_faculty' ? 'active-role' : ''; ?>">✈️ RASAP Faculty</a>
            <a href="<?php echo $basePath; ?>auth/login.php?switch_role=parent" class="<?php echo $currentRole === 'parent' ? 'active-role' : ''; ?>">👨‍👩‍👦 Parent Portal</a>
          </div>
        </div>

        <a href="<?php echo $basePath; ?>auth/login.php?logout=1" class="btn btn-outline btn-sm">Logout &rarr;</a>
      </div>
    </div>
  </header>
