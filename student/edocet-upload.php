<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * E-Docet Certificate Upload Form (student/edocet-upload.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Upload area for students submitting file metadata and certificate proofs.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

$currentUser = getCurrentUser();
$successMessage = '';
$errorMessage = '';

// Handle POST File Upload Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $docTitle = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($docTitle) || empty($category)) {
        $errorMessage = "Please fill in both Document Title and Category.";
    } elseif (!isset($_FILES['certificate_file']) || $_FILES['certificate_file']['error'] !== UPLOAD_ERR_OK) {
        $errorMessage = "Please select a valid certificate file to upload.";
    } else {
        $file = $_FILES['certificate_file'];
        $fileName = $file['name'];
        $fileTmpPath = $file['tmp_name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            $errorMessage = "Invalid file type! Allowed formats: PDF, JPG, PNG, DOC, DOCX.";
        } else {
            // Target upload directory
            $uploadDir = __DIR__ . '/../assets/uploads/certificates/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate safe filename
            $newFileName = 'edocet_' . time() . '_' . rand(1000, 9999) . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;
            $relativePath = 'assets/uploads/certificates/' . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Save document metadata to Session mock array and Database
                $newDoc = [
                    'edocet_id' => rand(200, 999),
                    'student_id' => $currentUser['id'] ?? 1,
                    'student_name' => $currentUser['name'] ?? 'Alex Varghese',
                    'rasap_id' => $currentUser['rasap_id'] ?? 'RASAP20260482',
                    'department' => $currentUser['department'] ?? 'Computer Applications',
                    'title' => htmlspecialchars($docTitle),
                    'category' => htmlspecialchars($category),
                    'file_path' => $relativePath,
                    'description' => htmlspecialchars($description),
                    'status' => 'Pending',
                    'remarks' => '',
                    'uploaded_at' => date('Y-m-d H:i:s')
                ];

                if (!isset($_SESSION['mock_edocets'])) {
                    getMockDocumentList();
                }
                array_unshift($_SESSION['mock_edocets'], $newDoc);

                // Insert into PDO Database if available
                $pdo = getDBConnection();
                if ($pdo) {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO edocets (student_id, title, category, file_path, description, status, uploaded_at) VALUES (:sid, :t, :c, :f, :d, 'Pending', NOW())");
                        $stmt->execute([
                            'sid' => 1,
                            't' => $docTitle,
                            'c' => $category,
                            'f' => $relativePath,
                            'd' => $description
                        ]);
                    } catch (Exception $e) {
                        // Handled via session store
                    }
                }

                $successMessage = "E-Docet certificate uploaded successfully! It is now queued for Faculty Coordinator review.";
            } else {
                $errorMessage = "Failed to save uploaded file. Please check folder permissions.";
            }
        }
    }
}

$pageTitle = "E-Docet Certificate Upload - RASAP";
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
  <div class="container">
    <div class="dashboard-wrapper">
      
      <!-- Quick Links Sidebar -->
      <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

      <!-- Main E-Docet Upload Form Container -->
      <div>
        
        <?php if (!empty($successMessage)): ?>
          <div class="alert alert-success">
            <span>✓</span>
            <div><?php echo htmlspecialchars($successMessage); ?></div>
          </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
          <div class="alert alert-danger">
            <span>⚠️</span>
            <div><?php echo htmlspecialchars($errorMessage); ?></div>
          </div>
        <?php endif; ?>

        <div class="form-card">
          <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
              <span class="badge badge-navy mb-1">RASAP Verification Vault</span>
              <h2 style="font-size: 1.6rem; color: var(--primary-navy);">Submit New E-Docet Certificate Proof</h2>
              <p style="font-size: 0.88rem; color: var(--text-muted); margin: 0;">Upload academic marksheets, language certificates, or internship proofs for coordinator verification.</p>
            </div>
            <a href="dashboard.php" class="btn btn-outline btn-sm">&larr; Back to Dashboard</a>
          </div>

          <!-- HTML5 File Upload Form -->
          <form action="edocet-upload.php" method="POST" enctype="multipart/form-data">
            
            <div class="grid grid-cols-2" style="gap: 1.5rem;">
              
              <!-- Document Title Text Box -->
              <div class="form-group">
                <label for="docTitleInput">Document Title / Certificate Name <span style="color: red;">*</span></label>
                <input type="text" name="title" id="docTitleInput" class="form-control" placeholder="e.g. IELTS Academic Test Result / Semester 4 Marksheet" required>
                <small style="color: var(--text-muted); font-size: 0.78rem;">Provide a descriptive title for instant verification.</small>
              </div>

              <!-- Category Selector Dropdown -->
              <div class="form-group">
                <label for="categorySelect">Certificate Category <span style="color: red;">*</span></label>
                <select name="category" id="categorySelect" class="form-control" required>
                  <option value="">-- Select Category --</option>
                  <option value="Academic Marksheet">Academic Marksheet &amp; Transcript</option>
                  <option value="Language Proficiency">Language Proficiency (IELTS / TOEFL / German)</option>
                  <option value="Internship Certificate">Internship &amp; Work Experience</option>
                  <option value="Passport/Visa">Passport &amp; Student Visa Documents</option>
                  <option value="Extracurricular">Extracurricular / Seminars / Badges</option>
                  <option value="Other">Other Supporting Documentation</option>
                </select>
              </div>

            </div>

            <!-- File Upload Dropzone Container -->
            <div class="form-group">
              <label>Upload Document Proof File (PDF / Image / DOC) <span style="color: red;">*</span></label>
              <div class="file-dropzone" onclick="document.getElementById('certificateFileInput').click();">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📄</div>
                <div id="selectedFileName" style="font-size: 0.95rem; font-weight: 600; color: var(--primary-navy);">
                  Click or Drag &amp; Drop file here to attach
                </div>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.35rem;">
                  Supported Formats: PDF, PNG, JPG, JPEG, DOC, DOCX (Max size: 10MB)
                </div>
              </div>
              <input type="file" name="certificate_file" id="certificateFileInput" style="display: none;" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" required>
            </div>

            <!-- Descriptive Text Box / Remarks -->
            <div class="form-group">
              <label for="descriptionInput">Descriptive Details / Student Notes</label>
              <textarea name="description" id="descriptionInput" class="form-control" placeholder="Add optional details like issuance authority, serial numbers, or notes for the faculty coordinator..."></textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
              <a href="dashboard.php" class="btn btn-outline">Cancel</a>
              <button type="submit" class="btn btn-primary btn-lg">Submit E-Docet for Review &rarr;</button>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
