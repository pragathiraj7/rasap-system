/**
 * RASAP - Student Monitoring and Documentation System
 * Shared JavaScript Utilities (main.js)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Handles Role Dropdown, Mobile Nav Toggle, Modal Dialogs, Tab Switching,
 * and Document Action Confirmation logic.
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('RASAP System JS Initialized.');

    // ----------------------------------------------------------------------
    // 1. Role Switcher Dropdown Toggle
    // ----------------------------------------------------------------------
    const roleBtn = document.getElementById('roleSwitcherBtn');
    const roleDropdown = document.getElementById('roleDropdownMenu');

    if (roleBtn && roleDropdown) {
        roleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            roleDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!roleBtn.contains(e.target) && !roleDropdown.contains(e.target)) {
                roleDropdown.classList.remove('show');
            }
        });
    }

    // ----------------------------------------------------------------------
    // 2. Mobile Navigation Menu Toggle
    // ----------------------------------------------------------------------
    const navToggle = document.getElementById('mobileNavToggle');
    const navMenu = document.getElementById('mainNavMenu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            navMenu.classList.toggle('active');
        });
    }

    // ----------------------------------------------------------------------
    // 3. Document Action Remarks Modal (Approve / Reject / Return)
    // ----------------------------------------------------------------------
    window.openReviewModal = function (documentId, actionType, studentName, docTitle) {
        const modalBackdrop = document.getElementById('reviewActionModal');
        if (!modalBackdrop) return;

        document.getElementById('modalDocId').value = documentId;
        document.getElementById('modalActionType').value = actionType;
        document.getElementById('modalStudentName').textContent = studentName;
        document.getElementById('modalDocTitle').textContent = docTitle;
        document.getElementById('modalActionTitle').textContent = actionType.toUpperCase() + ' DOCUMENT';
        
        const actionBtn = document.getElementById('modalSubmitBtn');
        const remarksBox = document.getElementById('modalRemarksInput');
        remarksBox.value = '';

        if (actionType === 'approve') {
            actionBtn.className = 'btn btn-success';
            actionBtn.textContent = 'Confirm Approval';
            remarksBox.placeholder = 'Optional approval remarks (e.g. Verified original marklist)';
        } else if (actionType === 'reject') {
            actionBtn.className = 'btn btn-danger';
            actionBtn.textContent = 'Confirm Rejection';
            remarksBox.placeholder = 'Required: State reason for rejection...';
            remarksBox.required = true;
        } else if (actionType === 'return') {
            actionBtn.className = 'btn btn-warning';
            actionBtn.textContent = 'Return for Resubmission';
            remarksBox.placeholder = 'Required: Specify modifications required by student...';
            remarksBox.required = true;
        }

        modalBackdrop.classList.add('show');
    };

    window.closeReviewModal = function () {
        const modalBackdrop = document.getElementById('reviewActionModal');
        if (modalBackdrop) {
            modalBackdrop.classList.remove('show');
        }
    };

    // ----------------------------------------------------------------------
    // 4. File Dropzone Display Helper (E-Docet Form)
    // ----------------------------------------------------------------------
    const fileInput = document.getElementById('certificateFileInput');
    const fileNameDisplay = document.getElementById('selectedFileName');

    if (fileInput && fileNameDisplay) {
        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                fileNameDisplay.innerHTML = `<strong>Selected:</strong> ${file.name} (${sizeMB} MB)`;
                fileNameDisplay.style.color = 'var(--primary-navy)';
            } else {
                fileNameDisplay.textContent = 'Drag & drop file here or click to browse (PDF, PNG, JPG up to 10MB)';
                fileNameDisplay.style.color = 'var(--text-muted)';
            }
        });
    }

    // ----------------------------------------------------------------------
    // 5. Tab Switching Component
    // ----------------------------------------------------------------------
    window.switchTab = function (tabId, element) {
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => content.style.display = 'none');

        const tabLinks = document.querySelectorAll('.tab-link');
        tabLinks.forEach(link => link.classList.remove('active'));

        const targetTab = document.getElementById(tabId);
        if (targetTab) {
            targetTab.style.display = 'block';
        }
        if (element) {
            element.classList.add('active');
        }
    };
});
