<?php
// Test script to verify landing page generation
require_once 'includes/TemplateEngine.php';

// Test configuration data
$testConfig = [
    'name' => 'test_landing_page',
    'title' => 'صفحة اختبار ديناميكية',
    'main_title' => 'تخفيض 50% على المنتج الجديد + توصيل مجاني',
    'order_text' => 'اطلب منتجك الآن واحصل على خصم حصري',
    'price_1' => '199',
    'price_2' => '349',
    'currency' => 'د.م',
    'product_type' => 'single_variant',
    'has_offer' => 'yes',
    'facebook_pixel' => '123456789012345',
    'tiktok_pixel' => 'ABC123DEF456GHI',
    'slider_images' => [
        'https://via.placeholder.com/800x600/FF6B6B/FFFFFF?text=صورة+المنتج+1',
        'https://via.placeholder.com/800x600/4ECDC4/FFFFFF?text=صورة+المنتج+2',
        'https://via.placeholder.com/800x600/45B7D1/FFFFFF?text=صورة+المنتج+3'
    ],
    'body_images' => [
        'https://via.placeholder.com/600x400/96CEB4/FFFFFF?text=فوائد+المنتج',
        'https://via.placeholder.com/600x400/FFEAA7/000000?text=طريقة+الاستخدام'
    ],
    'color_options' => ['أسود', 'أبيض', 'أزرق'],
    'color_values' => ['black', 'white', 'blue'],
    'reviews' => [
        ['name' => 'أميرة محمد', 'review' => 'منتج رائع، جودة عالية وتوصيل سريع. أنصح به بشدة!'],
        ['name' => 'كمال الدين', 'review' => 'تبارك الله عليكم، المنتج كما هو موضح في الصور والجودة ممتازة'],
        ['name' => 'فاطمة الزهراء', 'review' => 'طلبت واحد وصلني اليوم، زوين بزاف ومريح. شكرا لكم']
    ],
    'sheetdb_api' => 'demo_api_key',
    'sheetdb_token' => 'Bearer demo_token'
];

echo "<h1>🧪 Testing Landing Page Generation</h1>";
echo "<p>Testing with configuration:</p>";
echo "<pre>" . json_encode($testConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

try {
    // Create template engine instance
    $templateEngine = new TemplateEngine($testConfig);
    
    echo "<h2>✅ TemplateEngine created successfully</h2>";
    
    // Generate landing page
    $zipFile = $templateEngine->generateLandingPage();
    
    if ($zipFile) {
        echo "<h2>🎉 Landing page generated successfully!</h2>";
        echo "<p><strong>ZIP File:</strong> " . $zipFile . "</p>";
        echo "<p><strong>Project Directory:</strong> " . dirname(dirname(__FILE__)) . "/" . $testConfig['name'] . "</p>";
        
        // Check if files exist
        $projectDir = dirname(dirname(__FILE__)) . "/" . $testConfig['name'];
        if (file_exists($projectDir)) {
            echo "<h3>📁 Generated Files:</h3>";
            echo "<ul>";
            
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($projectDir, RecursiveDirectoryIterator::SKIP_DOTS)
            );
            
            foreach ($files as $file) {
                $relativePath = str_replace($projectDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                echo "<li>" . $relativePath . "</li>";
            }
            echo "</ul>";
            
            // Check if index.html exists and show preview link
            if (file_exists($projectDir . '/index.html')) {
                $relativePath = str_replace(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR, '', $projectDir);
                echo "<h3>🌐 Preview Links:</h3>";
                echo "<p><a href='../" . $relativePath . "/index.html' target='_blank'>View Generated Landing Page</a></p>";
                echo "<p><a href='../" . $relativePath . "/' target='_blank'>Browse Project Directory</a></p>";
            }
        }
        
        // Check if ZIP file exists
        if (file_exists($zipFile)) {
            $zipRelativePath = str_replace(dirname(__FILE__) . DIRECTORY_SEPARATOR, '', $zipFile);
            echo "<p><a href='" . $zipRelativePath . "' download>Download ZIP File</a></p>";
        }
        
    } else {
        echo "<h2>❌ Failed to generate landing page</h2>";
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Error occurred:</h2>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h2>🔧 System Information:</h2>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . PHP_VERSION . "</li>";
echo "<li><strong>Current Directory:</strong> " . __DIR__ . "</li>";
echo "<li><strong>Template Directory:</strong> " . (file_exists('templates') ? '✅ Exists' : '❌ Missing') . "</li>";
echo "<li><strong>Chemise Simple Directory:</strong> " . (file_exists('../chemise_simple') ? '✅ Exists' : '❌ Missing') . "</li>";
echo "<li><strong>ZipArchive Extension:</strong> " . (class_exists('ZipArchive') ? '✅ Available' : '❌ Missing') . "</li>";
echo "</ul>";

?>
