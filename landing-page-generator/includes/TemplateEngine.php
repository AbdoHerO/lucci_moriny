<?php

class TemplateEngine {
    private $config;
    private $outputDir;
    
    public function __construct($config, $outputDir = null) {
        $this->config = $config;

        // Generate in root path (same level as chemise_simple)
        if ($outputDir === null) {
            $this->outputDir = dirname(__DIR__, 2); // Go up to lucci-moriny-main root
        } else {
            $this->outputDir = $outputDir;
        }
    }
    
    public function generateLandingPage() {
        $projectName = $this->config['name'];
        // Clean project name for directory
        $projectName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $projectName);
        $projectDir = $this->outputDir . '/' . $projectName;

        // Create project directory
        if (!file_exists($projectDir)) {
            mkdir($projectDir, 0755, true);
        }

        // Generate files
        $this->generateHTML($projectDir);
        $this->copyAssets($projectDir);
        $this->generateAPIFile($projectDir);

        // Create ZIP file
        return $this->createZipFile($projectDir, $projectName);
    }

    public function generatePreviewHTML() {
        $html = $this->getHTMLTemplate();

        // Replace placeholders with preview data
        $replacements = [
            '{{TITLE}}' => $this->config['title'],
            '{{MAIN_TITLE}}' => $this->config['main_title'] ?? 'تخفيض 50% و التوصيل مجاني و سريع',
            '{{ORDER_TEXT}}' => $this->config['order_text'] ?? 'للطلب المرجو ملٱ الإستمارة اسفله',
            '{{FACEBOOK_PIXEL}}' => 'PREVIEW_FB_PIXEL',
            '{{TIKTOK_PIXEL}}' => 'PREVIEW_TT_PIXEL',
            '{{PRICE_1}}' => $this->config['price_1'] ?? '229',
            '{{PRICE_2}}' => $this->config['price_2'] ?? '399',
            '{{CURRENCY}}' => $this->config['currency'] ?? 'د.م',
            '{{SLIDER_IMAGES}}' => $this->generateSliderImages(),
            '{{SLIDER_DOTS}}' => $this->generateSliderDots(),
            '{{SLIDER_SCRIPT}}' => $this->generateSliderScript(),
            '{{BODY_IMAGES}}' => $this->generateBodyImages(),
            '{{FORM_FIELDS}}' => $this->generateFormFields(),
            '{{FORM_VALIDATION_SCRIPT}}' => $this->generateFormValidationScript(),
            '{{REVIEWS}}' => $this->generateReviews(),
            '{{PRODUCT_NAME}}' => $this->config['name'],
            '{{PRODUCT_ID}}' => 'PREVIEW_PRODUCT_ID',
            '{{OFFER_TEXT}}' => $this->generateOfferText(),
            '{{PRICE_UPDATE_SCRIPT}}' => $this->generatePriceUpdateScript(),
            '{{GUARANTEES_SECTION}}' => $this->generateGuaranteesSection(),
            '{{FAQ_SECTION}}' => $this->generateFAQSection()
        ];

        foreach ($replacements as $placeholder => $value) {
            $html = str_replace($placeholder, $value, $html);
        }

        // Add preview-specific styles and scripts
        $html = str_replace('</head>', $this->getPreviewStyles() . '</head>', $html);
        $html = str_replace('</body>', $this->getPreviewScripts() . '</body>', $html);

        return $html;
    }

    private function getPreviewStyles() {
        return '
        <style>
            /* Preview-specific styles */
            .preview-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: rgba(255, 193, 7, 0.9);
                color: #000;
                text-align: center;
                padding: 10px;
                z-index: 9999;
                font-weight: bold;
            }

            body {
                padding-top: 50px;
            }

            /* Disable form submission in preview */
            #formInfo {
                pointer-events: none;
                opacity: 0.8;
            }

            #formInfo::after {
                content: "معاينة فقط - النموذج غير فعال";
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(255, 193, 7, 0.9);
                padding: 10px 20px;
                border-radius: 5px;
                font-weight: bold;
                z-index: 10;
            }

            #formInfo {
                position: relative;
            }
        </style>
        ';
    }

    private function getPreviewScripts() {
        return '
        <script>
            // Add preview overlay
            document.addEventListener("DOMContentLoaded", function() {
                const overlay = document.createElement("div");
                overlay.className = "preview-overlay";
                overlay.innerHTML = "🔍 معاينة صفحة الهبوط - هذه ليست الصفحة النهائية";
                document.body.insertBefore(overlay, document.body.firstChild);

                // Disable all form submissions
                document.querySelectorAll("form").forEach(function(form) {
                    form.addEventListener("submit", function(e) {
                        e.preventDefault();
                        alert("هذه معاينة فقط. لا يمكن إرسال النماذج.");
                    });
                });

                // Replace images with placeholders if they don\'t exist
                document.querySelectorAll("img").forEach(function(img) {
                    img.addEventListener("error", function() {
                        this.src = "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxOCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPtmF2LnYp9mK2YbYqSDYtdmI2LHYqTwvdGV4dD48L3N2Zz4=";
                    });
                });
            });
        </script>
        ';
    }
    
    private function generateHTML($projectDir) {
        $html = $this->getHTMLTemplate();
        
        // Replace placeholders
        $replacements = [
            '{{TITLE}}' => $this->config['title'],
            '{{MAIN_TITLE}}' => $this->config['main_title'] ?? 'تخفيض 50% و التوصيل مجاني و سريع',
            '{{ORDER_TEXT}}' => $this->config['order_text'] ?? 'للطلب المرجو ملٱ الإستمارة اسفله',
            '{{FACEBOOK_PIXEL}}' => $this->config['facebook_pixel'] ?? '',
            '{{TIKTOK_PIXEL}}' => $this->config['tiktok_pixel'] ?? '',
            '{{PRICE_1}}' => $this->config['price_1'] ?? '229',
            '{{PRICE_2}}' => $this->config['price_2'] ?? '399',
            '{{CURRENCY}}' => $this->config['currency'] ?? 'د.م',
            '{{SLIDER_IMAGES}}' => $this->generateSliderImages(),
            '{{SLIDER_DOTS}}' => $this->generateSliderDots(),
            '{{SLIDER_SCRIPT}}' => $this->generateSliderScript(),
            '{{BODY_IMAGES}}' => $this->generateBodyImages(),
            '{{FORM_FIELDS}}' => $this->generateFormFields(),
            '{{FORM_VALIDATION_SCRIPT}}' => $this->generateFormValidationScript(),
            '{{REVIEWS}}' => $this->generateReviews(),
            '{{PRODUCT_NAME}}' => $this->config['name'],
            '{{PRODUCT_ID}}' => uniqid(),
            '{{OFFER_TEXT}}' => $this->generateOfferText(),
            '{{PRICE_UPDATE_SCRIPT}}' => $this->generatePriceUpdateScript(),
            '{{GUARANTEES_SECTION}}' => $this->generateGuaranteesSection(),
            '{{FAQ_SECTION}}' => $this->generateFAQSection()
        ];
        
        foreach ($replacements as $placeholder => $value) {
            $html = str_replace($placeholder, $value, $html);
        }
        
        file_put_contents($projectDir . '/index.html', $html);
    }
    
    private function generateCSS($projectDir) {
        // Copy base CSS files
        $cssDir = $projectDir . '/css';
        if (!file_exists($cssDir)) {
            mkdir($cssDir, 0755, true);
        }
        
        // Copy CSS files from template
        $templateCssDir = dirname(__DIR__, 2) . '/chemise_simple/css';
        if (file_exists($templateCssDir)) {
            $this->copyDirectory($templateCssDir, $cssDir);
        }
    }
    
    private function generateJS($projectDir) {
        $jsDir = $projectDir . '/js';
        if (!file_exists($jsDir)) {
            mkdir($jsDir, 0755, true);
        }
        
        // Copy JS files from template
        $templateJsDir = dirname(__DIR__, 2) . '/chemise_simple/js';
        if (file_exists($templateJsDir)) {
            $this->copyDirectory($templateJsDir, $jsDir);
        }
        
        // Generate API file
        $this->generateAPIFile($projectDir);
    }
    
    private function generateAPIFile($projectDir) {
        $apiTemplate = $this->getAPITemplate();

        $replacements = [
            '{{PRODUCT_NAME}}' => $this->config['name'],
            '{{PRODUCT_ID}}' => uniqid(),
            '{{SHEETDB_API}}' => $this->config['sheetdb_api'] ?? 'fh6013f2bw0nq',
            '{{SHEETDB_TOKEN}}' => $this->config['sheetdb_token'] ?? 'Bearer pzp30odfswbvbrfuo8idrfvddn51e36q8zmtwilh',
            '{{CUSTOM_DATA_FORMAT}}' => $this->generateCustomDataFormat(),
            '{{SUCCESS_URL}}' => "order_success.html"
        ];

        foreach ($replacements as $placeholder => $value) {
            $apiTemplate = str_replace($placeholder, $value, $apiTemplate);
        }

        file_put_contents($projectDir . '/api_cozmo_landing_page.js', $apiTemplate);
    }
    
    private function generateSliderImages() {
        $images = $this->config['slider_images'] ?? [];
        $html = '';

        foreach ($images as $index => $image) {
            $slideNumber = $index + 1;
            $totalSlides = count($images);

            $html .= "
                <div class=\"mySlides fade\">
                    <div class=\"numbertext\">{$slideNumber} / {$totalSlides}</div>
                    <img src=\"{$image}\" style=\"width:100%\">
                </div>
            ";
        }

        return $html;
    }

    private function generateSliderDots() {
        $images = $this->config['slider_images'] ?? [];
        $dotsHtml = '';

        for ($i = 0; $i < count($images); $i++) {
            $slideNumber = $i + 1;
            $dotsHtml .= "<span class=\"dot\" onclick=\"currentSlide({$slideNumber})\"></span>\n";
        }

        return $dotsHtml;
    }

    private function generateSliderScript() {
        return '
        <script>
            let slideIndex = 1;
            showSlides(slideIndex);

            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            function currentSlide(n) {
                showSlides(slideIndex = n);
            }

            function showSlides(n) {
                let i;
                let slides = document.getElementsByClassName("mySlides");
                let dots = document.getElementsByClassName("dot");
                if (n > slides.length) { slideIndex = 1 }
                if (n < 1) { slideIndex = slides.length }
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                for (i = 0; i < dots.length; i++) {
                    dots[i].className = dots[i].className.replace(" active", "");
                }
                slides[slideIndex - 1].style.display = "block";
                dots[slideIndex - 1].className += " active";
            }

            // Auto-scroll functionality
            let autoScrollInterval;

            function startAutoScroll() {
                autoScrollInterval = setInterval(function () {
                    plusSlides(1);
                }, 3000);
            }

            function stopAutoScroll() {
                clearInterval(autoScrollInterval);
            }

            // Touch swipe functionality
            let touchStartX;

            document.addEventListener("touchstart", function (e) {
                touchStartX = e.touches[0].clientX;
                stopAutoScroll();
            });

            document.addEventListener("touchend", function (e) {
                let touchEndX = e.changedTouches[0].clientX;
                let swipeDistance = touchEndX - touchStartX;

                if (swipeDistance > 50) {
                    plusSlides(-1);
                } else if (swipeDistance < -50) {
                    plusSlides(1);
                }

                startAutoScroll();
            });

            // Start auto-scroll on page load
            startAutoScroll();
        </script>
        ';
    }
    
    private function generateBodyImages() {
        $images = $this->config['body_images'] ?? [];
        $html = '';
        
        foreach ($images as $image) {
            $html .= "<img src=\"{$image}\" alt=\"\">\n";
        }
        
        return $html;
    }
    
    private function generateFormFields() {
        $productType = $this->config['product_type'] ?? 'two_variants';
        $hasOffer = $this->config['has_offer'] ?? 'yes';
        
        $html = '';
        
        // Offer/Quantity field
        if ($hasOffer === 'yes') {
            $html .= '
                <div class="form-group is-required">
                    <label class="form-label">عدد الطبقات</label>
                    <select required name="tier_variante" id="tier_variante">
                        <option value="">المرجو إختيار الكمية</option>
                        <option value="1">واحد ب ' . ($this->config['price_1'] ?? '229') . ' درهم فقط</option>
                        <option value="2">إثنان ب ' . ($this->config['price_2'] ?? '399') . ' درهم فقط</option>
                    </select>
                </div>
            ';
        }
        
        // Color field for single_variant and two_variants
        if ($productType === 'single_variant' || $productType === 'two_variants') {
            $colorOptions = $this->config['color_options'] ?? ['أسود', 'أبيض'];
            $colorValues = $this->config['color_values'] ?? ['dark', 'white'];
            
            $html .= '
                <div class="form-group is-required">
                    <label class="form-label">لون المنتج</label>
                    <select required name="product_color" id="product_color">
                        <option value="">المرجو إختيار لون المنتج</option>
            ';
            
            foreach ($colorOptions as $index => $option) {
                $value = $colorValues[$index] ?? $option;
                $html .= "<option value=\"{$value}\">{$option}</option>\n";
            }
            
            $html .= '
                    </select>
                </div>
            ';
        }
        
        // Size field for two_variants
        if ($productType === 'two_variants') {
            $sizeOptions = $this->config['size_options'] ?? ['S', 'M', 'L', 'XL'];
            $sizeValues = $this->config['size_values'] ?? ['S', 'M', 'L', 'XL'];
            
            $html .= '
                <div class="form-group is-required">
                    <label class="form-label">الحجم</label>
                    <select required name="product_size" id="product_size">
                        <option value="">المرجو إختيار الحجم</option>
            ';
            
            foreach ($sizeOptions as $index => $option) {
                $value = $sizeValues[$index] ?? $option;
                $html .= "<option value=\"{$value}\">{$option}</option>\n";
            }
            
            $html .= '
                    </select>
                </div>
            ';
        }
        
        // Standard fields (name, phone, address)
        $html .= '
            <input type="hidden" name="number_tier" id="number_tier" value="1">
            <input type="hidden" name="price_tiers" id="price_tiers" value="">
            
            <div class="form-group is-required">
                <label class="form-label">الإسم الكامل</label>
                <input required type="text" name="fullname" placeholder="الأسم الكامل">
            </div>
            
            <div class="form-group is-required">
                <label class="form-label">رقم الهاتف</label>
                <input required type="number" name="phone" placeholder="رقم الهاتف">
            </div>
            
            <div class="form-group is-required">
                <label class="form-label">العنوان</label>
                <input required type="text" name="adresse" placeholder="العنوان">
            </div>
        ';
        
        return $html;
    }

    private function generateFormValidationScript() {
        $productType = $this->config['product_type'] ?? 'two_variants';

        if ($productType === 'two_variants') {
            return '
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var colorSelect = document.getElementById("product_color");
                    var sizeSelect = document.getElementById("product_size");
                    var orderButton = document.getElementById("save_guest_order");

                    function checkAvailability() {
                        var selectedColor = colorSelect.value;
                        var selectedSize = sizeSelect.value;

                        // Add custom availability logic here
                        orderButton.disabled = false;
                    }

                    checkAvailability();
                    colorSelect.addEventListener("change", checkAvailability);
                    sizeSelect.addEventListener("change", checkAvailability);
                });
            </script>
            ';
        }

        return '';
    }

    private function generateOfferText() {
        $price1 = $this->config['price_1'] ?? '229';
        $price2 = $this->config['price_2'] ?? '399';
        $currency = $this->config['currency'] ?? 'درهم';

        return "قطعة واحدة بسعر <span class=\"price_hot_sale_1\">{$price1} {$currency}</span> و قطعتين بسعر <span class=\"price_hot_sale_1\">{$price2} {$currency}</span>";
    }

    private function generatePriceUpdateScript() {
        $price1 = $this->config['price_1'] ?? '229';
        $price2 = $this->config['price_2'] ?? '399';

        return '
        <script>
            var price_tiers = document.getElementById("price_tiers");
            var selectElement = document.getElementById("tier_variante");
            var currencyElement = document.getElementById("price_displayed");

            if (selectElement) {
                selectElement.addEventListener("change", function () {
                    var selectedValue = selectElement.value;

                    if (selectedValue === "1") {
                        price_tiers.value = "' . $price1 . '";
                        currencyElement.innerText = "' . $price1 . '";
                    } else if (selectedValue === "2") {
                        price_tiers.value = "' . $price2 . '";
                        currencyElement.innerText = "' . $price2 . '";
                    } else {
                        price_tiers.value = "' . $price1 . '";
                        currencyElement.innerText = "' . $price1 . '";
                    }
                });
            }
        </script>
        ';
    }

    private function generateGuaranteesSection() {
        return '
        <section class="section page-section title-section"
            style="margin-top: 40px; background: linear-gradient(to bottom, #f1f0ee, #ffffff, #ffffff, #ffffff) !important;">
            <div class="inner-container">
                <p class="title-section-content" style="margin-bottom: 60px;">
                    <span class="title-section-stylish"></span>
                    <a href="" target="_blank" class="title-section-text">
                        الضمانات و الإسترجاع
                    </a>
                </p>
                <div class="guaranties-column">
                    <img style="width: 18%; margin: auto;" src="images/icons/costumer-service.png" alt="">
                    <h2 style="font-weight: 700; font-size: 20px;">خدمة ما بعد البيع</h2>
                    <p style="font-size: 17px; font-weight: 500;">خدمة العملاء متوفرة طيلة أيام الأسبوع</p>
                </div>
                <div class="guaranties-column">
                    <img style="width: 18%; margin: auto;" src="images/icons/delivery.png" alt="">
                    <h2 style="font-weight: 700; font-size: 20px;">الشحن مجاني</h2>
                    <p style="font-size: 17px; font-weight: 500;">التوصيل سريع و مجاني لجميع أنحاء المغرب</p>
                </div>
                <div class="guaranties-column">
                    <img style="width: 18%; margin: auto;" src="images/icons/money.png" alt="">
                    <h2 style="font-weight: 700; font-size: 20px;">الدفع عند الاستلام</h2>
                    <p style="font-size: 17px; font-weight: 500;">لن تدفع اي شي حتى تتوصل بالمنتج</p>
                </div>
                <div class="guaranties-column">
                    <img style="width: 18%; margin: auto;" src="images/icons/bird.png" alt="">
                    <h2 style="font-weight: 700; font-size: 20px;">ضمان المنتج</h2>
                    <p style="font-size: 17px; font-weight: 500;">ضمان 10 أيام إذا لم يعجبك يمكنك استرداد أموالك</p>
                </div>
            </div>
        </section>
        ';
    }

    private function generateFAQSection() {
        return '
        <section class="section page-section title-section"
            style="background: linear-gradient(to bottom, #fff, #d0dee7, #d0dee7, #fff) !important; padding-bottom: 20px;">
            <div class="faq-container">
                <p class="title-section-content" style="text-align: center; font-size: 1.4rem; margin-bottom: 45px;">
                    <span class="title-section-stylish"></span>
                    <a href="" target="_blank" class="title-section-text">
                        الاسئلة الشائعة حول المنتج
                    </a>
                </p>
                <div class="question">هل تقبلون الدفع عند الإستلام؟</div>
                <div class="answer">نعم، نحن نقبل الدفع عند الإستلام</div>

                <div class="question">كم فترة التوصيل؟</div>
                <div class="answer">فترة التوصيل تعتمد على المدينة، و عادة ما تكون بين 1 و 3 أيام عمل</div>

                <div class="question">هل يمكن إرجاع المنتج إذا لم يعجبني؟</div>
                <div class="answer">نعم، يمكنك إرجاع المنتج في حالة عدم الرضا عنه في غضون 10 أيام من تاريخ الشراء وبشرط أن يكون في حالته الأصلية</div>

                <div class="question">هل يوجد ضمان على المنتج؟</div>
                <div class="answer">نعم، ضمان المنتج يشمل عيوب التصنيع و تختلف مدة الضمان حسب المنتج</div>
            </div>
        </section>
        ';
    }

    private function generateReviews() {
        $reviews = json_decode($this->config['selected_reviews'] ?? '[]', true);
        $html = '';
        
        foreach ($reviews as $review) {
            $html .= "
                <div class=\"u-align-right u-container-style u-list-item u-repeater-item\">
                    <div class=\"u-container-layout u-similar-container u-valign-middle-md u-container-layout-1\">
                        <div class=\"u-container-style u-group u-radius-20 u-shape-round u-similar-fill u-white u-group-1\">
                            <div class=\"u-container-layout u-valign-middle u-container-layout-2\">
                                <p class=\"u-align-left u-text u-text-black u-text-font u-text-2\">{$review['review']}</p>
                                <h5 class=\"u-align-right u-custom-font u-heading-font u-text u-text-black u-text-3\">- {$review['name']}</h5>
                            </div>
                        </div>
                        <div class=\"u-image u-image-circle u-preserve-proportions u-image-1\" alt=\"\"></div>
                    </div>
                </div>
            ";
        }
        
        return $html;
    }
    
    private function generateCustomDataFormat() {
        $customFormat = $this->config['custom_data_format'] ?? '';
        
        if (empty($customFormat)) {
            // Default format
            return '
                var sheetDBData = {
                    name: "' . $this->config['name'] . '",
                    date: new Date().toString(),
                    customer_name: fullname,
                    phone: phone,
                    city: "-",
                    address: adresse,
                    quantity: variant,
                    price: price,
                    product_notice: "",
                    notice: "Color: " + product_color,
                    status: "pending",
                    fees_shipping: "",
                    size: product_size,
                };
            ';
        }
        
        return $customFormat;
    }
    
    private function copyAssets($projectDir) {
        $templateDir = dirname(__DIR__, 2) . '/chemise_simple';

        if (!file_exists($templateDir)) {
            throw new Exception("Template directory not found: {$templateDir}");
        }

        // Copy all directories and files from chemise_simple
        $directoriesToCopy = [
            'css',
            'js',
            'images',
            'clients_reviews',
            'pages'
        ];

        foreach ($directoriesToCopy as $dir) {
            $sourceDir = $templateDir . '/' . $dir;
            $targetDir = $projectDir . '/' . $dir;

            if (file_exists($sourceDir)) {
                $this->copyDirectory($sourceDir, $targetDir);
            }
        }

        // Copy individual files
        $filesToCopy = [
            'loader.html',
            'order_success.html'
        ];

        foreach ($filesToCopy as $file) {
            $sourceFile = $templateDir . '/' . $file;
            $targetFile = $projectDir . '/' . $file;

            if (file_exists($sourceFile)) {
                copy($sourceFile, $targetFile);
            }
        }
    }
    
    private function copyDirectory($source, $destination) {
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            $relativePath = str_replace($source . DIRECTORY_SEPARATOR, '', $item->getPathname());
            $target = $destination . DIRECTORY_SEPARATOR . $relativePath;

            if ($item->isDir()) {
                if (!file_exists($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                copy($item->getPathname(), $target);
            }
        }
    }
    
    private function createZipFile($projectDir, $projectName) {
        // Create ZIP in the landing-page-generator directory for download
        $zipFile = dirname(__DIR__) . '/generated/' . $projectName . '.zip';

        // Ensure generated directory exists
        $generatedDir = dirname(__DIR__) . '/generated';
        if (!file_exists($generatedDir)) {
            mkdir($generatedDir, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($projectDir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $file) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($projectDir) + 1);

                if ($file->isDir()) {
                    $zip->addEmptyDir($relativePath);
                } else {
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
            return $zipFile;
        }

        return false;
    }
    
    private function getHTMLTemplate() {
        return file_get_contents('templates/landing-page-template.html');
    }
    
    private function getAPITemplate() {
        return file_get_contents('templates/api-template.js');
    }
}

?>
