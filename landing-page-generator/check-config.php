<?php
/**
 * Configuration Checker for Landing Page Generator
 * This script helps diagnose common setup issues
 */

header('Content-Type: text/html; charset=utf-8');

function checkStatus($condition, $message) {
    $icon = $condition ? '✅' : '❌';
    $class = $condition ? 'success' : 'error';
    echo "<div class='status-item {$class}'>{$icon} {$message}</div>";
    return $condition;
}

function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

$allGood = true;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration Checker</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap');
        * { font-family: 'Cairo', sans-serif; }
        body { background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .status-item { padding: 10px; margin: 5px 0; border-radius: 5px; border-left: 4px solid; }
        .success { background: #d4edda; border-color: #28a745; color: #155724; }
        .error { background: #f8d7da; border-color: #dc3545; color: #721c24; }
        .warning { background: #fff3cd; border-color: #ffc107; color: #856404; }
        .info { background: #d1ecf1; border-color: #17a2b8; color: #0c5460; }
        .section { margin: 20px 0; }
        .section h3 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: 600; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Configuration Checker</h1>
            <p>فحص شامل لإعدادات النظام</p>
        </div>

        <div class="section">
            <h3>📋 System Requirements</h3>
            <?php
            $allGood &= checkStatus(version_compare(PHP_VERSION, '7.4.0') >= 0, 'PHP Version: ' . PHP_VERSION . ' (Required: 7.4+)');
            $allGood &= checkStatus(extension_loaded('pdo'), 'PDO Extension');
            $allGood &= checkStatus(extension_loaded('pdo_sqlite'), 'SQLite Extension');
            $allGood &= checkStatus(extension_loaded('zip'), 'ZIP Extension');
            $allGood &= checkStatus(extension_loaded('json'), 'JSON Extension');
            $allGood &= checkStatus(function_exists('file_get_contents'), 'File Functions');
            $allGood &= checkStatus(function_exists('curl_init'), 'cURL Extension');
            ?>
        </div>

        <div class="section">
            <h3>📁 Directory Structure</h3>
            <?php
            $dirs = [
                'data' => 'Database Storage',
                'generated' => 'Generated Files',
                'uploads' => 'File Uploads',
                'uploads/images' => 'Image Uploads',
                'templates' => 'Templates',
                'includes' => 'Core Files',
                'assets' => 'Assets'
            ];

            foreach ($dirs as $dir => $desc) {
                $exists = file_exists($dir);
                $writable = $exists ? is_writable($dir) : false;
                
                if (!$exists) {
                    @mkdir($dir, 0755, true);
                    $exists = file_exists($dir);
                    $writable = $exists ? is_writable($dir) : false;
                }
                
                $status = $exists && $writable;
                $message = "{$desc} ({$dir})";
                if ($exists && !$writable) {
                    $message .= " - Directory exists but not writable";
                } elseif (!$exists) {
                    $message .= " - Directory missing";
                }
                
                $allGood &= checkStatus($status, $message);
            }
            ?>
        </div>

        <div class="section">
            <h3>🗂️ Required Files</h3>
            <?php
            $files = [
                'index.php' => 'Main Dashboard',
                'install.php' => 'Installation Script',
                'preview.php' => 'Preview System',
                'includes/TemplateEngine.php' => 'Template Engine',
                'templates/landing-page-template.html' => 'HTML Template',
                'templates/api-template.js' => 'API Template',
                'assets/css/dashboard.css' => 'Dashboard Styles',
                'assets/js/dashboard.js' => 'Dashboard Scripts'
            ];

            foreach ($files as $file => $desc) {
                $exists = file_exists($file);
                $readable = $exists ? is_readable($file) : false;
                $status = $exists && $readable;
                $allGood &= checkStatus($status, "{$desc} ({$file})");
            }
            ?>
        </div>

        <div class="section">
            <h3>🎯 Template Source</h3>
            <?php
            $templatePaths = [
                '../chemise_simple' => 'Relative Path (../chemise_simple)',
                dirname(__DIR__) . '/chemise_simple' => 'Absolute Path',
                '../../chemise_simple' => 'Alternative Path (../../chemise_simple)'
            ];

            $templateFound = false;
            foreach ($templatePaths as $path => $desc) {
                $exists = file_exists($path);
                if ($exists) {
                    $templateFound = true;
                    checkStatus(true, "Template found: {$desc}");
                    
                    // Check key template files
                    $templateFiles = ['index.html', 'css', 'js', 'images'];
                    foreach ($templateFiles as $tf) {
                        $tfPath = $path . '/' . $tf;
                        checkStatus(file_exists($tfPath), "Template {$tf}: {$tfPath}");
                    }
                    break;
                }
            }
            
            if (!$templateFound) {
                $allGood = false;
                checkStatus(false, "Template source (chemise_simple) not found in any expected location");
            }
            ?>
        </div>

        <div class="section">
            <h3>🗄️ Database Test</h3>
            <?php
            try {
                $testDb = 'data/test_' . time() . '.db';
                $pdo = new PDO("sqlite:{$testDb}");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->exec("CREATE TABLE test (id INTEGER PRIMARY KEY, name TEXT)");
                $pdo->exec("INSERT INTO test (name) VALUES ('test')");
                $result = $pdo->query("SELECT COUNT(*) FROM test")->fetchColumn();
                $pdo->exec("DROP TABLE test");
                unlink($testDb);
                
                checkStatus($result == 1, "SQLite Database Test - Create, Insert, Select, Delete");
            } catch (Exception $e) {
                $allGood = false;
                checkStatus(false, "Database Test Failed: " . $e->getMessage());
            }
            ?>
        </div>

        <div class="section">
            <h3>📦 ZIP Test</h3>
            <?php
            try {
                $testZip = 'data/test_' . time() . '.zip';
                $zip = new ZipArchive();
                if ($zip->open($testZip, ZipArchive::CREATE) === TRUE) {
                    $zip->addFromString('test.txt', 'Test content');
                    $zip->close();
                    $size = filesize($testZip);
                    unlink($testZip);
                    checkStatus($size > 0, "ZIP Creation Test - Created ZIP file ({$size} bytes)");
                } else {
                    $allGood = false;
                    checkStatus(false, "ZIP Creation Failed");
                }
            } catch (Exception $e) {
                $allGood = false;
                checkStatus(false, "ZIP Test Failed: " . $e->getMessage());
            }
            ?>
        </div>

        <div class="section">
            <h3>📊 System Information</h3>
            <table>
                <tr><th>Setting</th><th>Value</th></tr>
                <tr><td>PHP Version</td><td><?= PHP_VERSION ?></td></tr>
                <tr><td>Operating System</td><td><?= PHP_OS ?></td></tr>
                <tr><td>Server Software</td><td><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></td></tr>
                <tr><td>Memory Limit</td><td><?= ini_get('memory_limit') ?></td></tr>
                <tr><td>Max Execution Time</td><td><?= ini_get('max_execution_time') ?> seconds</td></tr>
                <tr><td>Upload Max Size</td><td><?= ini_get('upload_max_filesize') ?></td></tr>
                <tr><td>Post Max Size</td><td><?= ini_get('post_max_size') ?></td></tr>
                <tr><td>Max File Uploads</td><td><?= ini_get('max_file_uploads') ?></td></tr>
                <tr><td>Document Root</td><td><?= $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' ?></td></tr>
                <tr><td>Current Directory</td><td><?= __DIR__ ?></td></tr>
            </table>
        </div>

        <div class="section">
            <h3>🎯 Overall Status</h3>
            <?php if ($allGood): ?>
                <div class="status-item success">
                    🎉 <strong>All checks passed!</strong> Your system is ready to run the Landing Page Generator.
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="install.php" class="btn">Continue to Installation</a>
                    <a href="index.php" class="btn">Go to Dashboard</a>
                </div>
            <?php else: ?>
                <div class="status-item error">
                    ⚠️ <strong>Issues detected!</strong> Please fix the errors above before proceeding.
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="check-config.php" class="btn">Recheck Configuration</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="section">
            <h3>📚 Quick Actions</h3>
            <div style="text-align: center;">
                <a href="QUICK-START.md" class="btn">📖 Quick Start Guide</a>
                <a href="README.md" class="btn">📋 Full Documentation</a>
                <a href="setup-guide.md" class="btn">🔧 Setup Guide</a>
            </div>
        </div>
    </div>
</body>
</html>
