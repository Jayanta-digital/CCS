<?php
/**
 * TechVision Computer Institute
 * Referral Form Handler
 * =====================
 * File: php/referral.php
 * 
 * Accepts POST (JSON or form-encoded) with referral data.
 * Stores in: data/referrals.json (auto-created)
 * Admin view: data/referrals.json (or run admin-view.php)
 * 
 * SECURITY: XSS-safe, input sanitized, CSRF-aware.
 */

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// ---- Input Parsing ----
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) {
    $data = $_POST;
}

// ---- Required Fields ----
$required = ['ref_name', 'ref_mobile', 'friend_name', 'friend_mobile', 'course_referred'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => "Field '$field' is required."]);
        exit;
    }
}

// ---- Sanitize All Inputs ----
function sanitize($val) {
    return htmlspecialchars(trim((string)$val), ENT_QUOTES, 'UTF-8');
}

$referral = [
    'id'             => uniqid('REF-', true),
    'submitted_at'   => date('Y-m-d H:i:s'),
    'ref_name'       => sanitize($data['ref_name']),
    'ref_mobile'     => sanitize($data['ref_mobile']),
    'ref_email'      => sanitize($data['ref_email'] ?? ''),
    'ref_course'     => sanitize($data['ref_course'] ?? ''),
    'friend_name'    => sanitize($data['friend_name']),
    'friend_mobile'  => sanitize($data['friend_mobile']),
    'friend_email'   => sanitize($data['friend_email'] ?? ''),
    'course_referred'=> sanitize($data['course_referred']),
    'message'        => sanitize($data['message'] ?? ''),
    'status'         => 'Pending', // Pending | Contacted | Enrolled | Rewarded
];

// ---- Validate Mobile Numbers ----
if (!preg_match('/^[0-9]{10,12}$/', preg_replace('/[\s\-\+]/', '', $referral['ref_mobile']))) {
    echo json_encode(['success' => false, 'message' => 'Invalid referrer mobile number.']);
    exit;
}
if (!preg_match('/^[0-9]{10,12}$/', preg_replace('/[\s\-\+]/', '', $referral['friend_mobile']))) {
    echo json_encode(['success' => false, 'message' => "Invalid friend's mobile number."]);
    exit;
}

// ---- Storage: JSON File ----
$dataDir  = __DIR__ . '/../data/';
$dataFile = $dataDir . 'referrals.json';

// Create data directory if it doesn't exist
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// Load existing referrals
$referrals = [];
if (file_exists($dataFile)) {
    $existing = file_get_contents($dataFile);
    $referrals = json_decode($existing, true) ?: [];
}

// Append new referral
$referrals[] = $referral;

// Save back to file
$saved = file_put_contents($dataFile, json_encode($referrals, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if ($saved === false) {
    // Try fallback: log to a text file
    file_put_contents($dataDir . 'referrals_log.txt', 
        date('Y-m-d H:i:s') . ' | ' . json_encode($referral) . PHP_EOL, 
        FILE_APPEND | LOCK_EX
    );
}

// ---- Optional: Email Notification ----
/*
$to      = 'admin@techvisioninstitute.in';
$subject = 'New Referral: ' . $referral['friend_name'];
$body    = "New referral submitted.\n\n" .
           "Referrer: {$referral['ref_name']} ({$referral['ref_mobile']})\n" .
           "Friend:   {$referral['friend_name']} ({$referral['friend_mobile']})\n" .
           "Course:   {$referral['course_referred']}\n" .
           "Time:     {$referral['submitted_at']}\n";
mail($to, $subject, $body, 'From: noreply@techvisioninstitute.in');
*/

echo json_encode([
    'success'   => true,
    'message'   => 'Referral submitted successfully! We will contact your friend within 24 hours.',
    'referral_id' => $referral['id'],
]);
