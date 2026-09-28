<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Faculty Mentoring Maintenance Module (coordinator/mentoring.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Directly satisfies Proposal Section 5.2 & 5.3:
 * "Entry and maintenance of mentoring records, periodic mentor meeting records,
 * observations, agreed action points, and shareable flag for parental visibility."
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

$currentUser = getCurrentUser();
$successMessage = '';
$errorMessage = '';

// Handle POST Mentoring Entry
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentName = trim($_POST['student_name'] ?? 'Alex Varghese');
    $sessionDate = trim($_POST['session_date'] ?? date('Y-m-d'));
    $observations = trim($_POST['observations'] ?? '');
    $actionItems = trim($_POST['action_items'] ?? '');
    $isShareable = isset($_POST['is_shareable']) ? 1 : 0;

    if (empty($observations)) {
        $errorMessage = "Please enter mentor observations before submitting.";
    } else {
        $newLog = [
            'log_id' => rand(400, 999),
            'student_name' => htmlspecialchars($studentName),
            'rasap_id' => 'RASAP20260482',
            'mentor_name' => $currentUser['name'] ?? 'Dr. Thomas Paul',
            'session_date' => htmlspecialchars($sessionDate),
            'observations' => htmlspecialchars($observations),
            'action_items' => htmlspecialchars($actionItems),
            'followup_status' => 'Pending',
            'is_shareable' => $isShareable
        ];

        if (!isset($_SESSION['mock_mentoring'])) {
            getMockMentoringLogs();
        }
        array_unshift($_SESSION['mock_mentoring'], $newLog);

        // Save to PDO DB if connected
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO mentoring_logs (student_id, faculty_id, session_date, observations, action_items, is_shareable) VALUES (1, 4, :d, :o, :a, :s)");
                $stmt->execute(['d' => $sessionDate, 'o' => $observations, 'a' => $actionItems, 's' => $isShareable]);
            } catch (Exception $e) {
                // Handled via session store
            }
        }

        $successMessage = "Mentoring session record saved successfully!";
    }
}

$mentoringLogs = getMockMentoringLogs();

$pageTitle = "Mentoring Maintenance Module - RASAP";
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
  <div class="container">
    <div class="dashboard-wrapper">
      
      <!-- Quick Links Sidebar -->
      <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

      <!-- Main Mentoring Content Shell -->
      <div>
        
        <?php if (!empty($successMessage)): ?>
          <div class="alert alert-success">✓ <?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
          <div class="alert alert-danger">⚠️ <?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Form Card for Logging Mentoring Sessions -->
        <div class="form-card mb-3">
          <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem;">
            <span class="badge badge-navy mb-1">Proposal Section 5.2 Module</span>
            <h2 style="color: var(--primary-navy); font-size: 1.5rem;">Log Periodic Mentoring Session</h2>
            <p style="font-size: 0.88rem; color: var(--text-muted); margin: 0;">Record periodic observations, agreed action points, and parental visibility permissions.</p>
          </div>

          <form action="mentoring.php" method="POST">
            
            <div class="grid grid-cols-2" style="gap: 1.5rem;">
              <div class="form-group">
                <label for="studentSelect">Select RASAP Student <span style="color: red;">*</span></label>
                <select name="student_name" id="studentSelect" class="form-control" required>
                  <option value="Alex Varghese">Alex Varghese (RASAP20260482 - BCA)</option>
                  <option value="Riya Mariam">Riya Mariam (RASAP20260490 - BBA)</option>
                  <option value="Kevin Jacob">Kevin Jacob (RASAP20260512 - BSW)</option>
                </select>
              </div>

              <div class="form-group">
                <label for="sessionDate">Session Date <span style="color: red;">*</span></label>
                <input type="date" name="session_date" id="sessionDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="obsInput">Mentor Observations &amp; Academic Guidance <span style="color: red;">*</span></label>
              <textarea name="observations" id="obsInput" class="form-control" rows="3" placeholder="Enter session observations regarding academic progress, study abroad readiness, language tests..." required></textarea>
            </div>

            <div class="form-group">
              <label for="actionInput">Agreed Action Points &amp; Follow-up Items</label>
              <textarea name="action_items" id="actionInput" class="form-control" rows="2" placeholder="Specify follow-up actions agreed upon during the mentoring session..."></textarea>
            </div>

            <div class="form-group" style="background-color: #F8FAFC; padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
              <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: var(--primary-navy); font-weight: 700;">
                <input type="checkbox" name="is_shareable" value="1" checked style="width: 18px; height: 18px;">
                <span>Share Summary with Parent (Proposal Section 5.3)</span>
              </label>
              <small style="color: var(--text-muted); display: block; margin-top: 0.35rem;">
                When checked, verified summary observations will be visible in the Parent Ward Dashboard.
              </small>
            </div>

            <div style="text-align: right;">
              <button type="submit" class="btn btn-primary btn-lg">Save Mentoring Record &rarr;</button>
            </div>

          </form>
        </div>

        <!-- Mentoring History Log Table -->
        <div class="table-card">
          <div class="table-header">
            <div>
              <h3 style="color: var(--primary-navy); font-size: 1.25rem;">🤝 Recorded Mentoring History</h3>
              <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">Continuous tracking of all past mentor meetings and follow-up status.</p>
            </div>
          </div>

          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Session Date</th>
                  <th>Student Name</th>
                  <th>Faculty Mentor</th>
                  <th>Mentor Observations</th>
                  <th>Agreed Action Points</th>
                  <th>Parent Visibility</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($mentoringLogs as $log): ?>
                  <tr>
                    <td><strong><?php echo date('d M Y', strtotime($log['session_date'])); ?></strong></td>
                    <td><strong style="color: var(--primary-navy);"><?php echo htmlspecialchars($log['student_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($log['mentor_name']); ?></td>
                    <td style="font-size: 0.88rem;"><?php echo htmlspecialchars($log['observations']); ?></td>
                    <td style="font-size: 0.85rem; color: var(--slate-blue);"><?php echo htmlspecialchars($log['action_items']); ?></td>
                    <td>
                      <?php if (!empty($log['is_shareable'])): ?>
                        <span class="badge badge-approved">✓ Parent Visible</span>
                      <?php else: ?>
                        <span class="badge badge-navy">🔒 Private Note</span>
                      <?php endif; ?>
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
