// Demo Data for Testing Landing Page Generator Dashboard
// This script provides sample data for testing all dynamic features

const demoData = {
    // Test Set 1: Sample Product (Free Sample)
    sampleProduct: {
        name: "عينة مجانية من الكريم",
        title: "احصل على عينة مجانية من كريم البشرة",
        main_title: "عينة مجانية + شحن مجاني لجميع أنحاء المغرب",
        order_text: "احصل على عينتك المجانية الآن",
        price_1: "0",
        price_2: "50",
        currency: "د.م",
        product_type: "sample",
        has_offer: "yes",
        facebook_pixel: "123456789012345",
        tiktok_pixel: "ABC123DEF456GHI",
        slider_images: [
            "https://via.placeholder.com/800x600/FF6B6B/FFFFFF?text=صورة+المنتج+1",
            "https://via.placeholder.com/800x600/4ECDC4/FFFFFF?text=صورة+المنتج+2",
            "https://via.placeholder.com/800x600/45B7D1/FFFFFF?text=صورة+المنتج+3"
        ],
        body_images: [
            "https://via.placeholder.com/600x400/96CEB4/FFFFFF?text=فوائد+المنتج",
            "https://via.placeholder.com/600x400/FFEAA7/000000?text=طريقة+الاستخدام"
        ],
        sheetdb_api: "demo_api_key",
        sheetdb_token: "Bearer demo_token"
    },

    // Test Set 2: Single Variant Product (T-Shirt)
    singleVariant: {
        name: "قميص رجالي أنيق",
        title: "قميص رجالي عصري - جودة عالية",
        main_title: "تخفيض 40% على القمصان الرجالية + توصيل مجاني",
        order_text: "اطلب قميصك المفضل الآن",
        price_1: "199",
        price_2: "349",
        currency: "د.م",
        product_type: "single_variant",
        has_offer: "yes",
        color_options: ["أسود", "أبيض", "أزرق داكن", "رمادي"],
        color_values: ["black", "white", "navy", "gray"],
        facebook_pixel: "987654321098765",
        tiktok_pixel: "XYZ789ABC123DEF",
        slider_images: [
            "https://via.placeholder.com/800x600/2D3436/FFFFFF?text=قميص+أسود",
            "https://via.placeholder.com/800x600/DDD/000000?text=قميص+أبيض",
            "https://via.placeholder.com/800x600/0984E3/FFFFFF?text=قميص+أزرق"
        ],
        body_images: [
            "https://via.placeholder.com/600x400/00B894/FFFFFF?text=جودة+القماش",
            "https://via.placeholder.com/600x400/E17055/FFFFFF?text=تصميم+عصري"
        ]
    },

    // Test Set 3: Two Variants Product (Shoes)
    twoVariants: {
        name: "حذاء رياضي مريح",
        title: "أحذية رياضية عالية الجودة للرجال والنساء",
        main_title: "عرض خاص: حذاء رياضي بتقنية متطورة - خصم 50%",
        order_text: "اختر مقاسك ولونك المفضل واطلب الآن",
        price_1: "299",
        price_2: "499",
        currency: "د.م",
        product_type: "two_variants",
        has_offer: "yes",
        color_options: ["أسود", "أبيض", "أحمر", "أزرق"],
        color_values: ["black", "white", "red", "blue"],
        size_options: ["38", "39", "40", "41", "42", "43", "44"],
        size_values: ["38", "39", "40", "41", "42", "43", "44"],
        facebook_pixel: "555666777888999",
        tiktok_pixel: "DEF456GHI789JKL",
        slider_images: [
            "https://via.placeholder.com/800x600/2D3436/FFFFFF?text=حذاء+أسود",
            "https://via.placeholder.com/800x600/DDD/000000?text=حذاء+أبيض",
            "https://via.placeholder.com/800x600/E84393/FFFFFF?text=حذاء+أحمر",
            "https://via.placeholder.com/800x600/0984E3/FFFFFF?text=حذاء+أزرق"
        ],
        body_images: [
            "https://via.placeholder.com/600x400/00B894/FFFFFF?text=راحة+القدم",
            "https://via.placeholder.com/600x400/FDCB6E/000000?text=تقنية+متطورة",
            "https://via.placeholder.com/600x400/6C5CE7/FFFFFF?text=تصميم+رياضي"
        ]
    },

    // Sample Reviews for Testing
    sampleReviews: [
        {
            id: 1,
            name: "أميرة محمد",
            review: "منتج رائع، جودة عالية وتوصيل سريع. أنصح به بشدة!"
        },
        {
            id: 2,
            name: "كمال الدين",
            review: "تبارك الله عليكم، المنتج كما هو موضح في الصور والجودة ممتازة"
        },
        {
            id: 3,
            name: "فاطمة الزهراء",
            review: "طلبت واحد وصلني اليوم، زوين بزاف ومريح. شكرا لكم"
        },
        {
            id: 4,
            name: "أحمد العلوي",
            review: "خدمة ممتازة والتوصيل في الوقت المحدد. سأطلب مرة أخرى بإذن الله"
        },
        {
            id: 5,
            name: "خديجة بنت محمد",
            review: "المنتج يستحق السعر، جودة عالية وخدمة عملاء ممتازة"
        }
    ],

    // Custom API Data Format Examples
    customApiFormats: {
        basic: `{
    "name": "اسم المنتج",
    "date": "التاريخ الحالي",
    "customer_name": "اسم العميل",
    "phone": "رقم الهاتف",
    "address": "العنوان",
    "quantity": "الكمية",
    "price": "السعر",
    "status": "pending"
}`,
        advanced: `{
    "product_info": {
        "name": "اسم المنتج",
        "id": "معرف المنتج",
        "category": "فئة المنتج"
    },
    "customer_info": {
        "full_name": "الاسم الكامل",
        "phone": "رقم الهاتف",
        "city": "المدينة",
        "address": "العنوان الكامل"
    },
    "order_details": {
        "quantity": "الكمية",
        "color": "اللون",
        "size": "الحجم",
        "total_price": "السعر الإجمالي",
        "shipping_fee": "رسوم الشحن"
    },
    "metadata": {
        "order_date": "تاريخ الطلب",
        "source": "مصدر الطلب",
        "status": "حالة الطلب",
        "notes": "ملاحظات"
    }
}`
    }
};

