<?php
// Halaman default starter PHP Native. Ganti dengan kode aplikasi Anda.
$appName = getenv('APP_NAME') ?: 'PHP Native Starter';
$appUrl  = getenv('APP_URL')  ?: ('http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
$dbHost  = getenv('DB_HOST')  ?: '(belum di-set)';
$phpVer  = phpversion();

// Probe DB kalau env DB_HOST ada
$dbStatus = 'tidak dikonfigurasi';
if (getenv('DB_HOST') && getenv('DB_USER') && getenv('DB_PASS') && getenv('DB_NAME')) {
    try {
        $mysqli = @new mysqli(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASS'),
            getenv('DB_NAME'),
            (int) (getenv('DB_PORT') ?: 3306)
        );
        $dbStatus = $mysqli->connect_errno
            ? '❌ ' . htmlspecialchars($mysqli->connect_error)
            : '✓ terhubung (' . htmlspecialchars($mysqli->server_info) . ')';
        if (!$mysqli->connect_errno) $mysqli->close();
    } catch (Throwable $e) {
        $dbStatus = '❌ ' . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($appName) ?></title>
<style>
  body { font-family: system-ui, sans-serif; max-width: 720px; margin: 60px auto; padding: 0 20px; color: #1e293b; }
  h1 { color: #4f46e5; }
  .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin: 20px 0; }
  code { background: #e2e8f0; padding: 2px 6px; border-radius: 4px; font-size: 0.9em; }
  .grid { display: grid; grid-template-columns: 1fr 2fr; gap: 8px 16px; }
  .ok { color: #059669; }
  .err { color: #dc2626; }
  a { color: #4f46e5; }
</style>
</head>
<body>
  <h1>🐘 <?= htmlspecialchars($appName) ?></h1>
  <p class="ok">✓ Container PHP Native (Apache) berjalan dengan baik.</p>

  <div class="card">
    <h3>Info Runtime</h3>
    <div class="grid">
      <div><strong>PHP Version</strong></div><div><?= htmlspecialchars($phpVer) ?></div>
      <div><strong>Apache</strong></div><div><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') ?></div>
      <div><strong>DocumentRoot</strong></div><div><code><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? '') ?></code></div>
      <div><strong>APP_URL</strong></div><div><?= htmlspecialchars($appUrl) ?></div>
      <div><strong>DB_HOST</strong></div><div><code><?= htmlspecialchars($dbHost) ?></code></div>
      <div><strong>DB Status</strong></div><div><?= $dbStatus ?></div>
    </div>
  </div>

  <div class="card">
    <h3>Mulai bangun aplikasi Anda</h3>
    <ol>
      <li>Replace file <code>index.php</code> ini dengan kode aplikasi Anda</li>
      <li>Push ke branch <code>main</code> → GitHub Actions otomatis build &amp; deploy</li>
      <li>Pakai env vars di App Settings → Environment Variables (auto-inject ke container)</li>
      <li>Persistence file (upload, cache): mount volume ke <code>/var/www/html/uploads</code></li>
    </ol>
    <p>Dokumentasi: <a href="https://flazhost.com/docs/paas">flazhost.com/docs/paas</a></p>
  </div>
</body>
</html>
