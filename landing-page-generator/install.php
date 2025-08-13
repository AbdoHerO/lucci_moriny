<?php
/**
 * Installation Script for Landing Page Generator
 * This script sets up the necessary directories and permissions
 */

$errors = [];
$success = [];

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0') < 0) {
    $errors[] = 'PHP 7.4 or higher is required. Current version: ' . PHP_VERSION;
}

// Check required extensions
$required_extensions = ['pdo', 'pdo_sqlite', 'zip', 'json'];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "Required PHP extension '{$ext}' is not loaded";
    }
}

// Create necessary directories
$directories = [
    'data',
    'generated',
    'uploads',
    'uploads/images'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0755, true)) {
            $success[] = "Created directory: {$dir}";
        } else {
            $errors[] = "Failed to create directory: {$dir}";
        }
    } else {
        $success[] = "Directory already exists: {$dir}";
    }
    
    // Check if directory is writable
    if (!is_writable($dir)) {
        $errors[] = "Directory is not writable: {$dir}";
    }
}

// Test database creation
try {
    $db_file = 'data/test.db';
    $pdo = new PDO("sqlite:$db_file");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE test (id INTEGER PRIMARY KEY)");
    $pdo->exec("DROP TABLE test");
    unlink($db_file);
    $success[] = "SQLite database test successful";
} catch (Exception $e) {
    $errors[] = "SQLite database test failed: " . $e->getMessage();
}

// Test ZIP functionality
try {
    $zip = new ZipArchive();
    $test_zip = 'data/test.zip';
    if ($zip->open($test_zip, ZipArchive::CREATE) === TRUE) {
        $zip->addFromString('test.txt', 'test content');
        $zip->close();
        unlink($test_zip);
        $success[] = "ZIP functionality test successful";
    } else {
        $errors[] = "ZIP functionality test failed";
    }
} catch (Exception $e) {
    $errors[] = "ZIP functionality test failed: " . $e->getMessage();
}

// Check if we can copy files from chemise_simple
$template_source = '../chemise_simple';
if (!file_exists($template_source)) {
    $errors[] = "Template source directory not found: {$template_source}";
} else {
    $success[] = "Template source directory found: {$template_source}";
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page Generator - Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap');
        * {
            font-family: 'Cairo', sans-serif !important;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .install-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            margin: 0 auto;
        }
        .install-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }
        .install-body {
            padding: 30px;
        }
        .status-item {
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            border-left: 4px solid;
        }
        .status-success {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .status-error {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        .btn-install {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-install:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-card">
            <div class="install-header">
                <h1><i class="fas fa-rocket"></i> Landing Page Generator</h1>
                <p class="mb-0">معالج التثبيت والإعداد</p>
            </div>
            
            <div class="install-body">
                <h3 class="mb-4">نتائج فحص النظام</h3>
                
                <?php if (!empty($success)): ?>
                    <h5 class="text-success"><i class="fas fa-check-circle"></i> العمليات الناجحة</h5>
                    <?php foreach ($success as $msg): ?>
                        <div class="status-item status-success">
                            <i class="fas fa-check"></i> <?= htmlspecialchars($msg) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                    <h5 class="text-danger mt-4"><i class="fas fa-exclamation-triangle"></i> المشاكل المكتشفة</h5>
                    <?php foreach ($errors as $error): ?>
                        <div class="status-item status-error">
                            <i class="fas fa-times"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <div class="mt-5">
                    <?php if (empty($errors)): ?>
                        <div class="alert alert-success">
                            <h5><i class="fas fa-thumbs-up"></i> التثبيت جاهز!</h5>
                            <p>جميع المتطلبات متوفرة ويمكنك الآن استخدام النظام.</p>
                        </div>
                        <div class="text-center">
                            <a href="index.php" class="btn btn-install btn-lg">
                                <i class="fas fa-arrow-right"></i> الانتقال إلى لوحة التحكم
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-exclamation-triangle"></i> يجب حل المشاكل أولاً</h5>
                            <p>يرجى حل المشاكل المذكورة أعلاه قبل المتابعة.</p>
                        </div>
                        <div class="text-center">
                            <button onclick="location.reload()" class="btn btn-install">
                                <i class="fas fa-redo"></i> إعادة الفحص
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-4">
                    <h5>معلومات النظام</h5>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>إصدار PHP:</strong></td>
                            <td><?= PHP_VERSION ?></td>
                        </tr>
                        <tr>
                            <td><strong>نظام التشغيل:</strong></td>
                            <td><?= PHP_OS ?></td>
                        </tr>
                        <tr>
                            <td><strong>الذاكرة المتاحة:</strong></td>
                            <td><?= ini_get('memory_limit') ?></td>
                        </tr>
                        <tr>
                            <td><strong>حد رفع الملفات:</strong></td>
                            <td><?= ini_get('upload_max_filesize') ?></td>
                        </tr>
                    </table>
                </div>
                
                <div class="mt-4">
                    <h5>الخطوات التالية</h5>
                    <ol>
                        <li>تأكد من أن جميع المشاكل محلولة</li>
                        <li>انتقل إلى لوحة التحكم</li>
                        <li>أنشئ صفحة هبوط جديدة</li>
                        <li>اختبر النظام مع بيانات تجريبية</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