// Helper Functions for Testing
const testHelpers = {
    // Fill form with demo data
    fillForm: function(dataSet) {
        const data = demoData[dataSet];
        if (!data) return;

        // Basic info
        document.querySelector('input[name="name"]').value = data.name;
        document.querySelector('input[name="title"]').value = data.title;

        // Content & Pricing
        document.querySelector('input[name="main_title"]').value = data.main_title;
        document.querySelector('input[name="order_text"]').value = data.order_text;
        document.querySelector('input[name="price_1"]').value = data.price_1;
        document.querySelector('input[name="price_2"]').value = data.price_2;
        document.querySelector('input[name="currency"]').value = data.currency;

        // Pixels
        document.querySelector('input[name="facebook_pixel"]').value = data.facebook_pixel;
        document.querySelector('input[name="tiktok_pixel"]').value = data.tiktok_pixel;

        // Product type
        document.querySelector('select[name="product_type"]').value = data.product_type;
        
        // Trigger form update
        updateFormFields();
    },

    // Add sample images
    addSampleImages: function(dataSet) {
        const data = demoData[dataSet];
        if (!data) return;

        // Clear existing images
        document.getElementById('slider-images-container').innerHTML = '';
        document.getElementById('body-images-container').innerHTML = '';

        // Add slider images
        data.slider_images.forEach(url => {
            addSliderImage();
            const containers = document.querySelectorAll('#slider-images-container .image-upload-container');
            const lastContainer = containers[containers.length - 1];
            lastContainer.querySelector('input[type="text"]').value = url;
        });

        // Add body images
        data.body_images.forEach(url => {
            addBodyImage();
            const containers = document.querySelectorAll('#body-images-container .image-upload-container');
            const lastContainer = containers[containers.length - 1];
            lastContainer.querySelector('input[type="text"]').value = url;
        });
    },

    // Test form validation
    testValidation: function() {
        // Test empty required fields
        document.querySelector('input[name="name"]').value = '';
        document.querySelector('input[name="title"]').value = '';
        
        // Try to save
        saveLandingPage();
    },

    // Test dynamic form changes
    testFormChanges: function() {
        const productTypeSelect = document.querySelector('select[name="product_type"]');
        
        // Test each product type
        ['sample', 'single_variant', 'two_variants'].forEach(type => {
            productTypeSelect.value = type;
            updateFormFields();
            console.log(`Form updated for product type: ${type}`);
        });
    }
};

// Make functions available globally for testing
window.demoData = demoData;
window.testHelpers = testHelpers;

console.log('Demo data and test helpers loaded successfully!');
console.log('Available test sets:', Object.keys(demoData));
console.log('Available helpers:', Object.keys(testHelpers));
