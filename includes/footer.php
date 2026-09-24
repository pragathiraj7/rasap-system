<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Institutional Footer (includes/footer.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Renders bottom footer bar and includes JavaScript assets.
 */

if (!isset($basePath)) {
    $basePath = './';
}
?>

  <!-- Main System Footer -->
  <footer style="background-color: #0F172A; color: #94A3B8; padding: 2.5rem 0 1.5rem 0; margin-top: 3rem; border-top: 3px solid var(--gold-accent);">
    <div class="container">
      <div class="grid grid-cols-4" style="gap: 2rem; margin-bottom: 2rem;">
        
        <!-- Column 1: Institution Info -->
        <div>
          <h4 style="color: var(--white); font-size: 1.1rem; margin-bottom: 0.85rem;">Rajagiri College</h4>
          <p style="font-size: 0.85rem; line-height: 1.6; color: #CBD5E1;">
            Rajagiri College of Social Sciences (Autonomous)<br>
            Rajagiri Valley P.O., Kakkanad, Kochi,<br>
            Kerala 682039, India.
          </p>
        </div>

        <!-- Column 2: System Programs -->
        <div>
          <h4 style="color: var(--white); font-size: 1.1rem; margin-bottom: 0.85rem;">RASAP Programs</h4>
          <ul style="list-style: none; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.4rem;">
            <li><a href="https://rajagiri.edu" target="_blank" style="color: #94A3B8;">Computer Applications (BCA)</a></li>
            <li><a href="https://rajagiri.edu" target="_blank" style="color: #94A3B8;">Social Work (BSW)</a></li>
            <li><a href="https://rajagiri.edu" target="_blank" style="color: #94A3B8;">Business Administration (BBA)</a></li>
            <li><a href="https://rajagiri.edu" target="_blank" style="color: #94A3B8;">Commerce (B.Com)</a></li>
          </ul>
        </div>

        <!-- Column 3: Quick Links -->
        <div>
          <h4 style="color: var(--white); font-size: 1.1rem; margin-bottom: 0.85rem;">Portal Links</h4>
          <ul style="list-style: none; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.4rem;">
            <li><a href="https://rajagiri.edu" target="_blank" style="color: #94A3B8;">Official Website (rajagiri.edu)</a></li>
            <li><a href="https://rasap.rajagiri.edu" target="_blank" style="color: #94A3B8;">RASAP Portal (rasap.rajagiri.edu)</a></li>
            <li><a href="<?php echo $basePath; ?>student/dashboard.php" style="color: #94A3B8;">Student Portal</a></li>
            <li><a href="<?php echo $basePath; ?>coordinator/dashboard.php" style="color: #94A3B8;">Coordinator Portal</a></li>
          </ul>
        </div>

        <!-- Column 4: Technology Stack Info -->
        <div>
          <h4 style="color: var(--white); font-size: 1.1rem; margin-bottom: 0.85rem;">System Specifications</h4>
          <p style="font-size: 0.82rem; color: #CBD5E1;">
            Built with PHP 8.x, PDO MySQL (3NF Schema), Vanilla JavaScript, HTML5, CSS3, &amp; Chart.js.
          </p>
          <span class="badge badge-navy" style="margin-top: 0.5rem; display: inline-block;">PHP 8.x PDO &bull; 3NF Schema</span>
        </div>

      </div>

      <!-- Copyright Bottom Strip -->
      <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; font-size: 0.82rem;">
        <p style="margin: 0; color: #94A3B8;">
          &copy; <?php echo date('Y'); ?> Rajagiri College of Social Sciences. All Rights Reserved. RASAP Continuous Student Monitoring System.
        </p>
        <div style="display: flex; gap: 1rem;">
          <a href="https://rajagiri.edu/privacy" target="_blank" style="color: #94A3B8;">Privacy Policy</a>
          <a href="https://rajagiri.edu/terms" target="_blank" style="color: #94A3B8;">Terms of Service</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- System JavaScript Files -->
  <script src="<?php echo $basePath; ?>assets/js/main.js"></script>
  <script src="<?php echo $basePath; ?>assets/js/dashboardCharts.js"></script>
</body>
</html>
