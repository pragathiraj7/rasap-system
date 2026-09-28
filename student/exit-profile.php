<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * 3rd-Year Consolidated Exit-Interview Profile Generator (student/exit-profile.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Directly satisfies Proposal Sections 3, 5.5, and 7:
 * "Automatic generation of the consolidated exit-interview profile in a fixed format
 * serving as the documentary basis for the third-year exit interview."
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

$currentUser = getCurrentUser();
$docs = getMockDocumentList();
$activities = getMockActivitiesList();
$mentoring = getMockMentoringLogs();

$pageTitle = "3rd-Year Consolidated Exit Profile Report - RASAP";
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
  <div class="container">
    
    <!-- Action Bar for Printing / PDF Export -->
    <div class="flex-between mb-3" style="background-color: var(--white); padding: 1rem 1.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
      <div>
        <span class="badge badge-navy">Official Exit Record</span>
        <h3 style="color: var(--primary-navy); margin: 0;">3rd-Year Exit-Interview Profile Document</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">Consolidated three-year continuous monitoring record generated for international credit transfer &amp; exit interview.</p>
      </div>
      <div style="display: flex; gap: 0.75rem;">
        <button onclick="window.print();" class="btn btn-secondary">
          🖨️ Print / Save as PDF
        </button>
        <a href="dashboard.php" class="btn btn-outline">&larr; Back to Dashboard</a>
      </div>
    </div>

    <!-- ====================================================================
         CONSOLIDATED EXIT PROFILE REPORT CONTAINER (PRINTABLE FORMAT)
         ==================================================================== -->
    <div id="printableExitReport" style="background-color: var(--white); border: 2px solid var(--primary-navy); border-radius: var(--radius-lg); padding: 2.5rem; box-shadow: var(--shadow-md);">
      
      <!-- Institutional Letterhead Header -->
      <div style="border-bottom: 3px double var(--primary-navy); padding-bottom: 1.25rem; margin-bottom: 1.75rem; text-align: center;">
        <h2 style="font-size: 1.6rem; color: var(--primary-navy); font-weight: 800; letter-spacing: 0.5px;">
          RAJAGIRI COLLEGE OF SOCIAL SCIENCES (AUTONOMOUS), KOCHI
        </h2>
        <p style="font-size: 0.95rem; font-weight: 700; color: var(--slate-blue); margin-bottom: 0.25rem;">
          Rajagiri Accelerated Study Abroad Programme (RASAP)
        </p>
        <div style="display: inline-block; background-color: var(--primary-navy); color: var(--gold-accent); padding: 0.35rem 1.25rem; border-radius: 50px; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; margin-top: 0.5rem;">
          Consolidated 3-Year Exit-Interview Profile
        </div>
      </div>

      <!-- Section 1: Student Profile & Academic Background -->
      <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--primary-navy); border-bottom: 2px solid var(--gold-accent); padding-bottom: 0.35rem; font-size: 1.15rem; margin-bottom: 1rem;">
          1. Student Identity &amp; Programme Details
        </h3>
        
        <table class="data-table" style="border: 1px solid var(--border-color);">
          <tbody>
            <tr>
              <td style="width: 25%; font-weight: 700; background-color: #F8FAFC;">Student Full Name:</td>
              <td style="width: 25%;"><?php echo htmlspecialchars($currentUser['name']); ?></td>
              <td style="width: 25%; font-weight: 700; background-color: #F8FAFC;">RASAP Registration ID:</td>
              <td style="width: 25%;"><strong><?php echo htmlspecialchars($currentUser['rasap_id'] ?? 'RASAP20260482'); ?></strong></td>
            </tr>
            <tr>
              <td style="font-weight: 700; background-color: #F8FAFC;">Undergraduate Department:</td>
              <td><?php echo htmlspecialchars($currentUser['department'] ?? 'Computer Applications'); ?></td>
              <td style="font-weight: 700; background-color: #F8FAFC;">Academic Batch Session:</td>
              <td><?php echo htmlspecialchars($currentUser['batch'] ?? '2024-2027'); ?></td>
            </tr>
            <tr>
              <td style="font-weight: 700; background-color: #F8FAFC;">Cumulative CGPA:</td>
              <td><strong style="color: #065F46;">3.88 / 4.00</strong></td>
              <td style="font-weight: 700; background-color: #F8FAFC;">Overall Attendance:</td>
              <td><strong style="color: #065F46;">96.20%</strong></td>
            </tr>
            <tr>
              <td style="font-weight: 700; background-color: #F8FAFC;">Standardized Test Clearance:</td>
              <td colspan="3"><span class="badge badge-approved">IELTS Academic - Band 8.0</span> (Verified TRF #2026-IELTS-80)</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Section 2: 3-Year Academic Progression Summary -->
      <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--primary-navy); border-bottom: 2px solid var(--gold-accent); padding-bottom: 0.35rem; font-size: 1.15rem; margin-bottom: 1rem;">
          2. Academic Performance Track (Semesters 1 to 6)
        </h3>
        
        <table class="data-table" style="border: 1px solid var(--border-color);">
          <thead>
            <tr>
              <th>Semester</th>
              <th>Course Credits</th>
              <th>SGPA</th>
              <th>Attendance</th>
              <th>Status / Distinction</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Semester 1 (Year 1)</td>
              <td>22 Credits</td>
              <td>3.85 / 4.00</td>
              <td>97.0%</td>
              <td><span class="badge badge-approved">Passed with Distinction</span></td>
            </tr>
            <tr>
              <td>Semester 2 (Year 1)</td>
              <td>24 Credits</td>
              <td>3.90 / 4.00</td>
              <td>96.5%</td>
              <td><span class="badge badge-approved">Passed with Distinction</span></td>
            </tr>
            <tr>
              <td>Semester 3 (Year 2)</td>
              <td>22 Credits</td>
              <td>3.92 / 4.00</td>
              <td>95.8%</td>
              <td><span class="badge badge-approved">Passed with Distinction</span></td>
            </tr>
            <tr>
              <td>Semester 4 (Year 2)</td>
              <td>24 Credits</td>
              <td>3.85 / 4.00</td>
              <td>95.5%</td>
              <td><span class="badge badge-pending">Current Active Evaluation</span></td>
            </tr>
            <tr>
              <td>Semester 5 &amp; 6 (Year 3)</td>
              <td>International Transfer</td>
              <td>Target: 3.90</td>
              <td>100%</td>
              <td><span class="badge badge-navy">Partner Institution Mapping</span></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Section 3: Verified Co-Curricular & Extra-Curricular Profile -->
      <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--primary-navy); border-bottom: 2px solid var(--gold-accent); padding-bottom: 0.35rem; font-size: 1.15rem; margin-bottom: 1rem;">
          3. Co-Curricular &amp; Extra-Curricular Achievements
        </h3>

        <table class="data-table" style="border: 1px solid var(--border-color);">
          <thead>
            <tr>
              <th>Domain</th>
              <th>Activity Title</th>
              <th>Category</th>
              <th>Role Played</th>
              <th>Verification Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($activities as $act): ?>
              <tr>
                <td><span class="badge badge-navy"><?php echo htmlspecialchars($act['domain']); ?></span></td>
                <td><strong><?php echo htmlspecialchars($act['title']); ?></strong></td>
                <td><?php echo htmlspecialchars($act['category']); ?></td>
                <td><?php echo htmlspecialchars($act['role_played']); ?></td>
                <td><span class="badge badge-approved">✓ Verified</span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Section 4: Faculty Mentoring Record & Exit Observations -->
      <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--primary-navy); border-bottom: 2px solid var(--gold-accent); padding-bottom: 0.35rem; font-size: 1.15rem; margin-bottom: 1rem;">
          4. Faculty Mentoring &amp; Readiness Assessment
        </h3>

        <div style="background-color: #F8FAFC; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.25rem;">
          <?php foreach ($mentoring as $log): ?>
            <div style="margin-bottom: 1rem; border-bottom: 1px dashed var(--border-color); padding-bottom: 0.75rem;">
              <div class="flex-between mb-1">
                <strong>Mentor Observation (Date: <?php echo date('d M Y', strtotime($log['session_date'])); ?>)</strong>
                <span style="font-size: 0.85rem; color: var(--text-muted);">Mentor: <?php echo htmlspecialchars($log['mentor_name']); ?></span>
              </div>
              <p style="font-size: 0.88rem; color: var(--text-dark); font-style: italic; margin-bottom: 0.35rem;">
                "<?php echo htmlspecialchars($log['observations']); ?>"
              </p>
              <div style="font-size: 0.82rem; color: var(--primary-navy); font-weight: 600;">
                Follow-up Action: <?php echo htmlspecialchars($log['action_items']); ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Section 5: Exit Interview Panel Sign-off & Clearances -->
      <div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 2px solid var(--border-color);">
        <div class="grid grid-cols-3" style="gap: 2rem; text-align: center;">
          <div>
            <div style="min-height: 50px; border-bottom: 1px solid var(--text-dark); margin-bottom: 0.5rem;">
              <span style="font-size: 0.8rem; font-style: italic; color: var(--text-muted);">Signed digitally by Coordinator</span>
            </div>
            <strong>Prof. Mary Joseph</strong>
            <div style="font-size: 0.78rem; color: var(--text-muted);">Faculty Coordinator, RASAP</div>
          </div>

          <div>
            <div style="min-height: 50px; border-bottom: 1px solid var(--text-dark); margin-bottom: 0.5rem;">
              <span style="font-size: 0.8rem; font-style: italic; color: var(--text-muted);">Signed digitally by Mentor</span>
            </div>
            <strong>Dr. Thomas Paul</strong>
            <div style="font-size: 0.78rem; color: var(--text-muted);">Faculty Advisor / Mentor</div>
          </div>

          <div>
            <div style="min-height: 50px; border-bottom: 1px solid var(--text-dark); margin-bottom: 0.5rem;">
              <span style="font-size: 0.8rem; font-style: italic; color: var(--text-muted);">Institutional Seal</span>
            </div>
            <strong>Dr. Mathew Kurian</strong>
            <div style="font-size: 0.78rem; color: var(--text-muted);">Director, RASAP International Office</div>
          </div>
        </div>
      </div>

    </div>

  </div>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
