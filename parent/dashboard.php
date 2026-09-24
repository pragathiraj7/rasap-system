<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Parent Ward Progress Dashboard (parent/dashboard.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * View-Only Ward Progress Dashboard for parents monitoring academic progress,
 * fee clearances, and verified study-abroad documentation.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

// Force parent role for demo
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'parent') {
    $_SESSION['user'] = [
        'id' => 6,
        'name' => 'Varghese K.',
        'role' => 'parent',
        'email' => 'varghese.parent@gmail.com',
        'ward' => 'Alex Varghese',
        'rasap_id' => 'RASAP20260482',
        'batch' => '2024-2027'
    ];
}

$currentUser = getCurrentUser();
$uploadedDocs = getMockDocumentList();

$pageTitle = "Parent Ward Portal - RASAP";
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
  <div class="container">
    <div class="dashboard-wrapper">
      
      <!-- Quick Links Sidebar -->
      <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

      <!-- Main Ward Progress View Shell -->
      <div>
        
        <!-- Header Banner -->
        <div class="table-card mb-3" style="background: linear-gradient(135deg, #1B365D 0%, #2A4D7C 100%); color: var(--white);">
          <div class="flex-between flex-wrap gap-1">
            <div>
              <span class="badge badge-gold mb-1">Parent View-Only Portal</span>
              <h2 style="color: var(--white); font-size: 1.6rem;">Ward Monitoring: Alex Varghese</h2>
              <p style="font-size: 0.88rem; color: rgba(255, 255, 255, 0.85); margin: 0;">
                RASAP Student ID: <strong>RASAP20260482</strong> &bull; Department of Computer Applications (2024-2027)
              </p>
            </div>
            <div>
              <span class="badge badge-approved" style="padding: 0.6rem 1rem; font-size: 0.9rem;">
                ✓ Status: Good Standing
              </span>
            </div>
          </div>
        </div>

        <!-- ====================================================================
             1. WARD ACADEMIC & ATTENDANCE KPI METRICS
             ==================================================================== -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon kpi-icon-navy">🎓</div>
            <div class="kpi-info">
              <h3>3.88 / 4.0</h3>
              <p>Ward's CGPA Score</p>
            </div>
          </div>

          <div class="kpi-card">
            <div class="kpi-icon kpi-icon-green">📊</div>
            <div class="kpi-info">
              <h3>96.2%</h3>
              <p>Overall Attendance</p>
            </div>
          </div>

          <div class="kpi-card">
            <div class="kpi-icon kpi-icon-gold">💳</div>
            <div class="kpi-info">
              <h3>PAID</h3>
              <p>Spring 2026 Fee Clearance</p>
            </div>
          </div>

          <div class="kpi-card">
            <div class="kpi-icon kpi-icon-navy">✈️</div>
            <div class="kpi-info">
              <h3>Band 8.0</h3>
              <p>IELTS Language Clearance</p>
            </div>
          </div>
        </div>

        <!-- ====================================================================
             2. FEE STATUS SUMMARY BANNER (VIEW-ONLY)
             ==================================================================== -->
        <div class="fee-status-banner">
          <div class="fee-info">
            <h3>💳 Ward Fee Status &amp; Financial Clearances</h3>
            <p>
              Current Academic Session: <strong>Spring 2026 (Semester 4)</strong>.<br>
              Tuition Fee: <strong>PAID IN FULL</strong> &bull; RASAP Documentation Deposit: <strong>CLEARED</strong>.
            </p>
          </div>
          <div>
            <span class="fee-badge-pill" style="background-color: var(--white); color: var(--primary-navy);">
              ✓ Verified Paid
            </span>
          </div>
        </div>

        <!-- ====================================================================
             3. WARD VERIFIED E-DOCETS & PROGRESS TABLE
             ==================================================================== -->
        <div id="ward-documents" class="table-card mb-3">
          <div class="table-header">
            <div>
              <h3 style="color: var(--primary-navy); font-size: 1.25rem;">📁 Ward Verified E-Docets &amp; Certificates</h3>
              <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">View-only record of documents approved by Rajagiri faculty coordinators.</p>
            </div>
            <span class="badge badge-navy">View-Only Access</span>
          </div>

          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Doc ID</th>
                  <th>Document Title</th>
                  <th>Category</th>
                  <th>Uploaded Date</th>
                  <th>Faculty Status</th>
                  <th>Official Remarks</th>
                  <th>Proof View</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($uploadedDocs as $doc): ?>
                  <tr>
                    <td><strong>#DOC-<?php echo $doc['edocet_id']; ?></strong></td>
                    <td>
                      <strong style="color: var(--primary-navy);"><?php echo htmlspecialchars($doc['title']); ?></strong>
                    </td>
                    <td><span class="badge badge-navy"><?php echo htmlspecialchars($doc['category']); ?></span></td>
                    <td><?php echo date('d M Y', strtotime($doc['uploaded_at'])); ?></td>
                    <td>
                      <?php
                        $st = strtolower($doc['status']);
                        if ($st === 'approved') echo '<span class="badge badge-approved">✓ Approved</span>';
                        elseif ($st === 'pending') echo '<span class="badge badge-pending">⏳ Pending Review</span>';
                        elseif ($st === 'returned') echo '<span class="badge badge-returned">↩ Returned</span>';
                        else echo '<span class="badge badge-rejected">✕ Rejected</span>';
                      ?>
                    </td>
                    <td style="font-size: 0.85rem;">
                      <?php echo !empty($doc['remarks']) ? htmlspecialchars($doc['remarks']) : '<span class="text-muted">Verified</span>'; ?>
                    </td>
                    <td>
                      <a href="../<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="btn btn-outline btn-sm">View File</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- ====================================================================
             4. MENTORING LOGS & FACULTY FEEDBACK
             ==================================================================== -->
        <div id="academic-timeline" class="table-card">
          <div class="table-header">
            <div>
              <h3 style="color: var(--primary-navy); font-size: 1.25rem;">🤝 Faculty Advisor Mentoring Logs</h3>
              <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">Regular progress reviews conducted by Dr. Thomas Paul (Faculty Mentor).</p>
            </div>
          </div>

          <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="background-color: #F8FAFC; border-left: 4px solid var(--primary-navy); border-radius: var(--radius-md); padding: 1rem;">
              <div class="flex-between mb-1">
                <strong style="color: var(--primary-navy);">Semester 4 Mid-Term Mentoring Review</strong>
                <span class="text-muted" style="font-size: 0.8rem;">March 10, 2026</span>
              </div>
              <p style="font-size: 0.88rem; margin: 0;">
                "Alex is demonstrating excellent academic consistency (CGPA 3.88). IELTS preparation target achieved (Band 8.0). Approved for Infosys internship credit submission."
              </p>
            </div>

            <div style="background-color: #F8FAFC; border-left: 4px solid var(--slate-blue); border-radius: var(--radius-md); padding: 1rem;">
              <div class="flex-between mb-1">
                <strong style="color: var(--primary-navy);">Semester 3 Final Assessment Review</strong>
                <span class="text-muted" style="font-size: 0.8rem;">December 18, 2025</span>
              </div>
              <p style="font-size: 0.88rem; margin: 0;">
                "Completed all semester 3 coursework with distinctions. Attendance maintained above 95%. Passport document uploaded for partner university pre-screening."
              </p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
