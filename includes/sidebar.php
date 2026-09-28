<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Quick Links Sidebar Panel (includes/sidebar.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Renders Quick Links panel with external institutional links to https://rajagiri.edu
 * and https://rasap.rajagiri.edu alongside essential proposal shortcuts.
 */

if (!isset($basePath)) {
    $basePath = './';
}
?>

<!-- Quick Links Panel Sidebar -->
<aside class="dashboard-sidebar">
  
  <!-- Institutional Quick Links Section -->
  <div class="sidebar-title">🌐 Institutional Quick Links</div>
  <ul class="quick-links-list mb-3">
    <li class="quick-link-item">
      <a href="https://rajagiri.edu" target="_blank" rel="noopener noreferrer" title="Official Rajagiri College Portal">
        <span class="quick-link-icon">🏛️</span>
        <span>Rajagiri Main Website</span>
        <span style="margin-left: auto; font-size: 0.75rem; opacity: 0.7;">↗</span>
      </a>
    </li>
    <li class="quick-link-item">
      <a href="https://rasap.rajagiri.edu" target="_blank" rel="noopener noreferrer" title="RASAP International Portal">
        <span class="quick-link-icon">✈️</span>
        <span>RASAP Study Abroad Portal</span>
        <span style="margin-left: auto; font-size: 0.75rem; opacity: 0.7;">↗</span>
      </a>
    </li>
    <li class="quick-link-item">
      <a href="https://rajagiri.edu/academics" target="_blank" rel="noopener noreferrer">
        <span class="quick-link-icon">📚</span>
        <span>Academic Regulations</span>
      </a>
    </li>
  </ul>

  <!-- Internal Navigation Proposal Shortcuts -->
  <div class="sidebar-title">⚡ Proposal System Modules</div>
  <ul class="quick-links-list mb-3">
    <li class="quick-link-item">
      <a href="<?php echo $basePath; ?>student/exit-profile.php">
        <span class="quick-link-icon">🎓</span>
        <span>3rd-Year Exit Profile</span>
      </a>
    </li>
    <li class="quick-link-item">
      <a href="<?php echo $basePath; ?>student/edocet-upload.php">
        <span class="quick-link-icon">📄</span>
        <span>Upload Certificate</span>
      </a>
    </li>
    <li class="quick-link-item">
      <a href="<?php echo $basePath; ?>student/activity-submit.php">
        <span class="quick-link-icon">🏆</span>
        <span>Submit Activity</span>
      </a>
    </li>
    <li class="quick-link-item">
      <a href="<?php echo $basePath; ?>coordinator/mentoring.php">
        <span class="quick-link-icon">🤝</span>
        <span>Mentoring Maintenance</span>
      </a>
    </li>
    <li class="quick-link-item">
      <a href="<?php echo $basePath; ?>coordinator/document-review.php">
        <span class="quick-link-icon">🔍</span>
        <span>Review Queue</span>
      </a>
    </li>
    <li class="quick-link-item">
      <a href="<?php echo $basePath; ?>parent/dashboard.php">
        <span class="quick-link-icon">👨‍👩‍👦</span>
        <span>Parent Ward Portal</span>
      </a>
    </li>
  </ul>

  <!-- Contact & System Info Card -->
  <div style="background-color: #F8FAFC; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem; margin-top: 1rem;">
    <h5 style="color: var(--primary-navy); font-size: 0.88rem; margin-bottom: 0.35rem;">❓ RASAP Helpdesk</h5>
    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">For document upload queries or technical assistance:</p>
    <p style="font-size: 0.82rem; font-weight: 700; color: var(--primary-navy); margin: 0;">📧 rasap.support@rajagiri.edu</p>
    <p style="font-size: 0.82rem; font-weight: 700; color: var(--primary-navy); margin: 0;">📞 +91 484 2911111</p>
  </div>

</aside>
