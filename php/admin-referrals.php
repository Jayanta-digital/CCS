<?php
/**
 * TechVision Computer Institute
 * Referral Admin Viewer
 * =====================
 * File: php/admin-referrals.php
 * 
 * PASSWORD PROTECT THIS FILE IN PRODUCTION!
 * Add .htpasswd or update the password below.
 * 
 * Access: yourdomain.com/php/admin-referrals.php?pass=YourPassword
 */

// ---- CHANGE THIS PASSWORD ----
define('ADMIN_PASSWORD', 'TechVision@2025');

// ---- Authentication ----
$pass = $_GET['pass'] ?? '';
if ($pass !== ADMIN_PASSWORD) {
    http_response_code(401);
    echo '<!DOCTYPE html><html><head><title>Unauthorized</title></head><body style="font-family:sans-serif;max-width:400px;margin:80px auto;text-align:center">
    <h2>🔐 Admin Access</h2>
    <p>Add ?pass=YourPassword to the URL</p>
    <p><small>Change password in php/admin-referrals.php</small></p>
    </body></html>';
    exit;
}

// ---- Load Data ----
$dataFile = __DIR__ . '/../data/referrals.json';
$referrals = [];
if (file_exists($dataFile)) {
    $referrals = json_decode(file_get_contents($dataFile), true) ?: [];
}

// ---- Handle Status Update ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = $_POST['update_id'];
    $newStatus = htmlspecialchars($_POST['new_status'] ?? 'Pending');
    foreach ($referrals as &$ref) {
        if ($ref['id'] === $id) {
            $ref['status'] = $newStatus;
            $ref['updated_at'] = date('Y-m-d H:i:s');
        }
    }
    file_put_contents($dataFile, json_encode($referrals, JSON_PRETTY_PRINT));
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$statusColors = [
    'Pending'   => '#f59e0b',
    'Contacted' => '#3b82f6',
    'Enrolled'  => '#10b981',
    'Rewarded'  => '#8b5cf6',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Referrals Admin – TechVision</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: #f7f9fc; color: #1a1a2e; }
    .header { background: #1a3a6e; color: #fff; padding: 20px 32px; display: flex; justify-content: space-between; align-items: center; }
    .header h1 { font-size: 1.3rem; }
    .content { max-width: 1200px; margin: 32px auto; padding: 0 20px; }
    .stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 32px; }
    .stat { background: #fff; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .stat-num { font-size: 2rem; font-weight: 700; color: #1a3a6e; }
    .stat-label { font-size: 0.82rem; color: #6b7280; margin-top: 4px; }
    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    th { background: #1a3a6e; color: #fff; padding: 12px 16px; text-align: left; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    td { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; font-size: 0.88rem; vertical-align: middle; }
    tr:hover { background: #f7f9fc; }
    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: #fff; }
    select { padding: 5px 8px; border: 1px solid #dde3ef; border-radius: 6px; font-size: 0.82rem; cursor: pointer; }
    .update-btn { padding: 5px 14px; background: #1a3a6e; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 0.82rem; }
    .empty { text-align: center; padding: 60px; color: #9ca3af; }
    .export-btn { background: #059669; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 0.9rem; }
    @media (max-width: 768px) { .stats { grid-template-columns: repeat(2,1fr); } table { font-size: 0.78rem; } td,th { padding: 8px 10px; } }
  </style>
</head>
<body>

<div class="header">
  <h1>💻 TechVision – Referral Admin Panel</h1>
  <div style="font-size: 0.85rem; color: rgba(255,255,255,0.7)">Total: <?php echo count($referrals); ?> referrals</div>
</div>

<div class="content">

  <?php
  $counts = ['total' => count($referrals), 'pending' => 0, 'enrolled' => 0, 'rewarded' => 0];
  foreach ($referrals as $r) {
    if ($r['status'] === 'Pending') $counts['pending']++;
    if ($r['status'] === 'Enrolled') $counts['enrolled']++;
    if ($r['status'] === 'Rewarded') $counts['rewarded']++;
  }
  ?>

  <div class="stats">
    <div class="stat"><div class="stat-num"><?= $counts['total'] ?></div><div class="stat-label">Total Referrals</div></div>
    <div class="stat"><div class="stat-num" style="color:#f59e0b"><?= $counts['pending'] ?></div><div class="stat-label">Pending</div></div>
    <div class="stat"><div class="stat-num" style="color:#10b981"><?= $counts['enrolled'] ?></div><div class="stat-label">Enrolled</div></div>
    <div class="stat"><div class="stat-num" style="color:#8b5cf6"><?= $counts['rewarded'] ?></div><div class="stat-label">Rewarded</div></div>
  </div>

  <div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center">
    <h2 style="font-size: 1.1rem">All Referrals</h2>
    <button class="export-btn" onclick="exportCSV()">📥 Export CSV</button>
  </div>

  <?php if (empty($referrals)): ?>
    <div class="empty">
      <div style="font-size: 3rem; margin-bottom: 12px">📋</div>
      <p>No referrals submitted yet.</p>
    </div>
  <?php else: ?>
  <div style="overflow-x: auto">
  <table id="referralTable">
    <thead>
      <tr>
        <th>#</th>
        <th>Date</th>
        <th>Referrer</th>
        <th>Referrer Mobile</th>
        <th>Friend</th>
        <th>Friend Mobile</th>
        <th>Course</th>
        <th>Status</th>
        <th>Update</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach (array_reverse($referrals) as $i => $r): 
        $color = $statusColors[$r['status']] ?? '#6b7280';
      ?>
      <tr>
        <td><?= count($referrals) - $i ?></td>
        <td><?= htmlspecialchars(substr($r['submitted_at'], 0, 16)) ?></td>
        <td><strong><?= htmlspecialchars($r['ref_name']) ?></strong><br><small style="color:#9ca3af"><?= htmlspecialchars($r['ref_course'] ?? '') ?></small></td>
        <td><?= htmlspecialchars($r['ref_mobile']) ?></td>
        <td><?= htmlspecialchars($r['friend_name']) ?></td>
        <td><?= htmlspecialchars($r['friend_mobile']) ?></td>
        <td><?= htmlspecialchars($r['course_referred']) ?></td>
        <td><span class="badge" style="background:<?= $color ?>"><?= htmlspecialchars($r['status']) ?></span></td>
        <td>
          <form method="POST" style="display:flex;gap:6px;align-items:center">
            <input type="hidden" name="update_id" value="<?= htmlspecialchars($r['id']) ?>">
            <select name="new_status">
              <option <?= $r['status']==='Pending'?'selected':'' ?>>Pending</option>
              <option <?= $r['status']==='Contacted'?'selected':'' ?>>Contacted</option>
              <option <?= $r['status']==='Enrolled'?'selected':'' ?>>Enrolled</option>
              <option <?= $r['status']==='Rewarded'?'selected':'' ?>>Rewarded</option>
            </select>
            <button type="submit" class="update-btn">Save</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php endif; ?>

</div>

<script>
function exportCSV() {
  const table = document.getElementById('referralTable');
  if (!table) return;
  let csv = [];
  for (const row of table.rows) {
    const cells = [...row.cells].map(c => '"' + c.innerText.replace(/\n/g, ' ').replace(/"/g, '""') + '"');
    csv.push(cells.join(','));
  }
  const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = 'TechVision_Referrals_<?= date('Y-m-d') ?>.csv';
  a.click();
}
</script>
</body>
</html>
