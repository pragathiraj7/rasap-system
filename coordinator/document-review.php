<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Document Review Workflow (coordinator/document-review.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Features:
 * Interactive table listing student uploads with Approve, Reject, and Return buttons
 * alongside a remarks modal dialog.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

$currentUser = getCurrentUser();
$successMessage = '';
$errorMessage = '';

// Handle POST Document Action (Approve / Reject / Return)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type'])) {
    $docId = (int)($_POST['document_id'] ?? 0);
    $actionType = strtolower(trim($_POST['action_type'] ?? ''));
    $remarks = trim($_POST['remarks'] ?? '');

    $validStatusMap = [
        'approve' => 'Approved',
        'reject' => 'Rejected',
        'return' => 'Returned'
    ];

    if ($docId > 0 && isset($validStatusMap[$actionType])) {
        $newStatus = $validStatusMap[$actionType];
        
        // Update in session mock array
        if (isset($_SESSION['mock_edocets'])) {
            foreach ($_SESSION['mock_edocets'] as &$doc) {
                if ((int)$doc['edocet_id'] === $docId) {
                    $doc['status'] = $newStatus;
                    $doc['remarks'] = htmlspecialchars($remarks);
                    break;
                }
            }
        }

        // Update in PDO Database if available
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("UPDATE edocets SET status = :st, remarks = :rem, reviewed_at = CURRENT_TIMESTAMP WHERE edocet_id = :id");
                $stmt->execute(['st' => $newStatus, 'rem' => $remarks, 'id' => $docId]);
            } catch (Exception $e) {
                // Session handle fallback
            }
        }

        $successMessage = "Document #DOC-{$docId} has been successfully updated to <strong>" . strtoupper($newStatus) . "</strong> status.";
    } else {
        $errorMessage = "Invalid document action requested.";
    }
}

$uploadedDocs = getMockDocumentList();

$pageTitle = "Document Review Queue - RASAP";
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
  <div class="container">
    <div class="dashboard-wrapper">
      
      <!-- Quick Links Sidebar -->
      <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

      <!-- Main Review Table Container -->
      <div>
        
        <?php if (!empty($successMessage)): ?>
          <div class="alert alert-success">
            <span>✓</span>
            <div><?php echo $successMessage; ?></div>
          </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
          <div class="alert alert-danger">
            <span>⚠️</span>
            <div><?php echo htmlspecialchars($errorMessage); ?></div>
          </div>
        <?php endif; ?>

        <div class="table-card">
          <div class="table-header">
            <div>
              <span class="badge badge-navy mb-1">Faculty Review Queue</span>
              <h2 style="color: var(--primary-navy); font-size: 1.5rem;">E-Docet Document Review &amp; Verification</h2>
              <p style="font-size: 0.88rem; color: var(--text-muted); margin: 0;">Approve, Reject, or Return student certificate uploads with faculty remarks.</p>
            </div>
            <div>
              <a href="dashboard.php" class="btn btn-outline btn-sm">&larr; Back to Analytics</a>
            </div>
          </div>

          <!-- Document Review Table -->
          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Student Info</th>
                  <th>Department</th>
                  <th>Document Title</th>
                  <th>Category</th>
                  <th>Proof File</th>
                  <th>Status</th>
                  <th>Faculty Remarks</th>
                  <th style="text-align: center;">Verification Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($uploadedDocs as $doc): ?>
                  <tr>
                    <td>
                      <strong style="color: var(--primary-navy);"><?php echo htmlspecialchars($doc['student_name']); ?></strong>
                      <div style="font-size: 0.78rem;" class="text-muted">ID: <?php echo htmlspecialchars($doc['rasap_id']); ?></div>
                    </td>
                    <td><?php echo htmlspecialchars($doc['department']); ?></td>
                    <td>
                      <strong><?php echo htmlspecialchars($doc['title']); ?></strong>
                      <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($doc['description']); ?></div>
                    </td>
                    <td><span class="badge badge-navy"><?php echo htmlspecialchars($doc['category']); ?></span></td>
                    <td>
                      <a href="../<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="btn btn-outline btn-sm" title="View attached document">
                        📄 View File
                      </a>
                    </td>
                    <td>
                      <?php
                        $st = strtolower($doc['status']);
                        if ($st === 'approved') echo '<span class="badge badge-approved">✓ Approved</span>';
                        elseif ($st === 'pending') echo '<span class="badge badge-pending">⏳ Pending</span>';
                        elseif ($st === 'returned') echo '<span class="badge badge-returned">↩ Returned</span>';
                        else echo '<span class="badge badge-rejected">✕ Rejected</span>';
                      ?>
                    </td>
                    <td style="font-size: 0.85rem;">
                      <?php echo !empty($doc['remarks']) ? htmlspecialchars($doc['remarks']) : '<span class="text-muted">No remarks</span>'; ?>
                    </td>
                    <td>
                      <!-- Action Buttons: Approve, Reject, Return -->
                      <div class="table-actions" style="justify-content: center;">
                        
                        <button type="button" class="btn btn-success btn-sm" 
                                onclick="openReviewModal(<?php echo $doc['edocet_id']; ?>, 'approve', '<?php echo addslashes($doc['student_name']); ?>', '<?php echo addslashes($doc['title']); ?>');" 
                                title="Approve document">
                          Approve
                        </button>

                        <button type="button" class="btn btn-warning btn-sm" 
                                onclick="openReviewModal(<?php echo $doc['edocet_id']; ?>, 'return', '<?php echo addslashes($doc['student_name']); ?>', '<?php echo addslashes($doc['title']); ?>');" 
                                title="Return for student correction">
                          Return
                        </button>

                        <button type="button" class="btn btn-danger btn-sm" 
                                onclick="openReviewModal(<?php echo $doc['edocet_id']; ?>, 'reject', '<?php echo addslashes($doc['student_name']); ?>', '<?php echo addslashes($doc['title']); ?>');" 
                                title="Reject document">
                          Reject
                        </button>

                      </div>
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

<!-- ========================================================================
     DOCUMENT REVIEW REMARKS MODAL OVERLAY COMPONENT
     ======================================================================== -->
<div class="modal-backdrop" id="reviewActionModal">
  <div class="modal-content">
    <div class="modal-header">
      <h4 id="modalActionTitle">Document Action Confirmation</h4>
      <button type="button" class="modal-close-btn" onclick="closeReviewModal();">&times;</button>
    </div>
    
    <form action="document-review.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="document_id" id="modalDocId">
        <input type="hidden" name="action_type" id="modalActionType">

        <p style="font-size: 0.9rem; margin-bottom: 0.75rem;">
          Updating status for student <strong id="modalStudentName" style="color: var(--primary-navy);"></strong>:
        </p>
        
        <div style="background-color: #F8FAFC; padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; border: 1px solid var(--border-color);">
          <strong id="modalDocTitle" style="color: var(--primary-navy); font-size: 0.95rem;"></strong>
        </div>

        <div class="form-group mb-0">
          <label for="modalRemarksInput">Coordinator Verification Remarks &amp; Feedback</label>
          <textarea name="remarks" id="modalRemarksInput" class="form-control" placeholder="Enter optional or required remarks for the student..."></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeReviewModal();">Cancel</button>
        <button type="submit" id="modalSubmitBtn" class="btn btn-primary">Confirm Action</button>
      </div>
    </form>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
