<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Student Dashboard (student/dashboard.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Features:
 * 1. 3-Year Student Profile Overview (Year 1, Year 2, Year 3).
 * 2. Fee Status Banner (Paid / Pending semester status).
 * 3. Quick Upload Shortcuts and Recent E-Docet Uploads Table.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

// Force student role for demo if needed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    $_SESSION['user'] = [
        'id' => 1,
        'name' => 'Alex Varghese',
        'role' => 'student',
        'email' => 'alex.varghese@rajagiri.edu',
        'rasap_id' => 'RASAP20260482',
        'department' => 'Computer Applications',
        'batch' => '2024-2027',
        'year' => 2
    ];
}

$currentUser = getCurrentUser();
$uploadedDocs = getMockDocumentList();

$pageTitle = "Student Dashboard - RASAP";
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
  <div class="container">
    
    <div class="dashboard-wrapper">
      
      <!-- Left Sidebar Quick Links Panel -->
      <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

      <!-- Right Main Dashboard Content Shell -->
      <div>
        
        <!-- ====================================================================
             1. STUDENT PROFILE SUMMARY HEADER CARD
             ==================================================================== -->
        <div class="table-card mb-3" style="background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%);">
          <div class="flex-between flex-wrap gap-1" style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.25rem;">
            <div>
              <span class="badge badge-navy mb-1">Active RASAP Candidate</span>
              <h2 style="font-size: 1.75rem; color: var(--primary-navy);"><?php echo htmlspecialchars($currentUser['name']); ?></h2>
              <p style="font-size: 0.9rem; color: var(--text-muted); margin: 0;">
                RASAP ID: <strong><?php echo htmlspecialchars($currentUser['rasap_id'] ?? 'RASAP20260482'); ?></strong> &bull; 
                Department of <?php echo htmlspecialchars($currentUser['department'] ?? 'Computer Applications'); ?>
              </p>
            </div>
            <div class="text-right">
              <a href="edocet-upload.php" class="btn btn-primary">
                <span>📤 Upload E-Docet</span>
              </a>
            </div>
          </div>

          <!-- KPI Metric Highlights -->
          <div class="kpi-grid" style="margin-bottom: 0;">
            <div class="kpi-card">
              <div class="kpi-icon kpi-icon-navy">🎓</div>
              <div class="kpi-info">
                <h3>3.88 / 4.0</h3>
                <p>Cumulative CGPA</p>
              </div>
            </div>

            <div class="kpi-card">
              <div class="kpi-icon kpi-icon-gold">✈️</div>
              <div class="kpi-info">
                <h3>Band 8.0</h3>
                <p>IELTS Scorecard</p>
              </div>
            </div>

            <div class="kpi-card">
              <div class="kpi-icon kpi-icon-green">📁</div>
              <div class="kpi-info">
                <h3>5 / 6</h3>
                <p>E-Docets Verified</p>
              </div>
            </div>

            <div class="kpi-card">
              <div class="kpi-icon kpi-icon-red">🔔</div>
              <div class="kpi-info">
                <h3>96.2%</h3>
                <p>Overall Attendance</p>
              </div>
            </div>
          </div>
        </div>

        <!-- ====================================================================
             2. FEE STATUS BANNER (CRITICAL REQUIREMENT)
             ==================================================================== -->
        <div id="fee-status" class="fee-status-banner">
          <div class="fee-info">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
              <span style="font-size: 1.4rem;">💳</span>
              <h3>RASAP Institutional Fee Status</h3>
            </div>
            <p>
              Semester Fee Status: <strong style="color: var(--gold-accent);">PAID &amp; CLEARED</strong> (Spring 2026 Academic Session).<br>
              Next Installment Due: August 15, 2026 (Fall Semester Abroad Deposit).
            </p>
          </div>
          <div>
            <span class="fee-badge-pill">
              ✓ Fee Status: CLEAR
            </span>
          </div>
        </div>

        <!-- ====================================================================
             3. 3-YEAR STUDENT PROFILE OVERVIEW (CRITICAL REQUIREMENT)
             ==================================================================== -->
        <div id="academic-profile" class="table-card mb-3">
          <div class="table-header">
            <div>
              <h3 style="color: var(--primary-navy); font-size: 1.25rem;">📅 3-Year RASAP Academic &amp; Documentation Roadmap</h3>
              <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">Continuous tracking across all 6 semesters of the study abroad program.</p>
            </div>
            <span class="badge badge-gold">Current: Year 2 (Sem 4)</span>
          </div>

          <div class="grid grid-cols-3" style="gap: 1.25rem;">
            
            <!-- Year 1 Box -->
            <div style="background-color: #F8FAFC; border: 1px solid #A7F3D0; border-top: 4px solid var(--color-success); border-radius: var(--radius-md); padding: 1.25rem;">
              <div class="flex-between mb-2">
                <h4 style="font-size: 1.05rem; color: var(--primary-navy);">Year 1 (Sem 1 &amp; 2)</h4>
                <span class="badge badge-approved">Completed</span>
              </div>
              <ul style="list-style: none; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.5rem; color: var(--text-dark);">
                <li>✓ Foundation Modules Completed</li>
                <li>✓ Sem 1 &amp; 2 Marklists Uploaded</li>
                <li>✓ English Proficiency Enrollment</li>
                <li>✓ Mentoring Log Signed</li>
              </ul>
              <div style="margin-top: 1rem; font-size: 0.8rem; font-weight: 700; color: #065F46;">
                Verification: 100% Complete
              </div>
            </div>

            <!-- Year 2 Box (Active) -->
            <div style="background-color: #FFFDF0; border: 2px solid var(--gold-accent); border-top: 4px solid var(--gold-accent); border-radius: var(--radius-md); padding: 1.25rem; box-shadow: var(--shadow-sm);">
              <div class="flex-between mb-2">
                <h4 style="font-size: 1.05rem; color: var(--primary-navy);">Year 2 (Sem 3 &amp; 4)</h4>
                <span class="badge badge-pending">Active Session</span>
              </div>
              <ul style="list-style: none; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.5rem; color: var(--text-dark);">
                <li>✓ IELTS Band 8.0 Submitted</li>
                <li>⏳ Infosys Internship Review</li>
                <li>✓ Passport Verification</li>
                <li>⏳ Partner University Choice</li>
              </ul>
              <div style="margin-top: 1rem; font-size: 0.8rem; font-weight: 700; color: #B45309;">
                Verification: 80% In Progress
              </div>
            </div>

            <!-- Year 3 Box (Upcoming) -->
            <div style="background-color: #F8FAFC; border: 1px solid var(--border-color); border-top: 4px solid var(--slate-blue); border-radius: var(--radius-md); padding: 1.25rem; opacity: 0.85;">
              <div class="flex-between mb-2">
                <h4 style="font-size: 1.05rem; color: var(--primary-navy);">Year 3 (Sem 5 &amp; 6)</h4>
                <span class="badge badge-navy">Upcoming</span>
              </div>
              <ul style="list-style: none; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.5rem; color: var(--text-muted);">
                <li>&bull; International Campus Travel</li>
                <li>&bull; Credit Transfer Documentation</li>
                <li>&bull; Global Capstone Thesis</li>
                <li>&bull; Degree Clearance</li>
              </ul>
              <div style="margin-top: 1rem; font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">
                Scheduled for 2026–2027
              </div>
            </div>

          </div>
        </div>

        <!-- ====================================================================
             4. UPLOADED E-DOCETS & DOCUMENT STATUS TABLE
             ==================================================================== -->
        <div class="table-card">
          <div class="table-header">
            <div>
              <h3 style="color: var(--primary-navy); font-size: 1.25rem;">📁 Uploaded E-Docet Certificates &amp; Proofs</h3>
              <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">Track approval status and coordinator remarks for all uploaded documents.</p>
            </div>
            <a href="edocet-upload.php" class="btn btn-secondary btn-sm">+ Upload New E-Docet</a>
          </div>

          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Doc ID</th>
                  <th>Document Title</th>
                  <th>Category</th>
                  <th>Uploaded Date</th>
                  <th>Status</th>
                  <th>Coordinator Remarks</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($uploadedDocs as $doc): ?>
                  <tr>
                    <td><strong>#DOC-<?php echo $doc['edocet_id']; ?></strong></td>
                    <td>
                      <strong style="color: var(--primary-navy);"><?php echo htmlspecialchars($doc['title']); ?></strong>
                      <div style="font-size: 0.78rem; color: var(--text-muted);"><?php echo htmlspecialchars($doc['description']); ?></div>
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
                      <?php echo !empty($doc['remarks']) ? htmlspecialchars($doc['remarks']) : '<span class="text-muted">No remarks yet</span>'; ?>
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

      </div>
    </div>
  </div>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
