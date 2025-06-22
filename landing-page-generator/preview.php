<?php
session_start();

// Handle preview generation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/TemplateEngine.php';
    
    try {
        $config = $_POST;
        $templateEngine = new TemplateEngine($config);
        
        // Generate HTML content for preview
        $html = $templateEngine->generatePreviewHTML();
        
        // Store in session for display
        $_SESSION['preview_html'] = $html;
        $_SESSION['preview_config'] = $config;
        
        header('Location: preview.php?show=1');
        exit;
    } catch (Exception $e) {
        $error = 'Error generating preview: ' . $e->getMessage();
    }
}

// Show preview
if (isset($_GET['show']) && isset($_SESSION['preview_html'])) {
    echo $_SESSION['preview_html'];
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - Landing Page Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap');
        * {
            font-family: 'Cairo', sans-serif !important;
        }
        body {
            background: #f8f9fa;
        }
        .preview-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .preview-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .preview-frame {
            border: 1px solid #ddd;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .preview-iframe {
            width: 100%;
            height: 800px;
            border: none;
            border-radius: 10px;
        }
        .preview-controls {
            margin-bottom: 20px;
        }
        .btn-preview {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 5px;
        }
        .device-preview {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .device-frame {
            border: 2px solid #333;
            border-radius: 20px;
            padding: 20px;
            background: #000;
        }
        .device-desktop {
            width: 100%;
            max-width: 1200px;
        }
        .device-mobile {
            width: 375px;
            height: 667px;
        }
        .device-tablet {
            width: 768px;
            height: 1024px;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header">
            <h1><i class="fas fa-eye"></i> معاينة صفحة الهبوط</h1>
            <p class="mb-0">يمكنك مراجعة شكل الصفحة قبل التوليد النهائي</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <div class="preview-controls">
            <button class="btn btn-preview" onclick="setPreviewMode('desktop')">
                <i class="fas fa-desktop"></i> سطح المكتب
            </button>
            <button class="btn btn-preview" onclick="setPreviewMode('tablet')">
                <i class="fas fa-tablet-alt"></i> تابلت
            </button>
            <button class="btn btn-preview" onclick="setPreviewMode('mobile')">
                <i class="fas fa-mobile-alt"></i> موبايل
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> العودة للوحة التحكم
            </a>
        </div>
        
        <?php if (isset($_SESSION['preview_html'])): ?>
            <div class="preview-frame">
                <iframe id="preview-iframe" class="preview-iframe" src="preview.php?show=1"></iframe>
            </div>
            
            <div class="mt-3">
                <h5>معلومات المعاينة</h5>
                <div class="row">
                    <div class="col-md-6">
                        <strong>اسم المشروع:</strong> <?= htmlspecialchars($_SESSION['preview_config']['name'] ?? 'غير محدد') ?>
                    </div>
                    <div class="col-md-6">
                        <strong>عنوان الصفحة:</strong> <?= htmlspecialchars($_SESSION['preview_config']['title'] ?? 'غير محدد') ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <strong>نوع المنتج:</strong> 
                        <?php
                        $productType = $_SESSION['preview_config']['product_type'] ?? 'two_variants';
                        $types = [
                            'sample' => 'عينة',
                            'single_variant' => 'متغير واحد',
                            'two_variants' => 'متغيرين'
                        ];
                        echo $types[$productType] ?? $productType;
                        ?>
                    </div>
                    <div class="col-md-6">
                        <strong>السعر الأول:</strong> <?= htmlspecialchars($_SESSION['preview_config']['price_1'] ?? '229') ?> <?= htmlspecialchars($_SESSION['preview_config']['currency'] ?? 'د.م') ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> لا توجد معاينة متاحة. يرجى العودة إلى لوحة التحكم وإنشاء معاينة جديدة.
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function setPreviewMode(mode) {
            const iframe = document.getElementById('preview-iframe');
            const container = iframe.parentElement;
            
            // Reset classes
            container.className = 'preview-frame';
            
            switch(mode) {
                case 'desktop':
                    iframe.style.width = '100%';
                    iframe.style.height = '800px';
                    break;
                case 'tablet':
                    iframe.style.width = '768px';
                    iframe.style.height = '1024px';
                    container.style.margin = '0 auto';
                    break;
                case 'mobile':
                    iframe.style.width = '375px';
                    iframe.style.height = '667px';
                    container.style.margin = '0 auto';
                    break;
            }
        }
        
        // Auto-refresh iframe every 30 seconds to show any updates
        setInterval(function() {
            const iframe = document.getElementById('preview-iframe');
            if (iframe) {
                iframe.src = iframe.src;
            }
        }, 30000);
    </script>
</body>
</html>
