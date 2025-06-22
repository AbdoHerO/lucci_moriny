# 🚀 Quick Start Guide - Landing Page Generator

## Option 1: Local Development (Recommended for Testing)

### Step 1: Install XAMPP
1. Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Install XAMPP
3. Start Apache service from XAMPP Control Panel

### Step 2: Setup Files
1. Copy your entire `lucci-moriny-main` folder to `C:\xampp\htdocs\`
2. Your structure should look like:
   ```
   C:\xampp\htdocs\lucci-moriny-main\
   ├── chemise_simple/
   ├── landing-page-generator/
   └── [other landing pages...]
   ```

### Step 3: Run Quick Start (Windows)
1. Navigate to `lucci-moriny-main` folder
2. Double-click `landing-page-generator\quick-start.bat`
3. Follow the prompts

### Step 4: Access the System
1. Open browser
2. Go to: `http://localhost/lucci-moriny-main/landing-page-generator/install.php`
3. Follow installation wizard
4. Then go to: `http://localhost/lucci-moriny-main/landing-page-generator/`

---

## Option 2: Shared Hosting

### Step 1: Upload Files
1. Upload `landing-page-generator` folder to your hosting
2. Upload `chemise_simple` folder to the same directory level
3. Structure should be:
   ```
   public_html/
   ├── chemise_simple/
   ├── landing-page-generator/
   └── [other files...]
   ```

### Step 2: Set Permissions
Set these folder permissions via cPanel File Manager or FTP:
- `landing-page-generator/data/` → 755
- `landing-page-generator/generated/` → 755
- `landing-page-generator/uploads/` → 755

### Step 3: Run Installation
1. Go to: `https://yourdomain.com/landing-page-generator/install.php`
2. Fix any issues shown
3. Then go to: `https://yourdomain.com/landing-page-generator/`

---

## First Test - Create a Landing Page

### 1. Basic Information
- **Project Name**: `test-product`
- **Page Title**: `Test Product Landing Page`

### 2. Content & Prices Tab
- **Main Title**: `تخفيض 50% على المنتج التجريبي`
- **Order Text**: `للطلب المرجو ملء الاستمارة أسفله`
- **Price 1**: `199`
- **Price 2**: `349`
- **Currency**: `د.م`

### 3. Pixels Tab
- **Facebook Pixel**: `123456789` (test ID)
- **TikTok Pixel**: `TEST123` (test ID)

### 4. Images Tab
- **Slider Images**: Add some test URLs like:
  - `https://via.placeholder.com/800x600/FF0000/FFFFFF?text=Image+1`
  - `https://via.placeholder.com/800x600/00FF00/FFFFFF?text=Image+2`
  - `https://via.placeholder.com/800x600/0000FF/FFFFFF?text=Image+3`

- **Body Images**: Add test URLs like:
  - `https://via.placeholder.com/600x400/FFFF00/000000?text=Product+1`
  - `https://via.placeholder.com/600x400/FF00FF/FFFFFF?text=Product+2`

### 5. Form Tab
- **Product Type**: `Two Variants`
- **Has Offer**: `Yes`
- **Colors**: 
  - أسود → black
  - أبيض → white
  - أحمر → red
- **Sizes**:
  - S → S
  - M → M
  - L → L
  - XL → XL

### 6. Reviews Tab
- Click "اختيار التقييمات"
- Select 2-3 reviews from the list
- Click "اختيار"

### 7. API Tab
- **SheetDB API**: `test123` (for testing)
- **Token**: `Bearer test-token`
- Leave custom format empty (will use default)

### 8. Test the System
1. Click **"حفظ الإعدادات"** - Should show success message
2. Click **"معاينة"** - Should open preview in new tab
3. Click **"توليد وتحميل"** - Should generate and download ZIP file

---

## Troubleshooting

### Common Issues:

#### "Template source directory not found"
**Fix**: Make sure `chemise_simple` folder is at the same level as `landing-page-generator`

#### "Permission denied" errors
**Fix**: Set folder permissions to 755:
```bash
chmod 755 data/
chmod 755 generated/
chmod 755 uploads/
```

#### "SQLite not found"
**Fix**: Contact hosting provider to enable SQLite extension

#### Preview not working
**Fix**: Check if all required files are uploaded and permissions are correct

#### Download not working
**Fix**: Check if `generated` folder is writable (755 permissions)

---

## What You Get

After successful generation, you'll download a ZIP file containing:

```
test-product/
├── index.html                    # Complete landing page
├── api_cozmo_landing_page.js     # Custom API integration
├── css/                          # All styling files
├── js/                           # All JavaScript files
├── images/                       # Image assets
├── pages/                        # Policy pages
├── clients_reviews/              # Review components
├── loader.html                   # Loading page
└── order_success.html            # Success page
```

This is a complete, ready-to-upload landing page!

---

## Next Steps

1. **Test the generated landing page** by uploading it to a test domain
2. **Configure real SheetDB** for form submissions
3. **Add real pixel IDs** for tracking
4. **Upload your product images**
5. **Customize reviews** for your products

---

## Support

If you encounter issues:
1. Check the installation requirements first
2. Verify file permissions
3. Test with simple configuration
4. Check PHP error logs on your server

**You're ready to generate unlimited landing pages automatically!** 🎉
