<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Categorized Activity Submission Form (student/activity-submit.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Directly satisfies Proposal Sections 4 & 5.1:
 * "Activity submission form recording title, category (Co-curricular / Extra-curricular),
 * date, role, and supporting evidence."
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

$currentUser = getCurrentUser();
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $domain = trim($_POST['domain'] ?? 'Co-curricular');
    $category = trim($_POST['category'] ?? 'Conference / Seminar');
    $activityDate = trim($_POST['activity_date'] ?? date('Y-m-d'));
    $rolePlayed = trim($_POST['role_played'] ?? 'Participant');
    $description = trim($_POST['description'] ?? '');

    if (empty($title) || empty($category)) {
        $errorMessage = "Please enter both Activity Title and Category.";
    } else {
        $newAct = [
            'activity_id' => rand(300, 899),
            'student_name' => $currentUser['name'] ?? 'Alex Varghese',
            'rasap_id' => $currentUser['rasap_id'] ?? 'RASAP20260482',
            'title' => htmlspecialchars($title),
            'domain' => htmlspecialchars($domain),
            'category' => htmlspecialchars($category),
            'activity_date' => htmlspecialchars($activityDate),
            'role_played' => htmlspecialchars($rolePlayed),
            'status' => 'Pending',
            'remarks' => ''
        ];

        if (!isset($_SESSION['mock_activities'])) {
            getMockActivitiesList();
        }
        array_unshift($_SESSION['mock_activities'], $newAct);

        $successMessage = "Activity submission logged successfully! Queued for faculty coordinator verification.";
    }
}

$activities = getMockActivitiesList();

$pageTitle = "Activity Submission Form - RASAP";
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
  <div class="container">
    <div class="dashboard-wrapper">
      
      <!-- Quick Links Sidebar -->
      <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

      <div>
        
        <?php if (!empty($successMessage)): ?>
          <div class="alert alert-success">✓ <?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
          <div class="alert alert-danger">⚠️ <?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Activity Submission Form -->
        <div class="form-card mb-3">
          <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
              <span class="badge badge-navy mb-1">Proposal Section 5.1 Form</span>
              <h2 style="color: var(--primary-navy); font-size: 1.5rem;">Submit Co-Curricular / Extra-Curricular Activity</h2>
              <p style="font-size: 0.88rem; color: var(--text-muted); margin: 0;">Record seminars, workshops, hackathons, volunteering (Whole Person Education), or leadership positions.</p>
            </div>
            <a href="dashboard.php" class="btn btn-outline btn-sm">&larr; Dashboard</a>
          </div>

          <form action="activity-submit.php" method="POST" enctype="multipart/form-data">
            
            <div class="grid grid-cols-2" style="gap: 1.5rem;">
              <div class="form-group">
                <label for="actTitle">Activity / Event Title <span style="color: red;">*</span></label>
                <input type="text" name="title" id="actTitle" class="form-control" placeholder="e.g. National AI Symposium / Rural Volunteering Drive" required>
              </div>

              <div class="form-group">
                <label for="domainSelect">Data Domain <span style="color: red;">*</span></label>
                <select name="domain" id="domainSelect" class="form-control" required>
                  <option value="Co-curricular">Co-curricular (Seminars, Certifications, Projects, Internships)</option>
                  <option value="Extra-curricular">Extra-curricular (Sports, Arts, Volunteering, Leadership)</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-3" style="gap: 1.5rem;">
              <div class="form-group">
                <label for="catSelect">Activity Category <span style="color: red;">*</span></label>
                <select name="category" id="catSelect" class="form-control" required>
                  <option value="Conference / Seminar">Conference / Seminar / Workshop</option>
                  <option value="Competition / Hackathon">Competition / Hackathon</option>
                  <option value="Volunteering / Community">Volunteering (Whole Person Education)</option>
                  <option value="Internship / Research">Internship / Project Work</option>
                  <option value="Leadership / Club">Leadership / Club Position</option>
                </select>
              </div>

              <div class="form-group">
                <label for="actDate">Activity Date <span style="color: red;">*</span></label>
                <input type="date" name="activity_date" id="actDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
              </div>

              <div class="form-group">
                <label for="roleInput">Your Role / Achievement</label>
                <input type="text" name="role_played" id="roleInput" class="form-control" placeholder="e.g. First Prize Winner / Lead Coordinator / Participant">
              </div>
            </div>

            <div class="form-group">
              <label for="descInput">Activity Description &amp; Learnings</label>
              <textarea name="description" id="descInput" class="form-control" rows="2" placeholder="Briefly describe your role, achievements, and key learnings..."></textarea>
            </div>

            <div style="text-align: right;">
              <button type="submit" class="btn btn-primary btn-lg">Submit Activity for Verification &rarr;</button>
            </div>

          </form>
        </div>

        <!-- Submitted Activities List -->
        <div class="table-card">
          <div class="table-header">
            <div>
              <h3 style="color: var(--primary-navy); font-size: 1.25rem;">🏆 Submitted Activity Participation Record</h3>
            </div>
          </div>

          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Domain</th>
                  <th>Activity Title</th>
                  <th>Category</th>
                  <th>Date</th>
                  <th>Role</th>
                  <th>Verification Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($activities as $act): ?>
                  <tr>
                    <td><span class="badge badge-navy"><?php echo htmlspecialchars($act['domain']); ?></span></td>
                    <td><strong style="color: var(--primary-navy);"><?php echo htmlspecialchars($act['title']); ?></strong></td>
                    <td><?php echo htmlspecialchars($act['category']); ?></td>
                    <td><?php echo date('d M Y', strtotime($act['activity_date'])); ?></td>
                    <td><?php echo htmlspecialchars($act['role_played']); ?></td>
                    <td>
                      <?php
                        $st = strtolower($act['status']);
                        if ($st === 'approved') echo '<span class="badge badge-approved">✓ Verified</span>';
                        else echo '<span class="badge badge-pending">⏳ Pending Review</span>';
                      ?>
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
