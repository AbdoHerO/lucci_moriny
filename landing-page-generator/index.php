<?php
session_start();

// Database configuration
$db_file = 'data/landing_pages.db';

// Create data directory if it doesn't exist
if (!file_exists('data')) {
    mkdir('data', 0755, true);
}

// Initialize SQLite database
function initDatabase() {
    global $db_file;
    
    if (!file_exists($db_file)) {
        $pdo = new PDO("sqlite:$db_file");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create landing pages table
        $pdo->exec("
            CREATE TABLE landing_pages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                title VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                config TEXT NOT NULL
            )
        ");
        
        // Create reviews table
        $pdo->exec("
            CREATE TABLE reviews (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                review TEXT NOT NULL,
                profile_image VARCHAR(255),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Insert some default reviews
        $default_reviews = [
            ['أميرة', 'طلبت واحد وصلني اليوم زوين بزاف و مريح', 'profile1.png'],
            ['Mohammed Ka', 'قميجة رائع، كيجي سواء مع الكلاس او سبور، كنمشي بيه الخدمة حيت مريح بزاف', 'profile2.jpeg'],
            ['Kamal', 'تبار الله عليكم منتوج في المستوى، الجوة عالية، التوصيل سريع. شكرا لكم', 'profile3.jpeg'],
            ['فاطمة', 'المنتج ممتاز والجودة عالية، أنصح به بشدة', 'profile4.png'],
            ['أحمد', 'التوصيل سريع والمنتج كما هو موضح في الصور', 'profile1.png']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO reviews (name, review, profile_image) VALUES (?, ?, ?)");
        foreach ($default_reviews as $review) {
            $stmt->execute($review);
        }
    }
}

// Initialize database
initDatabase();

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'save_landing_page':
            saveLandingPage();
            break;
        case 'get_reviews':
            getReviews();
            break;
        case 'generate_landing_page':
            generateLandingPage();
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    exit;
}

function saveLandingPage() {
    global $db_file;
    
    try {
        $pdo = new PDO("sqlite:$db_file");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $config = json_encode($_POST);
        
        if (isset($_POST['landing_page_id']) && !empty($_POST['landing_page_id'])) {
            // Update existing
            $stmt = $pdo->prepare("UPDATE landing_pages SET name = ?, title = ?, config = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$_POST['name'], $_POST['title'], $config, $_POST['landing_page_id']]);
        } else {
            // Create new
            $stmt = $pdo->prepare("INSERT INTO landing_pages (name, title, config) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['title'], $config]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Landing page saved successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error saving landing page: ' . $e->getMessage()]);
    }
}

function getReviews() {
    global $db_file;
    
    try {
        $pdo = new PDO("sqlite:$db_file");
        $stmt = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC");
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'reviews' => $reviews]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching reviews: ' . $e->getMessage()]);
    }
}

function generateLandingPage() {
    require_once 'includes/TemplateEngine.php';

    try {
        $config = $_POST;
        $templateEngine = new TemplateEngine($config);
        $zipFile = $templateEngine->generateLandingPage();

        if ($zipFile && file_exists($zipFile)) {
            // Return download link
            $downloadUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', $zipFile);
            echo json_encode([
                'success' => true,
                'message' => 'Landing page generated successfully',
                'download_url' => $downloadUrl
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to generate ZIP file']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error generating landing page: ' . $e->getMessage()]);
    }
}

// Get saved landing pages for dropdown
function getSavedLandingPages() {
    global $db_file;
    
    try {
        $pdo = new PDO("sqlite:$db_file");
        $stmt = $pdo->query("SELECT id, name, title, created_at FROM landing_pages ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

$saved_pages = getSavedLandingPages();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page Generator Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <div class="position-sticky pt-3">
                    <h4 class="text-white text-center mb-4">
                        <i class="fas fa-rocket"></i> Landing Generator
                    </h4>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="#" data-section="new-landing">
                                <i class="fas fa-plus"></i> صفحة جديدة
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" data-section="saved-pages">
                                <i class="fas fa-list"></i> الصفحات المحفوظة
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" data-section="reviews-manager">
                                <i class="fas fa-star"></i> إدارة التقييمات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" data-section="templates">
                                <i class="fas fa-file-code"></i> القوالب
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">لوحة تحكم مولد صفحات الهبوط</h1>
                </div>

                <!-- New Landing Page Section -->
                <div id="new-landing" class="content-section">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-plus"></i> إنشاء صفحة هبوط جديدة</h3>
                        </div>
                        <div class="card-body">
                            <form id="landing-page-form">
                                <!-- Basic Information -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">اسم المشروع</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">عنوان الصفحة</label>
                                        <input type="text" class="form-control" name="title" required>
                                    </div>
                                </div>

                                <!-- Load Saved Page -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">تحميل صفحة محفوظة</label>
                                        <select class="form-control" id="load-saved-page">
                                            <option value="">اختر صفحة محفوظة...</option>
                                            <?php foreach ($saved_pages as $page): ?>
                                                <option value="<?= $page['id'] ?>"><?= htmlspecialchars($page['name']) ?> - <?= $page['title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Navigation Tabs -->
                                <ul class="nav nav-tabs" id="configTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button">المحتوى والأسعار</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pixels-tab" data-bs-toggle="tab" data-bs-target="#pixels" type="button">البكسل</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button">الصور</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="form-tab" data-bs-toggle="tab" data-bs-target="#form-config" type="button">النموذج</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">التقييمات</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="api-tab" data-bs-toggle="tab" data-bs-target="#api-config" type="button">API</button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3" id="configTabsContent">
                                    <!-- Content and Prices Tab -->
                                    <div class="tab-pane fade show active" id="content" role="tabpanel">
                                        <h5>المحتوى والأسعار</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">العنوان الرئيسي</label>
                                                <input type="text" class="form-control" name="main_title" value="تخفيض 50% و التوصيل مجاني و سريع">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">نص الطلب</label>
                                                <input type="text" class="form-control" name="order_text" value="للطلب المرجو ملٱ الإستمارة اسفله">
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label class="form-label">السعر الأول</label>
                                                <input type="number" class="form-control" name="price_1" value="229">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">السعر الثاني</label>
                                                <input type="number" class="form-control" name="price_2" value="399">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">العملة</label>
                                                <input type="text" class="form-control" name="currency" value="د.م">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pixels Tab -->
                                    <div class="tab-pane fade" id="pixels" role="tabpanel">
                                        <h5>إعدادات البكسل</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Facebook Pixel ID</label>
                                                <input type="text" class="form-control" name="facebook_pixel" placeholder="303561535711187">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">TikTok Pixel ID</label>
                                                <input type="text" class="form-control" name="tiktok_pixel" placeholder="CM08HVJC77U7MRPGKD5G">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Images Tab -->
                                    <div class="tab-pane fade" id="images" role="tabpanel">
                                        <h5>إدارة الصور</h5>
                                        <div id="slider-images">
                                            <h6>صور السلايدر</h6>
                                            <div id="slider-images-container">
                                                <!-- Dynamic slider images will be added here -->
                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="addSliderImage()">
                                                <i class="fas fa-plus"></i> إضافة صورة
                                            </button>
                                        </div>
                                        
                                        <div class="mt-4" id="body-images">
                                            <h6>صور المحتوى</h6>
                                            <div id="body-images-container">
                                                <!-- Dynamic body images will be added here -->
                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="addBodyImage()">
                                                <i class="fas fa-plus"></i> إضافة صورة
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Form Configuration Tab -->
                                    <div class="tab-pane fade" id="form-config" role="tabpanel">
                                        <h5>إعدادات النموذج</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">نوع المنتج</label>
                                                <select class="form-control" name="product_type" onchange="updateFormFields()">
                                                    <option value="sample">عينة (اسم، عنوان، هاتف، عرض)</option>
                                                    <option value="single_variant">متغير واحد (لون)</option>
                                                    <option value="two_variants">متغيرين (لون وحجم)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">يحتوي على عرض/هدية</label>
                                                <select class="form-control" name="has_offer">
                                                    <option value="yes">نعم</option>
                                                    <option value="no">لا</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div id="form-fields-config" class="mt-3">
                                            <!-- Dynamic form fields configuration will be added here -->
                                        </div>
                                    </div>

                                    <!-- Reviews Tab -->
                                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                                        <h5>التقييمات والمراجعات</h5>
                                        <div id="selected-reviews">
                                            <!-- Selected reviews will be displayed here -->
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="openReviewsModal()">
                                            <i class="fas fa-star"></i> اختيار التقييمات
                                        </button>
                                    </div>

                                    <!-- API Configuration Tab -->
                                    <div class="tab-pane fade" id="api-config" role="tabpanel">
                                        <h5>إعدادات API</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">SheetDB API Key</label>
                                                <input type="text" class="form-control" name="sheetdb_api" placeholder="fh6013f2bw0nq">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">SheetDB Authorization Token</label>
                                                <input type="text" class="form-control" name="sheetdb_token" placeholder="Bearer token">
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <label class="form-label">تنسيق البيانات المخصص (JSON)</label>
                                            <textarea class="form-control" name="custom_data_format" rows="10" placeholder='{"name": "product_name", "date": "current_date", ...}'></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="button" class="btn btn-success" onclick="saveLandingPage()">
                                        <i class="fas fa-save"></i> حفظ الإعدادات
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="previewLandingPage()">
                                        <i class="fas fa-eye"></i> معاينة
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="generateLandingPage()">
                                        <i class="fas fa-download"></i> توليد وتحميل
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Other sections will be added here -->
                <div id="saved-pages" class="content-section" style="display: none;">
                    <h3>الصفحات المحفوظة</h3>
                    <!-- Saved pages content -->
                </div>

                <div id="reviews-manager" class="content-section" style="display: none;">
                    <h3>إدارة التقييمات</h3>
                    <!-- Reviews manager content -->
                </div>

                <div id="templates" class="content-section" style="display: none;">
                    <h3>القوالب</h3>
                    <!-- Templates content -->
                </div>
            </main>
        </div>
    </div>

    <!-- Reviews Selection Modal -->
    <div class="modal fade" id="reviewsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">اختيار التقييمات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="available-reviews">
                        <!-- Available reviews will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" onclick="selectReviews()">اختيار</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/dashboard.js"></script>
</body>
</html>
