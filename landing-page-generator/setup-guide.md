# 🚀 Setup Guide - Landing Page Generator

## Step 1: File Organization

### Option A: Local Development (XAMPP/WAMP)

1. **Install XAMPP or WAMP**
   - Download from [xampp.org](https://www.apachefriends.org/) or [wampserver.com](https://www.wampserver.com/)
   - Install and start Apache and MySQL services

2. **Organize Your Files**
   ```
   htdocs/ (or www/)
   ├── lucci-moriny-main/
   │   ├── chemise_simple/          # Your existing template
   │   ├── landing-page-generator/  # New dashboard system
   │   └── [other landing pages]
   ```

3. **Access the System**
   - Open browser and go to: `http://localhost/lucci-moriny-main/landing-page-generator/install.php`

### Option B: Shared Hosting

1. **Upload Files via FTP/cPanel**
   - Upload the entire `lucci-moriny-main` folder to your hosting
   - Or upload `landing-page-generator` folder to your domain root

2. **File Structure on Server**
   ```
   public_html/ (or www/)
   ├── chemise_simple/              # Your existing template
   ├── landing-page-generator/      # New dashboard system
   └── [other files]
   ```

3. **Access the System**
   - Go to: `https://yourdomain.com/landing-page-generator/install.php`

## Step 2: Run Installation

1. **Open Installation Page**
   - Navigate to `install.php` in your browser
   - The system will check all requirements automatically

2. **Check Requirements**
   - PHP 7.4+ ✓
   - SQLite extension ✓
   - ZIP extension ✓
   - Writable directories ✓

3. **Fix Any Issues**
   - If directories aren't writable, set permissions to 755
   - Contact hosting provider if PHP extensions are missing

## Step 3: Access Dashboard

1. **Open Main Dashboard**
   - Go to: `index.php`
   - You'll see the main dashboard interface

2. **Create Your First Landing Page**
   - Click "صفحة جديدة" (New Page)
   - Fill in the required information
   - Test with preview functionality

## Step 4: Test the System

### Quick Test:
1. **Basic Information**
   - Name: `test-product`
   - Title: `Test Product Page`

2. **Content & Prices**
   - Main Title: `تخفيض 50% على المنتج التجريبي`
   - Price 1: `199`
   - Price 2: `299`

3. **Images**
   - Add some test image URLs or upload files

4. **Form Configuration**
   - Select "Two Variants"
   - Add colors: أسود (black), أبيض (white)
   - Add sizes: S, M, L, XL

5. **Preview & Generate**
   - Click "معاينة" to see preview
   - Click "توليد وتحميل" to generate ZIP file

## Common Issues & Solutions

### Issue 1: "Template source directory not found"
**Solution:** Make sure `chemise_simple` folder is in the correct location relative to `landing-page-generator`

### Issue 2: "Permission denied" errors
**Solution:** Set folder permissions:
```bash
chmod 755 data/
chmod 755 generated/
chmod 755 uploads/
```

### Issue 3: "SQLite not found"
**Solution:** Contact your hosting provider to enable SQLite extension

### Issue 4: "ZIP functionality failed"
**Solution:** Contact your hosting provider to enable ZIP extension

## File Permissions Guide

Set these permissions on your server:

```
landing-page-generator/          755
├── data/                        755 (writable)
├── generated/                   755 (writable)
├── uploads/                     755 (writable)
├── index.php                    644
├── install.php                  644
├── preview.php                  644
└── includes/                    755
    └── TemplateEngine.php       644
```

## Testing Checklist

- [ ] Installation page loads without errors
- [ ] Dashboard interface appears correctly
- [ ] Can create new landing page configuration
- [ ] Preview functionality works
- [ ] Can generate and download ZIP file
- [ ] Generated landing page opens correctly
- [ ] Form submission works (test with SheetDB)

## Next Steps

1. **Configure SheetDB**
   - Create account at [sheetdb.io](https://sheetdb.io)
   - Get your API key and token
   - Test form submissions

2. **Add Your Pixel IDs**
   - Get Facebook Pixel ID from Facebook Ads Manager
   - Get TikTok Pixel ID from TikTok Ads Manager

3. **Customize Reviews**
   - Add your own customer reviews
   - Organize by product categories

4. **Upload Your Images**
   - Prepare high-quality product images
   - Optimize for web (compress file sizes)

## Support

If you encounter issues:

1. Check the installation requirements
2. Verify file permissions
3. Check PHP error logs
4. Ensure all files are uploaded correctly
5. Test on a simple configuration first

## Security Notes

- Change default database location if needed
- Protect admin access with .htaccess if required
- Regular backups of your configurations
- Keep the system updated

---

**Ready to start generating landing pages automatically!** 🎉
