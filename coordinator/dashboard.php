<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Coordinator Analytics Dashboard (coordinator/dashboard.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Features:
 * 1. Department-wise student counts & analytics bar charts (Chart.js).
 * 2. Total student counts, approved count, pending review alerts KPI cards.
 * 3. Pending review alerts list with document review workflow shortcut.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

// Force coordinator role for demo
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['faculty_coordinator', 'rasap_coordinator', 'faculty', 'rasap_faculty'])) {
    $_SESSION['user'] = [
        'id' => 2,
        'name' => 'Prof. Mary Joseph',
        'role' => 'faculty_coordinator',
        'email' => 'mary.joseph@rajagiri.edu',
        'department' => 'Computer Applications',
        'batch' => '2024-2027'
    ];
}

$currentUser = getCurrentUser();
$uploadedDocs = getMockDocumentList();

// Calculate Analytics Counts
$totalStudents = 170;
$totalDocs = count($uploadedDocs);
$pendingDocs = array_filter($uploadedDocs, fn($d) => strtolower($d['status']) === 'pending');
$pendingCount = count($pendingDocs);
$approvedCount = count(array_filter($uploadedDocs, fn($d) => strtolower($d['status']) === 'approved'));

$pageTitle = "Coordinator Analytics Dashboard - RASAP";
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
  <div class="container">
    <div class="dashboard-wrapper">
      
      <!-- Quick Links Sidebar -->
      <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

      <!-- Main Analytics Shell -->
      <div>
        
        <!-- Header Banner -->
        <div class="flex-between flex-wrap gap-1 mb-3">
          <div>
            <span class="badge badge-navy mb-1">Administrative Overview</span>
            <h2 style="font-size: 1.75rem; color: var(--primary-navy);">Department Analytics &amp; Student Monitoring</h2>
            <p style="font-size: 0.88rem; color: var(--text-muted); margin: 0;">
              Log-in Role: <strong><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $currentUser['role']))); ?></strong> &bull; 
              Department of <?php echo htmlspecialchars($currentUser['department'] ?? 'Computer Applications'); ?>
            </p>
          </div>
          <div>
            <a href="document-review.php" class="btn btn-secondary">
              <span>📋 Open Review Queue (<?php echo $pendingCount; ?> Pending)</span>
            </a>
          </div>
        </div>

        <!-- ====================================================================
             1. KPI NUMERIC SUMMARY CARDS
             ==================================================================== -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon kpi-icon-navy">🎓</div>
            <div class="kpi-info">
              <h3><?php echo $totalStudents; ?></h3>
              <p>Total RASAP Students</p>
            </div>
          </div>

          <div class="kpi-card">
            <div class="kpi-icon kpi-icon-gold">📁</div>
            <div class="kpi-info">
              <h3><?php echo $totalDocs + 140; ?></h3>
              <p>Total E-Docets Submitted</p>
            </div>
          </div>

          <div class="kpi-card">
            <div class="kpi-icon kpi-icon-green">✓</div>
            <div class="kpi-info">
              <h3>142</h3>
              <p>Documents Approved</p>
            </div>
          </div>

          <div class="kpi-card">
            <div class="kpi-icon kpi-icon-red">🔔</div>
            <div class="kpi-info">
              <h3><?php echo $pendingCount; ?></h3>
              <p>Pending Review Alerts</p>
            </div>
          </div>
        </div>

        <!-- ====================================================================
             2. CHART.JS ANALYTICS VISUALIZATION SECTION
             ==================================================================== -->
        <div class="grid grid-cols-2 mb-3">
          
          <!-- Chart 1: Department-Wise RASAP Student Distribution (Bar Chart) -->
          <div class="table-card">
            <div class="table-header">
              <div>
                <h3 style="font-size: 1.15rem; color: var(--primary-navy);">📊 RASAP Enrolled Students by Department</h3>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">Breakdown across 5 active Rajagiri departments.</p>
              </div>
            </div>
            <div style="height: 280px; position: relative;">
              <canvas id="deptStudentChart" 
                      data-labels='["Computer Applications", "Social Work", "Business Admin", "Commerce", "Psychology"]'
                      data-counts='[45, 32, 28, 40, 25]'>
              </canvas>
            </div>
          </div>

          <!-- Chart 2: E-Docet Verification Status (Doughnut Chart) -->
          <div class="table-card">
            <div class="table-header">
              <div>
                <h3 style="font-size: 1.15rem; color: var(--primary-navy);">🍩 Document Verification Status</h3>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">Overall status distribution of submitted certificates.</p>
              </div>
            </div>
            <div style="height: 280px; position: relative;">
              <canvas id="docStatusChart" 
                      data-approved="142" 
                      data-pending="<?php echo $pendingCount; ?>" 
                      data-returned="8" 
                      data-rejected="5">
              </canvas>
            </div>
          </div>

        </div>

        <!-- ====================================================================
             3. PENDING REVIEW ALERTS SECTION
             ==================================================================== -->
        <div id="pending-alerts" class="table-card">
          <div class="table-header">
            <div>
              <h3 style="color: var(--primary-navy); font-size: 1.2rem;">🔔 Urgent Pending E-Docet Review Alerts</h3>
              <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">Documents requiring immediate action by the faculty coordinator.</p>
            </div>
            <a href="document-review.php" class="btn btn-outline btn-sm">Manage All Queue &rarr;</a>
          </div>

          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Student Name</th>
                  <th>RASAP ID</th>
                  <th>Department</th>
                  <th>Document Title</th>
                  <th>Category</th>
                  <th>Uploaded Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($pendingDocs) > 0): ?>
                  <?php foreach ($pendingDocs as $doc): ?>
                    <tr>
                      <td><strong><?php echo htmlspecialchars($doc['student_name']); ?></strong></td>
                      <td><span class="badge badge-navy"><?php echo htmlspecialchars($doc['rasap_id']); ?></span></td>
                      <td><?php echo htmlspecialchars($doc['department']); ?></td>
                      <td><strong style="color: var(--primary-navy);"><?php echo htmlspecialchars($doc['title']); ?></strong></td>
                      <td><?php echo htmlspecialchars($doc['category']); ?></td>
                      <td><?php echo date('d M Y, H:i', strtotime($doc['uploaded_at'])); ?></td>
                      <td>
                        <a href="document-review.php?review_id=<?php echo $doc['edocet_id']; ?>" class="btn btn-primary btn-sm">
                          Review Document &rarr;
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="7" class="text-center text-muted" style="padding: 2rem;">
                      ✓ All document review alerts have been processed!
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
