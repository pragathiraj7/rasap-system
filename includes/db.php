<?php
/**
 * RASAP - Student Monitoring and Documentation System
 * Database Connection & PDO Wrapper (db.php)
 * Rajagiri College of Social Sciences (Autonomous), Kochi
 * --------------------------------------------------------------------------
 * Strictly adheres to 3NF Database Schema for RASAP System.
 * Supports MySQL PDO with seamless fallback to SQLite/Session DB for instant testing under XAMPP.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Returns active PDO connection instance
 */
function getDBConnection() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $host = '127.0.0.1';
    $db   = 'rasap_db';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    try {
        // Try connecting to MySQL
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        // Fallback to local SQLite file for zero-config XAMPP deployment
        try {
            $sqliteFile = __DIR__ . '/../rasap_database.sqlite';
            $pdo = new PDO("sqlite:" . $sqliteFile);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            initialize3NFSchema($pdo);
        } catch (Exception $ex) {
            $pdo = null;
        }
    }

    return $pdo;
}

/**
 * Initializes 3NF Schema Tables and Seed Data
 */
function initialize3NFSchema($pdo) {
    if (!$pdo) return;

    // 1. Departments Table (1NF / 2NF / 3NF)
    $pdo->exec("CREATE TABLE IF NOT EXISTS departments (
        department_id INTEGER PRIMARY KEY AUTOINCREMENT,
        dept_name VARCHAR(100) NOT NULL,
        dept_code VARCHAR(20) NOT NULL UNIQUE
    )");

    // 2. Users Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        user_id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role VARCHAR(30) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        full_name VARCHAR(100) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 3. Students Table (3NF - Dependent on user_id & department_id)
    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        student_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        department_id INTEGER NOT NULL,
        rasap_id VARCHAR(30) NOT NULL UNIQUE,
        batch_year VARCHAR(20) NOT NULL,
        year_of_study INTEGER NOT NULL DEFAULT 1,
        parent_user_id INTEGER,
        fee_status VARCHAR(20) DEFAULT 'Paid',
        academic_score DECIMAL(3,2) DEFAULT 3.85,
        attendance_pct DECIMAL(5,2) DEFAULT 94.50,
        FOREIGN KEY (user_id) REFERENCES users(user_id),
        FOREIGN KEY (department_id) REFERENCES departments(department_id)
    )");

    // 4. E-Docets Table (Certificates & Documentation Uploads)
    $pdo->exec("CREATE TABLE IF NOT EXISTS edocets (
        edocet_id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER NOT NULL,
        title VARCHAR(150) NOT NULL,
        category VARCHAR(50) NOT NULL,
        file_path VARCHAR(255) NOT NULL,
        description TEXT,
        status VARCHAR(20) DEFAULT 'Pending',
        remarks TEXT,
        uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        reviewed_at DATETIME,
        reviewed_by INTEGER,
        FOREIGN KEY (student_id) REFERENCES students(student_id)
    )");

    // Seed Initial Departments if empty
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM departments");
    if ($stmt->fetch()['cnt'] == 0) {
        $pdo->exec("INSERT INTO departments (dept_name, dept_code) VALUES
            ('Computer Applications', 'BCA'),
            ('Social Work', 'BSW'),
            ('Business Administration', 'BBA'),
            ('Commerce', 'BCOM'),
            ('Psychology', 'BPSY')");
    }

    // Seed Sample Users if empty
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM users");
    if ($stmt->fetch()['cnt'] == 0) {
        $passHash = password_hash('password123', PASSWORD_DEFAULT);
        
        $pdo->exec("INSERT INTO users (username, password_hash, role, email, full_name) VALUES
            ('alex_student', '$passHash', 'student', 'alex.varghese@rajagiri.edu', 'Alex Varghese'),
            ('coord_mary', '$passHash', 'faculty_coordinator', 'mary.joseph@rajagiri.edu', 'Prof. Mary Joseph'),
            ('rasap_mathew', '$passHash', 'rasap_coordinator', 'mathew.kurian@rajagiri.edu', 'Dr. Mathew Kurian'),
            ('faculty_thomas', '$passHash', 'faculty', 'thomas.paul@rajagiri.edu', 'Dr. Thomas Paul'),
            ('rasap_sarah', '$passHash', 'rasap_faculty', 'sarah.john@rajagiri.edu', 'Prof. Sarah John'),
            ('parent_varghese', '$passHash', 'parent', 'varghese.parent@gmail.com', 'Varghese K.')");

        // Link student record
        $pdo->exec("INSERT INTO students (user_id, department_id, rasap_id, batch_year, year_of_study, parent_user_id, fee_status, academic_score, attendance_pct) VALUES
            (1, 1, 'RASAP20260482', '2024-2027', 2, 6, 'Paid', 3.88, 96.20)");

        // Seed Sample Certificates
        $pdo->exec("INSERT INTO edocets (student_id, title, category, file_path, description, status, remarks, uploaded_at) VALUES
            (1, 'IELTS Academic Scorecard (Band 8.0)', 'Language Proficiency', 'assets/uploads/certificates/ielts_scorecard.pdf', 'Certified IELTS Result Copy for Partner University Application', 'Approved', 'Verified against official TRF portal.', '2026-02-14 10:30:00'),
            (1, 'Semester 3 Marklist & Transcript', 'Academic Marksheet', 'assets/uploads/certificates/sem3_marklist.pdf', 'Official RCSS Autonomous Grade Sheet', 'Approved', 'Passed with Distinction', '2026-03-01 11:15:00'),
            (1, 'Global Internship Certificate - Infosys', 'Internship Certificate', 'assets/uploads/certificates/internship_infosys.pdf', '8-week Full Stack Software Engineering Internship', 'Pending', NULL, '2026-03-18 14:20:00'),
            (1, 'International Passport & Student Visa Copy', 'Passport/Visa', 'assets/uploads/certificates/passport_copy.pdf', 'Valid Indian Passport expiring 2031', 'Returned', 'Upload page 2 signature scan clearly.', '2026-03-20 09:45:00')");
    }
}

/**
 * Ensures session initialization and mock data fallback
 */
function getMockDocumentList() {
    if (!isset($_SESSION['mock_edocets'])) {
        $_SESSION['mock_edocets'] = [
            [
                'edocet_id' => 101,
                'student_id' => 1,
                'student_name' => 'Alex Varghese',
                'rasap_id' => 'RASAP20260482',
                'department' => 'Computer Applications',
                'title' => 'IELTS Academic Scorecard (Band 8.0)',
                'category' => 'Language Proficiency',
                'file_path' => 'assets/uploads/certificates/ielts_scorecard.pdf',
                'description' => 'Certified IELTS Result Copy for Partner University Application',
                'status' => 'Approved',
                'remarks' => 'Verified against official TRF portal.',
                'uploaded_at' => '2026-02-14 10:30:00'
            ],
            [
                'edocet_id' => 102,
                'student_id' => 1,
                'student_name' => 'Alex Varghese',
                'rasap_id' => 'RASAP20260482',
                'department' => 'Computer Applications',
                'title' => 'Semester 3 Marklist & Transcript',
                'category' => 'Academic Marksheet',
                'file_path' => 'assets/uploads/certificates/sem3_marklist.pdf',
                'description' => 'Official RCSS Autonomous Grade Sheet',
                'status' => 'Approved',
                'remarks' => 'Passed with SGPA 3.92',
                'uploaded_at' => '2026-03-01 11:15:00'
            ],
            [
                'edocet_id' => 103,
                'student_id' => 1,
                'student_name' => 'Alex Varghese',
                'rasap_id' => 'RASAP20260482',
                'department' => 'Computer Applications',
                'title' => 'Global Internship Certificate - Infosys',
                'category' => 'Internship Certificate',
                'file_path' => 'assets/uploads/certificates/internship_infosys.pdf',
                'description' => '8-week Software Engineering Internship',
                'status' => 'Pending',
                'remarks' => '',
                'uploaded_at' => '2026-03-18 14:20:00'
            ],
            [
                'edocet_id' => 104,
                'student_id' => 2,
                'student_name' => 'Riya Mariam',
                'rasap_id' => 'RASAP20260490',
                'department' => 'Business Administration',
                'title' => 'German B2 Proficiency Certificate',
                'category' => 'Language Proficiency',
                'file_path' => 'assets/uploads/certificates/german_b2.pdf',
                'description' => 'Goethe-Institut Exam Certificate',
                'status' => 'Pending',
                'remarks' => '',
                'uploaded_at' => '2026-03-21 16:10:00'
            ],
            [
                'edocet_id' => 105,
                'student_id' => 3,
                'student_name' => 'Kevin Jacob',
                'rasap_id' => 'RASAP20260512',
                'department' => 'Social Work',
                'title' => 'Community Fieldwork Report',
                'category' => 'Academic Marksheet',
                'file_path' => 'assets/uploads/certificates/fieldwork_report.pdf',
                'description' => 'Social Impact Research project documentation',
                'status' => 'Returned',
                'remarks' => 'Requires faculty advisor signature page.',
                'uploaded_at' => '2026-03-22 09:30:00'
            ]
        ];
    }
    return $_SESSION['mock_edocets'];
}

/**
 * Returns current authenticated user or default demo session
 */
function getCurrentUser() {
    if (!isset($_SESSION['user'])) {
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Alex Varghese',
            'role' => 'student',
            'role_label' => 'Student',
            'email' => 'alex.varghese@rajagiri.edu',
            'rasap_id' => 'RASAP20260482',
            'department' => 'Computer Applications',
            'batch' => '2024-2027',
            'year' => 2
        ];
    }
    return $_SESSION['user'];
}

// Auto-initialize DB connection on file include
$pdo = getDBConnection();
