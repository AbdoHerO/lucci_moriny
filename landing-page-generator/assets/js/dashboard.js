// Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    loadDefaultFormFields();
    loadAvailableReviews();
});

// Global variables
let selectedReviews = [];
let sliderImageCount = 0;
let bodyImageCount = 0;

// Initialize dashboard functionality
function initializeDashboard() {
    // Sidebar navigation
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Hide all content sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Show selected section
            const sectionId = this.getAttribute('data-section');
            const section = document.getElementById(sectionId);
            if (section) {
                section.style.display = 'block';
            }
        });
    });

    // Load saved page functionality
    document.getElementById('load-saved-page').addEventListener('change', function() {
        if (this.value) {
            loadSavedPage(this.value);
        }
    });
}

// Load default form fields
function loadDefaultFormFields() {
    updateFormFields();
    addSliderImage(); // Add first slider image
    addBodyImage(); // Add first body image
}

// Update form fields based on product type
function updateFormFields() {
    const productType = document.querySelector('select[name="product_type"]').value;
    const container = document.getElementById('form-fields-config');
    
    let html = '';
    
    switch (productType) {
        case 'sample':
            html = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    نموذج العينة يحتوي على: الاسم، العنوان، الهاتف، والعرض فقط
                </div>
            `;
            break;
            
        case 'single_variant':
            html = `
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">خيارات اللون</label>
                        <div id="color-options">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="color_options[]" placeholder="اسم اللون" value="أسود">
                                <input type="text" class="form-control" name="color_values[]" placeholder="قيمة اللون" value="dark">
                                <button type="button" class="btn btn-danger" onclick="removeColorOption(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="color_options[]" placeholder="اسم اللون" value="أبيض">
                                <input type="text" class="form-control" name="color_values[]" placeholder="قيمة اللون" value="white">
                                <button type="button" class="btn btn-danger" onclick="removeColorOption(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addColorOption()">
                            <i class="fas fa-plus"></i> إضافة لون
                        </button>
                    </div>
                </div>
            `;
            break;
            
        case 'two_variants':
            html = `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">خيارات اللون</label>
                        <div id="color-options">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="color_options[]" placeholder="اسم اللون" value="أسود">
                                <input type="text" class="form-control" name="color_values[]" placeholder="قيمة اللون" value="dark">
                                <button type="button" class="btn btn-danger" onclick="removeColorOption(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="color_options[]" placeholder="اسم اللون" value="أبيض">
                                <input type="text" class="form-control" name="color_values[]" placeholder="قيمة اللون" value="white">
                                <button type="button" class="btn btn-danger" onclick="removeColorOption(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addColorOption()">
                            <i class="fas fa-plus"></i> إضافة لون
                        </button>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">خيارات الحجم</label>
                        <div id="size-options">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="size_options[]" placeholder="اسم الحجم" value="S">
                                <input type="text" class="form-control" name="size_values[]" placeholder="قيمة الحجم" value="S">
                                <button type="button" class="btn btn-danger" onclick="removeSizeOption(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="size_options[]" placeholder="اسم الحجم" value="M">
                                <input type="text" class="form-control" name="size_values[]" placeholder="قيمة الحجم" value="M">
                                <button type="button" class="btn btn-danger" onclick="removeSizeOption(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="size_options[]" placeholder="اسم الحجم" value="L">
                                <input type="text" class="form-control" name="size_values[]" placeholder="قيمة الحجم" value="L">
                                <button type="button" class="btn btn-danger" onclick="removeSizeOption(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="size_options[]" placeholder="اسم الحجم" value="XL">
                                <input type="text" class="form-control" name="size_values[]" placeholder="قيمة الحجم" value="XL">
                                <button type="button" class="btn btn-danger" onclick="removeSizeOption(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addSizeOption()">
                            <i class="fas fa-plus"></i> إضافة حجم
                        </button>
                    </div>
                </div>
            `;
            break;
    }
    
    container.innerHTML = html;
}

// Add color option
function addColorOption() {
    const container = document.getElementById('color-options');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" class="form-control" name="color_options[]" placeholder="اسم اللون">
        <input type="text" class="form-control" name="color_values[]" placeholder="قيمة اللون">
        <button type="button" class="btn btn-danger" onclick="removeColorOption(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(div);
}

// Remove color option
function removeColorOption(button) {
    button.parentElement.remove();
}

// Add size option
function addSizeOption() {
    const container = document.getElementById('size-options');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" class="form-control" name="size_options[]" placeholder="اسم الحجم">
        <input type="text" class="form-control" name="size_values[]" placeholder="قيمة الحجم">
        <button type="button" class="btn btn-danger" onclick="removeSizeOption(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(div);
}

// Remove size option
function removeSizeOption(button) {
    button.parentElement.remove();
}

// Add slider image
function addSliderImage() {
    sliderImageCount++;
    const container = document.getElementById('slider-images-container');
    const div = document.createElement('div');
    div.className = 'image-upload-container';
    div.id = `slider-image-${sliderImageCount}`;
    div.innerHTML = `
        <div class="row">
            <div class="col-md-8">
                <input type="text" class="form-control" name="slider_images[]" placeholder="رابط الصورة أو اسم الملف">
            </div>
            <div class="col-md-2">
                <input type="file" class="form-control" accept="image/*" onchange="handleImageUpload(this, 'slider-image-${sliderImageCount}')">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="removeSliderImage('slider-image-${sliderImageCount}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="image-preview-container mt-2"></div>
    `;
    container.appendChild(div);
}

// Remove slider image
function removeSliderImage(id) {
    document.getElementById(id).remove();
}

// Add body image
function addBodyImage() {
    bodyImageCount++;
    const container = document.getElementById('body-images-container');
    const div = document.createElement('div');
    div.className = 'image-upload-container';
    div.id = `body-image-${bodyImageCount}`;
    div.innerHTML = `
        <div class="row">
            <div class="col-md-8">
                <input type="text" class="form-control" name="body_images[]" placeholder="رابط الصورة أو اسم الملف">
            </div>
            <div class="col-md-2">
                <input type="file" class="form-control" accept="image/*" onchange="handleImageUpload(this, 'body-image-${bodyImageCount}')">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="removeBodyImage('body-image-${bodyImageCount}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="image-preview-container mt-2"></div>
    `;
    container.appendChild(div);
}

// Remove body image
function removeBodyImage(id) {
    document.getElementById(id).remove();
}

// Handle image upload
function handleImageUpload(input, containerId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.getElementById(containerId);
            const previewContainer = container.querySelector('.image-preview-container');
            previewContainer.innerHTML = `<img src="${e.target.result}" class="image-preview" alt="Preview">`;
            
            // Update the text input with the file name
            const textInput = container.querySelector('input[type="text"]');
            textInput.value = file.name;
        };
        reader.readAsDataURL(file);
    }
}

// Load available reviews
function loadAvailableReviews() {
    fetch('index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_reviews'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayAvailableReviews(data.reviews);
        }
    })
    .catch(error => {
        console.error('Error loading reviews:', error);
    });
}

// Display available reviews
function displayAvailableReviews(reviews) {
    const container = document.getElementById('available-reviews');
    let html = '';
    
    reviews.forEach(review => {
        html += `
            <div class="review-item" data-review-id="${review.id}">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="${review.id}" id="review-${review.id}">
                    <label class="form-check-label" for="review-${review.id}">
                        <strong>${review.name}</strong><br>
                        <small class="text-muted">${review.review}</small>
                    </label>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Open reviews modal
function openReviewsModal() {
    const modal = new bootstrap.Modal(document.getElementById('reviewsModal'));
    modal.show();
}

// Select reviews
function selectReviews() {
    const checkboxes = document.querySelectorAll('#available-reviews input[type="checkbox"]:checked');
    selectedReviews = [];
    
    checkboxes.forEach(checkbox => {
        const reviewItem = checkbox.closest('.review-item');
        const reviewData = {
            id: checkbox.value,
            name: reviewItem.querySelector('strong').textContent,
            review: reviewItem.querySelector('small').textContent
        };
        selectedReviews.push(reviewData);
    });
    
    displaySelectedReviews();
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('reviewsModal'));
    modal.hide();
}

// Display selected reviews
function displaySelectedReviews() {
    const container = document.getElementById('selected-reviews');
    let html = '';
    
    selectedReviews.forEach((review, index) => {
        html += `
            <div class="review-item selected">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>${review.name}</strong><br>
                        <small>${review.review}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeSelectedReview(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Remove selected review
function removeSelectedReview(index) {
    selectedReviews.splice(index, 1);
    displaySelectedReviews();
}

// Save landing page
function saveLandingPage() {
    const form = document.getElementById('landing-page-form');
    const formData = new FormData(form);
    formData.append('action', 'save_landing_page');
    formData.append('selected_reviews', JSON.stringify(selectedReviews));
    
    // Show loading
    const saveBtn = document.querySelector('button[onclick="saveLandingPage()"]');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<span class="loading-spinner"></span> جاري الحفظ...';
    saveBtn.disabled = true;
    
    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('تم حفظ الإعدادات بنجاح', 'success');
        } else {
            showAlert('حدث خطأ أثناء الحفظ: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        showAlert('حدث خطأ أثناء الحفظ', 'danger');
        console.error('Error:', error);
    })
    .finally(() => {
        // Restore button
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
}

// Preview landing page
function previewLandingPage() {
    const form = document.getElementById('landing-page-form');
    const formData = new FormData(form);
    formData.append('selected_reviews', JSON.stringify(selectedReviews));

    // Add timestamp to force refresh and avoid caching
    formData.append('preview_timestamp', Date.now());

    // Create a temporary form to submit to preview.php
    const previewForm = document.createElement('form');
    previewForm.method = 'POST';
    previewForm.action = 'preview.php';
    previewForm.target = '_blank';

    // Add all form data as hidden inputs
    for (let [key, value] of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        previewForm.appendChild(input);
    }

    document.body.appendChild(previewForm);
    previewForm.submit();
    document.body.removeChild(previewForm);

    // Show success message
    showAlert('تم إنشاء المعاينة بنجاح! تحقق من النافذة الجديدة', 'success');
}

// Generate landing page
function generateLandingPage() {
    const form = document.getElementById('landing-page-form');
    const formData = new FormData(form);
    formData.append('action', 'generate_landing_page');
    formData.append('selected_reviews', JSON.stringify(selectedReviews));
    
    // Show loading
    const generateBtn = document.querySelector('button[onclick="generateLandingPage()"]');
    const originalText = generateBtn.innerHTML;
    generateBtn.innerHTML = '<span class="loading-spinner"></span> جاري التوليد...';
    generateBtn.disabled = true;
    
    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('تم توليد صفحة الهبوط بنجاح', 'success');
            if (data.download_url) {
                // Create download link
                const link = document.createElement('a');
                link.href = data.download_url;
                link.download = '';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        } else {
            showAlert('حدث خطأ أثناء التوليد: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        showAlert('حدث خطأ أثناء التوليد', 'danger');
        console.error('Error:', error);
    })
    .finally(() => {
        // Restore button
        generateBtn.innerHTML = originalText;
        generateBtn.disabled = false;
    });
}

// Load saved page
function loadSavedPage(pageId) {
    // This would load the saved page configuration
    showAlert('جاري تحميل الصفحة المحفوظة...', 'info');
}

// Show alert
function showAlert(message, type) {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type} alert-dismissible fade show`;
    alertContainer.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at the top of main content
    const main = document.querySelector('main');
    main.insertBefore(alertContainer, main.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (alertContainer.parentNode) {
            alertContainer.remove();
        }
    }, 5000);
}
