<?php
/**
 * TechVision Computer Institute
 * Contact Form Handler
 * ====================
 * File: php/contact.php
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

function sanitize($val) {
    return htmlspecialchars(trim((string)$val), ENT_QUOTES, 'UTF-8');
}

$name    = sanitize($data['name'] ?? '');
$mobile  = sanitize($data['mobile'] ?? '');
$subject = sanitize($data['subject'] ?? '');
$message = sanitize($data['message'] ?? '');

if (!$name || !$mobile || !$subject || !$message) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled.']);
    exit;
}

// Save to file
$dataDir  = __DIR__ . '/../data/';
$dataFile = $dataDir . 'enquiries.json';
if (!is_dir($dataDir)) mkdir($dataDir, 0755, true);

$enquiries = [];
if (file_exists($dataFile)) {
    $enquiries = json_decode(file_get_contents($dataFile), true) ?: [];
}

$enquiries[] = [
    'id'           => uniqid('ENQ-', true),
    'submitted_at' => date('Y-m-d H:i:s'),
    'name'         => $name,
    'mobile'       => $mobile,
    'subject'      => $subject,
    'message'      => $message,
];

file_put_contents($dataFile, json_encode($enquiries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Optional email
/*
mail(
    'admin@techvisioninstitute.in',
    'New Contact Form: ' . $subject,
    "Name: $name\nMobile: $mobile\n\nMessage:\n$message",
    'From: noreply@techvisioninstitute.in'
);
*/

echo json_encode(['success' => true, 'message' => 'Message received. We will respond within 24 hours.']);
