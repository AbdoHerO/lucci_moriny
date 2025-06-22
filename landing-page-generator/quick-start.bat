@echo off
echo ========================================
echo   Landing Page Generator - Quick Start
echo ========================================
echo.

echo Checking if you're in the right directory...
if not exist "chemise_simple" (
    echo ERROR: chemise_simple folder not found!
    echo Please make sure you're running this from the lucci-moriny-main directory
    echo that contains both chemise_simple and landing-page-generator folders.
    echo.
    pause
    exit /b 1
)

echo ✓ chemise_simple folder found
echo.

echo Checking for XAMPP installation...
if exist "C:\xampp\xampp-control.exe" (
    echo ✓ XAMPP found at C:\xampp\
    echo Starting XAMPP Control Panel...
    start "" "C:\xampp\xampp-control.exe"
    echo.
    echo Please start Apache service in XAMPP Control Panel
    echo.
) else (
    echo ⚠ XAMPP not found at default location
    echo Please install XAMPP from: https://www.apachefriends.org/
    echo Or start your local server manually
    echo.
)

echo Creating necessary directories...
if not exist "landing-page-generator\data" mkdir "landing-page-generator\data"
if not exist "landing-page-generator\generated" mkdir "landing-page-generator\generated"
if not exist "landing-page-generator\uploads" mkdir "landing-page-generator\uploads"
if not exist "landing-page-generator\uploads\images" mkdir "landing-page-generator\uploads\images"

echo ✓ Directories created
echo.

echo ========================================
echo   Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Make sure Apache is running in XAMPP
echo 2. Open your browser
echo 3. Go to: http://localhost/lucci-moriny-main/landing-page-generator/install.php
echo.
echo If you moved the files to a different location, adjust the URL accordingly.
echo.

set /p choice="Would you like to open the installation page now? (y/n): "
if /i "%choice%"=="y" (
    echo Opening installation page...
    start "" "http://localhost/lucci-moriny-main/landing-page-generator/install.php"
)

echo.
echo Happy landing page generating! 🚀
pause
