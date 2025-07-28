@echo off
echo Running Price Test...
echo.

REM Check if PHP is available
php --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: PHP is not installed or not in PATH
    echo Please install PHP and add it to your system PATH
    pause
    exit /b 1
)

REM Check if vendor directory exists
if not exist "vendor" (
    echo ERROR: vendor directory not found
    echo Please run 'composer install' first
    pause
    exit /b 1
)

REM Run the specific test
echo Running PriceTest...
vendor\bin\phpunit tests\PriceTest.php

if errorlevel 1 (
    echo.
    echo Test failed! Check the output above for details.
) else (
    echo.
    echo Test completed successfully!
)

echo.
pause 