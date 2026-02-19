<?php
/**
 * TechVision Computer Institute
 * Certificate Verification Backend
 * ================================
 * File: php/verify.php
 * Usage: POST with { cert_number: "TVI-YYYY-XXX" }
 * Returns: JSON { valid: bool, data: {...} | message: "..." }
 * 
 * SECURITY: This file is XSS-safe. All output is sanitized.
 * 
 * TO ADD CERTIFICATES:
 * Add entries to the $certificates array below, OR
 * switch to MySQL by uncommenting the database section.
 */

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['valid' => false, 'message' => 'Method not allowed.']);
    exit;
}

// ============================================================
// CERTIFICATE DATA
// Add/edit certificate entries here.
// Format: 'CERT-NUMBER' => [ 'name', 'course', 'year', 'grade' ]
// ============================================================
$certificates = [
    'TVI-2024-001' => [
        'name'   => 'Rahul Kumar Sharma',
        'course' => 'DCA – Diploma in Computer Applications',
        'year'   => '2024',
        'grade'  => 'A (85%)',
        'status' => 'VALID',
    ],
    'TVI-2024-002' => [
        'name'   => 'Priya Singh',
        'course' => 'ADCA – Advanced Diploma in Computer Applications',
        'year'   => '2024',
        'grade'  => 'A+ (92%)',
        'status' => 'VALID',
    ],
    'TVI-2023-018' => [
        'name'   => 'Amit Rajput',
        'course' => 'MS Office & Internet',
        'year'   => '2023',
        'grade'  => 'B+ (78%)',
        'status' => 'VALID',
    ],
    'TVI-2023-045' => [
        'name'   => 'Sunita Devi',
        'course' => 'Tally Prime with GST',
        'year'   => '2023',
        'grade'  => 'A (88%)',
        'status' => 'VALID',
    ],
    'TVI-2024-067' => [
        'name'   => 'Ravi Anand',
        'course' => 'Web Designing & Development',
        'year'   => '2024',
        'grade'  => 'A+ (94%)',
        'status' => 'VALID',
    ],
    'TVI-2022-012' => [
        'name'   => 'Kavita Verma',
        'course' => 'DCA – Diploma in Computer Applications',
        'year'   => '2022',
        'grade'  => 'B (72%)',
        'status' => 'VALID',
    ],
    'TVI-2024-089' => [
        'name'   => 'Suresh Yadav',
        'course' => 'Python Programming',
        'year'   => '2024',
        'grade'  => 'A (82%)',
        'status' => 'VALID',
    ],
    'TVI-2023-033' => [
        'name'   => 'Anjali Mishra',
        'course' => 'ADCA – Advanced Diploma in Computer Applications',
        'year'   => '2023',
        'grade'  => 'A+ (96%)',
        'status' => 'VALID',
    ],
];

// ============================================================
// INPUT HANDLING
// ============================================================

// Support both JSON body and POST form data
$raw = file_get_contents('php://input');
$json = json_decode($raw, true);

$cert_number = '';
if ($json && isset($json['cert_number'])) {
    $cert_number = $json['cert_number'];
} elseif (isset($_POST['cert_number'])) {
    $cert_number = $_POST['cert_number'];
}

// Sanitize input
$cert_number = strtoupper(trim(htmlspecialchars($cert_number, ENT_QUOTES, 'UTF-8')));

if (empty($cert_number)) {
    echo json_encode(['valid' => false, 'message' => 'Certificate number is required.']);
    exit;
}

// Validate format (optional strictness: TVI-YYYY-XXX)
if (!preg_match('/^[A-Z0-9\-]{3,20}$/', $cert_number)) {
    echo json_encode(['valid' => false, 'message' => 'Invalid certificate number format.']);
    exit;
}

// ============================================================
// DATABASE ALTERNATIVE (MySQL)
// Uncomment this section and comment out the array lookup below
// if you want to use a MySQL database.
// ============================================================
/*
$db = new PDO(
    'mysql:host=localhost;dbname=techvision_db;charset=utf8',
    'db_username',
    'db_password',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$stmt = $db->prepare(
    'SELECT name, course, year, grade, status FROM certificates WHERE cert_number = ? LIMIT 1'
);
$stmt->execute([$cert_number]);
$cert = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cert) {
    echo json_encode([
        'valid' => true,
        'data' => [
            'cert_number' => $cert_number,
            'name'        => htmlspecialchars($cert['name']),
            'course'      => htmlspecialchars($cert['course']),
            'year'        => htmlspecialchars($cert['year']),
            'grade'       => htmlspecialchars($cert['grade']),
            'status'      => htmlspecialchars($cert['status']),
            'issued_by'   => 'TechVision Computer Institute',
        ]
    ]);
} else {
    echo json_encode(['valid' => false, 'message' => 'Certificate not found.']);
}
exit;
*/

// ============================================================
// ARRAY LOOKUP (default, no database required)
// ============================================================
if (isset($certificates[$cert_number])) {
    $cert = $certificates[$cert_number];
    echo json_encode([
        'valid' => true,
        'data'  => [
            'cert_number' => $cert_number,
            'name'        => htmlspecialchars($cert['name'], ENT_QUOTES, 'UTF-8'),
            'course'      => htmlspecialchars($cert['course'], ENT_QUOTES, 'UTF-8'),
            'year'        => htmlspecialchars($cert['year'], ENT_QUOTES, 'UTF-8'),
            'grade'       => htmlspecialchars($cert['grade'], ENT_QUOTES, 'UTF-8'),
            'status'      => htmlspecialchars($cert['status'], ENT_QUOTES, 'UTF-8'),
            'issued_by'   => 'TechVision Computer Institute',
        ]
    ]);
} else {
    echo json_encode([
        'valid'   => false,
        'message' => 'No certificate found with this number. Please check and try again, or contact the institute.'
    ]);
}
